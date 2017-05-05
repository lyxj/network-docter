<?php
//登录
//注销登录
if($_GET['action'] == "logout"){
    unset($_SESSION['username']);
    unset($_SESSION['deviceid']);
    echo '注销登录成功！点击此处 <a href="index.html">登录</a>';
    exit;
}
if(!isset($_POST['submit'])){
    exit('非法访问!');
}
$username = htmlspecialchars($_POST['username']);
$password = $_POST['password'];
 $conn = mysql_connect("localhost","root","root9999") or die("数据库链接错误".mysql_error());  
 mysql_select_db("user",$conn) or die("数据库访问错误".mysql_error());  
 mysql_query("set names 'utf8'");  
//检测用户名及密码是否正确
  $result = mysql_query("select deviceid,username from user where username='$username' and password='$password';",$conn)or die(mysql_error());
if($result){
    //登录成功
    session_start();
	$row = mysql_fetch_array($result,MYSQL_ASSOC);
    $_SESSION['username'] = $username;
    $_SESSION['deviceid'] = $row['deviceid'];
	if($username=='router2'){
		$home_url = 'visual/flow2.html';
		header('Location: '.$home_url);
	}else if($username=='router1'){
		$home_url = 'visual/flow1.html';
		header('Location: '.$home_url);
	}
    exit;
} else {
    exit('登录失败！点击此处 <a href="javascript:history.back(-1);">返回</a> 重试');
}
?>