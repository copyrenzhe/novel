# 书虫网  [![Build Status](https://travis-ci.org/copyrenzhe/novel.png?branch=master)](https://travis-ci.org/copyrenzhe/novel)

> 书虫网是一个基于laravel5.2的小说采集/展示系统

### 环境要求
推荐使用[homestead](https://laravel.com/docs/5.2/homestead)环境
* PHP7
* Laravel5.2
* composer
* node.js 
* mysql
* beanstalkd
* redis

### 安装使用说明

1. 下载源码

    Linux下执行命令：
    ```bash
    git clone http://github.com/copyrenzhe/novel.git
    cd novel
    composer install
    npm install
    ```

2. 配置项目

    执行下列命令：
    ```bash
    cp .env.example .env
    php artisan key:generate
    touch .env  //根据实际情况修改数据库配置
    php artisan migrate
    php artisan db:seed --class=CreateAdminSeeder
    gulp
    ```

3. 运行系统
    保证8000端口未被占用后
    执行命令：
    ```bash
    php artisan serve
    ```
    然后在浏览器中输入`localhost:8000` 访问首页
    进入`localhost:8000/admin`进入后台，初始用户名/密码：admin/admin


4. 队列进程管理
    推荐使用[supervisor](http://supervisord.org/)来管理自动更新与采集队列进程。
    `supervisord.conf`的配置如下(可根据具体情况调整)：
    ```bash
    [program:worker]
    process_name=%(program_name)s_%(process_num)02d
    command=php /var/www/novel/artisan queue:work --sleep=3 --tries=1 --memory=512 --daemon
    autostart=true
    autorestart=true
    user=root
    numprocs=2
    redirect_stderr=true
    stdout_logfile=/var/www/novel/storage/logs/queue-worker.log


    [program:beanstalkd]
    process_name=%(program_name)s_%(process_num)s
    command=beanstalkd -l 127.0.0.1 -p 11300
    autostart=true
    autorestart=true
    numprocs=1
    redirect_stderr=true
    stdout_logfile=/var/www/novel/storage/logs/beanstalkd.log

    [program:redis]
    process_name=%(program_name)s_%(process_num)s
    command=redis-server
    directory=/root/soft/redis/src
    autostart=true
    autorestart=true
    numprocs=1
    redirect_stderr=true
    stdout_logfile=/var/www/novel/storage/logs/redis.log
    ```
    配置完成后，后台即可爬取 [笔趣阁](http://www.qu.la)、[看书中](http://www.kanshuzhong.com)、[名著吧](http://www.mzhu8.com)中的小说。所有爬取后的小说将会自动进行更新。

5. 更新策略

    > 更新策略可以根据服务器负载能力进行调整，部分策略会发送邮件，需要在`.env`中配置`smtp`并修改管理员邮箱。

      - 每天10点和18点更新排名前30的热门小说
      - 每天凌晨三点更新所有小说
      - 每周六与起点的周排行进行对比
      - 每个月的28号与起点的月排行进行对比
      - 每十分钟监测系统是否运行正常

6. 微信公众号配置

    在`.env`中配置公众号的APPID、SECRET、TOKEN，并将公众账号的URL配置为 */wechat (*为网站url)

### 引用的框架与包
* [Laravel/laravel](https://github.com/laravel/laravel)
* [overtrue/laravel-wechat](https://github.com/overtrue/laravel-wechat)
* [watson/sitemap](https://github.com/watson/sitemap)
* [pda/pheanstalk](https://github.com/pda/pheanstalk)
* [acacha/admin-lte-template-laravel](https://github.com/acacha/adminlte-laravel)
* [yajra/laravel-datatables-oracle](https://github.com/yajra/laravel-datatables)

### 联系作者
Email: copyrenzhe <copyrenzhe@gmail.com>
