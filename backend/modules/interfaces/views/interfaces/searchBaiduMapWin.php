<div style="width:730px;margin:0 auto;padding:5px 0px;">
	<table cellspacing=0 cellpadding=2 width="95%" align="center" border=0>
		<tr>
			<td colspan=3>请输入:
				<input id="search_"  type="text" value="" style="width:300px"  />
				<a href="javascript:searchMapByName()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查找</a>
				<span style="margin-left:10px;padding:4px;font-size:12px;color:red;background-color:yellow;">提示：点选时请按Ctrl+鼠标左键</span>
			</td>
		</tr>
		<tr>
			<td>经纬度:
				<input id="lngLat_"  type="text" style="width:180px;color:red;" />
			</td>
			<td colspan=2>地址：
				<input id="address_"  type="text" style="width:320px;color:red;" />
			</td>
			<td>
				<a href="javascript:confirmResult()" class="easyui-linkbutton" data-options="iconCls:'icon-ok'">确定</a>
			</td>
		</tr>
	</table>
	<div id="mapContainer" 
		style="position: absolute;
			margin-top:15px; 
			width: 740px; 
			height: 460px; 
			border: 1px solid gray;
			overflow:hidden;">
	</div>
</div>
<script type="text/javascript">
	var pageIn = <?php echo json_encode($pageIn); ?>;
	// 获取各种页面的详情表单中的经纬度或地址
	var lng = '', lat = '',address = '';
	switch(pageIn) {
		case 'chargeSpots':	// 在充电桩新增/修改窗口
			var form = $('#ChargeSpotsIndex_addEditWin_form');	
			lng = $("[name='lng']",form)[0].value;
			lat = $("[name='lat']",form)[0].value;
			address = $("[name='install_site']",form)[0].value;
			break;
        case 'chargeStation': // 在充电站新增/修改窗口
            var form = $('#chargeChargeStationIndex_addEditWin');
            lng = $("[name='cs_lng']",form)[0].value;
            lat = $("[name='cs_lat']",form)[0].value;
            address = $("[name='cs_address']",form)[0].value;
            break;
		case 'personal_add':   
			break; // 新增时不处理，都为默认的空值
		case 'personal_edit':   // 在客户管理-个人客户修改窗口
			var form = $('#easyui-form-customer-personal-edit');		
			lng = $("[name='personal_lng']",form)[0].value;
			lat = $("[name='personal_lat']",form)[0].value;
			address = $("[name='id_address']",form)[0].value;
			break;
		case 'company_add':   
			break;
		case 'company_edit':   // 在客户管理-企业客户修改窗口
			var form = $('#easyui-form-customer-company-edit');	
			lng = $("[name='company_lng']",form)[0].value;
			lat = $("[name='company_lat']",form)[0].value;
			address = $("[name='company_addr']",form)[0].value;
			break;
		case 'vipShareApproveWindow':	// 在会员分享-审核电桩窗口里
			var form = $('#vipShareIndex_approveWindow_moreInfoForm');	
			lng = $("[name='lng']",form)[0].value;
			lat = $("[name='lat']",form)[0].value;
			address = $("[name='install_site']",form)[0].value;
			break;
		default:
			$.messager.show({
				title: '提示',
				msg: '无法确定当前所在页面（未知的参数pageIn）！'
			});
	}
	
    var map = new BMap.Map("mapContainer");
    //map.addControl(new BMap.ScaleControl({anchor: BMAP_ANCHOR_BOTTOM_LEFT}));// 左下角，添加比例尺
    map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_RIGHT}));  //左下角，添加默认缩放平移控件
	
	// 地图初始化（修改时以旧数据定位；新增时以你当前所在城市）
	if((lng && lat) || address) {
		if(lng && lat) {
			var _point = new BMap.Point(lng,lat); 
			deepProcessing(_point,15);
		}else{
			$('#search_').val(address);
			searchMapByName(); 		
		}
	}else{ 
		var myCity = new BMap.LocalCity(); //根据ip定位城市
		myCity.get(function (result) { 
			var cityName = result.name;
			$('#search_').val(cityName);
			searchMapByName(); 
		});
	}
	
	//以ctrl+click点选某地
	map.addEventListener("click",function(e) {
		if(e.ctrlKey) { 
			var _point = e.point;	
			deepProcessing(_point,0); // 点选时停留在当前级别不缩放  						
		}
	});
	
	
	//查询某地
	function searchMapByName() {
		var searchValue = $("#search_").val();
		if(searchValue) {
			var localSearch = new BMap.LocalSearch(map);
			localSearch.setSearchCompleteCallback(function (searchResult) {
				var poi = searchResult.getPoi(0);
				var _point = poi.point;
				deepProcessing(_point,15);				
			});
			localSearch.search(searchValue);
		}
	} 
		
	// 进一步处理：设置中心点、添加标注，提示窗等
	function deepProcessing(point,level) {	
		map.centerAndZoom(point,level); 		// 设置中心点坐标和地图级别			
		var marker = new BMap.Marker(point);  	// 创建标注
		map.clearOverlays();		  			// 清空旧标注
		map.addOverlay(marker);		  			// 添加标注
		
		var lngLat = point.lng + ", " + point.lat;
		$("#lngLat_").val(lngLat);		
		var geoc = new BMap.Geocoder(); 
		geoc.getLocation(point, function(obj) { // 由经纬度坐标点获取文本地址
			var address = obj.address;
			$("#address_").val(address);	
			// 为标注添加单击事件
			var info = "<p style='font-size:14px;'>当前地理位置详情：<br/>" + 
							"<br/>经度：" + point.lng + 
							"<br/>纬度：" + point.lat + 
							'<br/>地址：' + address + 
						"</p>";
			var infoWindow = new BMap.InfoWindow(info);
			marker.addEventListener("click", function () { 
				this.openInfoWindow(infoWindow); 
			});
		}); 	
	}
	
	//点击确定按钮，给各详情表单作相关赋值
	function confirmResult(){
		var _lngLat = $("#lngLat_").val();
		var _address = $("#address_").val();
		var arr = _lngLat.split(', ');
		switch(pageIn) {
			case 'chargeSpots': 
				var data = {'lng':arr[0],'lat':arr[1],'install_site':_address};
				$('#ChargeSpotsIndex_addEditWin_form').form('load',data);
				break;
			case 'chargeStation':
				var data = {'cs_lng':arr[0],'cs_lat':arr[1],'cs_address':_address};
				$('#chargeChargeStationIndex_addEditWin').form('load',data);
				break;
			case 'personal_add':
				var data = {'personal_lng':arr[0],'personal_lat':arr[1],'id_address':_address};
				$('#easyui-form-customer-personal-add').form('load',data);
				break;
			case 'personal_edit':
				var data = {'personal_lng':arr[0],'personal_lat':arr[1],'id_address':_address};
				$('#easyui-form-customer-personal-edit').form('load',data);
				break;
			case 'company_add':
				var data = {'company_lng':arr[0],'company_lat':arr[1],'company_addr':_address};
				$('#easyui-form-customer-company-add').form('load',data);
				break;
			case 'company_edit':
				var data = {'company_lng':arr[0],'company_lat':arr[1],'company_addr':_address};
				$('#easyui-form-customer-company-edit').form('load',data);
				break;
			case 'vipShareApproveWindow':
				var data = {'lng':arr[0],'lat':arr[1],'install_site':_address};
				$('#vipShareIndex_approveWindow_moreInfoForm').form('load',data);
				break;
			default:
				$.messager.show({
					title:'错误',
					msg:'赋值失败：无法确定当前所在页面！'
				});
				return false;
		}
		$('#baiDuMapWin').dialog('clear').dialog('close');
	}
</script>