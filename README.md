介绍
========

> ThinkPHP 3.2 集成 [php-dotenv](https://github.com/josegonzalez/php-dotenv)

dotenv简介
---------

dotenv 解决一套代码在多处部署时各处代码所用的环境变量及配置的相互独立问题:

1. 团队协作时, 不同成员本地开发环境的系统,服务器类型,数据库用户密码都可能不同. 如果环境配置写死在项目中, 可能每次更新完代码都需要重新修改本地配置.

2. 本地与线上环境配置肯定不同, 如果直接利用版本库部署代码到线上, 必须防止将本地配置推送到线上.


使用
--------

```
composer require snowair/think-dotenv:dev-master
```

修改`Common\Conf\tags.php`:

```
return array(
    'app_begin'=>array(
        'Snowair\Dotenv\DotEnv',
    ),
);
```

修改 `.gitignore`, 添加`.env`文件到排除列表

现在你可以在项目根目录(即`APP_PATH`目录)下创建`.env`文件定义独立的环境配置了.

默认行为
------

默认情况下, `.env`文件中的配置会被添加到应用配置中, 并覆盖同名配置.

例如, 你的 config.php 中的数据库配置假设为:

```
return array(
    ...
    // 以下配置为线上数据库配置
    "DB_HOST"=>'127.0.0.1',
    "DB_NAME"=>'project',
    "DB_USER"=>'project',
    "DB_PWD"=>'uaasfu#h*x(a',
    ...
);
```

而你本地开发时, 一般需要修改成本地的数据库配置, 并且提交时必须小心翼翼防止将本地配置提交到线上.

现在, 你只需要创建如下内容的`.env`文件, 即可解决这个问题:

```
DB_NAME=dev_project # 假设你使用的本地数据库名称都加了dev前缀,以防手贱误操作了线上数据库
DB_USER=root        # 本地开发为了方便, 大家一般都使用root用户
DB_PWD=root         # 本地开发为了方便, 密码一般也很简单
```

应用启动后, .env 文件中的这三项配置将覆盖config.php中的这三项同名的配置. 

因此, 不同的机器,通过创建自己的.env文件,就可以避免开发和部署时的配置冲突问题.

注: windows平台创建以`.`号开头的特殊文件可能需要从编辑器中创建.

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

更多用法
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
