#!/bin/ash
DIR="/root/passive"
for file in $DIR/reduce-data/*
do
curl -F file=@$file "http://119.29.251.196/upload_passive.php" 
rm $file
#echo $file
done

