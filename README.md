
## About GOST-Panel

GOST-Panel是基于GOST V3版本的多用户，多节点管理面板。  
但这是前后端分离的，后端用Laravel 前端用VUE3，太白的就绕道吧。

### 关于节点模式
我们设计了两种连接模式  
- #### 中转模式：  
节点选中这个模式的时候，会自动在服务端配置一个中转服务，中转再通过隧道到节点，但是这个模式有个弊端就是需要你中转服务器有很多端口。
- #### 直连模式：  
节点选中这个模式的时候，客户就会直连节点，不通过中转服务，节点端口就很多了吧，这应该不成问题。



## WEB后端安装方式
#### 普通安装方法
- 克隆本项目。
- 进入项目根目录执行 composer install
- 复制 .env.example 更名 .env
- 修改 .env 里面的相关配置
- 其他的都跟laravel设置一样
- 数据库在根目录的server_gost_com.sql
  
#### 宝塔安装方法
- 克隆本项目。
- 进入项目根目录执行 composer install
- 复制 .env.example 更名 .env
- 修改 .env 里面的相关配置
- 设置 伪静态规则为 laravel
- 设置 网站目录-运行目录为 /public
- 访问如果能出laravel的页面就是成功了
- 数据库在根目录的server_gost_com.sql
  
#### 流量统计 - 没有这个 就不会统计流量
- 宝塔建立一个定时任务，几分钟随便你，就是定时统计一下流量
```
cd /wwwroot/你的项目地址
php artisan TrafficStatistics
```
  
  
  
## WEB前端安装方式
#### 源码编译模式
- 后端是vue写的，所以需要本地有node环境
- 进入项目根的GM目录执行 npm install
- 修改 .env.production 文件里的网址
- 执行 npm run build:prod
- 执行完了之后，会在目录生成一个dist的文件夹，把里面的东西上传到宝塔就好了


#### 直接修改模式
- 因为很多人不会vue编译什么的，所以有这个模式，直接改地址即可
- 根目录下有一个dist目录，就是编译好的后台，修改请求地址，static/encConfig.js里面的URL
- 然后上传到你的后台目录里，访问即可。
- 前后端分离的，应该有两个域名，比如后台服务的是server.a.com 后台界面的是admin.a.com这样
  
  
  
## 重点来了，GOST服务端安装方式
- 请看gostConfig目录
  
  
  
## 使用 Clash 连接 Gost 服务端示例
```
- name: "gost"
  type: socks5
  server: Gost服务端域名
  port: Gost代理端口
  username: Gost账户
  password: Gost密码
```


## 界面预览
![image](https://github.com/Czssr/GOST-Panel/raw/main/IMG/vue3-element-admin.png)
![image](https://github.com/Czssr/GOST-Panel/raw/main/IMG/vue3-element-admin%20(1).png)
![image](https://github.com/Czssr/GOST-Panel/raw/main/IMG/vue3-element-admin%20(4).png)
![image](https://github.com/Czssr/GOST-Panel/raw/main/IMG/vue3-element-admin%20(2).png)
![image](https://github.com/Czssr/GOST-Panel/raw/main/IMG/vue3-element-admin%20(3).png)


