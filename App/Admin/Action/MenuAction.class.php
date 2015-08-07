<?php

/**
* 菜单管理
*/
namespace Admin\Action;
use Common\Action\ACommonAction;
class MenuAction extends ACommonAction {

    private $menu_obj;

    public function _initialize() {
        parent::_initialize();
        $this->menu_obj = D('menu');
    }

    //更新缓存数据
    private function _refreshCacheData() {
        $return = $this->menu_obj->order(array('displayorder'=>'ASC'))->select();
        $m = array();
        foreach ($return as $c) {
            $m[$c['id']] = $c;
        }
        F('admin_menu', $m);
    }
    
    public function index() {
        $tree = new \Org\Util\Tree();
        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        
        $result = fn_get_menuinfo();

        $newmenus = array();
        foreach ($result as $m) {
            $newmenus[$m['id']] = $m;
        }
        foreach ($result as $n => $r) {
            $result[$n]['level'] = $this->_get_level($r['id'], $newmenus);
            $result[$n]['parentid_node'] = ($r['parentid']) ? ' class="child-of-node-'.$r['parentid'].'"' : '';

            $result[$n]['str_manage'] = '<a class="btnModal" data-title="添加子菜单" href="'.U('admin/menu/add', array('parentid'=>$r['id'])).'">添加子菜单</a>&nbsp;|&nbsp;<a class="btnModal" data-title="编辑菜单" href="'.U('admin/menu/edit', array('id'=>$r['id'])).'">修改</a>&nbsp;|&nbsp;<a class="btnDelete" href="#" data-id="'.$r['id'].'">删除</a> ';
            $result[$n]['status'] = $r['status'] ? '<span style="color:green">显示</span>' : '<span style="color:red">隐藏</span>';
            $result[$n]['app'] = $r['group'].'/'.$r['model'].'/'.$r['action'];
        }

        $tree->init($result);
        $str = "<tr id='node-\$id' \$parentid_node>
                    <td><input name='displayorder[\$id]' type='text' size='3' value='\$displayorder' style='width:30px;'></td>
                    <td>\$id</td>
                    <td>\$app</td>
                    <td>\$spacer\$title</td>
                    <td>\$status</td>
                    <td>\$str_manage</td>
                </tr>";
        $categorys = $tree->get_tree(0, $str);
        $this->assign('categorys', $categorys);

        $this->display();
    }

    private function _getMenuTree($parentid) {
        $arr = array();
        $tree = new \Org\Util\Tree();
        $result = fn_get_menuinfo();
        foreach ($result as $k => $r) {
            $r['selected'] = $k == $parentid ? 'selected' : '';
            array_push($arr, $r);
        }
        $str = "<option value='\$id' \$selected>\$spacer \$title</option>";
        $tree->init($arr);
        $select_categorys = $tree->get_tree(0, $str);
        $this->assign('select_categorys', $select_categorys);
    }

    //添加/修改
    private function _save() {
        if ($this->menu_obj->create()) {
            //数据校验
            if (trim($this->menu_obj->title) == '') {
                $this->error('请输入名称');
            }
            if (trim($this->menu_obj->group) == '') {
                $this->error('请输入群组');
            }
            if (trim($this->menu_obj->model) == '') {
                $this->error('请输入模块');
            }
            if (trim($this->menu_obj->action) == '') {
                $this->error('请输入方法');
            }
            if (I('post.id', 0, 'intval')) {//修改
                $state = $this->menu_obj->save();
                $msg = '修改';
            } else {//添加
                $state = $this->menu_obj->add();
                $msg = '添加';
            }
            if ($state) {
                $this->_refreshCacheData();
                $this->success($msg.'成功', U('admin/menu/index'));
            } else {
                $this->error($msg.'失败');
            }
        } else {
            $this->error($this->menu_obj->getError());
        }
    }

    //编辑
    public function edit() {
        if (IS_POST) {
            $this->_save();
        } else {
            $id = I('get.id', 0, 'intval');
            $info = fn_get_menuinfo_by_id($id);
            $this->_getMenuTree($info['parentid']);
            $this->assign('info', $info);

            $this->display('ajax_add');
        }
    }

    //新增
    public function add() {
        if (IS_POST) {
            $this->_save();
        } else {
            $parentid = I('get.parentid', 0, 'intval');
            $this->_getMenuTree($parentid);
            $this->display('ajax_add');
        }
    }

    //删除
    public function doDel() {
        if (IS_POST) {
            $return = array();
            $id = I('post.id', 0, 'intval');
            $count = $this->menu_obj->where(array('parentid'=>$id))->count();
            if ($count > 0) {
                $return['status'] = 0;
                $return['info'] = '该菜单下还有子菜单，无法删除！';
            } else {
                if ($this->menu_obj->where(array('id'=>$id))->delete()) {
                    $return['status'] = 1;
                    $return['id'] = $id;
                    $return['info'] = '删除成功！';
                    $this->_refreshCacheData();
                } else {
                    $return['status'] = 0;
                    $return['info'] = '删除失败！';
                }
            }
            exit(json_encode($return));
        }
    }

    //排序
    public function displayorder() {
        if (IS_POST) {
            $ids = $_POST['displayorder'];
            foreach ($ids as $k => $v) {
                $this->menu_obj->where(array('id'=>$k))->save(array('displayorder'=>$v));
            }
            $this->_refreshCacheData();
            $this->success('更新成功', U('admin/menu/index'));
        }
    }
}

?>