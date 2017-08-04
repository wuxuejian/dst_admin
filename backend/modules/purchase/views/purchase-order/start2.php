<form id="easyui-form-start2" class="easyui-form">
    <input type="hidden" name="id" value="<?=$ids ?>"/>
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
        承运公司：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="easyui-textbox" style="width:190px;" name="express_company" value = "<?=$data['express_company']?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        运单编号：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="easyui-textbox" style="width:190px;" name="express_number" value = "<?=$data['express_number']?>" disabled = "disabled"/><br>
        <div
        class="easyui-panel"
        style="width:100%;margin-bottom:15px;"
        closable="false"
        collapsible="false"
        minimizable="false"
        maximizable="false"
        border="false"
    ></div>
        联系电话：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="easyui-textbox" style="width:190px;" name="express_phone" value = "<?=$data['express_phone']?>" disabled = "disabled"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        预计到达时间：<input class="easyui-datetimebox" style="width:190px;" name="estimated_arrive_time" value = "<?=$data['estimated_arrive_time']?>" disabled = "disabled"/><br>
    </fieldset>

    <h2>物流状态</h2>
    <input class="easyui-textbox" id="express_track_id" style="width:470px;" name="express_track" value="" prompt="在此输入最新物流状态(时间与登记人由系统生成)" />&nbsp;&nbsp;<input id="tj_id" type="button" value="添加最新物流状态" onclick="tianjia()"/>
    <div
        class="easyui-panel"
        style="width:100%;margin-bottom:15px;"
        closable="false"
        collapsible="false"
        minimizable="false"
        maximizable="false"
        border="false"
    ></div>
    <textarea name="note2" id="note" rows="7" cols="90" readonly="readonly" value="" ><?=$data['express_track']?></textarea>
</form>
<script>
	//$(function(){

		function tianjia() {
				
	        //alert('123');
	        var aa  = $('#express_track_id').val();
	        $("#express_track_id").val();
	         //alert(aa)
	        //alert(aa);
	        //$("#note").html(aa).appendto("textarea");
	        //$('#note').append(aa);

	        //$("textarea").append(aa)+"\n";//在选择元素的末尾添加内容
	        aa= aa+"\r\r";
	        $("textarea").prepend(aa);
	       // var bb  = $('#note').val();
	       
	       // aa.appendTo('textarea'); 


       
    	}
	//});

</script>