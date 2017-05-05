<?php
error_reporting(E_ALL ^ E_DEPRECATED);
require("db_config.php");
$array= array();
class RTT{
	public $cap_time;
	public $dstip;
	public $avg;
}
$conn=mysql_connect($mysql_server_name,$mysql_username,$mysql_password) or die("error connecting") ;
session_start();
mysql_select_db($_SESSION['deviceid']);
mysql_query("set names 'utf8'"); //数据库输出编码
date_default_timezone_set("Asia/Shanghai");
$result = mysql_query("select cap_time,dstip,avg,min,max,med from active_data where param = 'RTT' order by cap_time,dstip;",$conn)or die(mysql_error());
while($row = mysql_fetch_array($result,MYSQL_ASSOC)){
	$rtt=new RTT();
	$rtt->cap_time = date('Y-m-d H:i:s',$row['cap_time']);
	$rtt->dstip = $row['dstip'];
	$rtt->avg = round($row['avg'],2);
	$array[]=$rtt;
}
mysql_close($conn);
$data=json_encode($array);
echo $data;
?> 