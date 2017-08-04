<div class="easyui-layout" data-options="{fit:true,border:false}">
    <div data-options="region:'west',title:'条件筛选',border:false" style="width:240px;">
        <div id="carmonitor_realtime_car_track_calendar" style="margin:10px auto;"></div>
        <div id="carmonitor_realtime_car_track_hour_mark">
        <?php
        $hasDataIndex = 0;
        foreach($threeDayHasDataHour as $k=>$v){
            echo '<div style="text-align:center;line-height:22px;">'.$k.'</div>';
            echo '<ul style="overflow:hidden;padding:0;margin:0;width:208px;margin:0 auto;list-style:none;">';
            for($i = 0;$i<=23;$i++){
                $i = $i < 10 ? '0'.$i : $i;
                if(isset($v[$i])){
                    echo '<li class="has-data" hasdataindex="'.$hasDataIndex.'" value="'.$v[$i].'" style="border:1px solid #25a825;width:20px;height:20px;line-height:20px;text-align:center;float:left;margin:0 4px 4px 0;color:#25a825;cursor:pointer;" onclick="CarmonitorRealtimeCarTrack.setMark(this)" >'.$i.'</li>';
                    $hasDataIndex ++;
                }else{
                    echo '<li style="border:1px solid #ccc;width:20px;height:20px;line-height:20px;text-align:center;float:left;margin:0 4px 4px 0;color:#555;">'.$i.'</li>';
                }
            }
            echo '</ul>';
        }
        ?>
        </div>
        <form class="data-search-form" id="search-form-carmonitor-realtime-car-track">
            <input type="hidden" name="car_vin" value="<?= $carVin; ?>" />
            <input type="hidden" name="start_date" />
            <input type="hidden" name="end_date" />
            <ul class="search-main">
                <li>
                    <div class="item-name" style="width:50px;">播放速度</div>
                    <div class="item-input" style="width:150px;">
                        <select
                            class="easyui-combobox"
                            name="playSpeed"
                            style="width:100%;"
                            data-options="{panelHeight: 'auto',editable:false,onChange: function(){
                                CarmonitorRealtimeCarTrack.search();
                            }}"
                        >
                            <option value="1" selected>x1倍速度</option>
                            <option value="2">x2倍速度</option>
                            <option value="4">x4倍速度</option>
                            <option value="8">x8倍速度</option>
                            <option value="16">x16倍速度</option>
                            <option value="32">x32倍速度</option>
                            <option value="64">x64倍速度</option>
                        </select>
                    </div>
                </li>
                <li>
                    <div class="item-name" style="width:50px;">轨迹捕捉</div>
                    <div class="item-input" style="width:150px;">
                        <select
                            class="easyui-combobox"
                            name="trackCatch"
                            style="width:100%;"
                            data-options="{panelHeight: 'auto',editable:false,onChange: function(){
                                CarmonitorRealtimeCarTrack.search();
                            }}"
                        >
                            <option value="0">不捕捉</option>
                            <option value="1" selected>1秒</option>
                            <option value="2">2秒</option>
                            <option value="3">3秒</option>
                            <option value="4">4秒</option>
                            <option value="5">5秒</option>
                        </select>
                    </div>
                </li>
            </ul>
        </form>
    </div>
    <div data-options="region:'center',title:'',border:false," style="padding:5px;background:#eee;"><iframe id="iframe-carmonitor-realtime-car-track" style="width:100%;height:100%;" frameborder="none"></iframe></div>
</div>
<script>
    var CarmonitorRealtimeCarTrack = {
        params: {
            url: {
                carTrack: "<?= yii::$app->urlManager->createUrl(['carmonitor/realtime/car-track']); ?>"
            }
        },
        init: function(){
            var searchForm = $('#search-form-carmonitor-realtime-car-track');
            searchForm.submit(function(){
                var iframe = document.getElementById('iframe-carmonitor-realtime-car-track');
                $(iframe.contentWindow.document.body).html('');
                $(iframe).attr('src',"<?php echo yii::$app->urlManager->createUrl(['carmonitor/realtime/car-track-map']); ?>"+'&'+$(this).serialize());
                return false;
            });
            //日历
            $('#carmonitor_realtime_car_track_calendar').calendar({
                width:200,
                height:160,
                current:  new Date("<?= date('Y-m-d',$day); ?>"),
				year: <?= date('Y',$day); ?>,
                month: <?= date('m',$day); ?>,
                onSelect: function(date){
                    var date = new Date(date);//(中国标准时间)
                    var year = date.getFullYear();
                    var month = date.getMonth() + 1;
                    var day = date.getDate();
                    date = year + '-' + (month<10 ? '0' + month : month) + '-' + (day<10 ? '0' + day : day);
                    var easyuiWindow = $('#easyui-dialog-carmonitor-realtime-index-car-track');
                    easyuiWindow.window('refresh',CarmonitorRealtimeCarTrack.params.url.carTrack+'&day='+date+'&car_vin=<?= $carVin; ?>');
                },
                styler: function(date){
                    var selectMonthHasDataDay = <?= json_encode($selectMonthHasDataDay); ?>;
                    var date = new Date(date);//Wed Oct 21 2015 10:36:25 GMT+0800 (中国标准时间)
                    var year = date.getFullYear();
                    var month = date.getMonth() + 1;
                    var day = date.getDate();
                    date = year + '-' + (month<10 ? '0' + month : month) + '-' + (day<10 ? '0' + day : day);
                    for(var i in selectMonthHasDataDay){
                        if(selectMonthHasDataDay[i] == date){
                            return 'background: #e0ecff;';
                        }
                    }
                    return '';
                }
            });

            $('.calendar-nav').click(function () {
                var o = $(this);
                var easyuiWindow = $('#easyui-dialog-carmonitor-realtime-index-car-track');
                var date = new Date();
				date.setYear(new Date("<?= date('Y-m-d',$day); ?>").getFullYear());
                if(o.hasClass('calendar-prevmonth')){
                	date.setMonth(new Date("<?= date('Y-m-d',$day); ?>").getMonth()-1);
                }else if (o.hasClass('calendar-nextmonth')){
                	date.setMonth(new Date("<?= date('Y-m-d',$day); ?>").getMonth()+1);
                }
                date = formatDateToString(Math.round(date.getTime()/1000));
                easyuiWindow.window('refresh',CarmonitorRealtimeCarTrack.params.url.carTrack+'&day='+date+'&car_vin=<?= $carVin; ?>');
            });
            //$(".calendar-nav calendar-prevmonth").click(function(){ 
            //	$("#btn3").click(); 
           // }); 

            //自动查询
            var hasDataLi = $('#carmonitor_realtime_car_track_hour_mark')
                .find('ul').eq(1).find('li.has-data')
                .first();
            if(hasDataLi.get(0)){
                this.setMark(hasDataLi.get(0));
            }
        },
        search: function(){
            $('#search-form-carmonitor-realtime-car-track').submit();
        },
        reset: function(){
            var searchForm = $('#search-form-carmonitor-realtime-car-track');
            searchForm.form('reset');
            searchForm.submit();
            return false;
        },
        setMark: function(obj){
            var hasMarkLi = $('#carmonitor_realtime_car_track_hour_mark').find('li.select-mark');
            if($(obj).hasClass('select-mark')){
                $(obj).removeClass('select-mark');
                $(obj).css({background: "#fff",color: "#25a825"});
            }else{
                if(hasMarkLi.length >= 2){
                    //移除最后一个被选择中的选中标志
                    hasMarkLi.last().removeClass('select-mark');
                    hasMarkLi.last().css({background: "#fff",color: "#25a825"});
                }
                //为当前点击对象添加选中
                $(obj).addClass('select-mark');
                $(obj).css({background: "#8bd18b",color: "white"});
                
            }
            var hasMarkLi = $('#carmonitor_realtime_car_track_hour_mark').find('li.select-mark');
            var searchForm = $('#search-form-carmonitor-realtime-car-track');
            switch(hasMarkLi.length){
                case 1:
                    //选择一个点
                    var startDate = hasMarkLi.first().attr('value');
                    searchForm.find('input[name=start_date]').val(startDate);
                    searchForm.find('input[name=end_date]').val('');
                    break;
                case 2:
                    //选择二个点
                    var startPoint = hasMarkLi.first();
                    var startDate = startPoint.attr('value');
                    var endPoint = hasMarkLi.last();
                    var endDate = endPoint.attr('value');
                    var startDateIndex = startPoint.attr('hasdataindex');
                    var endDateIndex = endPoint.attr('hasdataindex');
                    //console.log(startDateIndex+'-'+endDateIndex);
                    if(endDateIndex - startDateIndex >= 6){
                        $.messager.alert('操作失败','时间跨度不能超过6小时！','error');
                        return false;
                    }
                    searchForm.find('input[name=start_date]').val(startDate);
                    searchForm.find('input[name=end_date]').val(endDate);
                    break;
                default:
                    return;
            }
            searchForm.submit();
        }
    };
    CarmonitorRealtimeCarTrack.init();
</script>