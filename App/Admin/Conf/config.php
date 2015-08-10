<?php
return array(
    //'配置项'=>'配置值'
    'ADMIN_CACHE_TIME'  => '3600', // 后台数据缓存时间，以秒为单位
    'ADMIN_PAGE_SIZE'   => 10, // 后台分页

    'TMPL_PARSE_STRING' => array(
        '../Public' => __ROOT__.'/Public/Admin'
    ),

    //后台上传配置
    'ADMIN_UPLOAD_MAX'  => 3145728, // 最大3M
    'ADMIN_ALLOW_EXTS'  => array('jpg', 'gif', 'png', 'jpeg') // 允许上传的附件类型
);