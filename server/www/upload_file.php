<?php

if ($_FILES["file"]["error"] > 0)
{
	echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
}
else
{
	echo "Upload: " . $_FILES["file"]["name"] . "<br />";
	echo "Type: " . $_FILES["file"]["type"] . "<br />";
	echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";

	if (file_exists("/mydata/" . $_FILES["file"]["name"]))//判断文件是否已存在
	  {
	  echo $_FILES["file"]["name"] . " already exists. ";
	  }
	else
	  {
	  move_uploaded_file($_FILES["file"]["tmp_name"],//将文件从临时存储移至永久存储
	  "/mydata/" . $_FILES["file"]["name"]);
	  echo "Stored in: " . "/mydata/" . $_FILES["file"]["name"];
	  }
}
?>
