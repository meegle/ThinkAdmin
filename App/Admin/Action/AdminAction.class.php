<?php

/**
* 管理员
*/
namespace Admin\Action;
use Common\Action\ACommonAction;
class AdminAction extends ACommonAction {
    
    private $admin_obj;

    public function _initialize() {
        parent::_initialize();
        $this->admin_obj = D('admin');
    }

    public function index() {
        $list = $this->admin_obj->order('id DESC')->select();
        $this->assign('list', $list);

        $this->display();
    }

    //添加/修改
    private function _save() {
        if ($this->admin_obj->create()) {
            //数据校验
            if (trim($this->admin_obj->username) == '') {
                $this->error('请输入管理员用户名');
            }
            if (empty($this->admin_obj->group_id)) {
                $this->error('请选择所属管理组');
            }
            if (I('post.id', 0, 'intval')) {//修改
                //修改密码
                if (empty($this->admin_obj->password)) {
                    unset($this->admin_obj->password);
                } else {
                    $this->admin_obj->password = md5($this->admin_obj->password);
                }
                $state = $this->admin_obj->save();
                $msg = '修改';
            } else {//添加
                $this->admin_obj->create_time = time();
                if (empty($this->admin_obj->password)) {
                    $this->error('请输入管理员密码');
                }
                $this->admin_obj->password = md5($this->admin_obj->password);
                $state = $this->admin_obj->add();
                $msg = '添加';
            }
            if ($state) {
                $this->success($msg.'成功', U('admin/admin/index'));
            } else {
                $this->error($msg.'失败');
            }
        } else {
            $this->error($this->admin_obj->getError());
        }
    }

    //编辑
    public function edit() {
        if (IS_POST) {
            $this->_save();
        } else {
            $id = I('get.id', 0, 'intval');
            $this->assign('info', $this->admin_obj->where('id='.$id)->find());

            $this->display('ajax_add');
        }
    }

    //新增
    public function add() {
        if (IS_POST) {
            $this->_save();
        } else {
            $this->display('ajax_add');
        }
    }

    //删除
    public function doDel() {
        if (IS_POST) {
            $id = I('post.id', 0, 'intval');

            $return = array();
            if ($this->admin_obj->where(array('id'=>$id))->delete()) {
                $return['status'] = 1;
                $return['id'] = $id;
                $return['info'] = '删除成功';
            } else {
                $return['status'] = 0;
                $return['info'] = '删除失败';
            }
            exit(json_encode($return));
        }
    }

}

?>