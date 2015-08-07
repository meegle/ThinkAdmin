<?php

/**
* 网站设置
*/
namespace Admin\Action;
use Common\Action\ACommonAction;
class SettingAction extends ACommonAction {

    private $setting_obj;

    public function _initialize() {
        parent::_initialize();
        $this->setting_obj = D('setting');
    }

    public function index() {
        $list = $this->setting_obj->order(array('displayorder'=>'DESC'))->select();
        $this->assign('list', de_xie($list));

        $this->display();
    }

    //更新缓存数据
    private function _refreshCacheData() {
        $list = array();
        $list_t = $this->setting_obj->field('code, text')->select();
        foreach ($list_t as $v) {
            $list[$v['code']] = de_xie($v['text']);
        }
        F('global_setting', $list);
    }

    //添加
    public function doAdd() {
        if ($this->setting_obj->create()) {
            if ($this->setting_obj->add()) {
                $this->_refreshCacheData();
                $this->success('修改成功');
            } else {
                $this->error('修改失败');
            }
        } else {
            $this->error($this->setting_obj->getError());
        }
    }

    //编辑
    public function doEdit() {
        $data = $_POST;
        foreach ($data as $k => $v) {
            if (is_numeric($k)) $this->setting_obj->where(array('id'=>$k))->setField('text', $v);
        }
        $this->_refreshCacheData();
        $this->success('更新成功');
    }

    //删除
    public function doDel() {
        $return = array();
        $data = $_POST;
        if (M('setting')->where('id = \''.$data['id'].'\'')->delete()) {
            $this->_refreshCacheData();
            $return['status'] = 1;
            $return['id'] = $data['id'];
            $return['info'] = '删除成功';
        } else {
            $return['status'] = 0;
            $return['info'] = '删除失败';
        }
        
        exit(json_encode($return));
    }

    //修改管理员密码
    public function password() {
        if (IS_POST) {
            if (empty($_POST['old_password'])) {
                $this->error('原始密码不能为空！');
            }
            if (empty($_POST['password'])) {
                $this->error('新密码不能为空！');
            }
            if ($_POST['password'] != $_POST['repassword']) {
                $this->error('密码输入不一致！');
            }

            $admin_obj = D('admin');
            $old_password = $_POST['old_password'];
            $password = $_POST['password'];
            if (md5($old_password) == $this->admin['password']) {
                if ($this->admin['password'] == md5($password)) {
                    $this->error('新密码不能和原始密码相同！');
                } else {
                    $data = array();
                    $data['password'] = md5($password);
                    $data['id'] = $this->admin_id;
                    if ($admin_obj->save($data) !== false) {
                        $this->success('修改成功！');
                    } else {
                        $this->error('修改失败！');
                    }
                }
            } else {
                $this->error('原始密码不正确！');
            }
        } else {
            $this->display();
        }
    }
}

?>