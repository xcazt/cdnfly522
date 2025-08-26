## 简易安装教程
主控版本：5.2.2<br />
被控版本：5.1.19<br />
请支持正版 本站仅用于学习研究 不可用于商用以及违法用途<br />
现在只需要运行脚本就可以安装!!!<br />
可以直接输入命令进行安装<br />
CentOS7更换镜像源<br />
### 免费添加白名单地址：<br />
```bash
https://cdnfly522.cdn456.eu.org
```
<u>账号密码都是admin</u><br />

### 一键换源：<br />

```bash
bash <(curl -sSL https://gitee.com/SuperManito/LinuxMirrors/raw/main/ChangeMirrors.sh)
```

### 安装cdnfly控制面板<br />

```bash
curl -fsSL https://github.com/xcazt/cdnfly522/raw/refs/heads/main/master.sh -o master.sh && chmod +x master.sh && ./master.sh --es-dir /home/es
```
### DD镜像Ubnutu16.04
```bash
curl -O https://raw.githubusercontent.com/bin456789/reinstall/main/reinstall.sh
```
```bash
bash reinstall.sh ubuntu 16.04
```
```bash
reboot
```
然后等个十几分钟装好后,账号：root,密码：123@@@

<p>主控和被控均不能在 已安装nginx的情况下 执行安装命令，必须确保80 443端口未被占用!!!<br />
主控只支持Cetnos7系列系统<br />
被控只支持Cetnos7系列和ubnutu16.04系统<br />
主控需开放80 88 443 9200端口<br />
节点需要开放 80 443 5000端口<br />
初始化管理员账号：admin<br/>
初始化管理员密码：cdnfly</p>


###  其他操作
节点迁移至新主控 需要将旧节点的旧主控IP替换为新主控的IP

#依次在ssh登录每个节点并执行下面命令即可
#将 your_new_ip 替换为你自己的新主控IP
```bash
wget -qO change_ip.sh https://github.com/SidneySenn/cdnfly2025/raw/refs/heads/main/change_ip.sh && chmod +x change_ip.sh && bash change_ip.sh your_new_ip
```
或选择手动操作
```bash
new_master_ip="这里替换为主控IP"
sed -i "s/ES_IP =.*/ES_IP = "$new_master_ip"/" /opt/cdnfly/agent/conf/config.py
sed -i "s/MASTER_IP.*/MASTER_IP = "$new_master_ip"/g" /opt/cdnfly/agent/conf/config.py
sed -i "s/hosts:.*/hosts: ["$new_master_ip:9200"]/" /opt/cdnfly/agent/conf/filebeat.yml
sed -i "s#http://.*:88#http://$new_master_ip:88#" /usr/local/openresty/nginx/conf/listen_80.conf /usr/local/openresty/nginx/conf/listen_other.conf
ps aux | grep [/]usr/local/openresty/nginx/sbin/nginx | awk '{print $2}' | xargs kill -HUP || true
supervisorctl -c /opt/cdnfly/agent/conf/supervisord.conf restart filebeat
supervisorctl -c /opt/cdnfly/agent/conf/supervisord.conf restart agent
supervisorctl -c /opt/cdnfly/agent/conf/supervisord.conf restart task
```
## 重启进程

### 主控重启
```bash
supervisorctl -c /opt/cdnfly/master/conf/supervisord.conf restart all
```
### 节点重启
```bash
supervisorctl -c /opt/cdnfly/agent/conf/supervisord.conf restart all
```
### 如何初始化elasticsearch
```bash
cd /tmp;
wget http://us.centos.bz/cdnfly/int_es.sh -O int_es.sh;
chmod +x int_es.sh;
./int_es.sh /home/es;
```
### 备份数据库
```bash
cd /root;
curl http://us.centos.bz/cdnfly/backup_master.sh -o backup_master.sh;
chmod +x backup_master.sh;
./backup_master.sh;
```
这时候将在目录/root下，打包生成cdn.sql.gz文件，请把这个文件传输到新主控的/root/目录下，可以使用scp命令，命令如下：
```bash
cd /root
scp cdn.sql.gz   root@新主控IP:/root/
```
### 恢复数据库
```bash
cd /root;
curl http://us.centos.bz/cdnfly/restore_master.sh -o restore_master.sh;
chmod +x restore_master.sh;
./restore_master.sh;
```
### Cdnfly监控设置
尊敬的cdnfly用户:
为防止重启节点，Nginx服务启动不起来，可以在节点Tcp监控设置里面把主IP的监控端口设置为5000
节点管理-点击tcp-更多HTTP设置-端口：5000

### 官方最新公告
尊敬的cdnfly用户:
目前发现登录安全漏洞，需要及时按照如下方法来临时修复。找一个只有你知道的域名,这个域名用于管理员登录。
路径为:系统管理--->系统设置--->用户相关，限制管理员只能从此域名登录
