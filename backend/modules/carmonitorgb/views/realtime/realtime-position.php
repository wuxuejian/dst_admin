<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <style type="text/css">
    body, html,#allmap {width: 100%;height: 100%;overflow: hidden;margin:0;font-family:"微软雅黑";}
    </style>
    <script type="text/javascript" src="<?= $_config['bmap_api_addr']; ?>"></script>
    <script type="text/javascript" src="<?= yii::getAlias('@web'); ?>/js/jquery-1.7.2.min.js"></script>
</head>
<body>
    <div id="allmap"></div>
</body>
</html>
<script type="text/javascript">
    var timer = 0;
    var map;//全局地图
    var transPointInfo = {
        plate_number:'',
        car_vin: '',
        soc:'',
        speed:'',
        tdm:'',
        longitude_value:'',
        latitude_value:'',
        collection_datetime:''
    };//转换后点的信息
    //向后台请求定位数据
    function requestData(){
        $.ajax({
            'type':'post',
            'url':"<?php echo yii::$app->urlManager->createUrl(['carmonitorgb/realtime/realtime-position']); ?>",
            'data': {'car_vin': "<?= $carVin; ?>"},
            'dataType':'json',
            'success': function(rData){
                if(rData.status){
                    transPointInfo = rData.data;
                    transPosition({
                        "longitude_value": transPointInfo.longitudeValue,
                        "latitude_value": transPointInfo.latitudeValue
                    });
                }else{
                    window.parent.$.messager.show({
                        title:'获取数据失败',
                        msg: rData.msg
                    });
                }
                
            }
        });
    }
    //GPS坐标转百度坐标
    function transPosition(gps_point){
        if(parseInt(gps_point.longitude_value) <= 0 || parseInt(gps_point.latitude_value) <= 0){
            return false;
        }
        $.ajax({
            url: "<?= $_config['bmap_geoconv_addr']; ?>",
            type: "get",
            dataType: 'jsonp',
            jsonp: 'callback',
            data: {
                "coords": gps_point.longitude_value+','+gps_point.latitude_value
            },
            timeout: 5000,
            success: function (data) {
                if(data.status == 0){
                    transPointInfo.longitude_value = data.result[0].x;
                    transPointInfo.latitude_value = data.result[0].y;
                    setPositionInMap(true);
                }
            } 
        });  
    }
    //地图初始化
    function init(){
        //初始化地图
        map = new BMap.Map("allmap");// 创建Map实例
        map.addControl(new BMap.MapTypeControl());//添加地图类型控件
        //map.setCurrentCity("北京");//设置地图显示的城市此项是必须设置的
        map.enableScrollWheelZoom(true);//开启鼠标滚轮缩放
        //设置比例尺控件
        map.addControl(new BMap.ScaleControl({anchor: BMAP_ANCHOR_BOTTOM_LEFT}));// 左上角，添加比例尺
        map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_RIGHT}));  //左上角，添加默认缩放平移控件
        //map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_LEFT, type: BMAP_NAVIGATION_CONTROL_SMALL})); //右上角，仅包含平移和缩放按钮
        /*缩放控件type有四种类型:
        BMAP_NAVIGATION_CONTROL_SMALL：仅包含平移和缩放按钮；BMAP_NAVIGATION_CONTROL_PAN:仅包含平移按钮；BMAP_NAVIGATION_CONTROL_ZOOM：仅包含缩放按钮*/
        //以第一个点初始化地图
        setPositionInMap(false);
        requestData();//第一个点校准
        //定时向后台请求定位数据
        timer = setInterval(requestData,8000);
    }
    //利用转换后的点设置地图位置
    function setPositionInMap(is_reload){
        //判断是创建地图还是刷新地图
        var point = new BMap.Point(transPointInfo.longitude_value,transPointInfo.latitude_value);
        if(is_reload){
            map.clearOverlays();//删除原有标注
            //获取地图当前显示级别
            map.centerAndZoom(point, map.getZoom());//设置中心点坐标和地图级别
        }else{
            map.centerAndZoom(point, 15);//设置中心点坐标和地图级别
        }
        map.panTo(point);//定位地图
        //创建标注
        d = transPointInfo.direction;
        if(d > 337.5 || d <= 22.5){
            directionIcon = '0';
        }else if(d > 22.5 && d <= 67.5){
            directionIcon = '45';
        }else if(d > 67.5 && d <= 112.5){
            directionIcon = '90';
        }else if(d > 112.5 && d <= 157.5){
            directionIcon = '135';
        }else if(d > 157.5 && d <= 202.5){
            directionIcon = '180';
        }else if(d > 202.5 && d <= 247.5){
            directionIcon = '225';
        }else if(d > 247.5 && d <= 292.5){
            directionIcon = '270';
        }else{
            directionIcon = '315';
        }
        var carIcon = new BMap.Icon("<?= yii::getAlias('@web'); ?>/images/direction/"+directionIcon+'.png', new BMap.Size(60,60));
        var marker = new BMap.Marker(point,{icon:carIcon});//创建标注
        map.addOverlay(marker);// 将标注添加到地图中
        //marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画
        //创建信息窗口
        var opts = {
          width : 260,     // 信息窗口宽度
          height: 140,     // 信息窗口高度
          title : "" , // 信息窗口标题
          enableMessage:true,//设置允许信息窗发送短息
          message:""
        }
        var windowContent = '';
        //windowContent += '<b>车牌号：</b>'+transPointInfo.plate_number+'<br />';
        windowContent += '<b>车架号：</b>'+transPointInfo.carVin+'<br />';
        windowContent += '<b>SOC：</b>'+transPointInfo.soc+' %<br />';
        windowContent += '<b>车速：</b>'+transPointInfo.speed+' km/h<br />';
        windowContent += '<b>总里程：</b>'+transPointInfo.totalDrivingMileage+' km<br />';
        //windowContent += '<b>经度值：</b>'+transPointInfo.longitude_value+'<br />';
        //windowContent += '<b>纬度值：</b>'+transPointInfo.latitude_value+'<br />';
        windowContent += '<b>采集时间：</b>'+transPointInfo.collectionDatetime;
        var infoWindow = new BMap.InfoWindow(windowContent,opts);//创建信息窗口对象 
        map.openInfoWindow(infoWindow,point);//开启信息窗口
        marker.addEventListener("click", function(){          
            map.openInfoWindow(infoWindow,point);//开启信息窗口
        });
    }
    function clearTimer(){
        clearInterval(timer);
    }
    //执行
    init();
</script>