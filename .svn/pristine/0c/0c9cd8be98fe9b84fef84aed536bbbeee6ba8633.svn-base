<link rel="stylesheet" type="text/css" href="css/workbench.css?id=401" >
<div class='product sidebar-trans'>
    <div class='mbody gruy-bg'>
        <div class='home-section-notice'>
            <span class="notice-title">新闻:</span>
            <ul id="company-news" style="margin:0px; padding:0px">
            </ul>
        </div>
        <div class='home-section-main'>
            <div class='home-section-wrap fl col-9'>
                <div class='home-section-user'>
                    <div class='user-remind'>hi,您还有<span class='warncolor' id='dblcadd'></span>件待办工作未完成</div>
                    <div class='user-setting'><span class="mcolor iconfont icon-ico-set-off"></span></div>
                    <div class='user-warn'>
                        <ul style="margin:0px; padding:0px">
                            <span id="dblc1"><li><a href="#"><span class='mcolor'>提车申请</span><b>0</b></a></li>|</span>
                            <span id="dblc2"><li><a href="#"><span class='mcolor'>退车管理</span><b>0</b></a></li>|</span>
                            <span id="dblc3"><li><a href="#"><span class='mcolor'>车辆维修登记</span><b>0</b></a></li>|</span>
                            <span id="dblc4"><li><a href="#"><span class='mcolor'>客户报修登记</span><b>0</b></a></li></span>
                        </ul>
                    </div>
                </div>
            </div>
            <div class='home-section-wrap fr col-3'>
                <div class='home-section-update'>
                    <div class='title'>更新日志</div>
                    <ul class='content' id='change_log' style="padding:0px;">
                        <li class='month'>近期更新</li>

                        <!--<a href="#"><li class='day'><div class='code'></div><span>6.25</span>跟新内容1</li></a>
                        <a href="#"><div class='fr mcolor' id='change_log_more'>更多》</div></a>-->                        
                    </ul>   
                </div>
            </div>
            
            <div class='clear'></div>
            <!--<div class='home-section-wrap fl col-9'>
                <div class='home-section-statistics'>
                    <div class='title'>我关心的数据此处用echart插件做</div>
                </div>
            </div>
            <div class='home-section-wrap fr col-3'>
                <div class='home-section-topic'>
                    <div class='title'>团队目标</div>
                    <div class='content'>您还未在设置内选择自己所属团队请尽快设置粑粑粑粑粑粑粑粑粑粑粑粑粑粑粑粑粑粑粑粑粑粑粑粑粑粑粑</div>
                </div>
            </div>-->
            
            <div class='clear'></div>
        </div>
    </div>
</div>
<div id="easyui_dialog_index_index_car_back_alert_deal"></div>
<div id="easyui_dialog_index_index_pole_back_alert_deal"></div>
<script>
    var IndexWelcome = {
        params: {
            url: {
                //获取后台告警
                carBackAlert: "<?php echo yii::$app->urlManager->createUrl(['carmonitor/exception-deal/back-alert']); ?>",
                carAlertDeal: "<?= yii::$app->urlManager->createUrl(['carmonitor/exception-deal/alert-deal']); ?>",
                poleBackAlert: "<?= yii::$app->urlManager->createUrl(['polemonitor/alert/pole-back-alert']); ?>",
                polecarAlertDeal: "<?= yii::$app->urlManager->createUrl(['polemonitor/alert/deal']); ?>"
            },
            carBackAlertResivedId: 0,//车辆告警已经获取的告警的记录的id
            carBackAlert: [],//后台报警数据
            carBackAlertMessageBox: null,//弹出层对象
            carBackAlertMessageBoxOpendIndex: 0,//当前打开的报警索引号
            poleBackAlertResivedId: 0,//电桩告警已经获取的告警的记录的id
            poleBackAlert: [],
            poleBackAlertMessageBox: null,//弹出层对象
            poleBackAlertMessageBoxOpendIndex: 0,//当前打开的报警索引号
            windows: {
                carAlertDeal: $('#easyui_dialog_index_index_car_back_alert_deal'),
                poleAlertDeal: $('#easyui_dialog_index_index_pole_back_alert_deal')
            }
        },
        init: function(){
            //初始化车辆后台报警处理窗口
            this.params.windows.carAlertDeal.dialog({
                title: '车辆告警处理',
                width: 1000,
                height: 500,
                closed: true,
                cache: true,
                modal: true,
                maximizable: true,
                resizable: false,
                onClose: function () {
                    $(this).dialog('clear');
                },
                buttons: [{
                    text: '确定',
                    iconCls: 'icon-ok',
                    handler: function () {
                        CarmonitorExceptionAlertDeal.saveData();
                    }    
                }, {
                    text: '取消',
                    iconCls: 'icon-cancel',
                    handler: function () {
                        IndexWelcome.params.windows.carAlertDeal.dialog('close');
                    }
                }]
            });
            //初始化电桩后台警告异常处理窗口
            this.params.windows.poleAlertDeal.dialog({
                title: '电桩告警处理',
                width: 1000,
                height: 500,
                closed: true,
                cache: true,
                modal: true,
                maximizable: true,
                resizable: false,
                onClose: function () {
                    $(this).dialog('clear');
                },
                buttons: [{
                    text: '确定',
                    iconCls: 'icon-ok',
                    handler: function () {
                        PolemonitorAlertDeal.saveData();
                    }    
                }, {
                    text: '取消',
                    iconCls: 'icon-cancel',
                    handler: function () {
                        IndexWelcome.params.windows.poleAlertDeal.dialog('close');
                    }
                }]
            });
            //检测用户是否有权限查看车辆后台告警
            $.ajax({
                type: 'get',
                url: this.params.url.carBackAlert,
                dataType: 'json',
                success: function(rData){
                    if(typeof rData.error != 'undefined' && rData.error == 0){
                        IndexWelcome.params.carBackAlertResivedId = rData.max_id;
                        IndexWelcome.getCarBackAlertData();
                    }
                }
            });
            //检测用户是否有权限查看电桩后台告警
            $.ajax({
                type: 'get',
                url: this.params.url.poleBackAlert,
                dataType: 'json',
                success: function(rData){
                    if(typeof rData.error != 'undefined' && rData.error == 0){
                        IndexWelcome.params.poleBackAlertResivedId = rData.max_id;
                        IndexWelcome.getPoleBackAlertData();
                    }
                }
            });
        },
        //获取车辆后台报警数据
        getCarBackAlertData: function(){
            setTimeout(function(){
                $.ajax({
                    type: 'post',
                    url: IndexWelcome.params.url.carBackAlert,
                    data: {start_id: IndexWelcome.params.carBackAlertResivedId},
                    dataType: 'json',
                    success: function(rData){
                        var length = rData.length;
                        if(length > 0){
                            IndexWelcome.params.carBackAlertResivedId = rData[length - 1].id;
                            IndexWelcome.params.carBackAlert = IndexWelcome.params.carBackAlert.concat(rData);
                            IndexWelcome.showCarBackAlertMessageBox();
                        }
                    }
                });
                IndexWelcome.getCarBackAlertData();
            },60000);
        },
        //显示后台告警消息提示窗口
        showCarBackAlertMessageBox: function(index){
            var length = IndexWelcome.params.carBackAlert.length;
            if(length == 0){
                return false;
            }
            if(typeof index == 'undefined'){
                index = this.params.carBackAlertMessageBoxOpendIndex;
            }
            //防止越界
            index = index <= 0 ? 0 : index;
            index = index >= length ? length - 1 : index;
            //缓存当前索引号
            this.params.carBackAlertMessageBoxOpendIndex = index;
            var alertMessage = IndexWelcome.params.carBackAlert[index];
            var msg = '<div class="easyui_messagebox_index_index_car_back_alert" style="line-height:22px;">';
            msg += '<div>车架号：'+alertMessage.car_vin+',';
            msg += '车牌号：'+alertMessage.plate_number+',';
            msg += '报警项目：<b style="color:red">'+alertMessage.alert_type+'</b>,';
            msg += '系统提示：<b style="color:red">'+alertMessage.alert_content+'</b>,';
            msg += '报警值：<b style="color:red">'+alertMessage.alert_value+'</b></div>';
            msg += '<div style="text-align:center"><a class="easyui-linkbutton" data-options="iconCls:\'icon-search\'" href="javascript:void(0)" onclick="IndexWelcome.carBackAlertDeal('+index+','+alertMessage.id+',\''+alertMessage.car_vin+'\')">查看并处理</a></div>';
            msg += '<div style="text-align:center">未读告警总数(' + (index + 1) + '/' + length + ')</div>';
            msg += '<div style="text-align:center">';
            msg += '<a class="easyui-linkbutton" data-options="iconCls:\'icon-undo\'" href="javascript:void(0)" onclick="IndexWelcome.showCarBackAlertMessageBox(';
            if(index <= 0){
                msg += (length - 1);
            }else{
                msg += (index - 1);
            }
            msg += ')">上一条</a>&nbsp;&nbsp;';
            msg += '<a class="easyui-linkbutton" data-options="iconCls:\'icon-redo\'" href="javascript:void(0)" onclick="IndexWelcome.showCarBackAlertMessageBox(';
            if(index >= length - 1){
                msg += '0';
            }else{
                msg += (index + 1);
            }
            msg += ')">下一条</a>';
            msg += '</div>';
            msg += '</div>';
            if(IndexWelcome.params.carBackAlertMessageBox){
                try{
                    IndexWelcome.params.carBackAlertMessageBox.window('close');
                }catch(e){
                    IndexWelcome.params.carBackAlertMessageBox = null;
                } 
            }
            IndexWelcome.params.carBackAlertMessageBox = $.messager.show({
                iconCls: 'icon-tip',
                title: '车辆[' + alertMessage.car_vin + ']触发告警',
                width: 300,
                height: 200,
                msg: msg,
                timeout: 0,
                showType: 'fade'
            });
            $.parser.parse($('.easyui_messagebox_index_index_car_back_alert'));
        },
        //车辆后台告警处理
        carBackAlertDeal: function(index,id,car_vin){
            this.params.carBackAlert.splice(index,1);
            this.params.windows.carAlertDeal
                .dialog('open')
                .dialog('refresh',this.params.url.carAlertDeal+'&id='+id+'&car_vin='+car_vin);
        },
        //获取电桩后台告警
        getPoleBackAlertData: function(){
            setTimeout(function(){
                $.ajax({
                    type: 'post',
                    url: IndexWelcome.params.url.poleBackAlert,
                    data: {start_id: IndexWelcome.params.poleBackAlertResivedId},
                    dataType: 'json',
                    success: function(rData){
                        var length = rData.length;
                        if(length > 0){
                            IndexWelcome.params.poleBackAlertResivedId = rData[length - 1].id;
                            IndexWelcome.params.poleBackAlert = IndexWelcome.params.poleBackAlert.concat(rData);
                            IndexWelcome.showPoleBackAlertMessageBox();
                        }
                    }
                });
                IndexWelcome.getPoleBackAlertData();
            },60000);
        },
        //显示电桩告警弹出窗口
        showPoleBackAlertMessageBox: function(index){
            var length = IndexWelcome.params.poleBackAlert.length;
            if(length == 0){
                return false;
            }
            if(typeof index == 'undefined'){
                index = this.params.poleBackAlertMessageBoxOpendIndex;
            }
            //防止越界
            index = index <= 0 ? 0 : index;
            index = index >= length ? length - 1 : index;
            //缓存当前索引号
            this.params.poleBackAlertMessageBoxOpendIndex = index;
            var alertMessage = IndexWelcome.params.poleBackAlert[index];
            var msg = '<div class="easyui_messagebox_index_index_pole_back_alert" style="line-height:22px;">';
            msg += '<div>充电站：'+alertMessage.cs_name+',';
            msg += '充电桩：'+alertMessage.dev_addr+',';
            msg += '充电桩状态：'+alertMessage.pole_status+',';
            msg += '报警项目：<b style="color:red">'+alertMessage.alert_name+'</b>,';
            msg += '系统提示：<b style="color:red">'+alertMessage.alert_content+'</b>,';
            msg += '<div style="text-align:center"><a class="easyui-linkbutton" data-options="iconCls:\'icon-search\'" href="javascript:void(0)" onclick="IndexWelcome.poleBackAlertDeal('+index+','+alertMessage.id+')">查看并处理</a></div>';
            msg += '<div style="text-align:center">未读告警总数(' + (index + 1) + '/' + length + ')</div>';
            msg += '<div style="text-align:center">';
            msg += '<a class="easyui-linkbutton" data-options="iconCls:\'icon-undo\'" href="javascript:void(0)" onclick="IndexWelcome.showPoleBackAlertMessageBox(';
            if(index <= 0){
                msg += (length - 1);
            }else{
                msg += (index - 1);
            }
            msg += ')">上一条</a>&nbsp;&nbsp;';
            msg += '<a class="easyui-linkbutton" data-options="iconCls:\'icon-redo\'" href="javascript:void(0)" onclick="IndexWelcome.showPoleBackAlertMessageBox(';
            if(index >= length - 1){
                msg += '0';
            }else{
                msg += (index + 1);
            }
            msg += ')">下一条</a>';
            msg += '</div>';
            msg += '</div>';
            if(IndexWelcome.params.poleBackAlertMessageBox){
                try{
                    IndexWelcome.params.poleBackAlertMessageBox.window('close');
                }catch(e){
                    IndexWelcome.params.poleBackAlertMessageBox = null;
                } 
            }
            IndexWelcome.params.poleBackAlertMessageBox = $.messager.show({
                iconCls: 'icon-tip',
                title: '充电桩[' + alertMessage.dev_addr + ']触发告警',
                width: 300,
                height: 200,
                msg: msg,
                timeout: 0,
                showType: 'fade'
            });
            $.parser.parse($('.easyui_messagebox_index_index_pole_back_alert'));
        },
        //电桩后台告警处理
        poleBackAlertDeal: function(index,id){
            this.params.poleBackAlert.splice(index,1);
            this.params.windows.poleAlertDeal
                .dialog('open')
                .dialog('refresh',this.params.url.polecarAlertDeal+'&id='+id);
        },
    };
    IndexWelcome.init();
</script>
<script type="text/javascript" src='js/workbench.js'></script>
<script type="text/javascript">
    $.ajax({
        type: 'get',
        url: '<?php echo yii::$app->urlManager->createUrl('index/get-data'); ?>',
        dataType: 'json',
        success: function(rData){
            var dblc = 0;
            if(rData.tc_todo == 'NOACCESS'){
                $('#dblc1').hide();
                
            }else{
                $('#dblc1 b').text(rData.tc_todo);
                dblc += rData.tc_todo*1;
            }
            if(rData.back_car_todo == 'NOACCESS'){
                $('#dblc2').hide();
                
            }else{
                $('#dblc2 b').text(rData.back_car_todo);
                dblc += rData.back_car_todo*1;
            }
            if(rData.maintain_todo == 'NOACCESS'){
                $('#dblc3').hide();
                
            }else{
                $('#dblc3 b').text(rData.maintain_todo);
                dblc += rData.maintain_todo*1; 
            }
            if(rData.repair_todo== 'NOACCESS'){
                $('#dblc4').hide();
                
            }else{
                $('#dblc4 b').text(rData.repair_todo);
                dblc += rData.repair_todo*1;
            }
            $('#dblcadd').text(dblc);
            if(rData.tc_todo == 'NOACCESS' && rData.back_car_todo == 'NOACCESS' && rData.maintain_todo == 'NOACCESS' && rData.repair_todo == 'NOACCESS'){
                $('#dblcadd').parent('.user-remind').text("hi,您没有代办流程");
            }

            /*判断工作台项目的值是否为0*/
            var warn_amount = $('.home-section-user .user-warn b').length;
            var warn_body = $('.home-section-user .user-warn b');
            for (var i = 0; i < warn_amount; i++ ){
                if(warn_body.eq(i).text() != 0){
                    warn_body.eq(i).addClass('warncolor');
                }else{
                }
            }
            var html = [];
            for (var i = rData.sys_log.length -1; i >= 0 ; i--){
                html.push('<a class="update-log" href="#" onclick="updatelog()"><li class="day"><div class="code"></div><span>'+rData.sys_log[i].update_date.slice(0,10)+'</span>'+rData.sys_log[i].content+'</li></a>');
            }
            html.push('<a href="#" onclick="updatelog()"><div class="fr mcolor" id="change_log_more">更多》</div></a>'); 
            $(html.join('')).appendTo('#change_log');

            var html = [];
            for (var i = 0; i < 2; i++){
                 html.push('<li>');
                    html.push('<span class="data">['+rData.news[i].add_time.slice(0,10)+']</span>');
                    html.push('<a class="mcolor" target="_blank" href="http://www.dstzc.com/news_details.html?id='+rData.news[i].id+'">'+rData.news[i].title+'</a>');
                html.push('</li>');
            }
            $(html.join('')).appendTo('#company-news');
            
        }
    });
    //点击打开窗口
    function updatelog(){
        if ($('#easyui_tabs_index_index_main').tabs('exists', '系统升级日志')){
            $('#easyui_tabs_index_index_main').tabs('select', '系统升级日志');
        }else{
            $('#easyui_tabs_index_index_main').tabs('add', {
                title:  '系统升级日志',
                href: '<?php echo yii::$app->urlManager->createUrl('system/code-update-log/index'); ?>',
                closable: true

            });
        } 
    }
    $('#dblc1').click(function(){
        if ($('#easyui_tabs_index_index_main').tabs('exists', '提车申请')){
            $('#easyui_tabs_index_index_main').tabs('select', '提车申请');
        }else{
            $('#easyui_tabs_index_index_main').tabs('add', {
                title:  '提车申请',
                href: '<?php echo yii::$app->urlManager->createUrl('process/car/index'); ?>',
                closable: true

            });
        } 
    });
    
    $('#dblc2').click(function(){
        if ($('#easyui_tabs_index_index_main').tabs('exists', '退车管理')){
            $('#easyui_tabs_index_index_main').tabs('select', '退车管理');
        }else{
            $('#easyui_tabs_index_index_main').tabs('add', {
                title:  '退车管理',
                href: '<?php echo yii::$app->urlManager->createUrl('car/car-back/index'); ?>',
                closable: true

            });
        } 
    });
    $('#dblc3').click(function(){
        if ($('#easyui_tabs_index_index_main').tabs('exists', '车辆维修登记')){
            $('#easyui_tabs_index_index_main').tabs('select', '车辆维修登记');
        }else{
            $('#easyui_tabs_index_index_main').tabs('add', {
                title:  '车辆维修登记',
                href: '<?php echo yii::$app->urlManager->createUrl('process/repair/maintain'); ?>',
                closable: true

            });
        } 
    });
    $('#dblc4').click(function(){
        if ($('#easyui_tabs_index_index_main').tabs('exists', '客户报修登记')){
            $('#easyui_tabs_index_index_main').tabs('select', '客户报修登记');
        }else{
            $('#easyui_tabs_index_index_main').tabs('add', {
                title:  '客户报修登记',
                href: '<?php echo yii::$app->urlManager->createUrl('process/repair/index'); ?>',
                closable: true

            });
        } 
    });
    
</script>