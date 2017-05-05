<?php
  error_reporting(E_ALL ^ E_DEPRECATED);
  require("db_config.php");
  $array= array();
  class LMRTT{
  	public $cap_time;
  	public $avg;
  	public $min;
  	public $max;
  	public $med;
  }
  $conn=mysql_connect($mysql_server_name,$mysql_username,$mysql_password) or die("error connecting") ;
  mysql_query("set names 'utf8'"); //数据库输出编码
session_start();
mysql_select_db($_SESSION['deviceid']);
  date_default_timezone_set("Asia/Shanghai");
  $result = mysql_query("select cap_time,avg,min,max,med from active_data where param = 'LMRTT' order by cap_time;",$conn)or die(mysql_error());
  while($row = mysql_fetch_array($result,MYSQL_ASSOC)){
    $lmrtt=new LMRTT();
    $lmrtt->cap_time = date('Y-m-d H:i:s',$row['cap_time']);
    $lmrtt->avg = round($row['avg'],2);
    $lmrtt->min = round($row['min'],2);
    $lmrtt->max = round($row['max'],2);
    $lmrtt->med = round($row['med'],2);
    $array[]=$lmrtt;
  }
  $data=json_encode($array); 
  mysql_close($conn);
  echo $data;
?> 