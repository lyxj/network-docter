<?php
error_reporting(E_ALL ^ E_DEPRECATED);
require("db_config.php");
$array= array();
class Throughput{
	public $cap_time;
	public $avg;
	public $direction;
}
$conn=mysql_connect($mysql_server_name,$mysql_username,$mysql_password) or die("error connecting") ;
mysql_query("set names 'utf8'"); //数据库输出编码
session_start();
mysql_select_db($_SESSION['deviceid']);
date_default_timezone_set("Asia/Shanghai");
$result = mysql_query("select cap_time,tool,avg from active_data where param = 'BITRATE' order by cap_time,tool;",$conn)or die(mysql_error());
while($row = mysql_fetch_array($result,MYSQL_ASSOC)){
	$tp=new Throughput();
	$tp->cap_time = date('Y-m-d H:i:s',$row['cap_time']);
	$tp->avg = round($row['avg'],2);
	$tp->direction = $row['tool'];
	$array[]=$tp;
}
mysql_close($conn);
$data=json_encode($array);
echo $data;
?> 