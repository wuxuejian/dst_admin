<div class="easyui-layout" data-options="fit: true">  
    <div style="width:160px;" data-options="
        region:'west',
        title:'配置分类',
        split:true,
        border: false
    ">
        <ul id="easyui-tree-system-config-item-manage"></ul>  
    </div>  
    <div data-options="
        region:'center',
        title:'配置项目',
        split:true,
        border: false">
        <div id="easyui-panel-system-config-item-manage" class="easyui-panel" data-options="
            title: '',
            iconCls:'icon-save',
            closable:false,
            border: false,
            fit: true
        "></div>
    </div>  
</div>
<script>
	var SystemConfigItemManage = new Object();
	SystemConfigItemManage.init = function(){
		$('#easyui-tree-system-config-item-manage').tree({   
		    url: "<?php echo yii::$app->urlManager->createUrl(['system/config/get-category-tree']); ?>",
		    onLoadSuccess: function(){
		    	$('#easyui-tree-system-config-item-manage').tree('collapseAll');
			},
			onClick: function(node){
				if(node.parent_id !=0 ){
					$('#easyui-panel-system-config-item-manage').panel('refresh',"<?php echo yii::$app->urlManager->createUrl(['system/config/item-index']); ?>&belongsId="+node.id);
				}else{ //若是父级节点就展开/收缩下一级节点
                    $(this).tree('toggle',node.target);
                }
			}
		}); 
		
	}
	SystemConfigItemManage.init();
</script>