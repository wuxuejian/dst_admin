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
	<div style="height:30px;">
		<form id="form1">
			<div style="margin-top:8px;  font-size:12px">
				<input type="hidden" name=is_ajax  value="1"  />&nbsp;&nbsp;
				 电站名称&nbsp;
	            <input type="text"  name="cs_name" style="border-radius:3px;border:1px solid #95b8e7; height:20px;" />&nbsp;&nbsp;
	         	  电站类型&nbsp;
	            <select name="cs_type" style="width:20%;height:25px;border-radius:3px;border:1px solid #95b8e7;" onchange="tj()">
	            	<option value="">不限</option>
	                <option value="SELF_OPERATION">自营</option>
	                <option value="JOINT_OPERATION">联营</option>
	                <option value="COOPERATION">合作</option>
	                <option value="CUSTOMER_SELF_USE">客户自用</option>
	            </select>&nbsp;&nbsp;
	         	   电站状态&nbsp;
	            <select name="cs_status" style="width:20%;height:25px;border-radius:3px;border:1px solid #95b8e7;" onchange="tj()">
	               <option value="">不限</option>
	               <option value="NORMAL">正常</option>
	               <option value="ABULIDING">维护</option>
	               <option value="STOPPED">停用</option>
	            </select>&nbsp;&nbsp;
	              <input type="button"   style="height:25px;width:50px;border-radius:3px;border:1px solid #ccc;cursor:pointer;background:#FFFFFF"   onclick="tj()"   value="查询"/>
			</div>
		</form>
	</div>
	<div  style=" margin-top:5px;border:1px solid #95b8e7;" ></div>
    <div id="allmap"></div>
</body>

<script>
$(function(){
	tj();
})


function tj()
{
	var form = $('#form1');
	var data = form.serialize();
	$.ajax({
		type: 'get',
		url: "<?php echo yii::$app->urlManager->createUrl(['charge/charge-station/show-on-map']); ?>",
		data: data,
		dataType: 'json',
		success: function(data){
			if(data){

			var listData = data;

			/***********   MAP显示     **********/
				var map = new BMap.Map("allmap");
			    //map.centerAndZoom(new BMap.Point(116.404, 39.915), 5);
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
			    var opts = {width: 250,height: 150 };
			    var markedPoints = [];   		// 将保存所有标注出的点
			    var missedPoints = [];   		// 将保存所有未标注出的点

	
			    for(var i=0;i<listData.length;i++) {
			        var row = listData[i];
			        if(row){
			            var _lng = row.cs_lng;
			            var _lat = row.cs_lat;
			            var _address = row.cs_address;
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
			    //map.setViewport(markedPoints);


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
			            "<tr><td>标注电站数：</td><td><span style='color:red;float:right;'>" + markedNum + "</span></td></tr>" +
			            "<tr><td>丢失电站数：</td><td><span style='color:red;float:right;cursor:pointer;' onclick='toggleMissedPoints()'>" + missedNum + "</span></td></tr>" +
			            "</table>" +
			            "</div>" +
			            "<div id='missedPointsTable' style='display:none;'></div>" +
			            "</div>";
			    }else{
			        var _content = "<div style='font-size:12px;padding:3px;background-color:yellow;border:1px solid gray;'>" +
			            "<div>标注电站数: <span style='color:red;'>" + markedNum + "</span></div>" +
			            "</div>";
			    }
			    cr.addCopyright({id: 1, content: _content, bounds: bs});
			    //===右上角添加统计信息 end========================================================================




			    /********************************* 自定义函数 *********************************************/
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
			                    'missedName':row.cs_code,
			                    'missedReason':'所填地址在地图上定位不到'
			                };
			                missedPoints.push(item);
			            }
			        });
			        var _address = row.cs_address;
			        localSearch.search(_address);
			    }

			    // 进一步处理坐标点
			    function furtherProcessing(_point,row){
			        markedPoints.push(_point);
			        //map.centerAndZoom(_point, 11); 	// 设置为地图中心点
			        
			        
					var img ='';
			        switch(row.cs_type){
			        	//自营
			        	case 'SELF_OPERATION':
			            	img = './images/charge-station/icon1.png';
			            	break;
			            //联营
			        	case 'JOINT_OPERATION':
			            	img = './images/charge-station/icon2.png';
			            	break;
			            //合作
			        	case 'COOPERATION':
			            	img = './images/charge-station/icon3.png';
			            	break;
			            //客户自用
			        	case 'CUSTOMER_SELF_USE':
			            	img = './images/charge-station/icon4.png';
			            	break;
			        }
			      //设置点的图标
			        var myIcon = new BMap.Icon(img,  
			            new BMap.Size(48, 70), {  
			                offset: new BMap.Size(10, 25),  
			                //imageOffset: new BMap.Size(0, 0 - index * 25)  
			        });     
			        
			        
			        var marker = new BMap.Marker(_point,{icon:myIcon});  // 创建标注
			        map.addOverlay(marker);        	// 添加标注

			        var content = '';
			        content += 	'<span style="font-size:14px;"><b>' + row.cs_name + '</b></span><br/>' +
			        "<br/>电站编号：" + row.cs_code +
			        "<br/>电站位置：" + row.cs_address +
			        "<br/>电桩数量：" + row.charger_num;
			        //var label = new BMap.Label(row.cs_name,{offset:new BMap.Size(20,-10)}); // 创建标签
			        //marker.setLabel(label); // 设置标签
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
			        str += 	"<tr><th>No.</th><th>丢失电站</th><th>丢失原因</th></tr>";
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
			        str += 	"<tr><th colspan=3>百度地图丢失标注统计--充电站</th></tr>";
			        str += 	"<tr style='background:#ccc;'><th>No.</th><th>丢失电站</th><th>丢失原因</th></tr>";
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

			    /***********   MAP显示     **********/
			}
		}
	});
}
</script>
<!--    2016/9/21 改为AJAX请求获取数据
<script type="text/javascript">
    var map = new BMap.Map("allmap");
    //map.centerAndZoom(new BMap.Point(116.404, 39.915), 5);
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
    var opts = {width: 250,height: 150 };
    var markedPoints = [];   		// 将保存所有标注出的点
    var missedPoints = [];   		// 将保存所有未标注出的点

    var listData = <?php //echo json_encode($listData); ?>;
    for(var i=0;i<listData.length;i++) {
        var row = listData[i];
        if(row){
            var _lng = row.cs_lng;
            var _lat = row.cs_lat;
            var _address = row.cs_address;
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
    //map.setViewport(markedPoints);


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
            "<tr><td>标注电站数：</td><td><span style='color:red;float:right;'>" + markedNum + "</span></td></tr>" +
            "<tr><td>丢失电站数：</td><td><span style='color:red;float:right;cursor:pointer;' onclick='toggleMissedPoints()'>" + missedNum + "</span></td></tr>" +
            "</table>" +
            "</div>" +
            "<div id='missedPointsTable' style='display:none;'></div>" +
            "</div>";
    }else{
        var _content = "<div style='font-size:12px;padding:3px;background-color:yellow;border:1px solid gray;'>" +
            "<div>标注电站数: <span style='color:red;'>" + markedNum + "</span></div>" +
            "</div>";
    }
    cr.addCopyright({id: 1, content: _content, bounds: bs});
    //===右上角添加统计信息 end========================================================================




    /********************************* 自定义函数 *********************************************/
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
                    'missedName':row.cs_code,
                    'missedReason':'所填地址在地图上定位不到'
                };
                missedPoints.push(item);
            }
        });
        var _address = row.cs_address;
        localSearch.search(_address);
    }

    // 进一步处理坐标点
    function furtherProcessing(_point,row){
        markedPoints.push(_point);
        //map.centerAndZoom(_point, 11); 	// 设置为地图中心点
        
        
		var img ='';
        switch(row.cs_type){
        	//自营
        	case 'SELF_OPERATION':
            	img = './images/charge-station/icon1.png';
            	break;
            //联营
        	case 'JOINT_OPERATION':
            	img = './images/charge-station/icon2.png';
            	break;
            //合作
        	case 'COOPERATION':
            	img = './images/charge-station/icon3.png';
            	break;
            //客户自用
        	case 'CUSTOMER_SELF_USE':
            	img = './images/charge-station/icon4.png';
            	break;
        }
      //设置点的图标
        var myIcon = new BMap.Icon(img,  
            new BMap.Size(48, 70), {  
                offset: new BMap.Size(10, 25),  
                //imageOffset: new BMap.Size(0, 0 - index * 25)  
        });     
        
        
        var marker = new BMap.Marker(_point,{icon:myIcon});  // 创建标注
        map.addOverlay(marker);        	// 添加标注

        var content = '';
        content += 	'<span style="font-size:14px;"><b>' + row.cs_name + '</b></span><br/>' +
        "<br/>电站编号：" + row.cs_code +
        "<br/>电站位置：" + row.cs_address +
        "<br/>电桩数量：" + row.charger_num;
        //var label = new BMap.Label(row.cs_name,{offset:new BMap.Size(20,-10)}); // 创建标签
        //marker.setLabel(label); // 设置标签
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
        str += 	"<tr><th>No.</th><th>丢失电站</th><th>丢失原因</th></tr>";
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
        str += 	"<tr><th colspan=3>百度地图丢失标注统计--充电站</th></tr>";
        str += 	"<tr style='background:#ccc;'><th>No.</th><th>丢失电站</th><th>丢失原因</th></tr>";
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
</script>-->

</html>
