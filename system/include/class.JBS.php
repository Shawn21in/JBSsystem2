<?php

class JBS{
	
	var $item_db 	= 'item';
	var $kind_db 	= 'kind';
	var $cust_db 	= 'cust';
	var $stock_db 	= 'stock';
	var $order_db 	= 'weborder';
	var $orderd_db 	= 'weborderd';
	
	var $cuxa1no = 'TWD';
	var $cux1no  = '01';
	var $cux3no  = '2';
	var $cux5no  = '4';
	
	var $sel_db  = '';
	
	var $Error   = '';	
	
	function __construct( $sel_db = '' ){
		
		$this->sel_db = $sel_db;
	}
	
	function insert_mem( $cuno, $data = array() ){
		
		$mdb = new MsSQL($this->sel_db);
		
		if( !empty($cuno) && !empty($data) ){
			
			$data['cuxa1no']	= $this->cuxa1no;
			$data['cux1no'] 	= $this->cux1no;
			$data['cux3no'] 	= $this->cux3no;
			
			if( empty($data['cux5no']) ){
				
				$data['cux5no']	= $this->cux5no;
			}
							
			$mdb->query_data($this->cust_db, $data, 'INSERT');
			
			$this->Error = $mdb->Error;
		}
	}
	
	function update_mem( $cuno, $data = array() ){
		
		$mdb = new MsSQL($this->sel_db);
		
		if( !empty($cuno) && !empty($data) ){
			
			$mdb->Where = " WHERE cuno = '" .$cuno. "'";
			$mdb->query_data($this->cust_db, $data, 'UPDATE');
			
			$this->Error = $mdb->Error;
		}
	}
		
	function Check_Member( $No ){
		
		$Data = array();
		$db   = new MySQL();
		$mdb  = new MsSQL();

		$db->query("SELECT * FROM member WHERE mNo = '" .$No. "'");
		$_member = $db->query_fetch();
		
		if( empty($_member[mJbsNo]) ){
			
			if( !is_numeric($_member[mMobile]) ){
				
				JsBackHref("手機格式錯誤，請進到會員資料裡修改手機格式(請填寫全數字)，再重新下單","join_modify.php");
				exit;
			}
			$cuno	  = substr($_member[mMobile], 0, 10);
			
			$db->query("UPDATE member SET mJbsNo = '" .$cuno. "' WHERE mNo = '" .$No. "'");
		}else{
			
			$cuno = $_member[mJbsNo];
		}			
			
		$mdb->query("SELECT cuno, cuname1, cuaddr1, cuatel1 FROM cust WHERE cuno = '" .$cuno. "'");
		if( $mdb->query_rows() == 0 ){
			
			$cuname2  = mb_substr( $_member[mName], 0, 50 , "utf-8" );
			$cuname1  = mb_substr( $_member[mName], 0, 10 , "utf-8" );
			$cusex	  = $_member[mSex] == 1 ? "男" : "女";
			$CityName = GetCity($_member[mCity], "NAME");
			$AreaName = GetArea($_member[mArea], "NAME");
			$cuaddr1  = $CityName.$AreaName.$_member[mAddress];
			$cubirth1 = date("Ymd", strtotime($_member[mBirthday]));
			$cubirth  = (substr($cubirth1, 0, 4) - 1911).substr($cubirth1, 4);
			$cuatel1  = $_member[mMobile];

			$data = array(
						"cuno" => $cuno, 											//編號(10碼)
						"cuname2"=> $cuname2,										//名字(50碼)
						"cuname1"=> $cuname1,										//名字(10碼)		
						"cutel1"=> $_member[mTel],									//電話(20)
						"cuatel1"=> $cuatel1,										//手機(16)
						"cuemail"=> $_member[mEmail],								//EMAIL
						"cudate"=> (date("Y")-1911).date("md"),						//創建日期
						"cudate1"=> date("Ymd"),									//創建日期
						"cubirth"=> $cubirth,										//生日
						"cubirth1"=> $cubirth1,    									//生日
						"cusex"=> $cusex,											//性別
						"cuaddr1"=> $cuaddr1,										//住址
						"cuudf1"=> $CityName,										//住址
						"cuudf2"=> $AreaName,										//住址
						"cuudf3"=> $_member[mAddress],								//住址
						"cuxa1no"=> $this->cuxa1no,
						"cux1no"=> $this->cux1no,
						"cux3no"=> $this->cux3no,
						"cux5no"=> $this->cux5no
					);

			$mdb->query_data("cust", $data, INSERT);
			
			$db->SSD($mNo, $cuno, $cuno."創建JBS會員資料", $mdb->sql, "INSERT", $_SERVER[PHP_SELF]);
			
			if( $mdb->Error ){
				
				JsBackHref("會員資料有誤,如還有問題請聯絡我們","cart2.php");
				exit;
			}
		}else{
			
			$mrow = $mdb->query_fetch();
			$cuname1 = $mrow[cuname1];
			$cuatel1 = $mrow[cuatel1];
			$cuaddr1 = $mrow[cuaddr1];
		}
		
		$Data[mJbsNo] 	= $cuno;
		$Data[mName]  	= $cuname1;
		$Data[mMobile] 	= $cuatel1;
		$Data[mAddress]	= $cuaddr1;
		
		return $Data;
	}
	
	function Member_Data( $No ){
		
		$Data = array();
		$db   = new MySQL();
		$mdb  = new MsSQL();
		
		$mdb->Where = " WHERE cuno = '" .$No. "'";
		$mdb->query_sql("cust", "*");
		$Data = $mdb->query_fetch();
		
		$Data[mName] 	= $Data[cuname1];
		$Data[mSex] 	= $Data[cusex] == "男" ? 1 : 2;
		$Data[mTel] 	= $Data[cutel1];
		$Data[mMobile] 	= $Data[cuatel1];
		$Data[mAddress]	= $Data[cuudf3];
		
		$db->query("SELECT CID FROM city WHERE City = '" .$Data[cuudf1]. "'");
		$row = $db->query_fetch();
		$Data[mCity] = $row[CID];
		
		$db->query("SELECT AID FROM area WHERE Area = '" .$Data[cuudf2]. "'");
		$row = $db->query_fetch();
		$Data[mArea] = $row[AID];
		
		return $Data;
	}
	
	function get_stock( $data, $store ){//抓庫存
				
		$inarr = array();
		if( is_array($data) ){
			
			$inarr 		= $data;
		}else{
			
			$inarr[] 	= $data;
		}

		$mdb = new MsSQL($this->sel_db);
		
		$mdb->Where = " WHERE itno IN ('" .implode("','" ,$inarr). "') AND stno = '" .$store. "'";//抓庫存
		$mdb->query_sql($this->stock_db, '*');
		$Stock_Arr = array();
		while( $mrow = $mdb->query_fetch() ){
			
			$Stock_Arr[$mrow['itno']] = intval($mrow['itqty']);
		}
		
		$mdb->Where = " WHERE b.itno IN ('" .implode("','" ,$inarr). "') AND a.orderState != '2'";//未出貨
		$mdb->query_sql($this->order_db. ' as a LEFT JOIN ' .$this->orderd_db. ' as b ON a.orno = b.orno', 'b.itno, b.qty');
		$Ordernum_Arr = array();
		while( $mrow = $mdb->query_fetch() ){
			
			$Ordernum_Arr[$mrow['itno']] += intval($mrow['qty']);
		}
				
		foreach( $inarr as $itno ){
			
			if( empty($Stock_Arr[$itno]) ){
				
				$Stock_Arr[$itno] = 0;
			}else{
				
				$Stock_Arr[$itno] -= $Ordernum_Arr[$itno];
			}
		}
				
		return $Stock_Arr;
	}
	
	function check_item( $itno ){
		
		$mdb = new MsSQL($this->sel_db);
		
		$mdb->Where = " WHERE itno = '" .$itno. "'";
		$mdb->query_sql($this->item_db, 'itno');
		if( $mdb->query_rows() > 0 ){
			
			return true;
		}else{
			
			return false;
		}
	}
	
	function creat_item( $itno ){
		
		$mdb = new MsSQL($this->sel_db);
		
		$mdb_data = array(
			'itno' => $itno,
			'itname' => $this->itname,
			'itdate' => (date('Y') - 1911).date('md'),
			'itdate1' => date('Ymd'),
			'itbuyunit' => 2,
			'itsalunit' => 2,
			'itcodeslt' => 1,
			'itcodeno' => 2,
			'ittrait' => 3,
			'isenable' => 1,
			'itcostslt' => 1
		);
		
		$mdb->query_data($this->item_db, $mdb_data, 'INSERT');
	}
}
?>