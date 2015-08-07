<?php
namespace Common\Action;
use Think\Action;
use Think\Controller;
class ACommonAction extends Action {
    var $admin_id = 0;
    var $admin = NULL;
    var $pre = NULL;
    //身份验证
    public function _initialize() {
        $this->pre = C('DB_PREFIX');
        if (session('admin_id')) {
            $this->admin_id = session('admin_id');
            $this->admin = D('Admin')->where('id='.$this->admin_id)->find();
            //判断状态
            if (!$this->admin['status']) {
                $this->error('账号已被锁定，无法登陆！');
            }
            //判断权限
            if (!$this->check_access($this->admin['group_id'])) {
                $this->error('您没有访问权限！');
                exit;
            }
            $this->assign('admin', $this->admin);
        } elseif (!in_array(strtolower(ACTION_NAME), array('verify', 'login'))) {
            redirect(U('admin/index/login'));
            exit;
        }

        $this->setting = get_global_setting();
    }

    //用户组的权限判断
    private function check_access($group_id) {
        $g = MODULE_NAME;
        $m = CONTROLLER_NAME;
        $a = ACTION_NAME;
        //如果用户组是1，即超级管理员组，则无需判断
        if ($group_id == 1) return true;

        $info = D('Group')->field('status')->where('id='.$group_id)->find();
        if (!empty($info) && $info['status'] == 1) {
            if (M('Menu')->where(array('group'=>$g, 'model'=>$m, 'action'=>$a, 'type'=>0))->count()) {//只作为菜单
                $count = true;
            } else {//参与权限控制
                $count = M('Access')->where('group_id=\''.$group_id.'\' and g=\''.$g.'\' and m=\''.$m.'\' and a=\''.$a.'\'')->count() ? true : false;
            }
            return $count;
        } else {
            return false;
        }
    }

    //json返回数据
    protected function _ajaxReturn($data) {
        $return = array();
        $return['info']   = $data['info'];
        $return['status'] = $data['status'];
        $return['script'] = $data['script'];

        // 返回JSON数据格式
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($return));
    }

    /**
     * 获取菜单深度
     * @param $id
     * @param $array
     * @param $i
     */
    protected function _get_level($id, $array = array(), $i = 0) {
        if ($array[$id]['parentid'] == 0 || empty($array[$array[$id]['parentid']]) || $array[$id]['parentid'] == $id) {
            return  $i;
        } else {
            $i++;
            return $this->_get_level($array[$id]['parentid'], $array, $i);
        }
    }

}

?>