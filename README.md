介绍
========

> ThinkPHP 3.2 集成 [php-dotenv](https://github.com/josegonzalez/php-dotenv)

[composer中文文档](http://www.kancloud.cn/thinkphp/composer)

dotenv简介
---------

dotenv 解决一套代码在多处部署时各处代码所用的环境变量及配置的相互独立问题:

1. 团队协作时, 不同成员本地开发环境的系统,服务器类型,数据库用户密码都可能不同. 如果环境配置写死在项目中, 可能每次更新完代码都需要重新修改本地配置.

2. 本地与线上环境配置肯定不同, 如果直接利用版本库部署代码到线上, 必须防止将本地配置推送到线上.




使用
--------

##安装篇

```
composer require snowair/think-dotenv:dev-master
```

##配置篇

* 在 Common/Conf/tags.php 增加一个行为,如果已经添加过,就不用再添加了:
    ```
    return array(
         'app_init'=>array(
            'Snowair\Think\Behavior\HookAgent'
         ),
    )
    ```

修改 `.gitignore`, 添加`.env`文件到排除列表

现在你可以在项目根目录(即`APP_PATH`目录)下创建`.env`文件定义独立的环境配置了! 就是这么简单.

* `.env` 文件的格式见文档: <https://github.com/josegonzalez/php-dotenv>

默认行为
------

###原来
```
config.php

return array(
        'DB_TYPE'   => 'mysql', // 数据库类型
        'DB_HOST'   => 'localhost', // 服务器地址
        'DB_NAME'   => 'dbname', // 数据库名
        'DB_USER'   => 'root', // 用户名
        'DB_PWD'    => '123456', // 密码
        'DB_PORT'   => 3306, // 端口
        'DB_PREFIX' => '', // 数据库表前缀
);
```

###现在
```
.env

DB_TYPE=mysql
DB_HOST=localhost
DB_NAME=dbname
DB_USER=root
....

```

应用启动后, .env 文件中的配置项将覆盖config.php中的这些同名的配置项.

因此, 不同的机器,通过创建自己的.env文件,就可以避免开发和部署时的配置冲突问题.

注: windows平台创建以`.`号开头的特殊文件可能需要从编辑器中创建.

现在你就可以去验证下是否OK了。


### 复杂配置

TP中有些配置的值是数组, 使用 dotenv的配置格式为: `KeyP1.KeyP2.Key...=value`

例如:

```
"MODULE_ALLOW_LIST"=>array('Home','Admin','Api'),
```

如果想使用 .env 文件进行配置, 文件内容如下:

```
MODULE_ALLOW_LIST.0=Home
MODULE_ALLOW_LIST.1=Admin
MODULE_ALLOW_LIST.2=Api
```

高级用法
--------

一般情况下, 以上默认行为已经足够应付绝大多数场景. 但也许你也需要dotenv提供的其他功能(见文档).

think-dotenv 提供了以下配置对应dotenv提供的其他功能:

```
 "DOTENV" => array(
    "toEnv"=>true,              // 是否将 .env导入到 $_ENV 变量, 默认为false
    "toServer"=>true,           // 是否将 .env导入到 $_SERVER 变量, 默认为false
    "toConst"=>true,            // 是否将 .env导入到常量, 默认为false
    "expect"=>'env1,env2,env3', // 强制要求定义的env配置列表, 如果.env文件中没有定义, 则抛出异常
 
 ),
```

注意: 这些配置必须定义在 Conf/config.php 等配置文件中.
