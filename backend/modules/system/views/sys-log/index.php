<!--layout begin-->
<div class="easyui-layout" data-options="border:false" style="width:100%;height:100%;">
	<div class="easyui-panel" id="system_syslog_index_form_search_panel" title="查询面板" 
	data-options="region:'north',border:false,iconCls:'icon-search'" style="width:100%;height:90px;">
		<div class="data-search-form" style="width:99%;height:100%;">
			<form id="system_syslog_index_form_search" name="system_syslog_index_form_search" method="post">
				<ul class="search-main">
					<li>
						<div class="item-name">用户名</div>
						<div class="item-input">
							<input class="easyui-textbox" type="text" name="user_name" style="width:100%;"  />
						</div>
						<input type="hidden" name="search_flag" value="日志查询操作" />
					</li>                    
					<li>
						<div class="item-name">操作行为</div>
						<div class="item-input">
						   <input class="easyui-textbox" type="text" name="action" style="width:100%;"  />
						</div>
					</li>                    
					<li>
						<div class="item-name">日志类型</div>
						<div class="item-input">
							<select class="easyui-combobox"  name="log_type" style="width:100%;" data-options="editable:false,panelHeight:'auto'">
								<option value="" selected="selected">--请选择--</option>
								<?php  foreach($config['log_type'] as $val){ ?>
								<option value="<?= $val['value'] ?>"><?= $val['text'] ?></option>
								<?php } ?>
							</select>
						</div>
					</li> 
					<li>
						<div class="item-name">访问IP</div>
						<div class="item-input">
						   <input class="easyui-textbox" type="text" name="ip" style="width:100%;"  />
						</div>
					</li>                    
					<li>
						<div class="item-name">操作时间</div>
						<div class="item-input">
							<select name="search_timeduan" class="easyui-combobox" style="width:100%;" data-options="editable:false,panelHeight:'auto'">
							 <option value="">--请选择--</option>
							 <option value="today">今天</option>
							 <option value="yesterday">昨天</option>
							 <option value="thisWeek">本周</option>
							 <option value="lastWeek">上一周</option>
							 <option value="thisMonth">本月</option>
							 <option value="lastMonth">上一月</option>
							 <option value="thisYear">本年度</option>
							 <option value="lastYear">上一年度</option>
							</select>
						</div>
					</li> 
					<li>
						<div class="item-name">操作时间</div>
						<div class="item-input">
						    <input class="easyui-datebox" type="text" name="start_time" style="width:93px;"  />
                            -
                            <input class="easyui-datebox" type="text" name="end_time" style="width:93px;"  />
						</div>
					</li>                    
					<li class="search-button">
						<a href="javascript:void(0)" onclick="sysLogdoSearch()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">搜索</a>
                        <a href="javascript:void(0)" onclick="sysLogdoClearForm()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
                    </li>
				</ul>
			</form>
		</div>
	</div>
  
	<div data-options="region:'center', href:'', border:false" style="border-top:1px solid #95B8E7">
		<table id="system_syslog_index" title="日志列表" class="easyui-datagrid" style="width:100%;"
			data-options="
				iconCls:'icon-table-list',
				singleSelect:true, 
				rownumbers:true, 
				remoteSort:true, 
				border:false, 
				fit:true,
				url:'<?=\Yii::$app->urlManager->createUrl('system/sys-log/list')?>',
				pagination:true,
				pageSize:20,
				pageList:[20,50,100],striped:true,
				onLoadSuccess:function(data){ 
					$(this).datagrid('doCellTip',{
						position : 'bottom',
						maxWidth : '300px',
						onlyShowInterrupt : true,
						specialShowFields : [     
							{field : '',showField : ''}
						],
						tipStyler : {			 
							'backgroundColor' : '#E4F0FC',
							borderColor : '#87A9D0',
							boxShadow : '1px 1px 3px #292929'
						}
					});
				}				
			">
			<thead>  
				<th data-options="field:'log_id',align:'center', sortable:true, hidden:true" width="80">ID</th>
				<th data-options="field:'user_name',align:'center', sortable:true" width="100">用户名</th>
				<th data-options="field:'action',align:'left', sortable:true" width="300">操作行为</th> 
				<th data-options="field:'log_type', align:'center', codeAlias:'sys_log_type',editor:{type:'combobox'}" width="80">日志类型</th> 
				<th data-options="field:'ip',align:'center', sortable:true" width="100" >访问IP</th>
				<th data-options="field:'log_time',align:'center', sortable:true" width="130">操作时间</th>
				<th data-options="field:'is_super',align:'center', sortable:true,hidden:true">是否开发人员</th> 
				<th data-options="field:'qstring',align:'left'" width="320">请求地址</th>
			  </tr>
			</thead>
		</table>
	</div>
</div>
<!--layout end-->


<script type="text/javascript">
		// 为搜索区域添加回车事件相应
		$('#system_syslog_index_form_search_panel').keypress(function(e){
			var keyCode = e.keyCode ? e.keyCode : e.which ? e.which : e.charCode;
			if (keyCode == 13){
				sysLogdoSearch();
			}
		});  
		
		// 执行用户日志查询操作
		function sysLogdoSearch(){
			var form = $('#system_syslog_index_form_search');
			var data = {};
			var searchCondition = form.serializeArray(); 
			for(var i in searchCondition){
				data[searchCondition[i]['name']] = searchCondition[i]['value'];
			}
			$('#system_syslog_index').datagrid('load',data);
		}
	  
		function sysLogdoClearForm(){
			$('#system_syslog_index_form_search').form('reset');
            sysLogdoSearch();
		}
</script>