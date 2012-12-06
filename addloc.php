<?php 
	$_POST["myid"]=!empty($_POST["myid"]) ? $_POST["myid"] : null;
	$_POST["myloc"]=!empty($_POST["myloc"]) ? $_POST["myloc"] : null;
	if($_POST["myid"]&&$_POST["myloc"])
	{
		//尝试连接数据库
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
			$stuno=$_POST["myid"];
			$tryno=$_POST["myloc"];
			//写入数据库
			$sql_addtester="INSERT INTO loc(orID,loc) VALUES($stuno,'$tryno')";
			if(!mysql_query($sql_addtester,$con))
			{
				echo mysql_error();
			}
			mysql_close($con);
		}
	}
?>