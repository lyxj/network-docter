#!/bin/ash/
#passive-wrapper
#passive-wrapper.sh
#this shell should be executed when the boot is started ,add the shell into etc/rc.local 
#get the package and save in date,time.pcap  
tcpdump -i br-lan -s 84 -G 30 -w /root/passive/data/%F,%T.pcap