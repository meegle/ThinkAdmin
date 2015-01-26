/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50611
Source Host           : localhost:3306
Source Database       : mysite

Target Server Type    : MYSQL
Target Server Version : 50611
File Encoding         : 65001

Date: 2015-01-09 11:31:43
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ta_menu
-- ----------------------------
DROP TABLE IF EXISTS `ta_menu`;
CREATE TABLE `ta_menu` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '上级ID',
  `group` char(20) NOT NULL DEFAULT '' COMMENT '群组',
  `model` char(20) NOT NULL DEFAULT '' COMMENT '模块',
  `action` char(20) NOT NULL DEFAULT '' COMMENT '方法',
  `data` char(50) NOT NULL DEFAULT '' COMMENT '参数',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型：1参与权限控制，0只作为菜单',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态：1显示，0隐藏',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `icon` varchar(50) DEFAULT NULL COMMENT '图标',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `displayorder` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `parentid` (`parentid`),
  KEY `model` (`model`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ta_menu
-- ----------------------------
INSERT INTO `ta_menu` VALUES ('1', '0', 'Admin', 'Setting', 'default', '', '1', '1', '设置', 'icon-cogs', '', '2');
INSERT INTO `ta_menu` VALUES ('2', '1', 'Admin', 'Setting', 'password', '', '1', '1', '修改密码', '', '', '0');
INSERT INTO `ta_menu` VALUES ('3', '1', 'Admin', 'Setting', 'index', '', '1', '1', '网站设置', '', '', '0');
INSERT INTO `ta_menu` VALUES ('4', '1', 'Admin', 'Api', 'index', '', '1', '1', '信息接口', '', '', '0');
INSERT INTO `ta_menu` VALUES ('5', '1', 'Admin', 'Ad', 'index', '', '1', '1', '广告管理', '', '', '0');
INSERT INTO `ta_menu` VALUES ('6', '0', 'Admin', 'Admin', 'default', '', '1', '1', '用户管理', 'icon-user', '', '3');
INSERT INTO `ta_menu` VALUES ('7', '6', 'Admin', 'Group', 'default', '', '1', '1', '管理组', '', '', '0');
INSERT INTO `ta_menu` VALUES ('8', '7', 'Admin', 'Group', 'index', '', '1', '1', '组管理', '', '', '0');
INSERT INTO `ta_menu` VALUES ('9', '7', 'Admin', 'Admin', 'index', '', '1', '1', '管理员', '', '', '0');
INSERT INTO `ta_menu` VALUES ('10', '0', 'Admin', 'Menu', 'default', '', '1', '1', '菜单管理', 'icon-list', '', '4');
INSERT INTO `ta_menu` VALUES ('11', '10', 'Admin', 'Menu', 'index', '', '1', '1', '后台菜单', '', '', '0');
INSERT INTO `ta_menu` VALUES ('12', '0', 'Admin', 'Global', 'default', '', '1', '1', '缓存管理', 'icon-repeat', '', '7');
INSERT INTO `ta_menu` VALUES ('13', '12', 'Admin', 'Global', 'cleantpl', 'cache=1', '1', '1', '前台模板', '', '', '0');
INSERT INTO `ta_menu` VALUES ('14', '12', 'Admin', 'Global', 'cleantpl', 'cache=2', '1', '1', '后台模板', '', '', '0');
INSERT INTO `ta_menu` VALUES ('15', '12', 'Admin', 'Global', 'cleandata', '', '1', '1', '数据缓存', '', '', '0');
INSERT INTO `ta_menu` VALUES ('16', '12', 'Admin', 'Global', 'cleanall', '', '1', '1', '所有缓存', '', '', '0');
INSERT INTO `ta_menu` VALUES ('17', '0', 'Admin', 'Index', 'index', '', '0', '1', '主面板', 'icon-home', '', '1');
INSERT INTO `ta_menu` VALUES ('18', '17', 'Admin', 'Index', 'verify', '', '0', '0', '验证码', '', '', '0');
INSERT INTO `ta_menu` VALUES ('19', '17', 'Admin', 'Index', 'login', '', '0', '0', '登入后台', '', '', '0');
INSERT INTO `ta_menu` VALUES ('20', '17', 'Admin', 'Index', 'logout', '', '0', '0', '退出后台', '', '', '0');
INSERT INTO `ta_menu` VALUES ('21', '3', 'Admin', 'Setting', 'doAdd', '', '1', '0', '添加设置', '', '', '0');
INSERT INTO `ta_menu` VALUES ('22', '3', 'Admin', 'Setting', 'doEdit', '', '1', '0', '编辑设置', '', '', '0');
INSERT INTO `ta_menu` VALUES ('23', '3', 'Admin', 'Setting', 'doDel', '', '1', '0', '删除设置', '', '', '0');
INSERT INTO `ta_menu` VALUES ('24', '5', 'Admin', 'Ad', 'add', '', '1', '0', '广告添加', '', '', '0');
INSERT INTO `ta_menu` VALUES ('25', '5', 'Admin', 'Ad', 'edit', '', '1', '0', '广告编辑', '', '', '0');
INSERT INTO `ta_menu` VALUES ('26', '5', 'Admin', 'Ad', 'doDel', '', '1', '0', '广告删除', '', '', '0');
INSERT INTO `ta_menu` VALUES ('27', '25', 'Admin', 'Ad', 'swfUpload', '', '1', '0', '图片上传', '', '', '0');
INSERT INTO `ta_menu` VALUES ('28', '25', 'Admin', 'Ad', 'file_list', '', '0', '0', '图片列表', '', '', '0');
INSERT INTO `ta_menu` VALUES ('29', '25', 'Admin', 'Ad', 'file_operation', '', '1', '0', '图片操作', '', '', '0');
INSERT INTO `ta_menu` VALUES ('30', '8', 'Admin', 'Group', 'doSave', '', '1', '0', '组添加/编辑', '', '', '0');
INSERT INTO `ta_menu` VALUES ('31', '8', 'Admin', 'Group', 'doDel', '', '1', '0', '组删除', '', '', '0');
INSERT INTO `ta_menu` VALUES ('32', '8', 'Admin', 'Group', 'access', '', '1', '0', '组权限', '', '', '0');
INSERT INTO `ta_menu` VALUES ('33', '9', 'Admin', 'Admin', 'edit', '', '1', '0', '管理员编辑', '', '', '0');
INSERT INTO `ta_menu` VALUES ('34', '9', 'Admin', 'Admin', 'add', '', '1', '0', '管理员添加', '', '', '0');
INSERT INTO `ta_menu` VALUES ('35', '9', 'Admin', 'Admin', 'doDel', '', '1', '0', '管理员删除', '', '', '0');
INSERT INTO `ta_menu` VALUES ('36', '11', 'Admin', 'Menu', 'add', '', '1', '0', '菜单添加', '', '', '0');
INSERT INTO `ta_menu` VALUES ('37', '11', 'Admin', 'Menu', 'edit', '', '1', '0', '菜单编辑', '', '', '0');
INSERT INTO `ta_menu` VALUES ('38', '11', 'Admin', 'Menu', 'doDel', '', '1', '0', '菜单删除', '', '', '0');
INSERT INTO `ta_menu` VALUES ('39', '11', 'Admin', 'Menu', 'displayorder', '', '1', '0', '菜单排序', '', '', '0');
