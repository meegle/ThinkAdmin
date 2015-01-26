<?php
require_once APP_PATH.'Common/Common/lib.php';

//获取广告内容
function fn_get_ad($id) {
    $cache_name = 'ad_'.$id;
    $return = F($cache_name);
    if (empty($return)) {
        $return = M('ad')->where(array('id'=>$id))->getField('ad_content');
        F($cache_name, $return);
    }
    return $return;
}
?>