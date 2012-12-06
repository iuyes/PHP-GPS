<?php
	function getallorders(){
	$con=mysql_connect("localhost","root","");
	if(!$con)
	{
		die('Could not connect:'.mysql_error());
	}
	else 
	{
		mysql_select_db("gps",$con);
		mysql_query("SET NAMES 'utf8'"); 
		mysql_query("SET CHARACTER_SET_CLIENT=utf8"); 
		mysql_query("SET CHARACTER_SET_RESULTS=utf8"); 
		//写入数据库
		$sql_addtester="SELECT morID FROM myorders";
		$result = mysql_query($sql_addtester,$con);

		while($row = mysql_fetch_array($result))
		{
			$i = $row['morID'];
			echo '<option value="'.$i.'">'.$i.'号订单</option>';
		}
		mysql_close($con);
	}
	}
?>
<!DOCTYPE HTML>
<html lang="zh-CN">
<head>
<meta charset="UTF-8" />
<link rel="profile" href="http://gmpg.org/xfn/11" />
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<title>订单配送系统</title>
<link rel="stylesheet" type="text/css" href="style.css"/>
<link rel="icon" href="images/favicon.ico" type="image/x-icon" />
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&language=zh-CN"></script> 
<script type="text/javascript" src="js/jquery.js"></script>
</head>
<body>
<div id="container">

<div id="myorder">
<h1 id="ordername">订单配送系统</h1>
<hr>

<div class="orlabel">请您选择相应订单</div>
	<select name="jc_orders" id="allorders" onchange="getorderinfo()">
		<option selected value="选择相应订单号">选择相应订单号</option>
		<!--调用php函数显示订单数量-->
		<?php getallorders();?>
	</select>
<hr>

<div class="orlabel">配送地址</div>
<textarea id="ordes"></textarea>
<hr>
<input type="button" id="dw" value="定位配货地址"/>
<input type="hidden" id="ycwd" value="">
<input type="hidden" id="ycjd" value="">
<hr>
<input type="button" id="callz" value="计算配送路线"/>
<hr>
<input type="button" id="back" class="orlabel" value="返回管理页面"/>

</div>
<!--放置地图-->
<div id="map_canvas"></div>

</div>  
</body>
<script type="text/javascript">

//显示武汉地图
	var latlng = new google.maps.LatLng(30.593087,114.30535699999996);
	var myOptions = {
	  zoom: 14,
	  center: latlng,
	  scaleControl: true, 
	  mapTypeControl: true,         
	  mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},    
	  mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	var map = new google.maps.Map(document.getElementById("map_canvas"),
		myOptions); 
	//标记三个仓库位置
	var ck1=new google.maps.LatLng(30.5980924678046,114.27085306323238);
	var ck2=new google.maps.LatLng(30.50429400544093,114.38321616328426);
	var ck3=new google.maps.LatLng(30.595811410293724,114.39498569644161);
	var marimg='images/ck.png';
	var marck1=new google.maps.Marker({
		position: ck1,   
	  map: map,
	  icon: marimg,  
	  draggable: false 
	});
	var marck2=new google.maps.Marker({
	  position: ck2,   
	  map: map,
	  icon: marimg, 
	  draggable: false 
	});
	var marck3=new google.maps.Marker({
	  position: ck3,   
	  map: map,
	  icon: marimg,  
	  draggable: false 
	});
	//测试infowindow
	var message1 = new google.maps.InfoWindow( {  
		content : "一号仓库"
	}); 
	google.maps.event.addListener(marck1, 'mouseover', function() {
		message1.open(map, marck1);
	});
	google.maps.event.addListener(marck1, 'mouseout', function() {
		message1.close();
	});
	
	var message2 = new google.maps.InfoWindow( {  
		content : "二号仓库" 
	}); 
	google.maps.event.addListener(marck2, 'mouseover', function() {
		message2.open(map, marck2);
	});
	google.maps.event.addListener(marck2, 'mouseout', function() {
		message2.close();
	});
	
	var message3 = new google.maps.InfoWindow( {  
		content : "三号仓库" 
	}); 
	google.maps.event.addListener(marck3, 'mouseover', function() {
		message3.open(map, marck3);
	});
	google.maps.event.addListener(marck3, 'mouseout', function() {
		message3.close();
	});
	//菜单改变的响应函数
	function getorderinfo(){
		var myorid=$("#allorders").val();
		$.post(
			'orderinfo.php',
			{
			myid:myorid
			},
			function(data){
				var myjson='';  
				eval("myjson=" + data + ";"); 
				
				var textcon=myjson.dz;
				$("#ordes").val(textcon);
					
			}
		);
	}
	
	//快速定位函数
	
	function searchLocation(){
		var add = document.getElementById("ordes").value;
		var geocoder = new google.maps.Geocoder();
		geocoder.geocode({ 'address': add},geo);
	}
	//用于控制图形显示的变量
	var lineArray = [];
	function geo(results, status){
		if (status == google.maps.GeocoderStatus.OK) 
		{
			//移动地图
			map.setCenter(results[0].geometry.location);
			//$("#ycdz").val(results[0].geometry.location.lat()+','+results[0].geometry.location.lng());
			$("#ycwd").val(results[0].geometry.location.lat());
			$("#ycjd").val(results[0].geometry.location.lng());
			//清除之前的定位标记
			if (lineArray) {
				for (i in lineArray) {
					lineArray[i].setMap(null);
				}
			}
			//放置定位标志
			var temark=new google.maps.Marker({
			  position: results[0].geometry.location,   
			  map: map,  
			  draggable: false 
			});
			//压入整个图层显示控制
			lineArray.push(temark);
		} 
		else
		{
			alert("定位不成功，错误原因：" + status);
		}
	}
	
	//计算两个经纬度之间的距离函数
	Number.prototype.toRadians = function() {
		return this * Math.PI / 180;
	}
	
	function distance(latitude1, longitude1, latitude2, longitude2) {
		// R is the radius of the earth in kilometers
		var R = 6371;

		var deltaLatitude = (latitude2-latitude1).toRadians();
		var deltaLongitude = (longitude2-longitude1).toRadians();
		latitude1 = latitude1.toRadians(), latitude2 = latitude2.toRadians();

		var a = Math.sin(deltaLatitude/2) *
			  Math.sin(deltaLatitude/2) +
			  Math.cos(latitude1) *
			  Math.cos(latitude2) *
			  Math.sin(deltaLongitude/2) *
			  Math.sin(deltaLongitude/2);

		var c = 2 * Math.atan2(Math.sqrt(a),
							 Math.sqrt(1-a));
		var d = R * c;
		return d;
	}
	
	//计算两点之间路线的函数
	
	function p2p(p1,p2){
		var mode = google.maps.DirectionsTravelMode.DRIVING; //谷歌地图路线指引的模式
		var directionsDisplay = new google.maps.DirectionsRenderer();   //地图路线显示对象
		var directionsService = new google.maps.DirectionsService();    //地图路线服务对象
		var directionsVisible = false;  //是否显示路线
		directionsDisplay.setMap(null);
		directionsDisplay.setMap(map);
		var request = {
			origin: p1, //起点
			destination:p2, //终点
			travelMode: mode,
			optimizeWaypoints: true,
			avoidHighways: false,
			avoidTolls: false
		};
		directionsService.route(
		request,
		function(response, status) {
			if (status == google.maps.DirectionsStatus.OK) {
				directionsDisplay.setDirections(response);
				//定时清除线路
                setTimeout(function() { directionsDisplay.setMap(null) }, 50000);
			}
		});
		directionsVisible = true;
	}
	
//返回按钮的相应函数
$(document).ready(function(){
    $('#back').click(function(){
      window.location.href='./index.php';
    });
});
//定位配货地址
$(document).ready(function(){
    $('#dw').click(function(){
		searchLocation();
    });
});
//计算最短路径
$(document).ready(function(){
    $('#callz').click(function(){
		//searchLocation();
		var d1=distance(30.5980924678046,114.27085306323238,Number($("#ycwd").val()),Number($("#ycjd").val()));
		var d2=distance(30.50429400544093,114.38321616328426,Number($("#ycwd").val()),Number($("#ycjd").val()));
		var d3=distance(30.595811410293724,114.39498569644161,Number($("#ycwd").val()),Number($("#ycjd").val()));
		//比较三个仓库距离
		if(d1<=d2&&d1<=d3)
		{
			alert("选择一号仓库");
			var zd=new google.maps.LatLng(Number($("#ycwd").val()),Number($("#ycjd").val()));
			p2p(ck1,zd);
		}
		else if(d2<=d3&&d2<=d1)
		{
			alert("选择二号仓库");
			var zd=new google.maps.LatLng(Number($("#ycwd").val()),Number($("#ycjd").val()));
			p2p(ck2,zd);
		}
		else
		{
			alert("选择三号仓库");
			var zd=new google.maps.LatLng(Number($("#ycwd").val()),Number($("#ycjd").val()));
			p2p(ck3,zd);
		}
    });
});
</script>
</html>