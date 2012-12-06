<!DOCTYPE HTML>
<html lang="zh-CN">
<head>
<meta charset="UTF-8" />
<title>后台数据管理系统</title>
<link rel="stylesheet" type="text/css" href="style.css"/>
<link rel="icon" href="images/favicon.ico" type="image/x-icon" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	//tab初始化显示
	$("#ortable").show();
	$("#urtable").hide();
	$("#sptable").hide();
	$("#ddgl").mouseover(function(){
		$("#ortable").show();
		$("#urtable").hide();
		$("#sptable").hide();
	});
	$("#yhgl").mouseover(function(){
		$("#ortable").hide();
		$("#urtable").show();
		$("#sptable").hide();
	});
	$("#spgl").mouseover(function(){
		$("#ortable").hide();
		$("#urtable").hide();
		$("#sptable").show();
	});
	//返回管理页面
	$('#back').click(function(){
	  window.location.href='./index.php';
	});
});
</script>
</head>
<body>
<div id="headerfrm">
</div>
<div id="container_admin">
<div id="tabs">
<!--放置3个tab用来控制显示-->
<div id="ddgl" class="tablecss"><p>订单管理</p></div>
<div id="yhgl" class="tablecss"><p>用户管理</p></div>
<div id="spgl" class="tablecss"><p>商品管理</p></div>
<div id="ortable">
<?php
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
	$sql_addtester="SELECT * FROM myorders";
	$result = mysql_query($sql_addtester,$con);
	echo '<table id="t1">';
	echo '
		<tr>
			<td>订单ID</td>
			<td>用户ID</td>
			<td>商品ID</td>
			<td>订单提交时间</td>
			<td>订单完成状态</td>
			<td>订单完成时间</td>
			<td></td>
			<td></td>
		</tr>
	';
	while($row = mysql_fetch_array($result))
	{
		//$i = $row['morID'];
		//echo '<option value="'.$i.'">'.$i.'号订单</option>';
		if($row['isOK']==0)
		{
			$temp='未完成';
		}
		else
		{
			$temp='已经完成';
		}
		echo '<tr>';
		echo '<td>'.$row['morID'].'</td>'.'<td>'.$row['userID'].'</td>'.'<td>'.$row['spID'].'</td>'.'<td>'.$row['tjtime'].'</td>'.'<td>'.$temp.'</td>'.'<td>'.$row['okTime'].'</td>';
		echo '<td><input type="button" value="修改"></td>';
		echo '<td><input type="button" value="删除"></td>';
		echo '</tr>';
		
	}
	echo '</table>';
	mysql_close($con);
}
?>
</div>

<div id="urtable">
<?php
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
	$sql_addtester="SELECT * FROM users";
	$result = mysql_query($sql_addtester,$con);
	echo '<table id="t2">';
	echo '
		<tr>
			<td>用户ID</td>
			<td>用户名</td>
			<td>联系方式</td>
			<td>配货地址</td>
			<td></td>
			<td></td>
		</tr>
	';
	while($row = mysql_fetch_array($result))
	{
	
		echo '<tr>';
		echo '<td>'.$row['userID'].'</td>'.'<td>'.$row['name'].'</td>'.'<td>'.$row['tel'].'</td>'.'<td>'.$row['address'].'</td>';
		echo '<td><input type="button" value="修改"></td>';
		echo '<td><input type="button" value="删除"></td>';
		echo '</tr>';
		
	}
	echo '</table>';
	mysql_close($con);
}
?>
</div>

<div id="sptable">
<?php
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
	$sql_addtester="SELECT * FROM sp";
	$result = mysql_query($sql_addtester,$con);
	echo '<table id="t3">';
	echo '
		<tr>
			<td>商品编号</td>
			<td>商品名</td>
			<td>商品价格</td>
			<td></td>
			<td></td>
		</tr>
	';
	while($row = mysql_fetch_array($result))
	{
	
		echo '<tr>';
		echo '<td>'.$row['spid'].'</td>'.'<td>'.$row['spname'].'</td>'.'<td>'.$row['jg'].'</td>';
		echo '<td><input type="button" value="修改"></td>';
		echo '<td><input type="button" value="删除"></td>';
		echo '</tr>';
		
	}
	echo '</table>';
	mysql_close($con);
}
?>

</div>

</div>
<input type="button" id="back" class="orlabel" value="返回管理页面"/>
</div>  
</body>
</html>