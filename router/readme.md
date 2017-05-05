1、所有代码运行在固件为bismark的WNDR3800路由器的/root目录下
2、代码使用shell进行编写，可直接运行，注意在运行前添加可执行权限
3、passive文件夹为被动测量模块代码及数据临时存储目录；active文件夹为主动测量模块代码及数据临时存储目录。
4、部分文件需要设置开机执行，部分需要定时
开机执行添加在/etc/rc.local中:
sh /root/passive/passive-wrapper.sh
定时执行在设置在/etc/contab/root中
*/2  * * * * sh /root/passive/passive-audit.sh  

*/5 * * * * sh /root/passive/passive-upload.sh 

0 * * * * sh /root/active/measure-active

30 * * * * sh /root/active/measure-active
*/10 
* * * * sh /root/upload-device