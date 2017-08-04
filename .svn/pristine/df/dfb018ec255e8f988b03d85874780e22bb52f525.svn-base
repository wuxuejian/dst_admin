<table id="vip_favorite_index_datagrid"></table> 
<div id="vip_favorite_index_datagrid_toolbar">
    <div class="easyui-panel" title="检索区域" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="vip_favorite_index_searchFrom">
                <ul class="search-main">                                   
					<li>
                        <div class="item-name">会员编号</div>
                        <div class="item-input">
                           <input class="easyui-textbox" type="text" name="vip_code" style="width:100%;" />
                        </div>
                    </li>                    
					<li>
                        <div class="item-name">会员手机号</div>
                        <div class="item-input">
                           <input class="easyui-textbox" type="text" name="vip_mobile" style="width:100%;" />
                        </div>
                    </li>
                    <li class="search-button">
                        <a id="search-button" href="javascript:vipFavoriteIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a id="reset-button" href="javascript:vipFavoriteIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>

    <?php if(!empty($buttons)){ ?>
        <div class="easyui-panel" title="收藏列表" style="padding:3px 2px;width:100%;" data-options="
        iconCls: 'icon-table-list',
        border: false
    ">
            <?php foreach($buttons as $val){ ?>
                <a href="javascript:<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon'] ;?>'"><?= $val['text'] ;?></a>
            <?php } ?>
        </div>
    <?php } ?>

</div>

<script>
	var vipFavoriteIndex = new Object();
	var connection_type = <?= json_encode($config['connection_type']); ?>; 
	
	vipFavoriteIndex.init = function(){
		//获取列表数据
		$('#vip_favorite_index_datagrid').datagrid({  
			method: 'get', 
		    url:'<?php echo yii::$app->urlManager->createUrl(['vip/vip-favorite/get-list']); ?>',   
			fit: true,
			border: false,
			toolbar: "#vip_favorite_index_datagrid_toolbar",
			pagination: true,
			loadMsg: '数据加载中...',
			striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
			pageSize: 20,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',hidden:true},   
            ]],
		    columns:[
		        [
					{field: 'vip_code',title: '会员编号',width: 140,align:'center',sortable:true,rowspan:2}, //跨2行
					{field: 'vip_mobile',title: '会员手机号',width: 100,align:'center',sortable:true,rowspan:2}, //跨2行
					{title:'充电站信息',colspan:4}, //跨4列
					{field: 'mark',title: '备注',width: 200,halign:'center',rowspan:2},
					{field: 'systime',title: '收藏日期',align:'center',width: 90,sortable:true,rowspan:2,
						formatter: function(value,row,index){
							return formatDateToString(value);
						}
					}
				],[
					{field: 'cs_id',title: '充电站ID',hidden:true},
					{field: 'cs_code',title: '充电站编号',width: 100,align:'center',sortable:true},
					{field: 'cs_name',title: '充电站名称',width: 80,align:'left',sortable:true},
					{field: 'cs_address',title: '充电站地点',width: 250,halign:'left',sortable:true}
				]
		    ]
		});	
	}
	vipFavoriteIndex.init();
	
	//查询
	vipFavoriteIndex.search = function(){
		var form = $('#vip_favorite_index_searchFrom');
		var data = {};
		var searchCondition = form.serializeArray(); 
		for(var i in searchCondition){
			data[searchCondition[i]['name']] = searchCondition[i]['value'];
		}
		$('#vip_favorite_index_datagrid').datagrid('load',data);
	}
	
	//重置
	vipFavoriteIndex.reset = function(){
		$('#vip_favorite_index_searchFrom').form('reset');
	}
	
	//导出
    vipFavoriteIndex.exportGridData = function(){
		var form = $('#vip_favorite_index_searchFrom');
		var str = form.serialize();
        window.open("<?php echo yii::$app->urlManager->createUrl(['vip/vip-favorite/export-grid-data']); ?>&" + str);
    }

	
</script>