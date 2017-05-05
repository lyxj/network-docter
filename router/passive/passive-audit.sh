#!/bin/ash
DIR="/root/passive"
for file in $DIR/data/*
do
sh $DIR/passive-reduce.sh $file
rm $file
#echo $file
done
