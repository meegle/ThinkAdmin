<?php
/**
 * 广告管理
 */
namespace Admin\Action;
use Common\Action\ACommonAction;
class AdAction extends ACommonAction {
    private $ad_obj;

    public function _initialize() {
        parent::_initialize();
        $this->ad_obj = D('ad');
    }

    public function index() {
        $field = 'id,ad_content,start_time,end_time,create_time,ad_title';
        $list = $this->ad_obj->field($field)->order('id DESC')->select();

        $this->assign('list', $list);
        $this->display();
    }

    //添加
    public function add() {
        if (IS_POST) {
            $this->_save();
        } else {
            $this->assign('display_title', '添加');
            $this->display();
        }
    }

    //编辑
    public function edit() {
        if (IS_POST) {
            $this->_save();
        } else {
            $id = I('get.id', 0, 'intval');
            $info = $this->ad_obj->find($id);

            if (empty($info)) {
                $this->error('数据不存在');
            }

            $this->assign('info', $info);
            $this->assign('display_title', '修改');
            $this->display('add');
        }
    }

    //保存
    private function _save() {
        if (false === $this->ad_obj->create()) {
            $this->error($this->ad_obj->getError());
        }
        //数据校验
        if (empty($this->ad_obj->ad_type)) {
            $this->error('请选择广告类型！');
        }
        if (empty($this->ad_obj->ad_title)) {
            $this->error('请填写标题！');
        }
        $this->ad_obj->start_time = strtotime($this->ad_obj->start_time);
        $this->ad_obj->end_time = strtotime($this->ad_obj->end_time);
        
        if (!empty($this->ad_obj->id)) {//修改
            $result = $this->ad_obj->save();
            $msg = '修改';
        } else {//新增
            $this->ad_obj->create_time = time();
            $result = $this->ad_obj->add();
            $msg = '新增';
        }
        if ($result) {
            $this->success($msg.'成功', U('admin/ad/index'));
        } else {
            $this->error($msg.'失败');
        }
    }

    //删除
    public function doDel() {
        if (IS_POST) {
            $id = I('post.id', 0, 'intval');

            $return = array();
            if ($this->ad_obj->where(array('id'=>$id))->delete()) {
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

    //文件上传
    public function swfUpload() {
        if (IS_POST) {
            //上传处理
            $upload = new \Think\Upload(array(
                'maxSize'     => C('ADMIN_UPLOAD_MAX'),
                'rootPath'     => './',
                'savePath'     => 'ad/',
                'saveName'     => date('YmdHis').rand(0, 1000),
                'exts'         => C('ADMIN_ALLOW_EXTS'),
                'autoSub'     => false
            ), 'Ftp', FS('Apiconfig/ftpconfig')); // 实例化上传类
            $info = $upload->upload();
            if (!$info) {
                $this->error($upload->getError(), '', true);
            } else {
                //保存数据
                $first = array_shift($info);
                $aid = M('file')->add(array(
                    'filegroup'     => 'ad',
                    'dataid'         => I('post.id', 0, 'intval'),
                    'filename'         => $first['name'],
                    'fileext'         => $first['ext'],
                    'filesize'         => $first['size'],
                    'filepath'         => $this->setting['ftp_path'].$first['savepath'].$first['savename'],
                    'status'         => 1,
                    'uid'             => $this->admin_id,
                    'create_time'     => time()
                ));
                if ($aid) {
                    $this->success('<span style="color:green;">['.$first['name'].']上传成功</span>', '', true);
                } else {
                    $this->error('<span style="color:red;">['.$first['name'].']上传失败</span>', '', true);
                }
            }
        }
    }

    //广告图片列表
    public function file_list() {
        $dataid = I('get.dataid', 0, 'intval');
        $list = M('file')->where(array('filegroup'=>'ad', 'dataid'=>$dataid))->select();
        if (!empty($list)) {
            foreach ($list as $k => $v) {
                if (empty($v['extend'])) {
                    $v['extend'] = array(
                        'sm' => '',
                        'lj' => '',
                        'px' => 0
                    );
                } else {
                    $v['extend'] = unserialize($v['extend']);
                }
                $list[$k] = $v;
            }
        }
        $return = $list ? $list : array();

        echo json_encode($return);
        exit;
    }

    //广告图片操作
    public function file_operation() {
        if (IS_POST) {
            $op = I('get.op');
            $model = M('file');
            switch ($op) {
                case 'delete'://删除
                    $idstr = '';
                    $filearr = array();
                    $list = $model->field('id,filepath')->where('id in ('.join(',', $_POST['ids']).')')->select();
                    foreach ($list as $k => $v) {
                        $idstr = $idstr.($k?',':'').$v['id'];
                        array_push($filearr, $v['filepath']);
                    }
                    unset($list);
                    if ($model->where('id in ('.$idstr.')')->delete()) {
                        foreach ($filearr as $f) {
                            @unlink(__ROOT__.C('UPLOAD_PATH').'ad/'.$f);
                        }
                        $this->_ajaxReturn(array('info'=>'删除成功', 'status'=>1, 'script'=>'loadFileList()'));
                    } else {
                        $this->_ajaxReturn(array('info'=>'删除失败', 'status'=>0));
                    }
                    break;
                case 'status'://修改状态
                    $status = I('get.status', '', 'intval');
                    if (isset($_POST['ids']) && in_array($status, array(0, 1))) {
                        if ($model->where('id in ('.join(',', $_POST['ids']).')')->save(array('status'=>$status))!==false) {
                            if (0 === $status) {
                                $msg = '锁定';
                            } elseif(1 === $status) {
                                $msg = '正常';
                            }
                            $this->_ajaxReturn(array('info'=>'操作['.$msg.']成功！', 'status'=>1, 'script'=>'loadFileList()'));
                        } else {
                            $this->_ajaxReturn(array('info'=>'操作失败', 'status'=>0));
                        }
                    }
                    break;
                case 'save'://排序
                    foreach ($_POST['px'] as $k => $v) {
                        $extend = array(
                            'sm' => $_POST['sm'][$k],
                            'lj' => $_POST['lj'][$k],
                            'px' => $_POST['px'][$k]
                        );
                        $extend = serialize($extend);
                        $model->where('id='.$k)->save(array('extend'=>$extend));
                    }
                    $this->_ajaxReturn(array('info'=>'保存成功！', 'status'=>1, 'script'=>'loadFileList()'));
                    break;
                default:
                    $this->error('无效操作');
                    break;
            }
        }
    }
}
?>