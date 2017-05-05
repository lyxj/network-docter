<?php
/**
 * $splitChar 字段分隔符
 * $file 数据文件名
 * $table 数据库表名
 * $conn 数据库连接
 * $fields 数据对应的列名
 * $insertType 插入操作类型，包括INSERT,REPLACE
 */ 
function loadDataIntoDatabase($splitChar,$file,$filename,$table,$conn,$fields=array(),$insertType='INSERT'){
/*根据路由器的ID来选择对应的数据库*/
 //上传文件中，前14位为路由器的ID，根据这个ID来选择路由器的数据。
  $deviceid = substr($filename,0,14);
  mysql_select_db($deviceid, $conn);
  
  if(empty($fields)) $head = "{$insertType} INTO `{$table}` VALUES('";
  else $head = "{$insertType} INTO `{$table}`(`".implode('`,`',$fields)."`) VALUES('";  //数据表头
  $end = "');";
  $sqldata = trim(file_get_contents($file));
  if(empty($sqldata)){
	  unlink($file);
  }
  if(preg_replace('/\s*/i','',$splitChar) == '') {
    $splitChar = '/(\w+)(\s+)/i';
    $replace = "$1','";
    $specialFunc = 'preg_replace';
  }else {
    $splitChar = $splitChar;
    $replace = "','";
    $specialFunc = 'str_replace';
  }
  //处理数据体，二者顺序不可换，否则空格或Tab分隔符时出错
  $sqldata = $specialFunc($splitChar,$replace,$sqldata);        //替换分隔符
  $sqldata = preg_replace('/(\s*)(\n+)(\s*)/i','\'),(\'',$sqldata);  //替换换行
   //echo $sqldata;
  $query = $head.$sqldata.$end;  //数据拼接
  if(mysql_query($query,$conn)) return array(true);
  else{
	  echo mysql_error($conn);
    return array(false,mysql_error($conn),mysql_errno($conn));
  }
}


/*将被动测量数据存到path路径，通过遍历path路径下的文件，并将其存入数据库*/
function Insert_from_file($path,$conn){ 
	$splitChar = ',';  //Tab
	$fields = array('Cap_time','Srcmac','Desmac','Packsize',
		'Srcip','Srcport','Desip','Desport','Protocal','InfoSize');
	$table = 'passive_data';
	$insertType = 'INSERT';
	foreach(scandir($path) as $afile){
		if($afile=='.'||$afile=='..') continue; 
		//遍历目录
		if(is_dir($path.'/'.$afile)) { 
			Insert_from_file($path.$afile);
		}else{ 
			//echo $path.$afile;
			$result = loadDataIntoDatabase($splitChar,$path.$afile,$afile,$table,$conn,$fields,$insertType);
			if (array_shift($result)){
				echo 'Success!<br/>';
				unlink($path.$afile);	//存入后删
			}else {
				echo 'Failed!--Error:'.array_shift($result).'<br/>';
			}
			
		} 
	}
}

//连接数据
$conn = mysql_connect("localhost","root","root9999");
if (!$conn){
  die('Could not connect: ' . mysql_error());
} 
/*之后也会根据ID来分割文件存储的位置*/
$result = Insert_from_file("/mydata/passive/",$conn);
mysql_close($conn);
?>