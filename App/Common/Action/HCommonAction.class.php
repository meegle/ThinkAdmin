<?php
namespace Common\Action;
use Think\Action;
use Think\Controller;
class HCommonAction extends Action {
    //...
    public function _initialize() {
        $this->setting = get_global_setting();
    }
}