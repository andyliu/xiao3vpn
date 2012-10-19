http://www.xiao3.org
========

一个基于Free Radius简单的VPN销售管理平台

安装方法：<br />
1、导入xiao3_vpn.sql.zip到radius库<br />
2、重命名config/app.yaml.simple 为 config/app.yaml<br />
3、按注释配置config/app.yaml文件。<br />
4、所有文件到服务器并设置 public_html 目录为网站根目录<br />
5、保证 tmp 目录可写<br />

网址重写：<br />
1、Apache只要服务器支持就好了<br />
2、Nginx请参考public_html/nginx.conf.simple<br />

收款接口：目前使用双功能交易接口，如需更换请联系我。<br />

特别申明：<br />
1、此程序未经严格测试，切勿用于正式的商业环境，无视此警告导致任何损失与本作者无关。<br />
2、此程序侧重于VPN帐号管理，简单的订单账单系统，对于Free Radius仅仅提供简单的设置，任何关于Free Radius的设置、配置问题请自行查阅其他资料。<br />

技术支持：http://www.xiao3.org xiao3vv#gmail.com<br />


