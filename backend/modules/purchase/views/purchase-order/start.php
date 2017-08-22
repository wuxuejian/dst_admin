<form id="easyui-form-start" class="easyui-form">
    <!-- <div
        class="easyui-panel"
        title="订单信息"
        style="width:100%;margin-bottom:5px;"
        closable="false"
        collapsible="false"
        minimizable="false"
        maximizable="false"
        border="false"
    >
        <table cellpadding="5" cellspacing="0"> 
            <tr>
                <td align="right"><div style="width:70px;">订单编号</div></td>
                <td>
                    <input class="easyui-combotree" name="brand_id" />
                </td>
                <td align="right"><div style="width:70px;">接受方</div></td>
                <td>
                    <input class="easyui-combotree" name="brand_id" />
                </td> 
            </tr>
            <tr>
                <td align="right"><div style="width:70px;">预计发货时间</div></td>
                <td>
                    <input class="easyui-combotree" name="brand_id" />
                </td>
                <td align="right"><div style="width:70px;">实际发货时间</div></td>
                <td>
                    <input class="easyui-combotree" name="brand_id"/>
                </td> 
            </tr>
        </table>
    </div>

    <div
        class="easyui-panel"
        title="物流信息"
        style="width:100%;margin-bottom:5px;"
        closable="false"
        collapsible="false"
        minimizable="false"
        maximizable="false"
        border="false"
    >
        <table cellpadding="5" cellspacing="0">
            <tr>
                <td align="right"><div style="width:70px;">承运公司</div></td>
                <td>
                    <input class="easyui-combotree" name=""/>
                </td>
                <td align="right"><div style="width:70px;">运单编号</div></td>
                <td>
                    <input class="easyui-combotree" name=""/>
                </td> 
            </tr>
            <tr>
                <td align="right"><div style="width:70px;">联系电话</div></td>
                <td>
                    <input class="easyui-combotree" name=""/>
                </td>
                <td align="right"><div style="width:70px;">预计到达时间</div></td>
                <td>
                    <input class="easyui-combotree" name="" />
                </td> 
            </tr>
            
        </table>
    </div>   -->
    <input type="hidden" name="id" value="<?=$row['id'] ?>"/>
    <div
        class="easyui-panel"
        style="width:100%;margin-bottom:15px;"
        closable="false"
        collapsible="false"
        minimizable="false"
        maximizable="false"
        border="false"
    ></div>
    <fieldset border="10px">
        <legend >订单信息</legend>
        订单编号：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="easyui-textbox" style="width:190px;" name="order_number" value = "<?=$row['order_number']?>" disabled = "disabled"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        接受方：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="easyui-textbox" style="width:190px;" style="width:190px;" name="operating_company_id" value = "<?=$row['row_n']?>" disabled = "disabled"/><br>
         <div
        class="easyui-panel"
        style="width:100%;margin-bottom:15px;"
        closable="false"
        collapsible="false"
        minimizable="false"
        maximizable="false"
        border="false"
        ></div>
        预计发货时间：<input class="easyui-textbox" style="width:190px;" name="estimated_delivery_time" value = "<?=date('Y-m-d H:i:s',$row['estimated_delivery_time'])?>" disabled = "disabled"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        实际发货时间：<input class="easyui-datetimebox" style="width:190px;" name="true_delivery_time" value = "" /><br>
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
        <legend>物流信息</legend>
        承运公司：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="easyui-textbox" style="width:190px;" name="express_company" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        运单编号：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="easyui-textbox" style="width:190px;" name="express_number" value = ""/><br>
        <div
        class="easyui-panel"
        style="width:100%;margin-bottom:15px;"
        closable="false"
        collapsible="false"
        minimizable="false"
        maximizable="false"
        border="false"
    ></div>
        联系电话：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="easyui-textbox" style="width:190px;" name="express_phone" value = "" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        预计到达时间：<input class="easyui-datetimebox" style="width:190px;" name="estimated_arrive_time" value = ""/><br>
    </fieldset>

     <h3>选择车辆信息</h3>
    <!--  <table border="1px">
    
         <?php for($i=1;$i<=count($row_c);$i++){?>
            <tr>
                <?php for($j=0;$j<4;$j++){?>
                <td><?php echo $row_c[$i]['item_type'];?></td>
                <td><?php echo $row_c[$i]['brand_id'];?></td>
                <td><?php echo $row_c[$i]['car_type_id'];?></td>
                <?php }?>
            </tr>
        <?php }?> 
    

     </table> -->

     <table border="1" cellspacing="0" cellpadding="5" style="width:80%;" type="checkbox"  >
        <tr border="1px" type="checkbox">
            <td>类别</td><td>品牌</td><td>车型</td><td>总数量</td><td>已发数量</td><td>本次发送数量</td><td>其他</td>
        </tr>
        <?php foreach ($row_c as $row): ?>
            <tr type="checkbox">
				<td><?php if($row['item_type'] =='zhengche'){echo '整车';}?></td>
                <td><?=$row['brand_name']?></td>
                <td><?=$row['car_model_name']?></td>
				<td><?=$row['quantity']?></td>
				<td><?=$row['already_num']?></td>
				<td>
					<?php
						$max_start_num = $row['quantity']-$row['already_num'];	//本次最大发送数量
					?>
					<input type="hidden" name="order_details_id[]" value="<?=$row['id'] ?>"/>
					<input type="text" name="start_num[]" value="0" onblur="check_num(<?=$max_start_num?>,this)"/>
				</td>
				<td><?=$row['parts']?></td>
            </tr>
        <?php endforeach; ?>
    </table>


</form>
<script>
function check_num(max_start_num, start_num){
	if(!isNumber($(start_num).val())){
		$(start_num).val(0);
		alert("请输入数字");
	}
	if($(start_num).val() > max_start_num){
		$(start_num).val(0);
		alert("超过最大值");
	}
}
function isNumber(value) {
    var patrn = /^[0-9]*$/;
    if (patrn.exec(value) == null || value == "") {
        return false
    } else {
        return true
    }
}
</script>