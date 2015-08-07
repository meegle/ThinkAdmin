<?php
return array(
    //'配置项'=>'配置值'
    'ADMIN_CACHE_TIME'  => '3600', // 后台数据缓存时间，以秒为单位
    'ADMIN_PAGE_SIZE'   => 10, // 后台分页

    /* URL设置 */
    'URL_CASE_INSENSITIVE'  => true,    // 表示URL区分大小写，true则表示不区分大小写
    'URL_MODEL'             => 1,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
    // 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式，提供最好的用户体验和SEO支持
    
    'TMPL_PARSE_STRING' => array(
        '../Public' => __ROOT__.'/Public/Admin'
    ),

    //后台上传配置
    'ADMIN_UPLOAD_MAX'  => 3145728, // 最大3M
    'ADMIN_ALLOW_EXTS'  => array('jpg', 'gif', 'png', 'jpeg') // 允许上传的附件类型
);