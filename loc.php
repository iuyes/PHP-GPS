<!DOCTYPE HTML>
<html lang="zh-CN">
<head>
<meta charset="UTF-8" />
<title>位置实时发布</title>
<script type="text/javascript" src="js/jquery.js"></script>
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1"> 
<link rel="icon" href="images/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" type="text/css" media="all" href="style.css" />
<script type="text/javascript">
//自动获取客户端的经纬度信息
if(navigator.geolocation){
        navigator.geolocation.getCurrentPosition(updataPosition);
    }
	else{
		alert("对不起，您使用的浏览器不支持此属性");
    }
function updataPosition(position){
    //纬度信息
	var latitudeP = position.coords.latitude;
	//经度信息
    var longitudeP = position.coords.longitude;
	$("#noloc").val(latitudeP+','+longitudeP);
}
$(document).ready(function(){
		$('#tj_btn').click(function(){
			var stuno=$.trim($("#sdid").val());
			var tryid=$.trim($("#noloc").val());
			$.post(
				'addloc.php',
				{
				myid:stuno,
				myloc:tryid
				},
				function(){
					alert("提交数据成功");
					//window.location.href='./loc.php';
					$("#noloc").attr("value",'');
				}
			);
		});
	});
</script>
</head>
<body>
<div id="title">
	<h1>订单位置提交</h1>
</div>
<div class="box">
	<p>此处输入订单号码</p>
	<input type="text" id="sdid" value="" name="myid" />
	<p>当前经纬度（自动获取）</p>
	<input type="text" id="noloc" value="" name="myloc" />
	<div id="claer"></div>
	<input type="button" id="tj_btn" value="提交"/>
</div>
</body>
</html>