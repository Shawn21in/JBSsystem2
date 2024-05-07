<?php if( !function_exists('Chk_Login') ) header('Location: ../index.php'); ?>

<div class="form-horizontal">
<?php foreach( $_html_ as $key => $val ){ ?>
    <table class="table control-table1">
        <tbody>
        	<tr>	       	                                                                                	   	
        		<th colspan="2">訂單資訊</th>
        	</tr>
        
        	<tr>
                <td class="style">訂單編號</td>
                <td><?=$val[$Main_TablePre.'_ID']?></td>
            </tr>
            
            <tr>
                <td class="style">下單日期</td>
                <td><?=$val[$Main_TablePre.'_Sdate']?></td>
            </tr>
			
			<tr>
                <td class="style">付款方式</td>
                <td><?=$val['Delivery_Name']?></td>
            </tr>
            
            <tr>
                <td class="style">狀態</td>
                <td><?=$order_states[$val[$Main_TablePre.'_States']]?></td>
            </tr>
        </tbody>
    </table>
            
    <table class="table control-table1" border="1" cellspacing="0" cellpadding="0">
        <tbody>
        	<tr>	       	                                             	   	
                <th colspan="5">訂單明細</th>
            </tr>
        
            <tr>
                <td class="style2">商品名稱</td>
                <td class="style2">數量</td>
                <td class="style2">金額</td>
                <td class="style2">小計</td>
            </tr>
            
            <?php foreach( $order_list as $bkey => $bval ){ ?>
            <tr>
                <td><?=$bval[$Main_TablePre2.'_Name']?></td>
                <td><?=$bval[$Main_TablePre2.'_Count']?></td>
                <td><?=number_format($bval[$Main_TablePre2.'_Price1'])?></td>
                <td><?=number_format($bval[$Main_TablePre2.'_Price1']*$bval[$Main_TablePre2.'_Count'])?></td>
            </tr>
            <?php } ?>
            
            <tr>
                <td colspan="3" class="center">運費</td>
                <td class="style1"><?=number_format($val[$Main_TablePre.'_Freight'])?></td>
            </tr>
            
            <tr>
                <td colspan="3" class="center">總計金額</td>
                <td class="style1"><?=number_format($val[$Main_TablePre.'_TotalPrice'])?></td>
            </tr>
        </tbody>
    </table>
            
            
    <table class="table control-table1" border="0" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <th colspan="2">訂購資料</th>
            </tr>
        
            <tr>
                <td class="style">收件人</td>
                <td><?=$val[$Main_TablePre.'_RName']?></td>
            </tr>
            
            <tr>
                <td class="style">收件人聯絡手機</td>
                <td><?=$val[$Main_TablePre.'_RMobile']?></td>
            </tr>

            <tr>
                <td class="style">收件人地址</td>
                <td><?=$val[$Main_TablePre.'_RCity'].$val[$Main_TablePre.'_RCounty'].$val[$Main_TablePre.'_RAddr']?></td>
            </tr>
			
			<tr>
                <td class="style">匯款後五碼</td>
                <td><?=$val[$Main_TablePre.'_card5no']?></td>
            </tr>
			
        </tbody>
    </table>
<?php } ?>
</div>