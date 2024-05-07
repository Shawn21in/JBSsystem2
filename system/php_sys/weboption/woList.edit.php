<?php if( !function_exists('Chk_Login') ) header('Location: ../../index.php'); ?>

<div class="tab_container">

    <form id="form_edit_save" class="form-horizontal">

    <?php foreach( $_html_ as $key => $val ){ 
            
            $table_sn = 'tabsn'.$key;
    ?>
        <div class="Table_border <?=$table_sn?>">
        
            <input type="hidden" id="<?=$Main_Key?>" name="<?=$Main_Key?>[]" value="<?=$val[$Main_Key]?>">
        
        <?php 
		if( $WOtype == 1 ){
        
			//-----------------------------------------//
			$Arr_Name = 'WO_Name';
            if( $CfgWoption[$Arr_Name]['show'] ){
				
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
			}
			//-----------------------------------------//
			$Arr_Name = 'WO_Tel'; 
			if( $CfgWoption[$Arr_Name]['show'] ){ 
			
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1, '', '');	
			}
			//-----------------------------------------//
		/* 	$Arr_Name = 'WO_Tel1'; 
			if( $CfgWoption[$Arr_Name]['show'] ){ 
			
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1, '', '');	
			} */
			
			//-----------------------------------------//
			/*
			$Arr_Name = 'WO_Extension';
            
			echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
			*/
			//-----------------------------------------//			
			/* $Arr_Name = 'WO_Fax'; 
			if( $CfgWoption[$Arr_Name]['show'] ){ 
			
				echo $_TF->html_number($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1, '', '');	
			} */
			//-----------------------------------------//
			$Arr_Name = 'WO_Email';
            if( $CfgWoption[$Arr_Name]['show'] ){
				
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
			}
			//-----------------------------------------//
			$Arr_Name = 'WO_OpenTime'; 
            // if( $CfgWoption[$Arr_Name]['show'] ){
				
				echo $_TF->html_textarea($table_info[$Arr_Name]['Comment'], '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '');     
			// }
			//-----------------------------------------//
			$Arr_Name = 'WO_Zip'; 
			if( $CfgWoption[$Arr_Name]['show'] ){ 
			
				echo $_TF->html_number($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1, '', '');	
			}
			//-----------------------------------------//
			$Arr_Name = 'WO_Addr';
            if( $CfgWoption[$Arr_Name]['show'] ){
				
				$selcity  	= '#'.$Arr_Name;
				$selcounty	= '#WO_Addr1';
				echo $_TF->html_city($table_info[$Arr_Name]['Comment'], '', $Arr_Name, $val, '', $table_sn, $selcity, $selcounty);
			}
			//-----------------------------------------//
			$Arr_Name = 'WO_Addr1';
            if( $CfgWoption[$Arr_Name]['show'] ){
				
				echo $_TF->html_county($table_info[$Arr_Name]['Comment'], '', $Arr_Name, $val, '');
			}
			//-----------------------------------------//
			$Arr_Name = 'WO_Addr2';
            if( $CfgWoption[$Arr_Name]['show'] ){
				
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
			}
			//-----------------------------------------//
        }else if( $WOtype == 2 ){ 
		
			//-----------------------------------------//
			$Arr_Name = 'WO_Title';
            if( $CfgWoption[$Arr_Name]['show'] ){
				
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
			}
			//-----------------------------------------//
			$Arr_Name = 'WO_Url';
            if( $CfgWoption[$Arr_Name]['show'] ){
				
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
			}
			//-----------------------------------------//
			
			$Arr_Name = 'WO_Description';
            if( $CfgWoption[$Arr_Name]['show'] ){
				
				echo $_TF->html_textarea($table_info[$Arr_Name]['Comment'], 'description : 輸入網站的介紹', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '');     
			}
			//-----------------------------------------//
			
			
			$Arr_Name = 'WO_Keywords';
            if( $CfgWoption[$Arr_Name]['show'] ){
				
				
				//echo $_TF->html_textarea($table_info[$Arr_Name]['Comment'], 'keywords : 網站,介紹,關鍵字,keywords,web,site', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '');     
				echo $_TF->html_keyword( $table_info[$Arr_Name]['Comment'], '輸入關鍵字後按下ENTER完成', $Arr_Name, $val );
			}
			//-----------------------------------------//
			
			// $Arr_Name = 'WO_Privacy';
            // if( $CfgWoption[$Arr_Name]['show'] ){
				
				// echo $_TF->html_textedit($table_info[$Arr_Name]['Comment'], $Arr_Name, $val);
			// }
			//-----------------------------------------//
			$Arr_Name = 'WO_LineLink';
            if( $CfgWoption[$Arr_Name]['show'] ){
				
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '~ss123 或者 @ss123', '一般LINE前面加~，LINE@前面加@，網址https://line.me/ti/p/~ss123 或者 @ss123', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
			}
			//-----------------------------------------//
			$Arr_Name = 'WO_FBLink';
            if( $CfgWoption[$Arr_Name]['show'] ){
				
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '請填入FB連結', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
			}
			//-----------------------------------------//
			$Arr_Name = 'WO_IGLink';
            if( $CfgWoption[$Arr_Name]['show'] ){
				
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '請填入IG連結', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
			}
			
			//-----------------------------------------//
			/*$Arr_Name = 'WO_Youtube';
            if( $CfgWoption[$Arr_Name]['show'] ){
				
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], 'W177YvtTeQ4', '請填入Youtube影片碼', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
			}*/
			//-----------------------------------------//
			$Arr_Name = 'WO_YoutubeLink';
            if( $CfgWoption[$Arr_Name]['show'] ){
				
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '請填入Youtube影片連結', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
			}
			//-----------------------------------------//
			
			$Arr_Name = 'WO_GMAP';
            if( $CfgWoption[$Arr_Name]['show'] ){
				
				echo $_TF->html_textarea($table_info[$Arr_Name]['Comment'], '分享→嵌入地圖→自訂大小→600 x 450', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '');     
			}
			//-----------------------------------------//
			
			$Arr_Name = 'WO_GMAP_Link';
			
            echo $_TF->html_textarea($table_info[$Arr_Name]['Comment'], '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '');     
			
			//-----------------------------------------//
			/*
			$Arr_Name = 'WO_GAnalytics';
            if( $CfgWoption[$Arr_Name]['show'] ){
				
				echo $_TF->html_textarea($table_info[$Arr_Name]['Comment'], 'Google Analytics 網站追蹤碼', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '');     
			}*/
			//-----------------------------------------//
			/*
			$Arr_Name = 'WO_MapLat';
            if( $CfgWoption[$Arr_Name]['show'] ){
				
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
			}
			//-----------------------------------------//
			$Arr_Name = 'WO_MapLng';
            if( $CfgWoption[$Arr_Name]['show'] ){
				
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
			}*/

			//-----------------------------------------//
			$Arr_Name = 'WO_Open';
            if( $CfgWoption[$Arr_Name]['show'] ){
				
				echo $_TF->html_checkbox($table_info[$Arr_Name]['Comment'], '', $Arr_Name, $val, '', 1);
			}
			//-----------------------------------------//
			$Arr_Name = 'WO_Debug';
            if( $Admin_data['Group_ID'] == 1 ){
				
				echo $_TF->html_checkbox($table_info[$Arr_Name]['Comment'], '', $Arr_Name, $val, '', 1);
			}
		}else if( $WOtype == 3 ){ 
		
			//-----------------------------------------//
			$Arr_Name = 'WO_StmpHost';
            if( $CfgWoption[$Arr_Name]['show'] ){
				
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '範例 : ms39.hinet.net, msa.hinet.net, msr.hinet.net', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
			}
			//-----------------------------------------//
			$Arr_Name = 'WO_StmpPort';
            if( $CfgWoption[$Arr_Name]['show'] ){
				
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '預設 : 25', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
			}
			//-----------------------------------------//
			$Arr_Name = 'WO_SendName';
            if( $CfgWoption[$Arr_Name]['show'] ){
				
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '例如 : 王小名', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
			}
			//-----------------------------------------//
			$Arr_Name = 'WO_SendEmail';
            if( $CfgWoption[$Arr_Name]['show'] ){
				
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '例如 : xxxxxx@msa.hinet.net', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
			}
			//-----------------------------------------//
			$Arr_Name = 'WO_StmpAuth';
            if( $CfgWoption[$Arr_Name]['show'] ){
				
				echo $_TF->html_checkbox($table_info[$Arr_Name]['Comment'], '', $Arr_Name, $val, '', 1, $Arr_Name);
			}
			//-----------------------------------------//
			$Arr_Name = 'WO_StmpAcc';
            if( $CfgWoption[$Arr_Name]['show'] ){
				
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1, $val['WO_StmpAuth']?false:true, $Arr_Name);
			}
			//-----------------------------------------//
			$Arr_Name = 'WO_StmpPass';
            if( $CfgWoption[$Arr_Name]['show'] ){
				
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1, ($val['WO_StmpAuth']?false:true), $Arr_Name);
			}
			//-----------------------------------------//
			$Arr_Name = 'WO_StmpSecure';
            if( $CfgWoption[$Arr_Name]['show'] ){
				
				echo $_TF->html_select($table_info[$Arr_Name]['Comment'], '', $Arr_Name, $val, '', 1, $secure_states, 'NO_FIRST_OPTION');
			}
			//-----------------------------------------//
			$Arr_Name = 'WO_MailSubject';
            if( $CfgWoption[$Arr_Name]['show'] ){
				
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
			}
			//-----------------------------------------//
			$Arr_Name = 'WO_AddrName';
            if( $CfgWoption[$Arr_Name]['show'] ){
				
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
			}
			//-----------------------------------------//
			$Arr_Name = 'WO_AddrEmail';
            if( $CfgWoption[$Arr_Name]['show'] ){
				
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
			}
			//-----------------------------------------//
			$Arr_Name = 'WO_MailBody';
            if( $CfgWoption[$Arr_Name]['show'] ){
				
				echo $_TF->html_textedit($table_info[$Arr_Name]['Comment'], $Arr_Name, $val);
			}
		
		//隱形驗證碼reCaptcha
		}else if( $WOtype == 4 ){ 
		
			//-----------------------------------------//
			$Arr_Name = 'WO_StmpHost';
            if( $CfgWoption[$Arr_Name]['show'] ){
				
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '範例 : ms39.hinet.net, msa.hinet.net, msr.hinet.net', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
			}
			//-----------------------------------------//
			$Arr_Name = 'WO_StmpPort';
            if( $CfgWoption[$Arr_Name]['show'] ){
				
				echo $_TF->html_text($table_info[$Arr_Name]['Comment'], '', '預設 : 25', $table_info[$Arr_Name]['Field_Length'], $Arr_Name, $val, '', 1);
			}
			//-----------------------------------------//
		
		//設計LOGO圖
		}else if( $WOtype == 5 ){ 
		
			//-----------------------------------------//
			$Arr_Name = 'WO_LOGO';
					
			echo $_TF->html_uploadimg($table_info[$Arr_Name]['Comment'], '。您的企業形象標誌，會出現在網站的每一頁。圖片 280 x 280 pixel (PNG或JPG)', $Arr_Name, $val, '', 0);
			//-----------------------------------------//
			$Arr_Name = 'WO_FooterLOGO';
					
			echo $_TF->html_uploadimg($table_info[$Arr_Name]['Comment'], '。您的企業形象標誌，會出現在網站的每一頁。圖片 280 x 280 pixel (PNG或JPG)', $Arr_Name, $val, '', 0);
			//-----------------------------------------//
			$Arr_Name = 'WO_LOGO2';
					
			echo $_TF->html_uploadimg($table_info[$Arr_Name]['Comment'], '。登入本系統後台時左側呈現之LOGO圖案', $Arr_Name, $val, '', 0);
			//-----------------------------------------//
			// $Arr_Name = 'WO_favicon';
					
			// echo $_TF->html_uploadimg($table_info[$Arr_Name]['Comment'], '。您的網址小圖標會出現在瀏覽器網址列上和在書籤的網站名旁邊。圖片大小: 32 x 32 pixel (格式為 JPG 或 PNG)', $Arr_Name, $val, '', 0);
			//-----------------------------------------//
			$Arr_Name = 'WO_ShareIcon';
					
			echo $_TF->html_uploadimg($table_info[$Arr_Name]['Comment'], '。當使用者欲分享文章至社群網站並該文章無圖片可呈現時，呈現之預覽圖。(格式為 JPG 或 PNG)', $Arr_Name, $val, '', 0);
			//-----------------------------------------//
			$Arr_Name = 'WO_FooterImg';
					
			echo $_TF->html_uploadimg($table_info[$Arr_Name]['Comment'], '。版尾廣告圖(格式為 JPG 或 PNG)', $Arr_Name, $val, '', 0);
			//-----------------------------------------//
			
        }
		?>
        </div>
    <?php } ?>
    
        <div class="clear_both form-actions">
            <button id="saveb" class="btn btn-info" type="button" <?=(Menu_Use($Now_List, 'edit')||Menu_Use($Now_List, 'add'))?'onclick="form_edit_save()"':'disabled="disabled"'?>>
                <i class="ace-icon fa fa-check bigger-110"></i>儲存
            </button>&nbsp;&nbsp;&nbsp; 
                    
            <button id="rsetb" class="btn btn" type="reset">
                <i class="ace-icon fa fa-check bigger-110"></i>重設
            </button>&nbsp;&nbsp;&nbsp; 
            
        <?php if( $WOtype == 3 ){ ?>
            <button id="sendb" class="btn btn eshow" e-txt="" type="button">
                <i class="ace-icon fa fa-envelope-o bigger-110"></i>測試寄信
            </button>
        <?php } ?>
        </div>
    </form>
</div>