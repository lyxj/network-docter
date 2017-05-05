<?php
error_reporting(E_ALL ^ E_DEPRECATED);
/*
file为主动测量文件路径
filename为文件名，用于获取路由器iD
table为插入表格
fields为列名
*/


function insert_active_data($file,$filename,$table,$conn,$fields=array()){
	$doc = new DOMDocument();
	$doc->load($file);
	$data1=$doc->getElementsByTagName("measurements");
	//获取路由器ID，并选择对应的数据库。
	$deviceid = substr($filename,0,14);
    mysql_select_db($deviceid, $conn);
	
	foreach($data1 as $measurements){
		$measurement = $measurements->getElementsByTagName("measurement");
		$head = "INSERT INTO `{$table}`(`".implode('`,`',$fields)."`) VALUES ";
		$sp = ",";
		$count = 0;
		$timestamp = "'".substr($filename,15,10)."'";
		foreach ($measurement as $data){
			$param ="'".$data->getAttribute("param")."'";
			$tool = "'".$data->getAttribute("tool")."'";
			$srcip ="'".$data->getAttribute("srcip")."'";
			$dstip ="'".$data->getAttribute("dstip")."'";
			$avg = "'".$data->getAttribute("avg")."'";
			$std = "'".$data->getAttribute("std")."'";
			$min = "'".$data->getAttribute("min")."'";
			$max = "'".$data->getAttribute("max")."'";
			$med = "'".$data->getAttribute("med")."'";
			$iqr = "'".$data->getAttribute("iqr")."'";
			$sentence = "(".$param.$sp.$tool.$sp.$srcip.$sp.$dstip.$sp.$timestamp.
			$sp.$avg.$sp.$std.$sp.$min.$sp.$max.$sp.$med.$sp.$iqr.");";
			$sql = $head.$sentence;
			
			if(mysql_query($sql,$conn)){
				$count = $count +1;
			}
			else {
				echo mysql_error($conn)."\n";
			}
		}
		return $count;
	}
}
/*主动测量数据存入path下，遍历path目录并将其存入数据库*/
function Insert_from_file($path,$conn){
	foreach(scandir($path) as $afile){
		if($afile=='.'||$afile=='..') continue;
		//遍历目录
		if(is_dir($path.'/'.$afile)) {
			Insert_from_file($path.$afile);
		}else{
			$table = "active_data";
			$fields = array('param','tool','srcip','dstip',
					'cap_time','avg','std','min','max','med','iqr');
			$result = insert_active_data($path.$afile,$afile,$table,$conn,$fields);
			if ($result!=0){
				echo $result.'Success!<br/>';
				unlink($path.$afile);	//存入后删
			}else {
				echo 'Failed!--Error:'.array_shift($result).'<br/>';
			}
			
		}
	}
}
$conn=mysql_connect("localhost","root","root9999");
if (!$conn){
	die('Could not connect: ' . mysql_error());
} 
mysql_query("set names 'utf8'"); //数据库输出编码
$result = Insert_from_file("/mydata/active/",$conn);
mysql_close($conn);
?>