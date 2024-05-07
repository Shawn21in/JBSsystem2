<?php

class ShopCart{
	//會員訂單
	var $Tablem 				= 'web_ordermain';		//訂單主表
	var $Tabled					= 'web_orderdetail';	//訂單明細
	var $Table_OrderID 			= 'web_orderid';		//訂單編號流水表
	
	var $Tabletm 				= 'web_ordertmain';		//購物車主表
	var $Tabletd 				= 'web_ordertdetail';	//購物車明細
	var $Tablet_ID 				= 'web_ordert_sn';		//購物車編號暫存表
	
	var $product_db				= 'web_product';		//產品資料表
	var $TableDelivery 			= 'web_delivery';
	
	
	var $Order_List_Data = array();
		
	function Creat_OrderID( $Type = '' ){//創建訂單編號
		
		global $_MemberData;
		
		$db 	= new MySQL();
		
		$sn = date('Ymd');
		
		$States	 = 0;
		$Message = '';
		
		if( $Type == 'main' ){
			
			$Table 	= $this->Table_OrderID;
			$sn = 'O'.substr($sn, -6);
			$db->query("SELECT Order_ID FROM " .$Table. " WHERE Order_ID LIKE '" .$sn. "%' ORDER BY Order_ID DESC");
		}else{
			
			$Table 	= $this->Tabletm;
			$sn = 'X'.substr($sn, -6);
			$db->query("SELECT Ordertm_Sn FROM " .$Table. " WHERE Ordertm_Sn LIKE '" .$sn. "%' ORDER BY Ordertm_Sn DESC");
		}
		
		$row	= $db->query_fetch();
		
		if( empty($row) ){
	
			$_ID = $sn.'0001';
		}else{
		
			$_ID = $sn.str_pad(substr($row[0], -4)+1, 4, 0, STR_PAD_LEFT);
		}
		
		if( $Type != 'main' ){
			
			$db_data = array('Ordertm_Sn' => $_ID );
			
			$db->query_data( $Table, $db_data, 'INSERT');
			
			$db->query_data( $this->Tablet_ID , array( 'Ord_UID' => $_MemberData['Member_ID'] , 'Ord_Sn'=> $_ID ), 'INSERT');
			
			if( !empty($db->Error) ){
				
				$States  = 1;
				$Message = '訂單單號創建失敗, 請聯絡客服人員';
			}
		}else{
			
			$db->query_data($this->Table_OrderID, array('Order_ID'=>$_ID), 'INSERT');
		}
		
		$_data['id']	 	= $_ID;
		$_data['states'] 	= $States;
		$_data['message'] 	= $Message;
		
		return $_data;
	}
	
	function Check_OrderPro( $Order_ID, $Pro_ID, $Pro_Count, $Pro_Unit ){//將產品加入購物車
		
		global $_MemberData;
		
		$db = new MySQL();
		$db->Where = " WHERE Ordertm_Sn = '" .$Order_ID. "' AND Product_ID = '" .$Pro_ID. "'";
		$db->Where .= "AND Ordertd_Unit='" . $Pro_Unit . "'";
		$db->query_sql($this->Tabletd, '*');
		$row = $db->query_fetch();
		
		$States  = 0;
		$Message = '';
		if( !empty($row) ){
			
			$Message = '購物車已有此商品';
			$States  = 3;
			
		}else{
			
			
			$db_data = array(
				'Ordertm_Sn' 	=> $Order_ID,
				'Member_ID' 	=> $_MemberData['Member_ID'], 
				'Product_ID' 	=> $Pro_ID, 
				'Ordertd_Count'	=> $Pro_Count,
				'Ordertd_Unit'	=> $Pro_Unit
				);

			$db->query_data($this->Tabletd, $db_data, 'INSERT');
			
			if( !empty($db->Error) ){
			
				$States  = 2;
				$Message = '加入購物車失敗, 請聯絡客服人員';
			}
			
			$Message = '加入購物車成功';
		}
		
		$_data['states'] 	= $States;
		$_data['message'] 	= $Message;
		
		return $_data;
	}
	
	//---取得暫存購物車產品列表資訊
	function Get_OrdertList( $Order_ID, $Type = '' ){
		
		global $_MemberData;
		
		$db  = new MySQL();
		$db2 = new MySQL();
		
		$db2->Update_S = false;
		
		$Totalprice		= 0;//總金額
		$Totalprice_dis	= 0;//折扣金額
		$discountprice	= 0;//折扣後的金額加總
		$Totalnum 		= 0;//購物車數量
		$Totalnum_Sum  	= 0;//購物車商品數量
		$Total_unFreight = 0;//不含運費及優惠商品金額
		$TotalFeedbak 	= 0;//回饋金額
		
		$_data = array();
		$order_main = $order_list = array();
		
		$Sheet = $this->Tabletd . ' as a LEFT JOIN ' .$this->product_db. ' as b ON a.Product_ID = b.Product_ID';
		$db->Where 		= " WHERE Ordertm_Sn = '" .$Order_ID. "'";
		
		$_Field = "a.*,b.Product_Price, b.Product_Price1, b.Product_Name, b.Product_Mcp, b.Product_Unit, b.Product_Unit_ID";
		
		$db->query_sql($Sheet , $_Field );
		
		while($row = $db->query_fetch('','assoc') ){
			$unit = unserialize($row['Product_Unit_ID']);

			$order_list[$row['Product_ID']][$row['Ordertd_Unit']]['id']		= $row['Product_ID'];
			$order_list[$row['Product_ID']][$row['Ordertd_Unit']]['count']	= $row['Ordertd_Count'];
			$order_list[$row['Product_ID']][$row['Ordertd_Unit']]['name']	= $row['Product_Name'];
			// $order_list[$row['Product_ID']][$row['Ordertd_Unit']]['xxx']	= $unit;

			foreach ($unit as $key => $val) {
				if ($val == $row['Ordertd_Unit']) {
					$order_list[$row['Product_ID']][$row['Ordertd_Unit']]['price']	= unserialize($row['Product_Price'])[$key];
					$order_list[$row['Product_ID']][$row['Ordertd_Unit']]['price1']	= unserialize($row['Product_Price1'])[$key];
					$order_list[$row['Product_ID']][$row['Ordertd_Unit']]['unit']	= unserialize($row['Product_Unit'])[$key];
				}
			}
			
			$order_list[$row['Product_ID']][$row['Ordertd_Unit']]['mcp'] = !empty($row['Product_Mcp'])?Product_Url.$row['Product_Mcp']:'images/no_photo.jpg';
			
			// $order_list[$row['Product_ID']][$row['Ordertd_Unit']]['type']		= $row['Ordertd_Type'];
			// $order_list[$row['Product_ID']][$row['Ordertd_Unit']]['cname']		= $row['ProductC_Name'];
			$order_list[$row['Product_ID']][$row['Ordertd_Unit']]['sub']		= $row['Ordertd_Count'] * $order_list[$row['Product_ID']][$row['Ordertd_Unit']]['price1'];
			// $order_list[$row['Product_ID']][$row['Ordertd_Unit']]['bag']		= $row['Product_Bag'];
			$order_list[$row['Product_ID']][$row['Ordertd_Unit']]['open']		= $row['Product_Open'];
			//-------------------------------------------------------------------------------------------------------------------------
			// $_ThisPrice = $row['Product_Price1'];
			
			// $order_list[$row['Product_ID']]['count']	= $row['Ordertd_Count'];
			// $order_list[$row['Product_ID']]['mcp']		= !empty($row['Product_Mcp'])?Product_Url.$row['Product_Mcp']:'images/no_photo.jpg';
			// $order_list[$row['Product_ID']]['name']		= $row['Product_Name'];
			// $order_list[$row['Product_ID']]['unit']		= $row['Ordertd_Unit'];
			// $order_list[$row['Product_ID']]['price1']	= $_ThisPrice;
			// $order_list[$row['Product_ID']]['price']	= $row['Product_Price'];
			// $order_list[$row['Product_ID']]['sub']		= $row['Ordertd_Count'] * $_ThisPrice;
			
			
			$Totalnum_Sum += $row['Ordertd_Count'];
			$Totalnum++;
		}
		
		//取出訂單資料
		$db  = new MySQL();
		$db->Where 	= " WHERE Ordertm_Sn = '" .$Order_ID. "'";
		$db->query_sql($this->Tabletm, '*');
		if( $row = $db->query_fetch('','assoc') ){
			
			$row['Freight'] = $_fPrice;
		
			$order_main = $row;
		}
		
		//加入購物車商品金額
		foreach( $order_list as $key => $val ){
			foreach ($val as $key2 => $val2) {
				
				$price		 = $val2['price'];			
				$price1		 = $val2['price1'];			
				$sub		 = $val2['sub'];
		
				$Totalprice += $sub;
							
				if( $Type == 'step1' ){
					
					$db2->Where = " WHERE Ordertm_Sn = '" .$Order_ID. "' AND Product_ID = '" .$key. "' AND Ordertd_Unit = '" .$key2. "'";
					$db2->query_data($this->Tabletd, array('Ordertd_Price' => $price, 'Ordertd_Price1' => $price1), 'UPDATE');
				}
			}
		}
		
		//計算運費
		$_Deliery = $this->GET_DELIVERY( $order_main['Delivery_ID'] );
		
		if( $Totalprice < $_Deliery['Delivery_Free'] ) {
			
			$_fPrice = $_Deliery['Delivery_Price'];
		}else{
			
			$_fPrice = 0;
		}
			
		$Totalprice_cart = $Totalprice + $_fPrice;//總商品金額+運廢
		
		$_data['main']				= $order_main;
		$_data['list']				= $order_list;
		$_data['Totalprice']		= $Totalprice;//商品總金額
		$_data['deliveryprice']		= $_fPrice;	//運費金額
		$_data['deliveryfree']		= $_Deliery['Delivery_Free'];//免運金額
		$_data['Totalprice_cart']	= $Totalprice_cart;//購物車總計(含優惠及運費)
		$_data['Totalnum']			= $Totalnum;
		
		return $_data;
	}
	
	
	
	function Creat_OrderMain( $Arr = array() ){//創建訂單
		
		global $product_db , $_MemberData , $Ordersn_db;
		
		$db 				= new MySQL();
		$db->Error_Save 	= true;
		$db->Insert_S		= false;
		
		$mdb->Error_Save 	= false;
		$mdb->Insert_S		= false;
		
		$Sdate	 		= date('Y-m-d H:i:s');
		$States  		= '';
		$Message 		= '';
		$_Order_Finish 	= false;
		
		//暫存購物車編號
		if( !empty( $_MemberData['Member_ID'] ) ) {
		
			$Ordert_ID = GET_OrderSn( $_MemberData['Member_ID'] );
			
		}else if( !empty($_SESSION[$_Website]['website']['order_Sn']) ){
		
			$Ordert_ID = $_SESSION[$_Website]['website']['order_Sn'];
		}else{
			
			$States	 = 'ERROR01';
			$Message = '購物資料有誤, 請重新操作';
		}
		
		//---檢查購物車編號
		if( empty($Ordert_ID) ){
			
			$States	 = 'ERROR02';
			$Message = '購物資料有誤, 請重新操作';
		}
		
		//創建正式訂單
		if( empty($Message) && empty($States) ){
			
			$data 		= $this->Get_OrdertList($Ordert_ID);
			
			$order_main = $data['main'];//取得購物車訂單
			$order_list = $data['list'];//取得購物列表
			
			if( empty($order_list) || empty($order_main) ){
				
				$States	 = 'ERROR03';
				$Message = '購物資料有誤, 請重新操作';
			}else{
				
				$Creat_OrderID 	= $this->Creat_OrderID('main');
				$Orderm_ID 		= $Creat_OrderID['id'];
			}
		}
		
		//---檢查正式訂單編號
		if( empty($Orderm_ID) ){
			
			$States	 = 'ERROR04';
			$Message = '訂單編號創建失敗, 請重新操作';
		}
		
		
		if( empty($Message) && empty($States) ){
			
			$Member_ID 		= trim($order_main['Member_ID']);
			$R_name 		= trim($order_main['Ordertm_RName']);
			$R_sel_city 	= trim($order_main['Ordertm_RCity']);
			$R_sel_county 	= trim($order_main['Ordertm_RCounty']);
			$R_address		= trim($order_main['Ordertm_RAddr']);
			$R_mobile 		= trim($order_main['Ordertm_RMobile']);	
			$RNote 			= trim($order_main['Ordertm_Note']);
			$Delivery_type 	= trim($order_main['Delivery_ID']);
			
			$db_data = array(
				'Orderm_ID' 		=> $Orderm_ID,
				'Member_ID' 		=> $Member_ID,
				'Orderm_RName' 		=> $R_name,
				'Orderm_RMobile' 	=> $R_mobile,
				'Orderm_Freight' 	=> $data['deliveryprice'],
				'Orderm_TotalPrice' => $data['Totalprice_cart'],
				'Orderm_TotalSub' 	=> $data['Totalprice'],
				'Orderm_RCity' 		=> $R_sel_city,
				'Orderm_RCounty' 	=> $R_sel_county,
				'Orderm_RAddr' 		=> $R_address,
				'Orderm_Delivery' 	=> $Delivery_type,
				'Orderm_Note' 		=> $RNote,
				'Orderm_Sdate' 		=> $Sdate
			);	
		
			ob_start();
			
			$db->query_data($this->Tablem, $db_data, 'INSERT');//寫入訂單
			
			ob_end_clean();
			

			if( $db->Error ){
				
				$db->SSD( "會員", $Member_ID, $Orderm_ID.'訂單創建失敗'.$db->Error, $db->sql, 'ERROR', $_SERVER["PHP_SELF"] );
				
				$States	 = 'ERROR05';
				$Message = '訂單創建失敗, 請重新操作';	
			}else{
				
				$db->SSD( "會員", $Member_ID, $Orderm_ID.'訂單創建成功', $db->sql, 'INSERT', $_SERVER["PHP_SELF"] );
				
				
				// foreach( $order_list as $key => $val ){
				foreach( $order_list as $key => $val ){
					foreach ($val as $key2 => $val2) {
						
						$db_data = array(
							'Orderm_ID' 	=> $Orderm_ID,
							'Product_ID' 	=> $key,
							'Orderd_Name' 	=> $val2['name'],
							'Orderd_Count' 	=> $val2['count'],
							'Orderd_Unit' 	=> $val2['unit'],
							'Orderd_Price' 	=> $val2['price'],
							'Orderd_Price1' => $val2['price1'],
							'Orderd_Sdate' 	=> $Sdate
						);
					
						ob_start();
								
						$db->query_data($this->Tabled, $db_data, 'INSERT');
						
						ob_end_clean();
						
						if( $db->Error ){
						
							$db->SSD( "會員", $Member_ID, $Orderm_ID.'訂單明細創建失敗'.$db->Error, $db->sql, 'ERROR', $_SERVER["PHP_SELF"] );
							
							$States	 = 'ERROR06';
							$Message = '訂單明細創建失敗, 請重新操作';	
							break;
						}else{
							
							$_Order_Finish = true;
							
							$db->SSD( "會員", $Member_ID, $Orderm_ID.'訂單明細創建成功', $db->sql, 'INSERT', $_SERVER["PHP_SELF"] );
						}
					}
				}
			}		
		}
		
		
		if( !empty($States) || !empty($Message) ){
			
			//明細創建失敗
			if( !empty($Orderm_ID) ) {
				
				$db = new MySQL();
				$db->Where = " WHERE Orderm_ID = '" .$Orderm_ID. "'";
				$db->query_delete($this->Tablem);
				$db->query_delete($this->Tabled);
			}
		
		//訂單完成
		}else if( $_Order_Finish ){
			
			$States	 = 'success';
			$Message = '';
			
			$db->Where = " WHERE Ord_UID = '" .$Member_ID. "'";
			$db->query_delete("web_ordert_sn");
						
			unset( $_SESSION[$_Website]['website']['order_Sn'] );
			
		}else{
			
			$States	 = 'E001';
			$Message = '無此狀態';
		}
				
		$_data['id'] 		= $Orderm_ID;
		$_data['states'] 	= $States;
		$_data['message'] 	= $Message;
		
		return $_data;
	}
	
	function Get_Order( $Order_ID ){
		
		$db 	= new MySQL();

		
		$db->Where = " WHERE Orderm_ID = '" .$db->val_check($Order_ID). "'";
		$db->query_sql($this->Tablem, '*');
		
		
		$_data = array();
		if( $row = $db->query_fetch() ){
			
			$order_num = $db->query_count($this->Tabled);//總資料筆數
			/*
			$_row['Orderm_ID']		 	= $row['Orderm_ID'];
			$_row['Orderm_TotalPrice']	= $row['Orderm_TotalPrice'];
			$_row['Orderm_Freight']		= $row['Orderm_Freight'];
			$_row['Orderm_TotalSub']	= $row['Orderm_TotalSub'];
			$_row['Orderm_Sdate']		= $row['Orderm_Sdate'];
			$_row['Orderm_States']		= $row['Orderm_States'];*/
			$row['count']		 		= $order_num;
			$_data = $row;
		}

		return $_data;
	}
	
	function Get_OrderList( $Order_ID = ''){//取得訂單資料
		
		global $_MemberData;
		
		$db = new MySQL();
		
		$db->Order_By = ' ORDER BY Orderm_Sdate DESC';
		$db->Where = " WHERE Member_ID = '" .$db->val_check($_MemberData['Member_ID']). "'";
		if( !empty($Order_ID) ) {
			
			$db->Where .= " AND Orderm_ID = '" .$db->val_check($Order_ID). "'";
		}
		$order_num = $db->query_count($this->Tablem);//總資料筆數
				
		$db->query_sql($this->Tablem, '*');
		
		$order_list = array();
		while( $row = $db->query_fetch('','assoc') ){

			$order_list[$row['Orderm_ID']] = $row;
		}

		$_data['list'] 			= $order_list;
		$_data['order_num'] 	= $order_num;
		
		return $_data;
	}
	
	
	function Get_OrderDetails( $Order_ID ){
		
		global $product_db;
		
		ob_start();
		
		$db		= new MySQL();
				
		$order_buylist = array();
		
		$Sheet = $this->Tabled. ' as a LEFT JOIN ' .$product_db. ' as b ON a.Product_ID = b.Product_ID';
		
		$db->Where = " WHERE Orderm_ID = '" .$Order_ID. "'";
		$db->query_sql($Sheet, 'a.*,b.*');
		

		while( $row = $db->query_fetch() ){
			
			// $_row				= array();
			
			// $order_buylist[$row['Product_ID']] = $row;
			
			$unit = unserialize($row['Product_Unit']);

			$order_buylist[$row['Product_ID']][$row['Orderd_Unit']]['id']		= $row['Product_ID'];
			$order_buylist[$row['Product_ID']][$row['Orderd_Unit']]['count']	= $row['Orderd_Count'];
			$order_buylist[$row['Product_ID']][$row['Orderd_Unit']]['name']		= $row['Product_Name'];

			foreach ($unit as $key => $val) {
				
				if ($val == $row['Orderd_Unit']) {
					$order_buylist[$row['Product_ID']][$row['Orderd_Unit']]['price']	= $row['Orderd_Price'];
					$order_buylist[$row['Product_ID']][$row['Orderd_Unit']]['price1']	= $row['Orderd_Price1'];
					$order_buylist[$row['Product_ID']][$row['Orderd_Unit']]['unit']		= $row['Orderd_Unit'];
				}
			}
			$order_buylist[$row['Product_ID']][$row['Orderd_Unit']]['mcp'] = !empty($row['Product_Mcp'])?Product_Url.$row['Product_Mcp']:'images/no_photo.jpg';
			
			// $order_buylist[$row['Product_ID']][$row['Orderd_Unit']]['type']		= $row['Orderd_Type'];
			// $order_buylist[$row['Product_ID']][$row['Orderd_Unit']]['cname']	= $row['ProductC_Name'];
			$order_buylist[$row['Product_ID']][$row['Orderd_Unit']]['sub']		= $row['Orderd_Count'] * $order_buylist[$row['Product_ID']][$row['Orderd_Unit']]['price1'];
			// $order_buylist[$row['Product_ID']][$row['Orderd_Unit']]['bag']		= $row['Product_Bag'];
		}
		
		$_data['list'] = $order_buylist;
						
		ob_end_clean();
		
		return $_data;
	}
	
	//取得貨到付款上限金額
	function GET_DELIVERY_LIMIT(){
		
		$db = new MySQL();
		
		$db->query_sql( 'web_setting'  , 'Setting_PriceLimit');
		
		if( $row = $db->query_fetch('','assoc') ){
			
			return $row['Setting_PriceLimit'];
		}
	}
	
	function GET_ORDER_SUB( $Order_ID ){
	
		//-----------------計算訂單金額--------------------
		$db 			= new MySQL();
		$Sheet 			= $this->Tabletd . ' as a LEFT JOIN ' .$this->product_db. ' as b ON a.Product_ID = b.Product_ID';
		$_Field 		= "a.*, b.*";
		$_Sub 			= 0;
		
		$db->Where 		= " WHERE Ordertm_Sn = '" .$Order_ID. "'";
		$db->query_sql($Sheet , $_Field );
		
		while( $row = $db->query_fetch('','assoc') ){
		
			$unit = unserialize($row['Product_Unit_ID']);
			$list[$row['Product_ID']][$row['Ordertd_Unit']]['count']	= $row['Ordertd_Count'];
			foreach ($unit as $key => $val) {
				
				if ($val == $row['Ordertd_Unit']) {
					
					// $list[$row['Product_ID']][$row['Ordertd_Unit']]['price']		= $row['Orderd_Price'];
					$list[$row['Product_ID']][$row['Ordertd_Unit']]['price1']	= $row['Ordertd_Price1'];
					// $list[$row['Product_ID']][$row['Ordertd_Unit']]['unit']		= $row['Ordertd_Unit'];
				}
			}
			// $list[$row['Product_ID']][$row['Ordertd_Unit']]['sub']		= $row['Ordertd_Count'] * $order_list[$row['Product_ID']][$row['Ordertd_Unit']]['price1'];
			$_Sub	+= $list[$row['Product_ID']][$row['Ordertd_Unit']]['count'] * $list[$row['Product_ID']][$row['Ordertd_Unit']]['price1'];
		}
		
		
		// $_data['list'] = $order_buylist;
						
		return $_Sub;
	}
	
	//取得付款方式列表
	function GET_DELIVERY_LIST( $Order_ID ){
		
		$_Sub	= $this->GET_ORDER_SUB( $Order_ID );
		
		//-------------取得貨到付款上限金額----------------
		
		$_Limit = $this->GET_DELIVERY_LIMIT();
		
		//---------------取得付款方式列表------------------
		
		$db = new MySQL();
		$db->Where = "Where Delivery_Open = 1 ";
		$db->Where .= " And Delivery_Limit > ".$_Sub;
		$db->query_sql( $this->TableDelivery  , '*');
		
		while( $row = $db->query_fetch('','assoc') ){
			
			$_data[ $row['Delivery_ID'] ] = $row;
		}
		
		
		/* if( $_Sub > $_Limit ) {
		
			unset( $_data[202] );
		}
		 */
		return $_data;
	}
	
	//取得付款方式列表
	function GET_DELIVERY( $Input ){
		
		$db = new MySQL();
		
		$db->Where = "Where Delivery_ID = ".$Input;
		$db->query_sql( $this->TableDelivery  , 'Delivery_Price, Delivery_Free, Delivery_Name');
		
		if( $row = $db->query_fetch('','assoc') ){
			
			return $row;
		}
	}
	
	function Creat_Member( $Order_ID ){
		
		global $member_db;
		
		$db = new MySQL();
		
		$db->Where = " WHERE Ordertm_Sn = '" .$Order_ID. "'";
		$db->query_sql($this->Tabletm, '*');
		$row = $db->query_fetch();
		
		$Sdate			= date('Y-m-d H:i:s');
		
		$States  		= 0;
		$Message 		= '';
		$_MemberData 	= '';
		if( empty($row) ){
			
			$States	 = 'CM001';
			$Message = '會員資訊有誤, 請重新操作';
		}else{
			
			$MAcc 		= $row['Ordertm_Acc'];
			$MPwd 		= $row['Ordertm_Pwd'];
			$MName 		= $row['Ordertm_Name'];
			//$MSex 		= $row['MSex'];
			$MTel 		= $row['Ordertm_Tel'];
			$MMobile 	= $row['Ordertm_Mobile'];
			$MCity 		= $row['Ordertm_City'];
			$MCounty	= $row['Ordertm_County'];
			$MAddress 	= $row['Ordertm_Address'];
			
			$SN 		= 'M'.substr(date('Ymd'), -6);
			$Member_ID 	= GET_NEW_ID($member_db, 'Member_ID', $SN, 3);
			
			$db_data 	= array(
				'Member_ID' => $Member_ID,
				'Member_Acc' => $MAcc,
				'Member_Pwd' => $MPwd,
				'Member_Email' => $MAcc,
				//'Member_Company' => $company,
				//'Member_Uniform' => $uniform,
				'Member_Name' => $MName,
				//'Member_Gender' => $MSex,
				//'Member_Birthday' => $yyyy.'-'.$mm.'-'.$dd,
				'Member_Tel' => $MTel,
				//'Member_Fax' => $fax,
				'Member_Mobile' => $MMobile,
				//'Member_Zipcode' => $zipcode,
				'Member_City' => $MCity,
				'Member_County' => $MArea,
				'Member_Address' => $MAddress,
				//'Member_Intro' => $introduction,
				'Member_Open' => 1,
				'Member_Emailauth' => 1,
				'Member_Sdate' => $Sdate
			); 
			
			ob_start();
			
			$db->query_data($member_db, $db_data, 'INSERT');
			
			if( !empty($db->Error) ){
							
				$States	 = 'CM002';
				$Message = '會員資料創建失敗, 請重新操作';
			}else{
				
				$db = new MySQL();
				
				$db->Where = " WHERE Member_ID = '" .$Member_ID. "'";
				$db->query_sql($member_db, '*');
				$_MemberData = $db->query_fetch();
				
				$_SESSION['website']['member_id'] 	= $Member_ID; 
				$_SESSION['website']['member_acc'] 	= $_MemberData['Member_Acc'];
				$_SESSION['website']['member_name'] = $_MemberData['Member_Name'];	
			}
			
			ob_end_clean();
		}
		
		$_data['data'] 		= $_MemberData;
		$_data['states'] 	= $States;
		$_data['message'] 	= $Message;
		
		return $_data;
	}
	
	//創建虛擬銀行帳戶(結帳用)
	function Create_Virtual_TradAcc(){
		
		$_break 	= true;
		$db 		= new MySQL();
		
		while( $_break ){
			
			
			$_code = '8888'.str_pad( rand(0,99999999) , 8, 0, STR_PAD_LEFT );
			
			$db->Where = "Where Virtual_Code = '".$_code."'";
			if( $db->query_count('web_virtual') == 0 ){
				$db->Where = "";
				$db->query_data('web_virtual', array('Virtual_Code' => $_code), 'INSERT');
				$_break = false;
			}
		}
		
		return $_code;
	}

	function Get_Feedback( $Member_ID ){
		
		global $Feedback_db;
		
		$db = new MySQL();
		
		$db->Where = " WHERE Member_ID = '" .$Member_ID. "'";
		$db->Where .= " AND Feedback_status = 0 ";
		$db->query_sql($Feedback_db, '*');
		
		$Total_Amt = 0;
		
		while( $row = $db->query_fetch() ){
			
			$_list[$row['Feedback_ID']] = $row;
			$Total_Amt+=$row['Feedback_Amt'];
		}
		
		$_data['list'] 		= $_list;
		$_data['Total_Amt'] = $Total_Amt;
		
		return $_data;
	}	
	

	
}
?>