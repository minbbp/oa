# Host: localhost  (Version: 5.1.58-community)
# Date: 2013-10-14 17:25:07
# Generator: MySQL-Front 5.3  (Build 4.9)

/*!40101 SET NAMES utf8 */;

#
# Source for table "ci_sessions"
#

DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `ip_address` varchar(16) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

#
# Data for table "ci_sessions"
#

INSERT INTO `ci_sessions` VALUES ('460bce3a55ad0542b52edc4c2e873a24','127.0.0.1','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.89 Safari/537.1',1381742325,'a:15:{s:9:\"user_data\";s:0:\"\";s:10:\"DX_user_id\";s:1:\"1\";s:11:\"DX_username\";s:5:\"admin\";s:10:\"DX_role_id\";s:1:\"2\";s:12:\"DX_role_name\";s:5:\"Admin\";s:18:\"DX_parent_roles_id\";a:0:{}s:20:\"DX_parent_roles_name\";a:0:{}s:13:\"DX_permission\";a:3:{s:3:\"uri\";a:1:{i:0;s:3:\"\'/\'\";}s:4:\"edit\";s:1:\"1\";s:6:\"delete\";s:1:\"1\";}s:21:\"DX_parent_permissions\";a:0:{}s:12:\"DX_logged_in\";b:1;s:11:\"DX_realname\";s:12:\"超级管理\";s:6:\"DX_pid\";s:1:\"0\";s:8:\"DX_level\";s:1:\"1\";s:8:\"DX_email\";s:25:\"wb-zhibinliu@sohu-inc.com\";s:10:\"level_info\";b:0;}'),('e1ce1a5854434b2c196a338ac87b4080','127.0.0.1','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.66 Safari/537.36',1381742114,'a:15:{s:9:\"user_data\";s:0:\"\";s:10:\"DX_user_id\";s:1:\"1\";s:11:\"DX_username\";s:5:\"admin\";s:10:\"DX_role_id\";s:1:\"2\";s:12:\"DX_role_name\";s:5:\"Admin\";s:18:\"DX_parent_roles_id\";a:0:{}s:20:\"DX_parent_roles_name\";a:0:{}s:13:\"DX_permission\";a:3:{s:3:\"uri\";a:1:{i:0;s:3:\"\'/\'\";}s:4:\"edit\";s:1:\"1\";s:6:\"delete\";s:1:\"1\";}s:21:\"DX_parent_permissions\";a:0:{}s:12:\"DX_logged_in\";b:1;s:11:\"DX_realname\";s:12:\"超级管理\";s:6:\"DX_pid\";s:1:\"0\";s:8:\"DX_level\";s:1:\"1\";s:8:\"DX_email\";s:25:\"wb-zhibinliu@sohu-inc.com\";s:10:\"level_info\";b:0;}');

#
# Source for table "git_groups"
#

DROP TABLE IF EXISTS `git_groups`;
CREATE TABLE `git_groups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户组主键',
  `group_name` varchar(32) DEFAULT NULL COMMENT '组名',
  `group_creator` int(11) DEFAULT NULL COMMENT '组的创建者',
  `group_description` varchar(255) DEFAULT NULL COMMENT '用户组描述',
  `group_state` tinyint(3) DEFAULT NULL COMMENT '用户组状态',
  `is_lock` tinyint(3) DEFAULT '0' COMMENT '0, 为开启，1为锁定',
  `addtime` int(11) DEFAULT NULL COMMENT '添加时间',
  `last_changetime` int(11) DEFAULT NULL COMMENT '最后修改时间',
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='git用户组表';

#
# Data for table "git_groups"
#

/*!40000 ALTER TABLE `git_groups` DISABLE KEYS */;
INSERT INTO `git_groups` VALUES (1,'adserver',1,'adserver 项目的所有人员',1,0,1381479237,1381739904),(2,'ctrgroup',3,'ctr 组人员协同开发',1,0,1381482619,1381554038);
/*!40000 ALTER TABLE `git_groups` ENABLE KEYS */;

#
# Source for table "git_groups_user"
#

DROP TABLE IF EXISTS `git_groups_user`;
CREATE TABLE `git_groups_user` (
  `guser_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) DEFAULT NULL COMMENT 'git组主键',
  `user_id` int(11) DEFAULT NULL COMMENT '用户主键',
  `addtime` int(11) DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`guser_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='git组成员';

#
# Data for table "git_groups_user"
#

INSERT INTO `git_groups_user` VALUES (1,1,1,1381479237),(2,1,2,1381479237),(3,1,3,1381479237),(5,1,3,1381482245),(6,2,1,1381482619),(7,2,2,1381482619),(8,2,3,1381482619);

#
# Source for table "git_sshkey"
#

DROP TABLE IF EXISTS `git_sshkey`;
CREATE TABLE `git_sshkey` (
  `key_id` int(11) NOT NULL AUTO_INCREMENT,
  `git_id` int(11) DEFAULT NULL COMMENT '对应的git机器',
  `git_auth` varchar(128) DEFAULT NULL COMMENT '运维指定的认证标识',
  `gitpub` varchar(255) DEFAULT NULL COMMENT 'gitpub当做一条记录',
  `key_state` tinyint(3) DEFAULT NULL COMMENT 'key_state,-1 操作失败，0为未操作，1操作成功',
  `addtime` int(11) DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`key_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

#
# Data for table "git_sshkey"
#

/*!40000 ALTER TABLE `git_sshkey` DISABLE KEYS */;
INSERT INTO `git_sshkey` VALUES (1,1,'admin001','admin1381479278_7.pub',1,1381479278),(2,2,'minbbp001','minbbp1381480717_6.pub',0,1381480717),(3,3,NULL,'minbbp1381481088_1.pub',0,1381481088),(4,4,'minbbp002','minbbp1381481628_6.pub',1,1381481628),(5,5,NULL,'user1381559909_2.pub',0,1381559909),(6,1,'admin33301','admin1381564242.pub',1,NULL);
/*!40000 ALTER TABLE `git_sshkey` ENABLE KEYS */;

#
# Source for table "gits"
#

DROP TABLE IF EXISTS `gits`;
CREATE TABLE `gits` (
  `git_id` int(11) NOT NULL AUTO_INCREMENT,
  `add_user` int(11) DEFAULT NULL COMMENT '申请人的id',
  `add_datagroups` varchar(32) DEFAULT NULL COMMENT '加入组，这个字段目前没用，先留着',
  `cfilename` varchar(128) DEFAULT NULL COMMENT '受控目录',
  `git_state` tinyint(3) DEFAULT '0' COMMENT '-1代表处理失败，0,代表未处理，1代表已受理，2代表处理成功',
  `addtime` int(11) DEFAULT NULL COMMENT '申请人添加的时间',
  PRIMARY KEY (`git_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

#
# Data for table "gits"
#

/*!40000 ALTER TABLE `gits` DISABLE KEYS */;
INSERT INTO `gits` VALUES (1,1,'1,2','admin',1,1381479278),(2,3,'1,2','minbbp',1,1381480717),(3,3,NULL,NULL,0,1381481088),(4,3,'2','minbbp2',1,1381481628),(5,2,'1,2',NULL,0,1381559909);
/*!40000 ALTER TABLE `gits` ENABLE KEYS */;

#
# Source for table "gits_level_op"
#

DROP TABLE IF EXISTS `gits_level_op`;
CREATE TABLE `gits_level_op` (
  `gits_opid` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` tinyint(3) unsigned DEFAULT '0' COMMENT '0为主管审批，1为运维审批',
  `apply_type` tinyint(3) DEFAULT '0' COMMENT '0,为新申请审批，1增加机器审批，2为增加git组审批',
  `git_id` int(11) DEFAULT NULL COMMENT 'git 认证id',
  `user_id` int(11) DEFAULT NULL COMMENT '操作人员',
  `state` tinyint(3) DEFAULT NULL COMMENT '-1 驳回，0未操作，1 操作成功',
  `description` varchar(255) DEFAULT '' COMMENT '驳回原因',
  `btime` int(11) DEFAULT NULL COMMENT '开始时间',
  `etime` int(11) DEFAULT NULL COMMENT '操作完成时间',
  `filename` varchar(128) DEFAULT NULL COMMENT '新增gitpub 名',
  `newgroups_id` varchar(32) DEFAULT NULL COMMENT '新增组的字符串',
  `level_id` int(11) DEFAULT NULL COMMENT '主管审批，的主键id,一个关联主键的外检',
  `optime` int(11) DEFAULT NULL COMMENT 'op审批时间',
  PRIMARY KEY (`gits_opid`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='git认证运维审批';

#
# Data for table "gits_level_op"
#

/*!40000 ALTER TABLE `gits_level_op` DISABLE KEYS */;
INSERT INTO `gits_level_op` VALUES (1,1,0,1,1,1,'',NULL,NULL,NULL,NULL,NULL,1381479323),(2,0,0,2,1,1,'',NULL,1381481683,NULL,NULL,NULL,NULL),(4,0,0,3,1,-1,'今天心情不好，你明天在申请吧！',NULL,1381482218,NULL,NULL,NULL,NULL),(5,0,0,4,1,1,'',1381481628,1381482488,NULL,NULL,NULL,NULL),(6,1,0,2,1,1,'',NULL,1381481683,NULL,NULL,2,1381482245),(7,1,0,4,1,1,'',1381481628,1381482488,NULL,NULL,5,1381482502),(8,0,0,5,6,1,'',1381559909,1381560363,NULL,NULL,NULL,NULL),(10,1,0,5,0,0,'',1381563529,1381560363,NULL,NULL,8,NULL),(11,1,1,1,1,1,'',1381564242,NULL,'admin1381564242.pub',NULL,NULL,1381564342);
/*!40000 ALTER TABLE `gits_level_op` ENABLE KEYS */;

#
# Source for table "group_creator"
#

DROP TABLE IF EXISTS `group_creator`;
CREATE TABLE `group_creator` (
  `gcre_id` int(11) NOT NULL AUTO_INCREMENT,
  `gcre_creator` int(11) DEFAULT NULL COMMENT 'git组创建者',
  `group_id` int(11) DEFAULT NULL COMMENT 'git组id',
  `gle_id` int(11) DEFAULT NULL COMMENT '主管审批id',
  `git_id` int(11) DEFAULT NULL COMMENT 'git认证，这个字段目前可以忽略',
  `change_id` int(11) DEFAULT NULL COMMENT '申请加入git组的用户',
  `gcre_state` tinyint(3) DEFAULT NULL COMMENT '用户组审批状态',
  `gcre_description` varchar(255) DEFAULT NULL COMMENT '驳回原因',
  `addtime` int(11) DEFAULT NULL COMMENT '审批时间',
  PRIMARY KEY (`gcre_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='操作者审核表';

#
# Data for table "group_creator"
#

/*!40000 ALTER TABLE `group_creator` DISABLE KEYS */;
INSERT INTO `group_creator` VALUES (1,1,1,2,2,3,1,NULL,NULL),(2,1,1,8,5,2,1,NULL,NULL),(3,3,2,8,5,2,1,NULL,NULL);
/*!40000 ALTER TABLE `group_creator` ENABLE KEYS */;

#
# Source for table "group_level"
#

DROP TABLE IF EXISTS `group_level`;
CREATE TABLE `group_level` (
  `gle_id` int(11) NOT NULL AUTO_INCREMENT,
  `gle_level` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `change_id` int(11) DEFAULT NULL,
  `gle_state` tinyint(3) DEFAULT NULL COMMENT '-1 审核不通过0未审核1审核通过',
  `gle_description` varchar(255) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `apply_time` int(11) DEFAULT NULL COMMENT '审批时间',
  PRIMARY KEY (`gle_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='主管审核表';

#
# Data for table "group_level"
#

/*!40000 ALTER TABLE `group_level` DISABLE KEYS */;
INSERT INTO `group_level` VALUES (1,1,2,3,1,NULL,NULL,1381482640);
/*!40000 ALTER TABLE `group_level` ENABLE KEYS */;

#
# Source for table "group_ops"
#

DROP TABLE IF EXISTS `group_ops`;
CREATE TABLE `group_ops` (
  `gop_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) DEFAULT NULL COMMENT '要操作的组',
  `gop_oper` int(11) DEFAULT NULL,
  `change_id` int(11) DEFAULT NULL COMMENT '发去审核请求者',
  `gop_state` tinyint(3) DEFAULT NULL COMMENT 'op的操作结果，0为未操作，-1为操作失败，1为操作成功',
  `gop_description` varchar(255) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL COMMENT '提交审批给op进行操作的时间',
  `endtime` int(11) DEFAULT NULL COMMENT 'op操作完成时间',
  PRIMARY KEY (`gop_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='op操作记录表';

#
# Data for table "group_ops"
#

/*!40000 ALTER TABLE `group_ops` DISABLE KEYS */;
INSERT INTO `group_ops` VALUES (1,1,1,1,1,NULL,1381479237,1381479258),(2,2,1,3,1,NULL,1381482640,1381554038);
/*!40000 ALTER TABLE `group_ops` ENABLE KEYS */;

#
# Source for table "login_attempts"
#

DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(40) COLLATE utf8_bin NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

#
# Data for table "login_attempts"
#


#
# Source for table "permissions"
#

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `data` text COLLATE utf8_bin,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

#
# Data for table "permissions"
#

INSERT INTO `permissions` VALUES (1,2,'a:3:{s:3:\"uri\";a:1:{i:0;s:3:\"\'/\'\";}s:4:\"edit\";s:1:\"1\";s:6:\"delete\";s:1:\"1\";}'),(2,1,'a:3:{s:3:\"uri\";a:12:{i:0;s:9:\"\'/index/\'\";i:1;s:15:\"\'/index/index/\'\";i:2;s:7:\"\'/git/\'\";i:3;s:20:\"\'/git/gitshowapply/\'\";i:4;s:13:\"\'/git/mygit/\'\";i:5;s:21:\"\'/git/h_level_apply/\'\";i:6;s:17:\"\'/git/apply_add/\'\";i:7;s:13:\"\'/gitgroups/\'\";i:8;s:21:\"\'/gitgroups/alllist/\'\";i:9;s:16:\"\'/groupcreator/\'\";i:10;s:24:\"\'/groupcreator/alllist/\'\";i:11;s:15:\"\'/git_creator/\'\";}s:4:\"edit\";s:1:\"1\";s:6:\"delete\";s:1:\"1\";}'),(3,5,'a:1:{s:3:\"uri\";a:15:{i:0;s:9:\"\'/index/\'\";i:1;s:15:\"\'/index/index/\'\";i:2;s:7:\"\'/git/\'\";i:3;s:20:\"\'/git/gitshowapply/\'\";i:4;s:13:\"\'/git/mygit/\'\";i:5;s:21:\"\'/git/h_level_apply/\'\";i:6;s:17:\"\'/git/apply_add/\'\";i:7;s:15:\"\'/git/alllist/\'\";i:8;s:13:\"\'/gitgroups/\'\";i:9;s:21:\"\'/gitgroups/alllist/\'\";i:10;s:16:\"\'/groupcreator/\'\";i:11;s:24:\"\'/groupcreator/alllist/\'\";i:12;s:12:\"\'/groupops/\'\";i:13;s:20:\"\'/groupops/alllist/\'\";i:14;s:15:\"\'/git_creator/\'\";}}'),(4,6,'a:1:{s:3:\"uri\";a:17:{i:0;s:9:\"\'/index/\'\";i:1;s:15:\"\'/index/index/\'\";i:2;s:7:\"\'/git/\'\";i:3;s:20:\"\'/git/gitshowapply/\'\";i:4;s:13:\"\'/git/mygit/\'\";i:5;s:21:\"\'/git/h_level_apply/\'\";i:6;s:17:\"\'/git/apply_add/\'\";i:7;s:13:\"\'/gitgroups/\'\";i:8;s:21:\"\'/gitgroups/alllist/\'\";i:9;s:14:\"\'/grouplevel/\'\";i:10;s:22:\"\'/grouplevel/alllist/\'\";i:11;s:16:\"\'/groupcreator/\'\";i:12;s:24:\"\'/groupcreator/alllist/\'\";i:13;s:14:\"\'/grouplevel/\'\";i:14;s:22:\"\'/grouplevel/alllist/\'\";i:15;s:13:\"\'/git_level/\'\";i:16;s:15:\"\'/git_creator/\'\";}}');

#
# Source for table "roles"
#

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(30) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

#
# Data for table "roles"
#

INSERT INTO `roles` VALUES (1,0,'User'),(2,0,'Admin'),(5,0,'op'),(6,0,'leader');

#
# Source for table "user_autologin"
#

DROP TABLE IF EXISTS `user_autologin`;
CREATE TABLE `user_autologin` (
  `key_id` char(32) COLLATE utf8_bin NOT NULL,
  `user_id` mediumint(8) NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`key_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

#
# Data for table "user_autologin"
#


#
# Source for table "user_profile"
#

DROP TABLE IF EXISTS `user_profile`;
CREATE TABLE `user_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `country` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

#
# Data for table "user_profile"
#

INSERT INTO `user_profile` VALUES (1,1,NULL,NULL),(2,3,NULL,NULL);

#
# Source for table "user_temp"
#

DROP TABLE IF EXISTS `user_temp`;
CREATE TABLE `user_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_bin NOT NULL,
  `password` varchar(34) COLLATE utf8_bin NOT NULL,
  `email` varchar(100) COLLATE utf8_bin NOT NULL,
  `activation_key` varchar(50) COLLATE utf8_bin NOT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

#
# Data for table "user_temp"
#


#
# Source for table "users"
#

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL DEFAULT '1',
  `username` varchar(25) COLLATE utf8_bin NOT NULL,
  `password` varchar(34) COLLATE utf8_bin NOT NULL,
  `email` varchar(100) COLLATE utf8_bin NOT NULL,
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `ban_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `newpass` varchar(34) COLLATE utf8_bin DEFAULT NULL,
  `newpass_key` varchar(32) COLLATE utf8_bin DEFAULT NULL,
  `newpass_time` datetime DEFAULT NULL,
  `last_ip` varchar(40) CHARACTER SET utf8 DEFAULT '',
  `last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `pid` smallint(6) DEFAULT NULL COMMENT '用户的上级，如果为0 则顶级，否则填写父级',
  `level` tinyint(3) DEFAULT NULL COMMENT '为1怎为主管',
  `realname` varchar(128) COLLATE utf8_bin DEFAULT NULL COMMENT '用户的真实姓名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

#
# Data for table "users"
#

INSERT INTO `users` VALUES (1,2,'admin','$1$Il1.xT5.$R7SaXlmMeNNA0ImWnk.760','wb-zhibinliu@sohu-inc.com',0,NULL,NULL,NULL,NULL,'127.0.0.1','2013-10-14 16:38:12','2008-11-30 04:56:32','2013-10-14 16:38:12',0,1,'超级管理'),(2,1,'user','$1$bO..IR4.$CxjJBjKJ5QW2/BaYKDS7f.','838402064@qq.com',0,NULL,NULL,NULL,NULL,'127.0.0.1','2013-10-14 09:49:38','2008-12-01 14:01:53','2013-10-14 09:49:38',6,0,'员工'),(3,5,'minbbp','$1$KE4.L4..$TXhMYnL1e9/9QNaf4ccAW1','957907332@qq.com',0,NULL,NULL,NULL,NULL,'127.0.0.1','2013-10-12 14:53:21','2013-08-26 17:38:21','2013-10-12 14:53:21',1,0,'测试者'),(6,6,'leader','$1$MW/.lX5.$rBM5BqObSFquMitfte9ya/','957907332@qq.com',0,NULL,NULL,NULL,NULL,'127.0.0.1','2013-10-14 09:50:01','2013-08-27 16:57:46','2013-10-14 09:50:01',0,1,'领导1'),(7,1,'other','$1$F40.qX0.$xY/T4WUxvRUvvjUO99Szs/','other@sina.com',0,NULL,NULL,NULL,NULL,'127.0.0.1','2013-09-05 13:54:37','2013-08-27 17:32:11','2013-09-05 13:54:37',0,0,'杂户'),(8,1,'testminbbp','$1$Xa5.UG0.$w.23PBngu.GhUWptW7CVu.','wb-zhibinliu@sohu-inc.com',0,NULL,'$1$7H5.CT0.$H12Gu.E5J0y6mC/VoV2fY1','8e735f88d9804af51194fc12e6c13733','2013-09-09 05:42:47','127.0.0.1','2013-09-09 17:19:37','2013-09-09 17:18:15','2013-09-09 17:27:47',0,0,'刘志宾'),(9,1,'testpp','$1$970.cE0.$jkX7cbaXSdCXTwoWpg0wo0','wb-zhibinliu100@sohu-inc.com',0,NULL,NULL,NULL,NULL,'','0000-00-00 00:00:00','2013-09-10 16:01:09','2013-09-11 16:55:32',0,1,'王毅'),(10,1,'huhu','$1$tN0.yy4.$aZFuC7omZNGyI756ciAQC/','tianda@sohu-inc.com',0,NULL,NULL,NULL,NULL,'','0000-00-00 00:00:00','2013-09-11 15:59:00','2013-09-11 15:59:00',0,1,'天大'),(11,1,'huhudend','$1$xj0.m1..$Ih/B5IwSp2mLNQCY86z3F1','huhud@sohu-inc.com',0,NULL,NULL,NULL,NULL,'','0000-00-00 00:00:00','2013-09-11 16:02:47','2013-09-11 16:19:15',0,1,'王二'),(12,5,'hahahuh','$1$il..Dw5.$FDI/alIo96u132PhcTsax1','mach@sina.com',0,NULL,NULL,NULL,NULL,'','0000-00-00 00:00:00','2013-09-11 16:23:12','2013-09-11 16:23:12',11,0,'mach'),(13,1,'testwang2','$1$c44./H5.$jm70m0fRylYi/LTuLa9gb1','wang@sina.com',0,NULL,NULL,NULL,NULL,'','0000-00-00 00:00:00','2013-09-11 16:23:54','2013-09-11 16:23:54',6,0,'wang');
