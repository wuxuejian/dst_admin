<fieldset border="10px">
        <legend>订单信息:</legend>
        订单编号：<td><?php echo $result_express_r['order_number'];?></td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!-- <input class="feng" name="order_number" value="<?php echo $result_express_r['order_number']; ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -->
        接受方：<td><?php echo $result_express_r['company_name'];?></td><!-- <input class="feng" name="name" value="<?php echo $result_express_r['operating_company_id']; ?>" style="width: 40%;"/><br> -->
        <div
            class="easyui-panel"
            style="width:100%;margin-bottom:15px;"
            closable="false"
            collapsible="false"
            minimizable="false"
            maximizable="false"
            border="false"
        ></div>
        运单编号：<td><?php echo $result_express_r['express_number'];?></td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!-- <input class="feng" name="express_number" value="<?php echo $result_express_r['express_number']; ?>"/> -->
        送达时间：<td><?php echo date("Y-m-d",$result_express_r['estimated_arrive_time']); ?></td><!-- input class="feng" name="estimated_arrive_time" value="<?php echo date("Y-m-d",$result_express_r['estimated_arrive_time']); ?>"/><br> -->
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
            <?php foreach($result_express as $key => $value){ ?>
           
            <tr>
                <td><?php echo "整车";?></td>
                <td><?php echo $value['brand_name']?></td>
                <td><?php echo $value['car_model_name']?></td>
                <td><?=$value['vehicle_dentification_number']?><!-- <input type="text" name="vehicle_dentification_number[]" style="width:100px;" value="<?=$value['vehicle_dentification_number']?>"> --></td>
                <td><?=$value['invoice_number']?><!-- <input type="text" name="invoice_number[]" style="width:100px;" value="<?=$value['invoice_number']?>"> --></td>
                <td><?=$value['storage_location']?><!-- <input type="text" name="storage_location[]" style="width:200px;" value="<?=$value['storage_location']?>"> --></td>
            </tr>
            <input type="hidden" name="id[]" value="<?php echo $dat['id'] ?>">
            <?php } ?>
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