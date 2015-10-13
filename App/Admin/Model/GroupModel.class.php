<?php
namespace Admin\Model;
use Think\Model;
class GroupModel extends Model {

    protected $tableName = 'group';

    protected $_validate = array(
        array('name','require','名称不能为空'),
        array('name', '', '名称已经存在', self::MUST_VALIDATE, 'unique', self::MODEL_BOTH)
    );

    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('update_time', NOW_TIME, self::MODEL_UPDATE)
    );

    protected $insertFields = array('name', 'status', 'remark');

    protected $updateFields = array('id', 'name', 'status', 'remark', 'displayorder');

}