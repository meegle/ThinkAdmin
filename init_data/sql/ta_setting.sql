/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50611
Source Host           : localhost:3306
Source Database       : mysite

Target Server Type    : MYSQL
Target Server Version : 50611
File Encoding         : 65001

Date: 2015-01-09 11:31:48
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ta_setting
-- ----------------------------
DROP TABLE IF EXISTS `ta_setting`;
CREATE TABLE `ta_setting` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(10) DEFAULT NULL COMMENT '数据类型',
  `text` text COMMENT '内容',
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `tip` varchar(200) DEFAULT NULL COMMENT '说明',
  `displayorder` int(11) DEFAULT '0' COMMENT '排序',
  `code` varchar(20) DEFAULT NULL COMMENT '代码',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ta_setting
-- ----------------------------
INSERT INTO `ta_setting` VALUES ('1', 'input', 'MySite', '网站名称', '出现在每个页面的title后面', '1', 'web_name');
INSERT INTO `ta_setting` VALUES ('2', 'input', 'http://www.mysite.com/', '网站地址', '使用http://web_url/的格式填写，注意以/结尾', '1', 'web_url');
INSERT INTO `ta_setting` VALUES ('3', 'input', 'http://upload.remoteserve.com/mysite/', '远程上传地址', '远程资源服务器根目录，注意以/结尾', '1', 'ftp_path');
