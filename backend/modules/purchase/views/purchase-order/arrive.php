<form id="easyui-form-start" class="easyui-form">
    <fieldset border="10px">
        <legend>订单信息:</legend>
        订单编号：<input class="feng" name="order_number" disabled="disabled" value="<?php echo $order_row['order_number']; ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        接受方：<input class="feng" name="name" disabled="disabled" value="<?php echo $order_row['name']; ?>" style="width: 40%;"/><br>
        <div
            class="easyui-panel"
            style="width:100%;margin-bottom:15px;"
            closable="false"
            collapsible="false"
            minimizable="false"
            maximizable="false"
            border="false"
        ></div>
        运单编号：<input class="feng" name="express_number" disabled="disabled" value="<?php echo $order_row['express_number']; ?>"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;


        送达时间：<input class="feng" name="estimated_arrive_time" disabled="disabled" value="<?php echo $order_row['estimated_arrive_time']; ?>"/><br>
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
                <th>操作</th>
            </thead>
            <tbody>
            <?php foreach ($order_detials as $key=>$dat):?>
            <tr>
                <td><?php if($dat['item_type']=='zhengche') {echo "整车";}?></td>
                <td><?php echo $dat['brand_name']?></td>
                <td><?php echo $dat['type_name']?></td>
                <td><input type="text" name="vehicle_dentification_number[]"></td>
                <td><input type="text" name="invoice_number[]"></td>
                <td></td>
                <td>保存</td>
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

    <span style="width: 100%;">
        <fieldset border="3px" style="width:40%;float: left;">
            <legend>货物信息确认:</legend>
            采购中心责任人：<input class="feng" name="brand_id" />
                <div
                        class="easyui-panel"
                        style="width:100%;margin-bottom:11px;"
                        closable="false"
                        collapsible="false"
                        minimizable="false"
                        maximizable="false"
                        border="false"
                ></div>
            大区运维车管负责人：<input class="feng" name="brand_id" disabled="disabled"/>&nbsp;&nbsp;&nbsp;
        </fieldset>
        <fieldset border="3px" style="width:50%;float: right;">
            <legend>物流评价:</legend>
            送货速度：
            <br>
            服务态度：
            <br>
            车管部负责人：
        </fieldset>
    </span>
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