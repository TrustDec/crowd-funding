function search_api(api_address, city) {
	var myValue = '';
	if(city){
		myValue = city;
	}
	if(api_address){
		myValue += api_address;
	}

	setPlace(myValue);

}

function setPlace(myValue) {
	var map = new BMap.Map("container");
	map.centerAndZoom(new BMap.Point(0, 0), 11);

	var top_left_control = new BMap.ScaleControl({
		anchor : BMAP_ANCHOR_TOP_LEFT
	});// 左上角，添加比例尺
	var top_left_navigation = new BMap.NavigationControl(); //左上角，添加默认缩放平移控件
	map.clearOverlays(); //清除地图上所有覆盖物

	function myFun() {
		try {
			var pp = local.getResults().getPoi(0).point; //获取第一个智能搜索的结果
			map.centerAndZoom(pp, 18);
			map.addOverlay(new BMap.Marker(pp)); //添加标注
			map.addControl(top_left_control);
			map.addControl(top_left_navigation);
			$("input[name='xpoint']").attr('value', pp.lng);
			$("input[name='ypoint']").attr('value', pp.lat);
		} catch (ex) {
			alert("搜索不到您输入的地址");
		}
	}

	var local = new BMap.LocalSearch(map, { //智能搜索
		onSearchComplete : myFun
	});
	local.search(myValue);

}
function draw_map(xpoint, ypoint) {
	var map = new BMap.Map("container");
	var opts = {
		type : BMAP_NAVIGATION_CONTROL_ZOOM
	}
	map.addControl(new BMap.NavigationControl());
	// map.centerAndZoom(new BMap.Point(116.404, 39.915), 11);  
	// 创建地理编码服务实例  
	var point = new BMap.Point(xpoint, ypoint);

	// 将结果显示在地图上，并调整地图视野  
	map.centerAndZoom(point, 16);
	map.addOverlay(new BMap.Marker(point));
}

/*弹出窗口，并初始化地图*/
function editMap(x, y) {
	$("#container_front").show();
	creat_map(x, y);
}

/*对弹出窗口初始化地图*/
function creat_map(xpoint, ypoint) {
	var map = new BMap.Map("container_m");
	var point = new BMap.Point(xpoint, ypoint);
	map.centerAndZoom(point, 17);
	var myIcon = new BMap.Icon(red_point, new BMap.Size(28, 38));
	var marker = new BMap.Marker(point, {
		icon : myIcon
	}); // 创建标注  
	var name = $("input[name='name']").attr('value');
	var label = create_lable(name);
	marker.setLabel(label);
	map.addOverlay(marker); // 将标注添加到地图中  
	// map.enableScrollWheelZoom();  // 开启鼠标滚轮缩放         
	//新坐标
	var myIcon = new BMap.Icon(blue_point, new BMap.Size(21, 27));
	var marker_change = new BMap.Marker(point, {
		icon : myIcon
	});
	map.addOverlay(marker_change);
	marker_change.addEventListener("dragend", function(e) {
		if (confirm('是否更新标记坐标?')) {
			$("input[name='xpoint']").attr('value', e.point.lng);
			$("input[name='ypoint']").attr('value', e.point.lat);
			$("#container_front").hide();
			new_map(e.point.lng, e.point.lat, xpoint, ypoint);
		}
	})
	marker_change.enableDragging();

	map.addControl(new BMap.NavigationControl());
}

/*生成2个标记*/
function new_map(x, y, old_x, old_y) {
	var map = new BMap.Map("container");
	var xpoint = old_x;
	var ypoint = old_y;
	var point = new BMap.Point(xpoint, ypoint);
	map.centerAndZoom(point, 17);
	var point_new = new BMap.Point(x, y);
	var myIcon_curron = new BMap.Icon(blue_point, new BMap.Size(28, 38));
	var marker = new BMap.Marker(point_new, {
		icon : myIcon_curron
	});
	var label = create_lable("修正坐标");
	marker.setLabel(label);
	map.addOverlay(marker); // 将标注添加到地图中  
	var opts = {
		type : BMAP_NAVIGATION_CONTROL_SMALL
	}
	map.addControl(new BMap.NavigationControl(opts));
	map.enableScrollWheelZoom(); // 开启鼠标滚轮缩放 
	var myIcon = new BMap.Icon(red_point, new BMap.Size(28, 38));
	var marker_old = new BMap.Marker(point, {
		icon : myIcon
	});
	var label_old = create_lable("原始坐标");
	marker_old.setLabel(label_old);
	map.addOverlay(marker_old);
	marker.addEventListener('mouseover', function() {
		map.panTo(new BMap.Point(x, y));
	});
	window.setTimeout(function() {
		map.panTo(point_new);
	}, 2000);
}
function create_lable(name) {
	var label = new BMap.Label(name, {
		"offset" : new BMap.Size(-8, -20)
	});
	label.setStyle({
		borderColor : "#808080",
		color : "#333",
		cursor : "pointer"
	});
	return label;
}
