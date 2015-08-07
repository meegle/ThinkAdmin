<?php
/**
* 前台主页
*/
namespace Home\Action;
use Common\Action\HCommonAction;
Class IndexAction extends HCommonAction {
    public function index() {
        $this->display();
    }
}