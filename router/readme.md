1�����д��������ڹ̼�Ϊbismark��WNDR3800·������/rootĿ¼��
2������ʹ��shell���б�д����ֱ�����У�ע��������ǰ��ӿ�ִ��Ȩ��
3��passive�ļ���Ϊ��������ģ����뼰������ʱ�洢Ŀ¼��active�ļ���Ϊ��������ģ����뼰������ʱ�洢Ŀ¼��
4�������ļ���Ҫ���ÿ���ִ�У�������Ҫ��ʱ
����ִ�������/etc/rc.local��:
sh /root/passive/passive-wrapper.sh
��ʱִ����������/etc/contab/root��
*/2  * * * * sh /root/passive/passive-audit.sh  

*/5 * * * * sh /root/passive/passive-upload.sh 

0 * * * * sh /root/active/measure-active

30 * * * * sh /root/active/measure-active
*/10 
* * * * sh /root/upload-device