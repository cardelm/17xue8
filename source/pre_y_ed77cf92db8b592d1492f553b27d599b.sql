/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50045
Source Host           : localhost:3306
Source Database       : 17xue8

Target Server Type    : MYSQL
Target Server Version : 50045
File Encoding         : 65001

Date: 2013-11-01 19:20:18
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_y_ed77cf92db8b592d1492f553b27d599b`
-- ----------------------------
DROP TABLE IF EXISTS `pre_y_ed77cf92db8b592d1492f553b27d599b`;
CREATE TABLE `pre_y_ed77cf92db8b592d1492f553b27d599b` (
  `shopsortid` smallint(6) unsigned NOT NULL auto_increment,
  `upmokuai` smallint(6) NOT NULL,
  `sortname` char(20) character set gbk NOT NULL,
  `sorttitle` char(20) character set gbk NOT NULL,
  `sortlevel` smallint(6) NOT NULL,
  `sortupid` smallint(6) NOT NULL,
  `displayorder` smallint(6) NOT NULL,
  `upids` text character set gbk NOT NULL,
  PRIMARY KEY  (`shopsortid`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_y_ed77cf92db8b592d1492f553b27d599b
-- ----------------------------
INSERT INTO `pre_y_ed77cf92db8b592d1492f553b27d599b` VALUES ('1', '0', 'clothhover', '餐饮', '1', '0', '0', '0');
INSERT INTO `pre_y_ed77cf92db8b592d1492f553b27d599b` VALUES ('2', '0', 'foothover', '娱乐', '1', '0', '1', '');
INSERT INTO `pre_y_ed77cf92db8b592d1492f553b27d599b` VALUES ('9', '0', 'peishihover', '服装', '1', '0', '2', '');
INSERT INTO `pre_y_ed77cf92db8b592d1492f553b27d599b` VALUES ('3', '0', 'chuancai', '川菜', '2', '1', '1', '');
INSERT INTO `pre_y_ed77cf92db8b592d1492f553b27d599b` VALUES ('10', '0', 'homehover', '箱包', '1', '0', '3', '');
INSERT INTO `pre_y_ed77cf92db8b592d1492f553b27d599b` VALUES ('8', '0', 'ktv', 'KTV', '2', '2', '1', '');
INSERT INTO `pre_y_ed77cf92db8b592d1492f553b27d599b` VALUES ('7', '0', 'lucai', '鲁菜', '2', '1', '3', '');
INSERT INTO `pre_y_ed77cf92db8b592d1492f553b27d599b` VALUES ('11', '0', 'washhover', '家居', '1', '0', '4', '');
INSERT INTO `pre_y_ed77cf92db8b592d1492f553b27d599b` VALUES ('12', '0', 'facehover', '化妆', '1', '0', '5', '');
INSERT INTO `pre_y_ed77cf92db8b592d1492f553b27d599b` VALUES ('13', '0', 'foodhover', '数码', '1', '0', '6', '');
INSERT INTO `pre_y_ed77cf92db8b592d1492f553b27d599b` VALUES ('14', '0', 'zizhucan', '自助餐', '2', '1', '2', '');
INSERT INTO `pre_y_ed77cf92db8b592d1492f553b27d599b` VALUES ('15', '0', 'tongzhuang', '童装', '2', '9', '1', '');
INSERT INTO `pre_y_ed77cf92db8b592d1492f553b27d599b` VALUES ('16', '0', 'yimao', '衣帽', '2', '9', '0', '');
INSERT INTO `pre_y_ed77cf92db8b592d1492f553b27d599b` VALUES ('17', '0', 'dianying', '电影', '2', '2', '0', '');
INSERT INTO `pre_y_ed77cf92db8b592d1492f553b27d599b` VALUES ('18', '0', 'sheying', '摄影写真', '2', '12', '0', '');
INSERT INTO `pre_y_ed77cf92db8b592d1492f553b27d599b` VALUES ('19', '0', 'jiajuriyong', '家居日用', '2', '11', '0', '');
