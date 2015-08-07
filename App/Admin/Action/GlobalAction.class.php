<?php

/**
* 全局操作
*/
namespace Admin\Action;
use Common\Action\ACommonAction;
class GlobalAction extends ACommonAction {

    //清空全部缓存
    public function cleanall() {
        $dir = APP_PATH.'Runtime';
        rmdirr($dir);
        @mkdir($dir, 0777, true);
        $this->success($dir.'目录下缓存清除成功!');
    }

    public function cleandata() {
        $dir = APP_PATH.'Runtime/Data';
        rmdirr($dir);
        @mkdir($dir, 0777, true);
        $this->success($dir.'目录下缓存清除成功!');
    }

    public function cleantpl() {
        $cache = I('get.cache', 0, 'intval');
        
        switch ($cache) {
            case 1://前台
                $dir = APP_PATH.'Runtime/Cache/Home';
                break;
            case 2://后台
                $dir = APP_PATH.'Runtime/Cache/Admin';
                break;
            default:
                $this->error('操作不存在');
                break;
        }
        rmdirr($dir);
        @mkdir($dir, 0777, true);
        $this->success($dir.'目录下缓存清除成功!');
    }
}

?>