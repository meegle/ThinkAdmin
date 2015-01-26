<?php

/**
* 接口配置
*/
namespace Admin\Action;
use Common\Action\ACommonAction;
class ApiAction extends ACommonAction {
	
	public function index() {
        if (IS_POST) {
            $action = I('post.action', '', 'trim');
            switch ($action) {
                case 'mail':
                    FS('mailconfig', $_POST['mail'], 'Apiconfig/');
                    $this->success('操作成功', U('admin/api/index'));
                    break;
                case 'alipay':
                    FS('alipayconfig', $_POST['alipay'], 'Apiconfig/');
                    $this->success('保存成功', U('admin/api/index'));
                    break;
                case 'ftp':
                    FS('ftpconfig', $_POST['ftp'], 'Apiconfig/');
                    $this->success('保存成功', U('admin/api/index'));
                    break;
                default:
                    $this->error('无效操作');
                    break;
            }
        } else {
            //邮箱配置
            $apiconfig = FS('Apiconfig/mailconfig');
            $this->assign('stmp_config', $apiconfig['stmp']);
            //支付宝配置
            $this->assign('alipay_config', FS('Apiconfig/alipayconfig'));
            //FTP上传配置
            $this->assign('ftp_config', FS('Apiconfig/ftpconfig'));
            $this->display();
        }
	}
}

?>