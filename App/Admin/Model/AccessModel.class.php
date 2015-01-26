<?php
namespace Admin\Model;
use Think\Model;
class AccessModel extends Model {

    /**
     * 授权
     * @param type $groupid
     * @param type $addauthorize array(0=>array(...))
     * @return boolean
     */
    public function access_authorize($groupid, $addauthorize) {
        if(!$groupid || !$addauthorize || !is_array($addauthorize)){
            return false;
        }
        //删除旧的权限
        $this->where(array('group_id' => $groupid))->delete();
        return $this->addAll($addauthorize);
    }
}

?>