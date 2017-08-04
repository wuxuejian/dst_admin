<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <style type="text/css">
        body, html,#allmap {
            width: 100%;height: 100%;overflow: hidden;margin:0;font-family:"微软雅黑";
        }
    </style>
    <script type="text/javascript" src="<?php echo $config['bmap_api_addr']; ?>"></script><!-- 百度地图API -->
    <script type="text/javascript" src="<?= yii::getAlias('@web'); ?>/js/jquery-1.7.2.min.js"></script>
</head>
<body>
<div id="allmap"></div>
</body>
<script type="text/javascript">
	var map = new BMap.Map("allmap");
    // 设置您当前所在城市为地图中心
    var myCity = new BMap.LocalCity(); //根据ip定位城市
    myCity.get(function (result) {
        var cityName = result.name;
        map.centerAndZoom(cityName,11); // 设置中心点坐标和地图级别
    });

    map.enableScrollWheelZoom(); //鼠标滚轮
    map.addControl(new BMap.ScaleControl({anchor: BMAP_ANCHOR_BOTTOM_LEFT}));// 左下角，添加比例尺
    map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_RIGHT}));  //左下角，添加默认缩放平移控件
	map.clearOverlays();  // 清空旧标注
	
	// 信息窗口设置
    var opts = {width: 250,height: 160 };
    var markedPoints = [];   		// 将保存所有标注出的点
    var missedPoints = [];   		// 将保存所有未标注出的点

    var listData = <?php echo json_encode($listData); ?>;
    for(var i=0;i<listData.length;i++) {
		var row = listData[i];
		if(row){
			var _lng = row.lng;
			var _lat = row.lat;
			var _address = row.install_site;
			if(_lng && _lat){ //优先查经纬度
				var _point = new BMap.Point(_lng,_lat);
				furtherProcessing(_point,row);
			}else if(_address){
				searchAddress(row);
			}else{ // 注意：没有经纬度和地址则地图上肯定不能定位并标注出来。
				var item = {
					'missedName':row.code_from_compony, 
					'missedReason':'经纬度和地址都没有填写'
				};
				missedPoints.push(item);
			} 
		}
	}
	
	// 让所有点都在视野范围内
	// map.setViewport(markedPoints);
	
	
	//===右上角添加统计信息 begin====================================================================
	var cr = new BMap.CopyrightControl({anchor: BMAP_ANCHOR_TOP_RIGHT,offset:new BMap.Size(10, 10)}); 
	map.addControl(cr); 										// 添加版权控件
	var bs = map.getBounds();   								// 返回地图可视区域
	// 判断是否存在未标出的点
	var markedNum = markedPoints.length;
	var missedNum = listData.length - markedNum; //这里丢失数量不直接用 missedPoints.length,因为 localSearch.search()作异步地址查询可能导致 missedPoints.push()稍慢一点。
	if(missedNum > 0){ 
		var _content = "<div style='font-size:12px;padding:3px 5px;background-color:yellow;border:1px solid gray;'>" +
							"<div style='height:32px;'>" + 
								"<table cellspacing=0 cellpadding=0 border=0 style='float:right;'>" + 
									"<tr><td>标注电桩数：</td><td><span style='color:red;float:right;'>" + markedNum + "</span></td></tr>" + 
									"<tr><td>丢失电桩数：</td><td><span style='color:red;float:right;cursor:pointer;' onclick='toggleMissedPoints()'>" + missedNum + "</span></td></tr>" + 
								"</table>" + 
							"</div>" + 
							"<div id='missedPointsTable' style='display:none;'></div>" +
						"</div>";
	}else{
		var _content = "<div style='font-size:12px;padding:3px;background-color:yellow;border:1px solid gray;'>" +
							"<div>标注电桩数: <span style='color:red;'>" + markedNum + "</span></div>" +
						"</div>";
	}
	cr.addCopyright({id: 1, content: _content, bounds: bs}); 
	//===右上角添加统计信息 end========================================================================
	
	
	
	
	/********************************* 自定义函数 *****************************************************************/
	//查询某地
	function searchAddress(row){
		var localSearch = new BMap.LocalSearch(map);
		localSearch.setSearchCompleteCallback(function (searchResult) {
			var poi = searchResult.getPoi(0); 
			// 注意：某些地址可能因为太详细等原因在地图上也无法定位并标注出来。
			if(poi && poi.length > 0){ 
				var _point = poi.point; 	
				furtherProcessing(_point,row);
			}else{ 
				var item = {
					'missedName':row.code_from_compony, 
					'missedReason':'所填地址在地图上定位不到'
				};
				missedPoints.push(item);
			}
		});
		var _address = row.install_site;
		localSearch.search(_address);
	}
	
	// 进一步处理坐标点
	function furtherProcessing(_point,row){	
		markedPoints.push(_point);
		//map.centerAndZoom(_point, 11); 	// 设置为地图中心点
		var marker = new BMap.Marker(_point);    		// 创建标注
		map.addOverlay(marker);        	// 添加标注
				
		var content = '';
		content += 	'<span style="font-size:14px;"><b>电桩详情：</b></span><br/>' +
					"<br/>电桩编号：" + row.code_from_compony +
					"<br/>连接方式：" + row.connection_type_txt + 
					"<br/>安装方式：" + row.install_type_txt + 
					"<br/>安装地点：" + row.install_site;
		var label = new BMap.Label(row.code_from_compony,{offset:new BMap.Size(20,-10)}); // 创建标签
		marker.setLabel(label); // 设置标签
		addInfoWin(content,marker);		
	}
	
	// 创建信息窗口
	function addInfoWin(content,marker){ 
		marker.addEventListener("click",function(e){
			var p = e.point;
			var curPoint = new BMap.Point(p.lng, p.lat);
			var infoWindow = new BMap.InfoWindow(content,opts);  
			map.openInfoWindow(infoWindow,curPoint); 			 
		});
	}
	
		
	// 切换显示或隐藏丢失客户标注的详情信息
	function toggleMissedPoints(){
		var div = document.getElementById('missedPointsTable');
		if(div.style.display == "none"){
			if(div.innerHTML == ''){
				if(missedPoints.length == missedNum){
					printTable();
				}else{
					div.innerHTML = "<span>正在获取数据...</span>";
					timedCount();
				}
			}
			div.style.display="inline";	
		}else{
			div.style.display = "none";
		}
	}
	
	// 将数据输出成为table表格显示
	function printTable(){
		var _div = document.getElementById('missedPointsTable');
		var str = '';
		str += "<table cellspacing=0 cellpadding=3>";
		str += 	"<tr><th>No.</th><th>丢失电桩</th><th>丢失原因</th></tr>";
		if(missedPoints.length > 0){
			for(var i=0;i<missedPoints.length;i++){
				if(i < 10){
					var item = missedPoints[i];
					str += "<tr>"; 
					str += 		"<td>" + (i+1) + "</td>"; 
					str += 		"<td>" + item.missedName + "</td>"; 
					str += 		"<td>" + item.missedReason + "</td>"; 
					str += "</tr>"; 
				}else{
					str += "<tr><td colspan=3 onclick='openNewWin()' style='color:blue;text-decoration:underline;'>查看全部</td></tr>";
					break;
				}
			}
		}
		str += "</table>";	
		_div.innerHTML = str;
	}
	
	function openNewWin(){ 
		var newWin = window.open("missingPointsOnBaiDuMap.html","missingPointsOnBaiDuMap", "height=500, width=800,toolbar=no,enubar=no");
		var str = '';
		str += "<table cellspacing=0 cellpadding=3 border=1 width='80%' align='center'>";
		str += 	"<tr><th colspan=3>百度地图丢失标注统计--电桩</th></tr>";
		str += 	"<tr style='background:#ccc;'><th>No.</th><th>丢失电桩</th><th>丢失原因</th></tr>";
		if(missedPoints.length > 0){
			for(var i=0;i<missedPoints.length;i++){
				var item = missedPoints[i];
				str += "<tr>"; 
				str += 		"<td>" + (i+1) + "</td>"; 
				str += 		"<td>" + item.missedName + "</td>"; 
				str += 		"<td>" + item.missedReason + "</td>"; 
				str += "</tr>"; 
			}
		}
		str += "</table>";		
		newWin.document.write(str);
	}
	
	// 计时器
	var t;
	var i = 0;
	function timedCount(){
		if(missedPoints.length == missedNum){
			printTable();
			clearTimeout(t); //清除计时器 
		}else{
			t = setTimeout("timedCount()",1000); //建立计时器
			if(++i == 30){
				clearTimeout(t); //清除计时器 
				var _div = document.getElementById('missedPointsTable');
				_div.innerHTML = "<span>获取失败，重新打开试试！</span>";
			};
		}	
	}
	
</script>

</html>