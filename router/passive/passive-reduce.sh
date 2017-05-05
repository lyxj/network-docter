#!/bin/ash/
inDIR="/root/passive/data"
outDIR="/root/passive/reduce-data"
tmpdat=$(date +'%Y-%m-%d-%H-%M-%S')
DEVICE_ID=$(cat /etc/bismark/ID)
LOG_FILE_NAME="${DEVICE_ID}_${tmpdat}-passive"
tempdat=$(date +'%Y-%m-%d')
#tcpdump -q -n -e -r $* | grep "IPv4" | sed 's/,/ /g; s/>/ /g; s/  IPv4  //g;s/://g;s/length//g' >>$outDIR/$LOG_FILE_NAME
tcpdump -q -n -e -r $* | grep "IPv4"  | sed 's/,/ /g; s/>/ /g; s/  IPv4  //g; s/length//g' \
| awk  -v dat=$tempdat '   
BEGIN {
#example of flow in
#08:31:35.633133 c6:04:15:9a:db:dd   4c:34:88:e7:b6:7f 106: 192.168.142.1.22   192.168.142.219.53918: tcp 52
}
{
    gsub(":","",$2)
    gsub(":","",$3)
    gsub(":","",$4)
    gsub(":","",$6)
    split($5,tmp1,".")
    split($6,tmp2,".")
    split($1,tmp3,".")
    
    # $1 time ; $2 src MAC ;$3 dst MAC ;$4 length ; $5 src IP.port ;$6dst IP.port; $7proto $8 length    
    print dat " " tmp3[1]","$2","$3","$4","tmp1[1]"."tmp1[2]"."tmp1[3]"."tmp1[4]","tmp1[5]","tmp2[1]"."tmp2[2]"."tmp2[3]"."tmp2[4]","tmp2[5]","$7","$8
  
}

END {
 
}
'>>$outDIR/$LOG_FILE_NAME