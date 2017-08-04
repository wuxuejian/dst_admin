<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <style type="text/css">
    body, html,#allmap {width: 100%;height: 100%;overflow: hidden;margin:0;font-family:"微软雅黑";}
    </style>
    <script type="text/javascript" src="<?php echo $config['bmap_api_addr']; ?>"></script>
    <script type="text/javascript" src="<?= yii::getAlias('@web'); ?>/js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="http://api.map.baidu.com/library/TextIconOverlay/1.2/src/TextIconOverlay_min.js"></script>
    <script type="text/javascript" src="http://api.map.baidu.com/library/MarkerClusterer/1.2/src/MarkerClusterer_min.js"></script>
</head>
<body>
    <div id="allmap"></div>
</body>
</html>
<script>
    // 百度地图API功能
    var map = new BMap.Map("allmap");
 	// 设置您当前所在城市为地图中心
    var myCity = new BMap.LocalCity(); //根据ip定位城市
    myCity.get(function (result) {
        //var cityName = result.name;
        //map.centerAndZoom(cityName,11); // 设置中心点坐标和地图级别
        map.centerAndZoom(new BMap.Point(result.center.lng, result.center.lat),11); // 设置中心点坐标和地图级别
        map.enableScrollWheelZoom();
      	//设置比例尺控件
        map.addControl(new BMap.ScaleControl({anchor: BMAP_ANCHOR_BOTTOM_LEFT}));// 左上角，添加比例尺
        map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_RIGHT}));  //左上角，添加默认缩放平移控件
        
        transPosition(0);
    });
    //map.centerAndZoom(new BMap.Point(116.404, 39.915), 5);
    //map.enableScrollWheelZoom();
    //设置比例尺控件
    //map.addControl(new BMap.ScaleControl({anchor: BMAP_ANCHOR_BOTTOM_LEFT}));// 左上角，添加比例尺
    //map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_RIGHT}));  //左上角，添加默认缩放平移控件
    var trackData = <?= json_encode($realTimeData); ?>;
    var transTrackData = [];
    var markers = [];
    var data_info = [];
    //地图信息窗口
    var opts = {
        width : 300,     // 信息窗口宽度
        height: 120,     // 信息窗口高度
        title : "" , // 信息窗口标题
        enableMessage:true//设置允许信息窗发送短息
    };
    //点转换
    function transPosition(startIndex){
        if(startIndex >= trackData.length-1){
            //转换完成
            //if(trackData.length != transTrackData.length){
                //window.parent.$.messager.alert('错误','调用api转换GPS数据失败！','error');
                //return false;
            //}
            transComplete();
            return true;
        }
        var str = '';
        var transIndex = startIndex;
        for(var i = startIndex; i <= startIndex + 99;i++){
            if(trackData[i]){
                transIndex = i;
				if(trackData[i].longitude_value=='0.0000000' || trackData[i].latitude_value=='0.0000000'){	//过滤经纬度为0的
					trackData[i].longitude_value='118.0000000';
					trackData[i].latitude_value='11.0000000';
					//continue;
				}
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
                    transPosition(transIndex + 1);
                }else {
					transPosition(transIndex + 1);
				}
            } 
        });  
    }
    
    //转换完成回调
    function transComplete(){
        for (var i in trackData) {
			if(transTrackData[i] == undefined){
				continue;
			}
            var pt = new BMap.Point(transTrackData[i].longitude_value,transTrackData[i].latitude_value);
            markers.push(new BMap.Marker(pt));
            var str = '<div style="text-align:left;">'
                + '&nbsp; &nbsp;车牌号：'+trackData[i].plate_number
                + '<br />&nbsp; &nbsp;车架号：'+trackData[i].car_vin
                + "<br />采集时间："+window.parent.formatDateToString(trackData[i].collection_datetime,true)
                + "<br />&nbsp; &nbsp; &nbsp;&nbsp;车速："+trackData[i].speed+"km/h"
                + "<br />&nbsp; &nbsp; &nbsp;&nbsp;SOC："+trackData[i].battery_package_soc+"%";
                + '</div>';
           data_info.push([
                transTrackData[i].longitude_value,
                transTrackData[i].latitude_value,
                str
            ]);
        }
        //最简单的用法，生成一个marker数组，然后调用markerClusterer类即可。
        var markerClusterer = new BMapLib.MarkerClusterer(map, {markers:markers});
        for(var i in markers){
            var content = data_info[i][2];
            //map.addOverlay(markers[i]);//将标注添加到地图中
            addClickHandler(content,markers[i]);
        }
    }
    function addClickHandler(content,marker){
        marker.addEventListener("click",function(e){
            openInfo(content,e)}
        );
    }
    function openInfo(content,e){
        var p = e.target;
        var point = new BMap.Point(p.getPosition().lng, p.getPosition().lat);
        var infoWindow = new BMap.InfoWindow(content,opts);  // 创建信息窗口对象 
        map.openInfoWindow(infoWindow,point); //开启信息窗口
    }
</script>