<?php 
	$_POST["myid"]=!empty($_POST["myid"]) ? $_POST["myid"] : null;
	if($_POST["myid"])
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
			//查询数据库
			$sql_addtester="SELECT name,tel,address,spname,tjtime 
							FROM myorders,sp,users 
							WHERE morID=".$_POST["myid"]." AND myorders.userID=users.userID AND myorders.spID=sp.spid";
			$result = mysql_query($sql_addtester,$con);
			while($row = mysql_fetch_array($result))
			{
				$temparr=array ('xm'=>$row['name'],'sj'=>$row['tel'],'dz'=>$row['address'],'spm'=>$row['spname'],'tjtm'=>$row['tjtime']);
			}
			//获取路径信息
			$sql_points="SELECT loc,curtime
						 FROM  loc
						 WHERE orID=".$_POST["myid"];
			$result_points=mysql_query($sql_points,$con);
			$temppt='';
			while($row = mysql_fetch_array($result_points))
			{
				$temppt=$temppt.$row['loc'].','.$row['curtime'].' | ';
			}
			$temparr['lz']=$temppt;
			echo json_encode($temparr);
			mysql_close($con);
		}
	}
?>