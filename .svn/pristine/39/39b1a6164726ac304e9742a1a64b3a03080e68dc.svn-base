<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>充电汽车管理系统</title>
    <!-- ue -->
    <script>var UEDITOR_HOME_URL = "./ueditor1_4_3-utf8-php/";</script>
    <script type="text/javascript" charset="utf-8" src="<?= yii::getAlias('@web'); ?>/ueditor1_4_3-utf8-php/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?= yii::getAlias('@web'); ?>/ueditor1_4_3-utf8-php/ueditor.all.min.js"> </script>
    <!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
    <!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
    <script type="text/javascript" charset="utf-8" src="<?= yii::getAlias('@web'); ?>/ueditor1_4_3-utf8-php/lang/zh-cn/zh-cn.js"></script>
    <!-- ue -->
    <!-- easyui -->
    <link rel="stylesheet" type="text/css" href="<?= yii::getAlias('@web'); ?>/jquery-easyui-1.4.3/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="<?= yii::getAlias('@web'); ?>/jquery-easyui-1.4.3/themes/icon.css">
	<script type="text/javascript" src="<?= yii::getAlias('@web'); ?>/jquery-easyui-1.4.3/jquery.min.js"></script>
	<script type="text/javascript" src="<?= yii::getAlias('@web'); ?>/jquery-easyui-1.4.3/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="<?= yii::getAlias('@web'); ?>/jquery-easyui-1.4.3/locale/easyui-lang-zh_CN.js"></script>
    <!-- easyui -->
    <?php /*<!-- 文件上传 -->
    <link rel="stylesheet" type="text/css" href="<?= yii::getAlias('@web'); ?>/file_upload/css/jqueryajaxfileupload.css" />
    <script type="text/javascript" src="<?= yii::getAlias('@web'); ?>/file_upload/js/jqueryajaxfileupload.js"></script> 
    <!-- 文件上传 -->*/?>
    <!-- 扩展css文件 -->
    <link rel="stylesheet" type="text/css" href="<?= yii::getAlias('@web'); ?>/css/extends.css" />
    <!-- 扩展css文件 -->
	<!-- 百度地图API -->
	<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=jD4uT96qsSYGL29H9OSv6Nps"></script>
    <!-- ECharts -->
    <script type="text/javascript" src="<?= yii::getAlias('@web'); ?>/echarts-2.2.7/build/dist/echarts-all.js"></script>
    <!-- ECharts -->
    <!-- 科陆读写卡程序 -->
    <script type="text/javascript" src="<?= yii::getAlias('@web'); ?>/js/cardReadWrite.js"></script>
    <style>
        .data-search-form{
        	margin: 0;
        	padding: 2px;
	        border-bottom: 1px solid #95B8E7;
        }
        .data-search-form .search-main{
	        margin: 0;
            padding: 0;
        	list-style: none;
        	overflow: hidden;
        }
        .data-search-form .search-main li{
        	height: 24px;
        	padding: 2px;
        	color: #444;
        	-background: red;
        	float: left;
        	overflow: hidden;
        }
        .data-search-form .search-main li.search-button{
	        float: right;
        	width: 116px;
        }
        .data-search-form .search-main li .item-name{
	        width: 97px;
        	height: 24px;
            padding-right: 2px;
        	line-height: 22px;
        	float: left;
        	text-align: right;
        	overflow: hidden;
        }
        .data-search-form .search-main li .item-input{
	        width: 205px;
        	height: 24px;
        	float: right;
        }
		.data-search-form .search-main li .item-input-datebox{
			width: 205px;
			height: 24px;
			float: right;
		}
    </style>
    <script>	
		/**
		 * 将时间戳（秒数）转换为日期、日期时间格式
		 * @timeStamp: 秒数
		 * @time: 是否要转为日期时间格式
		 */
    	function formatDateToString(timeStamp,time){
            if(timeStamp <= 0) return '';
        	var dateOjb = new Date(parseInt(timeStamp)*1000);
            var dateStr = parseDateObj(dateOjb,time);
			return dateStr;
        }
		
		/**
		 * 解析日期对象并转换为日期、日期时间格式
		 * @dateOjb: 日期对象
		 * @time: 是否要转为日期时间格式
		 */
    	function parseDateObj(dateOjb,time){
            var year = dateOjb.getFullYear();     
            var month = dateOjb.getMonth()+1;
            month = month < 10 ? ('0'+month) : month ;
            var day = dateOjb.getDate();
            day = day < 10 ? ('0'+day) : day ;
            if(time){
                var hour = dateOjb.getHours();
                hour = hour < 10 ? ('0'+hour) : hour ;   
                var minute = dateOjb.getMinutes();
                minute = minute < 10 ? ('0'+minute) : minute ;    
                var second = dateOjb.getSeconds();
                second = second < 10 ? ('0'+second) : second ;
                return year+"-"+month+"-"+day+" "+hour+":"+minute+":"+second;   
            }else{
                return year+"-"+month+"-"+day;
            }
        }
        //时期默认格式
        $.fn.datebox.defaults.formatter = function(date){
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = date.getDate();
            if(m < 10) m = '0' + m;
            if(d < 10) d = '0' + d;
            return y+'-'+m+'-'+d;
        }
    </script>
    <script>
		//验证规则
        $.extend($.fn.validatebox.defaults.rules, {
            //正则验证
            match: {
            	validator: function(value, param){
                    if(param[0].test(value)){
						return true;
                    }
                    return false; 
                },   
                message: '格式错误'  
            },
            //验证两个输入框内容是否相同
            equals: {   
                validator: function(value,param){   
                    return value == $(param[0]).val();   
                },   
                message: '值不匹配'  
            },
            //手机号码验证
            mobile: {
                validator: function(value,param){   
                    if(/^1[358]\d{9}$/.test(value)){
                        return true;
                    }
                    return false; 
                },   
                message: '不合法的手机号码！'  
            },
            //身份证验证
            idcard: {
                validator: function(value,param){   
                    if(/^(\d{14}|\d{17})[Xx\d]$/.test(value)){
                        return true;
                    }
                    return false; 
                },   
                message: '身份证号码错误！'  
            },
            //长度验证
            length: {
                validator: function(value,param){   
                    if(!param){
                        param  = 0;
                    }
                    if(value.length > param){
                        return false;
                    }
                    return true; 
                },   
                message: '长度超出！'  
            },
            //int型验证
            int: {
                validator: function(value,param){
                    if(!/^[0-9](\d+)?$/.test(value)){
                        return false;
                    }
                    if(parseInt(value) > param){
                        return false;
                    }
                    return true; 
                },   
                message: '请输入整型值！'  
            },
            //number类型
            number: {
                validator: function(value,length){
                    if(value.length > length){
                        //长度超出
                        return false;
                    }
                    if(!/^\d+$/.test(value)){
                        return false;
                    }
                    return true; 
                },   
                message: '只能输入数值类型的值！'  
            },
            //验证money
            money: {
                validator: function(value,param){
                    if(!/^\d{0,7}(\.\d{1,2})?$/.test(value)){
                        return false;
                    }
                    if(value > param){
                        return false;
                    }
                    return true; 
                },   
                message: '不是有效的金额（格式：0.00）！'  
            },
            //date验证
            date: {
                validator: function(value,param){
                    if(!/^20\d{2}(-\d{2}){2}$/.test(value)){
                        return false;
                    }
                    if(value > param){
                        return false;
                    }
                    return true; 
                },   
                message: '日期格式非法（格式：YYYY-mm-dd）！'  
            },
            //date验证
            datetime: {
                validator: function(value,param){
                    if(!/^20\d{2}(-\d{2}){2}\s(\d{2}:){2}\d{2}$/.test(value)){
                        return false;
                    }
                    if(value > param){
                        return false;
                    }
                    return true; 
                },   
                message: '日期时间格式非法（格式：YYYY-mm-dd HH:ii:ss）！'  
            },
            //验证值是否为空
            notempty: {
                validator: function(value,param){
                    console.log(value);
                    if(value == '' || value == 0 || value ==  0.00 || value){
                        return false;
                    }
                    return true; 
                },   
                message: '该项不能为空！'
            }
        });
		
		/**  
         * 为datagrid扩展方法：单元格内容悬浮提示  
         */  
        $.extend($.fn.datagrid.methods, {  
			/**
			 * 开启提示功能
			 */
			doCellTip:function (jq, params) {
				function showTip(showParams, td, e, dg) {
					//无文本，不提示。
					if ($(td).text() == "") return;
					params = params || {};
					var options = dg.data('datagrid');
					var styler = 'style="';
					if(showParams.width){
						styler = styler + "width:" + showParams.width + ";";
					}
					if(showParams.maxWidth){
						styler = styler + "max-width:" + showParams.maxWidth + ";";
					}
					if(showParams.minWidth){
						styler = styler + "min-width:" + showParams.minWidth + ";";
					}
					styler = styler + '"';
					showParams.content = '<div class="tipcontent" ' + styler + '>' + showParams.content + '</div>';
                    var showParams_content = showParams.content.replace(/<script>/i,'').replace(/<\/script>script>/i,'').replace(/\n|\r\n/g,'<br/>');
					$(td).tooltip({
						content: showParams_content,
						trackMouse:true,
						position:params.position,
						onHide:function () {
							$(this).tooltip('destroy');
						},
						onShow:function () {
							var tip = $(this).tooltip('tip');
							if(showParams.tipStyler){
								tip.css(showParams.tipStyler);
							}
							if(showParams.contentStyler){
								tip.find('div.tipcontent').css(showParams.contentStyler);
							}
						}
					}).tooltip('show');
				};
				return jq.each(function () {
					var grid = $(this);
					var options = $(this).data('datagrid');
					if (!options.tooltip) {
						var panel = grid.datagrid('getPanel').panel('panel');
						panel.find('.datagrid-body').each(function () {
							var delegateEle = $(this).find('> div.datagrid-body-inner').length ? $(this).find('> div.datagrid-body-inner')[0] : this;
							$(delegateEle).undelegate('td', 'mouseover').undelegate('td', 'mouseout').undelegate('td', 'mousemove').delegate('td[field]', {
								'mouseover':function (e) {
									//if($(this).attr('field')===undefined) return;
									var that = this;
									var setField = null;
									if(params.specialShowFields && params.specialShowFields.sort){
										for(var i=0; i<params.specialShowFields.length; i++){
											if(params.specialShowFields[i].field == $(this).attr('field')){
												setField = params.specialShowFields[i];
											}
										}
									}
									if(setField==null){
										options.factContent = $(this).find('>div').clone().css({'margin-left':'-5000px', 'width':'auto', 'display':'inline', 'position':'absolute'}).appendTo('body');
										var factContentWidth = options.factContent.width();
										params.content = $(this).text();
										if (params.onlyShowInterrupt) {
											if (factContentWidth > $(this).width()) {
												showTip(params, this, e, grid);
											}
										} else {
											showTip(params, this, e, grid);
										}
									}else{
										panel.find('.datagrid-body').each(function(){
											var trs = $(this).find('tr[datagrid-row-index="' + $(that).parent().attr('datagrid-row-index') + '"]');
											trs.each(function(){
												var td = $(this).find('> td[field="' + setField.showField + '"]');
												if(td.length){
													params.content = td.text();
												}
											});
										});
										showTip(params, this, e, grid);
									}
								},
								'mouseout':function (e) {
									if (options.factContent) {
										options.factContent.remove();
										options.factContent = null;
									}
								}
							});
						});
					}
				});
			},
			/**
			 * 关闭消息提示功能
			 */
			cancelCellTip:function (jq) {
				return jq.each(function () {
					var data = $(this).data('datagrid');
					if (data.factContent) {
						data.factContent.remove();
						data.factContent = null;
					}
					var panel = $(this).datagrid('getPanel').panel('panel');
					panel.find('.datagrid-body').undelegate('td', 'mouseover').undelegate('td', 'mouseout').undelegate('td', 'mousemove')
				});
			}
		});
    </script>
	<script>
        /**
         * 发送短信消息
         * @_url: 调用的发送接口
         * @_data：要发送的数据
         */
        function ajaxSendShortMessage(_url, _data){
            $.ajax({
                type: 'post',
                url: _url,
                data: _data,
                dataType: 'json',
                success: function(data){
                    if(!data.error){
                        $.messager.show({
                            title: '短信发送成功',
                            msg: data.msg
                        });
                    }else{
                        $.messager.show({
                            title: '短信发送失败',
                            msg: data.msg[0]
                        });
                    }
                }
            });
        }

		/*修改资料*/
		function editProfile(){
            $('#editProfileWin')
                .dialog('open')
                .dialog('refresh',"<?= yii::$app->urlManager->createUrl(['/site/edit-profile']); ?>");
		}
	</script>
</head>
<body class="easyui-layout">
    <div data-options="region:'north',title:''<?php /*,split:true*/ ?>" style="height:70px;width:100%;margin:0 auto;">
        <!-- 页面元素样式在web/CSS/extend.css下 -->
        <div class="img"></div>
        <div class="right">
			<a href="<?= yii::$app->urlManager->createUrl(['/site/logout']); ?>">安全退出</a>
        </div> 
        <div class="right">
			<a href="javascript:void(0);" onclick="editProfile()">修改资料</a>
        </div>
		<!-- 页面元素样式在web/CSS/extend.css下 -->				
		<!-- 弹窗-修改资料 begin-->
			<div id="editProfileWin"></div>
		<!-- 弹窗-修改资料 end-->
    </div>  
    <div data-options="region:'south',title:''" style="height:40px;">
        <div class="footer"><p>©地上铁&nbsp;新能源租车管理系统</p></div>
    </div>    
    <div data-options="region:'west',title:'菜单导航',split:true" style="width:200px;">
        <ul id="easyui-tree-index-index-menu"></ul>
    </div>  
    <div data-options="region:'center',title:''" style="">
        <div id="easyui_tabs_index_index_main" style="width:500px;height:250px;" data-options="
            border: false,
            fit: true
        ">
            <div title="系统首页" href="<?php echo yii::$app->urlManager->createUrl(['index/welcome']); ?>"></div>
        </div>  
    </div>
    <!-- tabs下拉菜单开始 -->
    <div id="index_index_tabs_nav">
        <div data-options="iconCls:'icon-reload'" name="reload">刷新</div>
        <div name="close">关闭</div>
        <div name="close_other">关闭其他</div>
        <div name="close_all">关闭所有</div>
    </div>
    <!-- tabs下拉菜单结束 -->
<?php echo $this->endBody(); ?>
<script>
    var IndexIndex = {
        params: {
            url: {
                menu: "<?php echo yii::$app->urlManager->createUrl(['index/menu']); ?>"
            },
            mainTabs: $('#easyui_tabs_index_index_main'),
            tabsNav: $('#index_index_tabs_nav'),
            menuTree: $('#easyui-tree-index-index-menu')
        },
        init: function(){
            this.params.mainTabs.tabs({
                border: false,
                onContextMenu:function(e, title,index){
                    e.preventDefault();
                    if(index > 0){
                        $('#index_index_tabs_nav').menu('show', {
                            left: e.pageX,
                            top: e.pageY
                        }).data("tabTitle", title);
                    }
                }
            });
            this.params.menuTree.tree({
                url: this.params.url.menu,
                lines: true,
                onClick: function(node){
                    if(node.target_url){
                        window.open(node.target_url);
                        return true;
                    }
                    if(node.mca){
                        if(IndexIndex.params.mainTabs.tabs('exists',node.text)){
                            IndexIndex.params.mainTabs.tabs('select',node.text);
                            IndexIndex.params.mainTabs.tabs('getTab',node.text).panel('refresh');
                        }else{
                            IndexIndex.params.mainTabs.tabs('add',{
                                title: node.text,   
                                content: '',
                                href: node.mca,
                                closable: true,
                                fit: true,
                                onContextMenu: function(e, title,index){
                                    e.preventDefault();
                                    IndexIndex.tabsNav.menu('show', {
                                        left: e.pageX,
                                        top: e.pageY
                                    }).data("tabTitle", title);
                                }
                            });
                        }
                    }else{
                        //若是父级节点就展开/收缩下一级节点
                        $(this).tree('toggle',node.target);
                    }
                }
            });
            //右键菜单click
            this.params.tabsNav.menu({
                onClick : function (item) {
                    var curTabTitle = $(this).data("tabTitle");
                    switch (item.name) {
                        case 'reload':
                            IndexIndex.params.mainTabs.tabs('select',curTabTitle)
                            IndexIndex.params.mainTabs.tabs('getTab',curTabTitle).panel('refresh');
                            break;
                        case 'close':
                            IndexIndex.params.mainTabs.tabs('close',curTabTitle);
                            break;
                        case 'close_other':
                            var tabs = IndexIndex.params.mainTabs.tabs('tabs');
                            var closeTableTitle = [];
                            $.each(tabs,function(i,n){
                                var opt = $(n).panel('options');
                                if(opt.closable && opt.title !== curTabTitle){
                                    closeTableTitle.push(opt.title);
                                }
                            });
                            for(var i in closeTableTitle){
                                IndexIndex.params.mainTabs.tabs('close',closeTableTitle[i]);
                            }
                            break;
                        case 'close_all':
                            var allTabs = IndexIndex.params.mainTabs.tabs('tabs');
                            var closeTableTitle = [];
                            $.each(allTabs,function(i,n){
                                var opt = $(n).panel('options');
                                if(opt.closable){
                                    closeTableTitle.push(opt.title);
                                }
                            });
                            for(var i in closeTableTitle){
                                IndexIndex.params.mainTabs.tabs('close',closeTableTitle[i]);
                            }
                            break;
                    }
                }
            });
        }
    };
	$(function(){
        IndexIndex.init();
		//--初始化【修改资料】弹窗
		$('#editProfileWin').dialog({
			title: '修改资料',
			width: 600,
			height: 380,
			closed: true,
			closable: <?=$pwd_format_ok?'true':'false'?>,
			cache: true,
			modal: true,
			maximizable: false,
			resizable: false,
			onClose: function () {
				$(this).dialog('clear');
			},
			buttons: [{
				text: '确定',
				iconCls: 'icon-ok',
				handler: function () {
					var form = $('#editProfileWin_form');
					// 若原密码不为空则表示要修改密码，这时要验证两次新密码。
					if($.trim($('[name="oldPassword"]',form).val())){
						if($.trim($('[name="newPassword"]',form).val()) != $.trim($('[name="newPasswordRepeat"]',form).val())){
							$.messager.show({
								title: '密码不一致',
								msg: '新密码两次输入不一致！'
							});
							return false;
						}
					}
					$.ajax({
						type: 'post',
						url: "<?= yii::$app->urlManager->createUrl(['/site/edit-profile']); ?>",
						data: form.serialize(),
						dataType: 'json',
						success: function (data) {
							if (data.status) {
								$.messager.show({
									title: '操作成功',
									msg: data.info
								});
								$('#editProfileWin').dialog('close');
							} else {
								$.messager.show({
									title: '操作失败',
									msg: data.info
								});
							}
						}
					});
				}
			}, {
				text: '取消',
				iconCls: 'icon-cancel',
				handler: function () {
					<?php
						if(!$pwd_format_ok){
							echo "$.messager.show({title: '密码格式不正确',msg: '密码必须同时包含英文大写、小写字母与数字，长度不少于8位。'});";
						}else {
							echo "$('#editProfileWin').dialog('close');";
						}
					?>
				}
			}]
			
		});	
		<?php
			if(!$pwd_format_ok){
				echo "editProfile();";
				echo "$.messager.show({title: '请修改密码',msg: '您当前的系统密码不符合要求，请按提示修改密码。'});";
			}
		?>
	});
</script>
</html>