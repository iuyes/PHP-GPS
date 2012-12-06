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
<title>配货实时位置监测</title>
<link rel="stylesheet" type="text/css" href="style.css"/>
<link rel="icon" href="images/favicon.ico" type="image/x-icon" />
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&language=zh-CN"></script> 
<script type="text/javascript" src="js/jquery.js"></script>
</head>
<body>
<div id="container">
	<div id="myorder">
		<h1 id="ordername">配货实时位置监测</h1>
		<hr>

		<div class="orlabel">请您选择相应订单</div>
		<select name="jc_orders" id="allorders" onchange="getorderinfo()">
			<option selected value="选择相应订单号">选择相应订单号</option>
			<!--调用php函数显示订单数量-->
			<?php getallorders();?>
		</select>
		<hr>

		<div class="orlabel">订单详情</div>
		<textarea id="orcon"></textarea>
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
	  zoom: 12,
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
	//用于控制图形显示的变量
	var lineArray = [];
	var counts=0;
	//得到订单信息函数
	setInterval("getorderinfo()", 10000);
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
				
				var lzpt = myjson.lz.split("|");
				if(lzpt.length!=counts)
				{
					counts=lzpt.length;
					var textcon='用户名:'+myjson.xm+'\n'+'手机:'+myjson.sj+'\n'+'住址:'+myjson.dz+'\n'+'商品名:'+myjson.spm+'\n'+'订单日期:'+myjson.tjtm;
					$("#orcon").val(textcon);
					//清除之前的路线信息
					if (lineArray) {
						for (i in lineArray) {
							lineArray[i].setMap(null);
						}
					}
					//显示路径
					
					var testarray=new google.maps.MVCArray();
					for(var i=0;i<lzpt.length-1;i++)
					{
					  var inpt=new google.maps.LatLng(lzpt[i].split(",")[0],lzpt[i].split(",")[1]);
					  //设置marker坐标
					  var tempmark=new google.maps.Marker({
						  position: inpt,   
						  map: map,
						  title:"此消息由车载GPS自动发送\n"+"到达此地时间："+lzpt[i].split(",")[2]+"\n您的订单正在努力配送中，请耐心等待。",
						  draggable: false 
						});
					  //压入整个图层显示控制
					  lineArray.push(tempmark);
					  //google 画线序列
					  testarray.push(inpt);
					}
					//显示新的路线
					var roadline=new google.maps.Polyline({
					  map:map,
					  strokeColor:"blue",
					  strokeWeight:6,					  
					  path:testarray
					});
					lineArray.push(roadline);
				}
			}
		);
	}
	//返回按钮的相应函数
	$(document).ready(function(){
		$('#back').click(function(){
		  window.location.href='./index.php';
		});
	});
</script>
</html>