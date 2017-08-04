<div style="padding:10px 40px 20px 40px">  
    <form id="easyui-form-process-repair-fault-add-from" class="easyui-form" method="post">
    	<input type="hidden" name="maintain_id" value="<?php echo $maintain_id;?>" />
        <div >
            <ul class="ulforform-resizeable">
            	<li class="ulforform-resizeable-group-single">
           		 <div class="ulforform-resizeable-title">父分类</div>
            		<div class="ulforform-resizeable-input">
                		<input name="pid" id="StationFalutCategory_addWin_form_pid" style="width:300px;" />
            		</div>
        		</li>
        		
                <li class="ulforform-resizeable-group-single">
                    <div class="ulforform-resizeable-title">故障名称</div>
                    <div class="ulforform-resizeable-input">
                        <select id="category" class="easyui-combobox"   style="width:300px;"  name="category_id" required="true" >
						<option value=""></option>
						<?php foreach ($faults as $fault):?>
							<option value="<?php echo $fault['id']?>"><?php echo $fault['category']?></option>
						<?php endforeach;?>
                        </select>
                    </div>
                </li>
            </ul>
        </div>
    </form>
</div>
<script>
    var StationFalutCategory_addWin  = {
        init: function(){
            var curMenuId = 0;
            $('#StationFalutCategory_addWin_form_pid').combotree({
                url: "<?php echo yii::$app->urlManager->createUrl(['station/fault/get-categorys']); ?>&isShowRoot=1&mark=1",
                editable: false,
                panelHeight:'auto',
                panelWidth:300,
                lines:false,
                onLoadSuccess: function(data){ //展开到当前菜单位置
                    if(parseInt(curMenuId)){
                        var combTree = $('#StationFalutCategory_addWin_form_pid');
                        combTree.combotree('setValue',curMenuId);
                        var t = combTree.combotree('tree');
                        var curNode = t.tree('getSelected');
                        t.tree('collapseAll').tree('expandTo',curNode.target);
                    }
                }
            });
        }
    };

	$("#StationFalutCategory_addWin_form_pid").combotree({
		onChange:function(newValue,oldValue){
			$('#category').combobox("clear");
			$.ajax({
		        type: "POST",
		        url: "<?php echo yii::$app->urlManager->createUrl(['process/repair/ajax-get-fault']); ?>",
		        cache: false,
		        dataType : "json",
		        data:{id:newValue},
		        success: function(data){
		        	$("#category").combobox("loadData",data);
		          }
		     });
		}
	
	});
    
    $('#category').combobox({ 
	    //  url:"", 
	     // editable:false, //不可编辑状态
	      cache: false,
	    //  panelHeight: 'auto',//自动高度适合
	      valueField:'id',   
	      textField:'category',
		    onHidePanel: function() {
	            var valueField = $(this).combobox("options").valueField;
	            var val = $(this).combobox("getValue");  //当前combobox的值
	            var allData = $(this).combobox("getData");   //获取combobox所有数据
	            var result = true;      //为true说明输入的值在下拉框数据中不存在
	            for (var i = 0; i < allData.length; i++) {
	                if (val == allData[i][valueField]) {
	                    result = false;
	                }
	            }
	            if (result) {
	                $(this).combobox("clear");
	            }
	        }  
	 });
    StationFalutCategory_addWin.init();
</script>
