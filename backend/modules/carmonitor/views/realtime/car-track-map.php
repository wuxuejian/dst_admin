<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <style type="text/css">
    body, html,#allmap {width: 100%;height: 100%;overflow: hidden;margin:0;font-family:"微软雅黑";}
    </style>
    <script type="text/javascript" src="<?= $config['bmap_api_addr']; ?>"></script>
    <script type="text/javascript" src="<?= yii::getAlias('@web'); ?>/js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="<?= $config['bmap_ls_addr']; ?>"></script>
</head>
<body>
    <div id="allmap"></div>
</body>
</html>
<script type="text/javascript">
    //请求数据
    var trackData = <?= json_encode($trackData); ?>;
    var transTrackData = [];//转换过后的轨迹
    var map;
    //初始化
    function init(){
        //将gps点转换为百度地图点
        //console.log('总长：'+trackData.length);
        transPosition(0);        
    }
    //递归调用转换直到所有坐标被转换
    //startIndex 开始的序号
    function transPosition(startIndex){
        if(startIndex >= trackData.length){
            //转换完成
            if(trackData.length != transTrackData.length){
                window.parent.$.messager.alert('错误','调用api转换GPS数据失败！','error');
                return false;
            }
            transComplete();
            return true;
        }
        var str = '';
        var transIndex = startIndex;
        for(var i = startIndex; i <= startIndex + 99;i++){
            if(trackData[i]){
                transIndex = i;
                str += trackData[i].longitude_value+','+trackData[i].latitude_value+';';
            }else{
                break;
            }
        }
        $.ajax({
            url: "<?= $config['bmap_geoconv_addr']; ?>",
            type: "get",
            dataType: 'jsonp',
            jsonp: 'callback',
            data: {"coords": str.substr(0,str.length - 1)},
            timeout: 5000,
            success: function (rData) {
                if(rData.status == 0){
                    for(var i in rData.result){
                        transTrackData.push({
                            'longitude_value': rData.result[i].x,
                            'latitude_value': rData.result[i].y,
                        });
                    }
                    //递归转换->必需放在success方法中去递归
                    //console.log('开始点：'+startIndex);
                    //console.log('转换后：'+transTrackData.length);
                    transPosition(transIndex + 1);
                }
            } 
        });  
    }
    //点转换成后回调执行
    function transComplete(){
        drawMap();//绘制地图
        addControl();//添加控件
        addCover();//绘制覆盖物
        drawLuShu();
    }
    //绘制地图
    function drawMap(){
        //初始化地图
        map = new BMap.Map("allmap");// 创建Map实例
        this.bmap = map;
        map.addControl(new BMap.MapTypeControl());//添加地图类型控件
        //map.setCurrentCity("北京");//设置地图显示的城市此项是必须设置的
        map.enableScrollWheelZoom(true);//开启鼠标滚轮缩放
        var point = new BMap.Point(transTrackData[0].longitude_value, transTrackData[0].latitude_value);
        map.centerAndZoom(point, 13);//设置中心点坐标和地图级别
    }
    /*添加自定义控件开始*/
    //播放路书/播放下一个路书
    function startLushu(){
        this.defaultAnchor = BMAP_ANCHOR_TOP_LEFT;
        this.defaultOffset = new BMap.Size(10, 10);
    }
    //暂停路书
    function pauseLushu(){
        this.defaultAnchor = BMAP_ANCHOR_TOP_LEFT;
        this.defaultOffset = new BMap.Size(50, 10);
    }
    //停止路书
    function stopLushu(){
        this.defaultAnchor = BMAP_ANCHOR_TOP_LEFT;
        this.defaultOffset = new BMap.Size(115, 10);
    }
    //隐藏描述窗口
    function toggleDescription(){
        this.defaultAnchor = BMAP_ANCHOR_TOP_LEFT;
        this.defaultOffset = new BMap.Size(180, 10);
        this.isHidden = false;
    }
    function addControl(){
        //设置比例尺控件
        map.addControl(new BMap.ScaleControl({anchor: BMAP_ANCHOR_BOTTOM_LEFT}));// 左上角，添加比例尺
        map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_RIGHT}));  //左上角，添加默认缩放平移控件
        //map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_RIGHT, type: BMAP_NAVIGATION_CONTROL_SMALL})); //右上角，仅包含平移和缩放按钮
        /*缩放控件type有四种类型:
        BMAP_NAVIGATION_CONTROL_SMALL：仅包含平移和缩放按钮；BMAP_NAVIGATION_CONTROL_PAN:仅包含平移按钮；BMAP_NAVIGATION_CONTROL_ZOOM：仅包含缩放按钮*/
        //路书播放控制
        startLushu.prototype = new BMap.Control();
        startLushu.prototype.initialize = function(map){
            // 创建一个DOM元素
            var div = document.createElement("div");
            // 添加文字说明
            div.appendChild(document.createTextNode("播放"));
            // 设置样式
            div.style.cursor = "pointer";
            div.style.border = "1px solid gray";
            div.style.backgroundColor = "white";
            div.style.fontSize = "12px";
            div.style.padding = "4px";
            // 绑定事件
            div.onclick = function(e){
                lushu.start();
            
            }
            // 添加DOM元素到地图中
            map.getContainer().appendChild(div);
            // 将DOM元素返回
            return div;
        }
        map.addControl(new startLushu());
        //路书暂停
        pauseLushu.prototype = new BMap.Control();
        pauseLushu.prototype.initialize = function(map){
            // 创建一个DOM元素
            var div = document.createElement("div");
            // 添加文字说明
            div.appendChild(document.createTextNode("暂停播放"));
            // 设置样式
            div.style.cursor = "pointer";
            div.style.border = "1px solid gray";
            div.style.backgroundColor = "white";
            div.style.fontSize = "12px";
            div.style.padding = "4px";
            // 绑定事件
            div.onclick = function(e){
                lushu.pause();
            }
            // 添加DOM元素到地图中
            map.getContainer().appendChild(div);
            // 将DOM元素返回
            return div;
        }
        map.addControl(new pauseLushu());
        //停止所有路书
        stopLushu.prototype = new BMap.Control();
        stopLushu.prototype.initialize = function(map){
            // 创建一个DOM元素
            var div = document.createElement("div");
            // 添加文字说明
            div.appendChild(document.createTextNode("停止播放"));
            // 设置样式
            div.style.cursor = "pointer";
            div.style.border = "1px solid gray";
            div.style.backgroundColor = "white";
            div.style.fontSize = "12px";
            div.style.padding = "4px";
            // 绑定事件
            div.onclick = function(e){
                lushu.stop();
            }
            // 添加DOM元素到地图中
            map.getContainer().appendChild(div);
            // 将DOM元素返回
            return div;
        }
        map.addControl(new stopLushu());
        //描述控制器
        toggleDescription.prototype = new BMap.Control();
        toggleDescription.prototype.initialize = function(map){
            // 创建一个DOM元素
            var div = document.createElement("div");
            // 添加文字说明
            div.appendChild(document.createTextNode("显示/隐藏描述信息"));
            // 设置样式
            div.style.cursor = "pointer";
            div.style.border = "1px solid gray";
            div.style.backgroundColor = "white";
            div.style.fontSize = "12px";
            div.style.padding = "4px";
            div.onclick = function(e){
                if(toggleDescription.isHidden){
                    lushu.showInfoWindow();
                }else{
                    lushu.hideInfoWindow();
                }
                toggleDescription.isHidden = !toggleDescription.isHidden;
            }
            // 添加DOM元素到地图中
            map.getContainer().appendChild(div);
            // 将DOM元素返回
            return div;
        }
        map.addControl(new toggleDescription());
    }
    /*----添加自定义控件结束----*/
    /*----绘制覆盖物开始----*/
    function addCover(){
        var startPoint = new BMap.Point(transTrackData[0].longitude_value,transTrackData[0].latitude_value);
        var myIcon = new BMap.Icon("<?= yii::getAlias('@web'); ?>/jquery-easyui-1.4.3/themes/icons/icon_gcoding_s.png", new BMap.Size(30,36));
        var marker = new BMap.Marker(startPoint,{icon:myIcon});
        map.addOverlay(marker); 
        var dataLength = transTrackData.length;
        var endPoint = new BMap.Point(transTrackData[dataLength - 1].longitude_value,transTrackData[dataLength - 1].latitude_value);
        var myIcon = new BMap.Icon("<?= yii::getAlias('@web'); ?>/jquery-easyui-1.4.3/themes/icons/icon_gcoding_e.png", new BMap.Size(30,36));
        var marker = new BMap.Marker(endPoint,{icon:myIcon});
        map.addOverlay(marker); 
        
    }
    /*----绘制覆盖物结束----*/
    //绘制路书
    var lushu;
    function drawLuShu(){
        var arrPois = [];
        var maxSpeed = 0;
        var landmarkPois = [];
        for(var i in transTrackData){
            var point = new BMap.Point(transTrackData[i].longitude_value,transTrackData[i].latitude_value);
            arrPois.push(point);
            <?php if($trackCatch){ ?>
            landmarkPois.push({
                lng:transTrackData[i].longitude_value,
                lat:transTrackData[i].latitude_value,
                html:'<div style="text-align:left;">&nbsp; &nbsp;车牌号：<?php echo $plate_number; ?>'
                +'<div style="text-align:left;">&nbsp; &nbsp;车架号：'+trackData[i].car_vin
                +'<div style="text-align:left;">采集时间：'+window.parent.formatDateToString(trackData[i].collection_datetime,true)
                +'<br />当前速度：'+trackData[i].speed+'km/h'
                +'<br />&nbsp; &nbsp; &nbsp; SOC：'+trackData[i].battery_package_soc+'%'
                +'<br />&nbsp; &nbsp; &nbsp; 经度：'+trackData[i].longitude_value
                +'<br />&nbsp; &nbsp; &nbsp; 纬度：'+trackData[i].latitude_value,
                pauseTime: <?php echo $trackCatch/$playSpeed*40; ?>
            });
            <?php } ?>
        }
        //画线
        var polyline = new BMap.Polyline(arrPois, {strokeColor:"blue", strokeWeight:6, strokeOpacity:0.5});//创建折线
        map.addOverlay(polyline);//增加折线
        var opts = {
            defaultContent:"车辆运行轨迹",
            autoView: true,//是否开启自动视野调整，如果开启那么路书在运动过程中会根据视野自动调整
            "landmarkPois": landmarkPois,
            icon: new BMap.Icon('http://developer.baidu.com/map/jsdemo/img/car.png', new BMap.Size(52,26),{anchor : new BMap.Size(27, 13)}),
            speed: <?php echo $playSpeed; ?>,
            enableRotation: true//是否设置marker随着道路的走向进行旋转
        };
        lushu = new BMapLib.LuShu(map,arrPois,opts);
        //alert(1);
    }
    //开始执行
    init();
</script>