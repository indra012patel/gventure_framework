/*
SQLyog Community v11.31 (64 bit)
MySQL - 5.0.51b-community-nt : Database - retail
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`db` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `db`;

/*Table structure for table `payment` */

DROP TABLE IF EXISTS `payment`;

CREATE TABLE `payment` (
  `id` int(11) NOT NULL auto_increment,
  `iduser` int(11) default NULL,
  `amount` double(12,6) default NULL,
  `type` tinyint(1) default NULL,
  `payment_date` datetime default NULL,
  `flag` tinyint(1) default NULL,
  `description` varchar(200) default NULL,
  `usertype` tinyint(1) default NULL,
  `created_by` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;

/*Data for the table `payment` */


/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `idtariff` int(11) NOT NULL default '-1',
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `type` tinyint(1) default NULL,
  `idparent` int(11) default NULL,
  `idivr` int(11) default NULL,
  `balance` double(12,8) default '0.00000000',
  `recharge_balance` double(12,8) default '0.00000000',
  `accountcode` varchar(50) default NULL,
  `usage` double(12,8) default '0.00000000' COMMENT 'Today total usage',
  `minutes` double(12,8) default '0.00000000' COMMENT 'Today total minutes',
  `month_usage` double(12,8) default '0.00000000',
  `month_minutes` double(12,8) default '0.00000000',
  `level` tinyint(1) default '0',
  `flag` tinyint(1) default '0',
  `unicode` varchar(20) default NULL,
  `apply_setting` tinyint(1) default '1',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

/*Data for the table `user` */

insert  into `user`(`id`,`idtariff`,`username`,`password`,`type`,`idparent`,`idivr`,`balance`,`recharge_balance`,`accountcode`,`usage`,`minutes`,`month_usage`,`month_minutes`,`level`,`flag`,`unicode`,`apply_setting`) values (1,-1,'admin','admin',0,-1,NULL,1000.00000000,0.00000000,'-1',0.00000000,0.00000000,0.00000000,0.00000000,6,0,NULL,1);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
