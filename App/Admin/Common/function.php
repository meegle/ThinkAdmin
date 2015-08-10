<?php

/**
 * 获取管理组数据
 * @return $return 返回数据
 */
function fn_get_groupinfo() {
    $return = F('admin_group');
    if (empty($return)) {
        $return = M('group')->order('displayorder DESC')->select();
        $mgroupinfo = array();
        foreach ($return as $c) {
            $mgroupinfo[$c['id']] = $c;
        }
        F('admin_group', $mgroupinfo);
        $return = $mgroupinfo;
    }
    return $return;
}

/**
 * 通过管理组ID获取数据
 * @param $id int 管理组ID
 * @param $field string 通过设置$field参数返回单字段数据
 * @return $return 返回数据
 */
function fn_get_groupinfo_by_id($id, $field = '') {
    $groupinfo = fn_get_groupinfo();
    if (array_key_exists($field, $groupinfo[$id])) {
        $return = $groupinfo[$id][$field];
    } else {
        $return = $groupinfo[$id];
    }
    return $return;
}

/**
 * 获取菜单数据
 * @param $status int 显示状态，1显示，0隐藏
 * @param $type int 类型，1参与权限控制，0只作为菜单
 * @return $return 返回数据
 */
function fn_get_menuinfo($status = NULL, $type = NULL) {
    $cache_name = 'admin_menu';
    $cache_name .= $status === NULL ? '' : '_status_'.$status;
    $cache_name .= $type === NULL ? '' : '_type_'.$type;
    $return = F($cache_name);
    if (empty($return)) {
        $where = array();
        if (!is_null($status)) {
            array_push($where, array('status' => $status));
        }
        if (!is_null($type)) {
            array_push($where, array('type' => $type));
        }
        $return = M('menu')->where($where)->order('displayorder ASC')->select();
        $m = array();
        foreach ($return as $c) {
            $m[$c['id']] = $c;
        }
        F($cache_name, $m);
        $return = $m;
    }
    return $return;
}

/**
 * 通过菜单ID获取数据
 * @param $id int 菜单ID
 * @param $field string 通过设置$field参数返回单字段数据
 * @return $return 返回数据
 */
function fn_get_menuinfo_by_id($id, $field = '') {
    $menuinfo = fn_get_menuinfo();
    if (array_key_exists($field, $menuinfo[$id])) {
        $return = $menuinfo[$id][$field];
    } else {
        $return = $menuinfo[$id];
    }
    return $return;
}

/**
 * 获取后台管理菜单
 * @param $parentid int 父ID
 * @param $tree object 树结构对象
 * @return $html string 返回菜单HTML
 */
function fn_get_menu_html($parentid = 0, &$tree = NULL) {
    $html = '';
    if (is_null($tree)) {
        $tree = new Org\Util\Tree();
        $tree->init(fn_get_menuinfo(1));
    }
    $menuArr = $tree->get_tree_array($parentid, '');
    $count = count($menuArr);

    $i = 1;
    foreach ($menuArr as $v) {
        if ($v['status'] == 0) continue;//隐藏菜单

        $group_id = M('admin')->where(array('id'=>session('admin_id')))->getField('group_id');
        if ($group_id != 1) {
            //判断权限
            if (!M('Menu')->where(array('group'=>$v['group'], 'model'=>$v['model'], 'action'=>$v['action'], 'type'=>0))->count()) {
                if (!M('Access')->where('group_id=\''.$group_id.'\' and g=\''.$v['group'].'\' and m=\''.$v['model'].'\' and a=\''.$v['action'].'\'')->count()) continue;
            }
        }

        $noChild = empty($v['child']);
        if ($noChild) {
            $dataArr = array();
            if (!empty($v['data'])) {//设置参数
                $dataArr = explode('&', $v['data']);
                $keyArr = array();
                $valArr = array();
                foreach ($dataArr as $d_k => $d_v) {
                    list($keyArr[$d_k], $valArr[$d_k]) = explode('=', $d_v);
                }
            }
            $url = U(strtolower($v['group'].'/'.$v['model'].'/'.$v['action']), array_combine($keyArr, $valArr));
            unset($dataArr, $keyArr, $valArr);
        }

        $html .= '<li';
        if ($parentid === 0) {
            if ($i === 1) {
                $html .= ' class="start"';
            } elseif ($i === $count) {
                $html .= ' class="last"';
            }
        }
        $html .= '>';
        $html .= '<a href="';
        if ($noChild) {
            $html .= $url;
        } else {
            $html .= 'javascript:;';
        }
        $html .= '">';
        if (!empty($v['icon'])) $html .= '<i class="'.$v['icon'].'"></i>';
        $html .= '<span class="title">'.$v['title'].'</span>';
        $html .= '<span class="';
        if ($noChild) {
            // $html .= 'selected';
        } else {
            $html .= 'arrow';
        }
        $html .= '">';
        $html .= '</span>';
        $html .= '</a>';
        if ($noChild) {
            $html .= '<span class="hide">'.$url.'</span>';
        }
        //sub-menu start
        if (!$noChild) {
            $html .= '<ul class="sub-menu">';
            $html .= fn_get_menu_html($v['id'], $tree);
            $html .= '</ul>';
        }
        //sub-menu end
        $html .= '</li>';
        $i++;
    }
    return $html;
}

/**
 * 根据类型获取产品分类
 * @param $productType int 类型
 * @return $return
 */
function fn_get_typeinfo_by_type($productType) {
    $return = F('admin_type_'.$productType);
    if (empty($return)) {
        $return = M('type')->where(array('productType'=>$productType))->order('id ASC')->select();
        $mtypeinfo = array();
        foreach ($return as $c) {
            $mtypeinfo[$c['id']] = $c;
        }
        F('admin_type_'.$productType, $mtypeinfo);
        $return = $mtypeinfo;
    }
    return $return;
}

/**
 * 导出数据为excel表格
 * @param $data    一个二维数组,结构如同从数据库查出来的数组
 * @param $title   excel的第一行标题,一个数组,如果为空则没有标题
 * @param $filename 下载的文件名
 * @examlpe
 * $stu = M('User');
 * $arr = $stu->select();
 * fn_export_excel($arr, array('id', '账户', '密码', '昵称'), '文件名!');
 */
function fn_export_excel($data = array(), $title = array(), $filename = 'report') {
    header("Content-type:application/octet-stream");
    header("Accept-Ranges:bytes");
    header("Content-type:application/vnd.ms-excel;charset=utf-8");
    header("Content-Disposition:attachment;filename=".$filename.".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    //导出开始
    if (!empty($title)) {
        foreach ($title as $k => $v) {
            $title[$k] = $v;
        }
        $title = implode("\t", $title);
        echo "$title\n";
    }
    if (!empty($data)) {
        foreach ($data as $key => $val) {
            foreach ($val as $ck => $cv) {
                $data[$key][$ck] = $cv;
            }
            $data[$key] = implode("\t", $data[$key]);
        }
        echo implode("\n", $data);
    }
}

?>