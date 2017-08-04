<form class="easyui-form" id="purchase-express-on-card-form">
	<input type="hidden" name="purchase_express_id" value="<?=$purchase_express_id?>">
	<input type="hidden" name="is_submit" value="0">
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
        <legend>上牌登记:</legend>
        <table cellpadding="7"  cellspacing="0" text-align="center"  width="100%" border="1px solid" >
            <thead>
                <th>类别</th>
                <th>品牌</th>
                <th>车型</th>
                <th>车架号</th>
				<th>发动机号</th>
				<th>车牌号</th>
                <th>发票号</th>
                <th>车辆存放点</th>
            </thead>
            <tbody>
            <?php foreach ($order_detials as $key=>$dat):?>
            <tr>
                <td><?php if($dat['item_type']=='zhengche') {echo "整车";}?></td>
                <td><?php echo $dat['brand_name']?></td>
                <td><?php echo $dat['type_name']?></td>
                <td><?=$dat['vehicle_dentification_number']?></td>
				<?php
					if($dat['is_storage']){
						echo '<td>'.$dat['engine_number']."</td>\n";
						echo '<td>'.$dat['plate_number']."</td>\n";
						echo '<input type="hidden" name="engine_number[]" value="'.$dat['engine_number'].'">'."\n";
						echo '<input type="hidden" name="plate_number[]" value="'.$dat['plate_number'].'">'."\n";
					}else {
						echo '<td><input type="text" name="engine_number[]" style="width:100px;" value="'.$dat['engine_number'].'"></td>'."\n";
						echo '<td><input type="text" name="plate_number[]" style="width:100px;" value="'.$dat['plate_number'].'"></td>'."\n";
					}
				?>
                <td><?=$dat['invoice_number']?></td>
                <td><?=$dat['storage_location']?></td>
            </tr>
            <input type="hidden" name="id[]" value="<?php echo $dat['id'] ?>">
			<input type="hidden" name="is_storage[]" value="<?php echo $dat['is_storage'] ?>">
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