<form class="easyui-form" id="purchase-express-arrive-form">
	<input type="hidden" name="purchase_express_id" value="<?=$purchase_express['id']?>">
    <fieldset border="10px">
        <legend>订单信息:</legend>
        订单编号：<input class="feng" name="order_number" disabled="disabled" value="<?php echo $purchase_express['order_number']; ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        接受方：<input class="feng" name="name" disabled="disabled" value="<?php echo $purchase_express['operating_company_name']; ?>" style="width: 40%;"/><br>
        <div
            class="easyui-panel"
            style="width:100%;margin-bottom:15px;"
            closable="false"
            collapsible="false"
            minimizable="false"
            maximizable="false"
            border="false"
        ></div>
        运单编号：<input class="feng" name="express_number" disabled="disabled" value="<?php echo $purchase_express['express_number']; ?>"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;


        送达时间：<input class="feng" name="estimated_arrive_time" disabled="disabled" value="<?php echo date("Y-m-d",$purchase_express['estimated_arrive_time']); ?>"/><br>
    </fieldset>
    <div
        class="easyui-panel"
        style="width:100%;margin-bottom:15px;"
        closable="false"
        collapsible="false"
        minimizable="false"
        maximizable="false"
        border="false"
    ></div>

    <fieldset border="3px">
        <legend>订单详情核对:</legend>
        <table cellpadding="7"  cellspacing="0" text-align="center"  width="100%" border="1px solid" >
            <thead>
                <th>类别</th>
                <th>品牌</th>
                <th>车型</th>
                <th>车架号</th>
                <th>发票号</th>
                <th>车辆存放点</th>
            </thead>
            <tbody>
            <?php foreach ($order_detials as $key=>$dat):?>
            <tr>
                <td><?php if($dat['item_type']=='zhengche') {echo "整车";}?></td>
                <td><?php echo $dat['brand_name']?></td>
                <td><?php echo $dat['type_name']?></td>
				<?php
					if($dat['is_storage']){
						echo '<td>'.$dat['vehicle_dentification_number']."</td>\n";
						echo '<td>'.$dat['invoice_number']."</td>\n";
						echo '<td>'.$dat['storage_location']."</td>\n";
						echo '<input type="hidden" name="vehicle_dentification_number[]" value="'.$dat['vehicle_dentification_number'].'">'."\n";
						echo '<input type="hidden" name="invoice_number[]" value="'.$dat['invoice_number'].'">'."\n";
						echo '<input type="hidden" name="storage_location[]" value="'.$dat['storage_location'].'">'."\n";
					}else {
						echo '<td><input type="text" name="vehicle_dentification_number[]" style="width:100px;" value="'.$dat['vehicle_dentification_number'].'"></td>'."\n";
						echo '<td><input type="text" name="invoice_number[]" style="width:100px;" value="'.$dat['invoice_number'].'"></td>'."\n";
						echo '<td><input type="text" name="storage_location[]" style="width:100px;" value="'.$dat['storage_location'].'"></td>'."\n";
					}
				?>
            </tr>
            <input type="hidden" name="id[]" value="<?php echo $dat['id'] ?>">
            <?php endforeach;?>
            </tbody>
        </table>
    </fieldset>

    <div
            class="easyui-panel"
            style="width:100%;margin-bottom:15px;"
            closable="false"
            collapsible="false"
            minimizable="false"
            maximizable="false"
            border="false"
    ></div>
</form>
<style>
    .feng{
        margin-left: 0px;
        margin-right: 18px;
        padding-top: 3px;
        padding-bottom: 3px;
        width: 150px;
    }
    #tab{
        width: 100%;
    }
    table,tr,th,td{
        text-align:center;
        align="center";
        border-collapse: collapse;
    }
</style>
<script>
    $(function () {
        $('.save').click(function () {
        })
    })
</script>