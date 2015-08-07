<?php

/**
* 后台管理主页
*/
namespace Admin\Action;
use Common\Action\ACommonAction;
Class IndexAction extends ACommonAction {
    public function index() {
        $Model = new \Think\Model();
        $mysql = $Model->query('SELECT VERSION() as version');
        $mysql = array_shift($mysql);
        $mysql = $mysql['version'];
        $mysql = empty($mysql) ? '未知' : $mysql;
        //服务器信息
        $info = array(
            '操作系统'      => PHP_OS,
            '运行环境'      => $_SERVER['SERVER_SOFTWARE'],
            'PHP运行方式'   => php_sapi_name(),
            'MYSQL版本'     => $mysql,
            '上传附件限制'  => ini_get('upload_max_filesize'),
            '执行时间限制'  => ini_get('max_execution_time').'秒',
            '剩余空间'      => round((@disk_free_space('.') / (1024 * 1024)), 2).'M',
        );
        $this->assign('server_info', $info);
        $this->display();
    }

    public function verify() {
        // ob_end_clean();
        $verify = new \Think\Verify();
        $verify->entry();
    }

    public function login() {
        if ($this->admin_id) {
            $this->redirect('index');
            exit;
        }
        if (IS_POST) {
            $verify = new \Think\Verify();
            if (!$verify->check(I('post.code'))) {
                $this->error('验证码错误');
            }
            $info = M('admin')->field('id')->where(array(
                'username' => I('post.admin_name'),
                'password' => md5(I('post.admin_pass'))
            ))->find();

            if (is_array($info) && count($info) > 0) {
                session('admin_id', $info['id']);
                M('admin')->where(array('id'=>$info['id']))->save(array(
                    'last_login_ip' => get_client_ip(),
                    'last_login_time' => time()
                ));
                $this->success('登陆成功', U('admin/index/index'));
            } else {
                $this->error('用户名或密码错误，登陆失败');
            }
        } else {
            $this->display();
        }
    }

    public function logout() {
        session(null);
        $this->success('注销成功', U('admin/index/login'));
    }
}
?>