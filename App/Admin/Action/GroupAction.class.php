<?php

/**
* 管理组
*/
namespace Admin\Action;
use Common\Action\ACommonAction;
class GroupAction extends ACommonAction {

	private $group_obj;

	public function _initialize() {
		parent::_initialize();
		$this->group_obj = D('group');
	}
	
	public function index() {
		$this->assign('list', fn_get_groupinfo());
		$this->display();
	}

	//更新缓存数据
	private function _refreshCacheData() {
		$return = $this->group_obj->order(array('displayorder'=>'DESC'))->select();
		$mgroupinfo = array();
		foreach ($return as $c) {
			$mgroupinfo[$c['id']] = $c;
		}
		F('admin_group', $mgroupinfo);
	}

	//添加/修改
    public function doSave() {
    	if (IS_POST) {
			if ($this->group_obj->create()) {
				if (I('post.id', 0, 'intval')) {
					$this->group_obj->update_time = time();
					$state = $this->group_obj->save();
					$msg = '修改';
				} else {
					$this->group_obj->create_time = time();
					$state = $this->group_obj->add();
					$msg = '添加';
				}
				if ($state) {
					$this->_refreshCacheData();
					$this->success($msg.'成功');
				} else {
					$this->error($msg.'失败');
				}
			} else {
				$this->error($this->group_obj->getError());
			}
    	}
    }

    //编辑
    public function edit() {
    	$return = array();
		$info = fn_get_groupinfo_by_id(I('get.id', 0, 'intval'));
		if (empty($info)) {
			$return['status'] = 0;
			$return['info'] = '数据不存在';
		} else {
			$return['status'] = 1;
			$return['data'] = $info;
		}
		exit(json_encode($return));
    }

	//删除
    public function doDel() {
    	$return = array();
		$id = I('post.id', 0, 'intval');
		if ($this->group_obj->where(array('id'=>$id))->delete()) {
			$return['status'] = 1;
			$return['id'] = $id;
			$return['info'] = '删除成功';
            $this->_refreshCacheData();
        } else {
            $return['status'] = 0;
            $return['info'] = '删除失败';
		}
		
		exit(json_encode($return));
    }

    //----- 权限控制 start -----
    /**
     * 授权
     */
    public function access() {
        $access_obj = D('Access');
        if (IS_POST) {
    		$groupid = I('post.id', 0, 'intval');
    		if (!$groupid) {
    			$this->error('需要授权的管理组不存在');
    		}
    		if (is_array($_POST['menuid']) && count($_POST['menuid']) > 0) {
    			//取得菜单数据
    			$menuinfo = fn_get_menuinfo();
    			$addauthorize = array();
    			//检测数据合法性
    			foreach ($_POST['menuid'] as $menuid) {
    				$info = array();
    				$info = $this->_get_menuinfo(intval($menuid), $menuinfo);
    				if ($info == false) {
    					continue;
    				}
                    $info['group_id'] = $groupid;
                    $data = $access_obj->create($info);
                    if (!$data) {
                        $this->error($access_obj->getError());
                    } else {
                        array_push($addauthorize, $data);
                    }
    			}
    			if ($access_obj->access_authorize($groupid, $addauthorize)) {
    				$this->success('授权成功', U('admin/group/access', array('id' => $groupid)));
    			} else {
    				$this->error('授权失败');
    			}
    		} else {
    			//当没有数据时，清除当前角色授权
    			$access_obj->where(array('group_id' => $groupid))->delete();
    			$this->error('没有接收到数据，执行清除授权成功');
    		}
    	} else {
	        $groupid = I('get.id', 0, 'intval');
	        if (!$groupid) {
	        	$this->error('参数错误');
	        }
	        $tree = new \Org\Util\Tree();
	        $tree->icon = array('│ ', '├─ ', '└─ ');
	        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
	        $result = fn_get_menuinfo(NULL, 1);
	        $access_data = $access_obj->where(array('group_id' => $groupid))->select();
	        foreach ($result as $n => $t) {
	        	$result[$n]['checked'] = ($this->_is_checked($t, $groupid, $access_data)) ? ' checked' : '';
	        	$result[$n]['level'] = $this->_get_level($t['id'], $result);
	        	$result[$n]['parentid_node'] = ($t['parentid']) ? ' class="child-of-node-' . $t['parentid'] . '"' : '';
	        }
	        $str = "<tr id='node-\$id' \$parentid_node>
            <td>\$spacer<label class='inline'><input type='checkbox' class='checkboxes' name='menuid[]' value='\$id' level='\$level' \$checked onclick='javascript:checknode(this);'>&nbsp;\$title</label></td>
	    			</tr>";
	        $tree->init($result);
	        $categorys = $tree->get_tree(0, $str);
	        
	        $this->assign('categorys', $categorys);
	        $this->assign('groupid', $groupid);
	        $this->display();
    	}
    }

    /**
     *  检查指定菜单是否有权限
     * @param array $data menu表中数组
     * @param int $groupid 需要检查的组ID
     */
    private function _is_checked($data, $groupid, $priv_data) {
    	if ($data['group'] == '') {
    		return false;
    	}
    	$mdata['group_id'] = $groupid;
    	$mdata['g'] = $data['group'];
    	$mdata['m'] = $data['model'];
    	$mdata['a'] = $data['action'];
    	$info = in_array($mdata, $priv_data);
    	if ($info) {
    		return true;
    	} else {
    		return false;
    	}
    }

    /**
     * 获取菜单表信息
     * @param int $menuid 菜单ID
     * @param int $menu_info 菜单数据
     */
    private function _get_menuinfo($menuid, $menu_info) {
        $info = $menu_info[$menuid];
        if(!$info){
            return false;
        }
        $return['g'] = $info['group'];
        $return['m'] = $info['model'];
        $return['a'] = $info['action'];
        return $return;
    }
    //----- 权限控制 end -----

}

?>