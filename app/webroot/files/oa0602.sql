/*
SQLyog Ultimate v11.25 (64 bit)
MySQL - 5.6.17 : Database - oa
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`oa` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `oa`;

/*Table structure for table `t_user` */

DROP TABLE IF EXISTS `t_user`;

CREATE TABLE `t_user` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `pid` int(6) NOT NULL DEFAULT '0' COMMENT '部门id',
  `user` char(20) NOT NULL COMMENT '用户名',
  `password` varchar(90) NOT NULL COMMENT '密码',
  `position` int(3) DEFAULT NULL COMMENT '职务id',
  `name` varchar(30) NOT NULL COMMENT '名称',
  `tel` char(15) DEFAULT NULL COMMENT '电话',
  `sex` char(10) DEFAULT NULL COMMENT '性别',
  `email` varchar(60) DEFAULT NULL COMMENT '邮箱',
  `ctime` char(20) DEFAULT NULL COMMENT '注册时间',
  `del` int(1) DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`),
  KEY `u_pid` (`pid`),
  KEY `u_user` (`user`,`password`),
  KEY `u_pos` (`position`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `t_user` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
