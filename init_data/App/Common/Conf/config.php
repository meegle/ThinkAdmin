<?php
return array(
     /* URL设置 */
    'URL_CASE_INSENSITIVE'  => true,    // 表示URL区分大小写，true则表示不区分大小写
    'URL_MODEL'             => 0,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
    // 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式，提供最好的用户体验和SEO支持

    // 配置Cookie
    'COOKIE_PREFIX' =>'mysite_',
    'COOKIE_PATH'   =>'/',
    'COOKIE_DOMAIN' =>'.mysite.com',

    //数据库连接参数
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => 'localhost',
    'DB_USER'   => 'root',
    'DB_PWD'    => '123456',
    'DB_NAME'   => 'mysite',
    'DB_PREFIX' => 'ta_',

    /* 默认设定 */
    'DEFAULT_C_LAYER' => 'Action', // 默认的控制器层名称

    //默认过滤函数
    'DEFAULT_FILTER' => 'htmlspecialchars',

    //文件上传配置
    'UPLOAD_PATH' => 'data/upload/',

    //开启调试
    'SHOW_PAGE_TRACE' => true
);