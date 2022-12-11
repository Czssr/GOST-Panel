/*
 Navicat Premium Data Transfer

 Source Server         : 192.168.50.121
 Source Server Type    : MySQL
 Source Server Version : 50739
 Source Host           : 192.168.50.121:3306
 Source Schema         : server_gost_com

 Target Server Type    : MySQL
 Target Server Version : 50739
 File Encoding         : 65001

 Date: 12/12/2022 04:31:13
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admins
-- ----------------------------
DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `invite_id` int(11) NOT NULL DEFAULT '0',
  `relations` json DEFAULT NULL,
  `username` varchar(100) NOT NULL COMMENT '账号',
  `password` varchar(255) NOT NULL COMMENT '密码',
  `nickname` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='管理员表';

-- ----------------------------
-- Records of admins
-- ----------------------------
BEGIN;
INSERT INTO `admins` (`id`, `invite_id`, `relations`, `username`, `password`, `nickname`, `avatar`, `status`, `updated_at`, `created_at`, `deleted_at`) VALUES (1, 0, NULL, 'admin', '$2y$10$1crt8t/C.lls.trtm6qvXeqEmMH/8aCuKesJSCtQomOraAnGZWuRC', 'admin', NULL, 1, NULL, NULL, NULL);
COMMIT;

-- ----------------------------
-- Table structure for audits
-- ----------------------------
DROP TABLE IF EXISTS `audits`;
CREATE TABLE `audits` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(100) DEFAULT NULL COMMENT 'IP',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COMMENT='分流器表，审计表';

-- ----------------------------
-- Records of audits
-- ----------------------------
BEGIN;
INSERT INTO `audits` (`id`, `url`, `updated_at`, `created_at`, `deleted_at`) VALUES (1, '*.dafahao.*', '2022-12-07 03:25:24', '2022-12-07 03:23:54', '2022-12-07 03:25:24');
INSERT INTO `audits` (`id`, `url`, `updated_at`, `created_at`, `deleted_at`) VALUES (2, '*.minghui.*', '2022-12-07 03:25:26', '2022-12-07 03:24:25', '2022-12-07 03:25:26');
INSERT INTO `audits` (`id`, `url`, `updated_at`, `created_at`, `deleted_at`) VALUES (3, '*.dafahao.*', '2022-12-07 03:26:15', '2022-12-07 03:26:15', NULL);
INSERT INTO `audits` (`id`, `url`, `updated_at`, `created_at`, `deleted_at`) VALUES (4, '*.minghui.*', '2022-12-07 03:28:59', '2022-12-07 03:26:39', '2022-12-07 03:28:59');
INSERT INTO `audits` (`id`, `url`, `updated_at`, `created_at`, `deleted_at`) VALUES (5, '*.dongtaiwang.*', '2022-12-07 03:26:51', '2022-12-07 03:26:51', NULL);
INSERT INTO `audits` (`id`, `url`, `updated_at`, `created_at`, `deleted_at`) VALUES (6, '*.xunlei.*', '2022-12-07 05:23:10', '2022-12-07 05:23:10', NULL);
INSERT INTO `audits` (`id`, `url`, `updated_at`, `created_at`, `deleted_at`) VALUES (7, '*.Thunder.*', '2022-12-07 05:23:16', '2022-12-07 05:23:16', NULL);
INSERT INTO `audits` (`id`, `url`, `updated_at`, `created_at`, `deleted_at`) VALUES (8, '*.hetzner.*', '2022-12-07 05:25:07', '2022-12-07 05:25:07', NULL);
COMMIT;

-- ----------------------------
-- Table structure for configs
-- ----------------------------
DROP TABLE IF EXISTS `configs`;
CREATE TABLE `configs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `key` varchar(100) NOT NULL,
  `value` varchar(255) NOT NULL,
  `suffix` varchar(100) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='配置表';

-- ----------------------------
-- Records of configs
-- ----------------------------
BEGIN;
INSERT INTO `configs` (`id`, `name`, `key`, `value`, `suffix`, `status`, `updated_at`, `created_at`, `deleted_at`) VALUES (1, '网站名称', 'website_name', 'GOST_PANEL', NULL, 1, NULL, NULL, NULL);
INSERT INTO `configs` (`id`, `name`, `key`, `value`, `suffix`, `status`, `updated_at`, `created_at`, `deleted_at`) VALUES (2, '是否允许注册', 'registered', '1', NULL, 1, NULL, NULL, NULL);
INSERT INTO `configs` (`id`, `name`, `key`, `value`, `suffix`, `status`, `updated_at`, `created_at`, `deleted_at`) VALUES (3, '注册赠送流量', 'registered_traffic', '1024', 'MB', 1, NULL, NULL, NULL);
COMMIT;

-- ----------------------------
-- Table structure for node_flows
-- ----------------------------
DROP TABLE IF EXISTS `node_flows`;
CREATE TABLE `node_flows` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `node_id` int(11) NOT NULL COMMENT '节点ID',
  `total` varchar(255) NOT NULL DEFAULT '0' COMMENT '流量',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='节点每日流量统计表';

-- ----------------------------
-- Records of node_flows
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for nodes
-- ----------------------------
DROP TABLE IF EXISTS `nodes`;
CREATE TABLE `nodes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(100) DEFAULT NULL COMMENT 'IP',
  `metrics_prefix` varchar(100) DEFAULT NULL COMMENT '数据面板路径',
  `metrics_port` varchar(100) DEFAULT NULL COMMENT '数据面板端口',
  `panel_prefix` varchar(100) DEFAULT NULL COMMENT '面板路径',
  `panel_port` varchar(100) DEFAULT NULL COMMENT '面板端口',
  `port` varchar(100) DEFAULT NULL COMMENT '端口',
  `auth` varchar(100) DEFAULT NULL COMMENT '认证',
  `multiple` tinyint(2) NOT NULL DEFAULT '1' COMMENT '流量倍数，默认1倍',
  `remark` varchar(255) DEFAULT NULL,
  `level` tinyint(1) NOT NULL DEFAULT '1' COMMENT '等级',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '节点类型 1中转 2直连',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COMMENT='节点表';

-- ----------------------------
-- Records of nodes
-- ----------------------------
BEGIN;
INSERT INTO `nodes` (`id`, `ip`, `metrics_prefix`, `metrics_port`, `panel_prefix`, `panel_port`, `port`, `auth`, `multiple`, `remark`, `level`, `type`, `status`, `updated_at`, `created_at`, `deleted_at`) VALUES (5, '192.168.50.123', NULL, NULL, 'apis', '17070', '20000', 'user:pass', 1, NULL, 1, 1, 1, '2022-12-09 04:34:13', '2022-12-09 04:33:55', '2022-12-09 04:35:57');
INSERT INTO `nodes` (`id`, `ip`, `metrics_prefix`, `metrics_port`, `panel_prefix`, `panel_port`, `port`, `auth`, `multiple`, `remark`, `level`, `type`, `status`, `updated_at`, `created_at`, `deleted_at`) VALUES (15, '192.168.50.123', 'metrics', '9000', 'api', '18080', '20001-30000', 'user:pass', 1, NULL, 1, 2, 1, '2022-12-09 12:00:37', '2022-12-09 11:45:03', '2022-12-09 12:03:23');
INSERT INTO `nodes` (`id`, `ip`, `metrics_prefix`, `metrics_port`, `panel_prefix`, `panel_port`, `port`, `auth`, `multiple`, `remark`, `level`, `type`, `status`, `updated_at`, `created_at`, `deleted_at`) VALUES (16, '192.168.50.123', 'metrics', '9000', 'api', '18080', '20001-30000', 'user:pass', 1, NULL, 1, 2, 1, '2022-12-09 12:03:38', '2022-12-09 12:03:38', NULL);
COMMIT;

-- ----------------------------
-- Table structure for ports
-- ----------------------------
DROP TABLE IF EXISTS `ports`;
CREATE TABLE `ports` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `node_id` int(11) NOT NULL COMMENT '节点ID',
  `node_port` int(5) NOT NULL DEFAULT '0' COMMENT '节点端口',
  `server_port` int(5) NOT NULL DEFAULT '0' COMMENT '服务端口',
  `last_dosage` varchar(255) DEFAULT NULL COMMENT '最后一次的流量统计，重启服务 一定要把这里清0',
  `total_dosage` varchar(255) DEFAULT NULL COMMENT '一共使用的流量统计',
  `last_dosage_time` datetime DEFAULT NULL COMMENT '上一次统计时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 0禁用 1启用',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COMMENT='端口占用表';

-- ----------------------------
-- Records of ports
-- ----------------------------
BEGIN;
INSERT INTO `ports` (`id`, `user_id`, `node_id`, `node_port`, `server_port`, `last_dosage`, `total_dosage`, `last_dosage_time`, `status`, `updated_at`, `created_at`, `deleted_at`) VALUES (46, 1, 16, 20001, 20001, '0', NULL, NULL, 1, '2022-12-09 12:04:04', '2022-12-09 12:03:38', NULL);
INSERT INTO `ports` (`id`, `user_id`, `node_id`, `node_port`, `server_port`, `last_dosage`, `total_dosage`, `last_dosage_time`, `status`, `updated_at`, `created_at`, `deleted_at`) VALUES (47, 2, 16, 20002, 20002, '0', NULL, NULL, 1, '2022-12-09 12:04:04', '2022-12-09 12:03:39', NULL);
INSERT INTO `ports` (`id`, `user_id`, `node_id`, `node_port`, `server_port`, `last_dosage`, `total_dosage`, `last_dosage_time`, `status`, `updated_at`, `created_at`, `deleted_at`) VALUES (48, 3, 16, 20003, 20003, '0', NULL, NULL, 1, '2022-12-09 12:04:04', '2022-12-09 12:03:39', NULL);
COMMIT;

-- ----------------------------
-- Table structure for user_flows
-- ----------------------------
DROP TABLE IF EXISTS `user_flows`;
CREATE TABLE `user_flows` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `total` varchar(255) NOT NULL DEFAULT '0' COMMENT '流量',
  `statistical_date` datetime NOT NULL COMMENT '统计日期',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COMMENT='用户每日流量统计表';

-- ----------------------------
-- Records of user_flows
-- ----------------------------
BEGIN;
INSERT INTO `user_flows` (`id`, `user_id`, `total`, `statistical_date`, `updated_at`, `created_at`, `deleted_at`) VALUES (7, 1, '67', '2022-12-09 00:00:00', '2022-12-09 11:47:36', '2022-12-09 11:45:44', NULL);
COMMIT;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `invite_id` int(11) NOT NULL DEFAULT '0',
  `relations` json DEFAULT NULL,
  `username` varchar(100) NOT NULL COMMENT '账号',
  `password` varchar(255) NOT NULL COMMENT '密码',
  `auth` varchar(100) DEFAULT NULL COMMENT '服务认证',
  `email` varchar(255) DEFAULT NULL COMMENT '邮箱',
  `nickname` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `level` tinyint(1) NOT NULL DEFAULT '1' COMMENT '等级',
  `traffic` bigint(11) NOT NULL DEFAULT '0' COMMENT '每月流量',
  `reset_traffic` datetime DEFAULT NULL COMMENT '流量重置时间',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='用户表';

-- ----------------------------
-- Records of users
-- ----------------------------
BEGIN;
INSERT INTO `users` (`id`, `invite_id`, `relations`, `username`, `password`, `auth`, `email`, `nickname`, `avatar`, `level`, `traffic`, `reset_traffic`, `status`, `updated_at`, `created_at`, `deleted_at`) VALUES (1, 0, NULL, 'test_01', '$2y$10$QoRmUarHrKuRORAEFmXRDeS39FSXQ.TdcJYQRnd8NA0p5QZOp67fG', 'skbsdz:p6j3tw', 'test_01@qq.com', 'test_01', NULL, 1, 0, NULL, 1, '2022-12-07 04:09:54', '2022-12-07 04:09:54', NULL);
INSERT INTO `users` (`id`, `invite_id`, `relations`, `username`, `password`, `auth`, `email`, `nickname`, `avatar`, `level`, `traffic`, `reset_traffic`, `status`, `updated_at`, `created_at`, `deleted_at`) VALUES (2, 0, NULL, 'test_02', '$2y$10$QoRmUarHrKuRORAEFmXRDeS39FSXQ.TdcJYQRnd8NA0p5QZOp67fG', 'skbsdz:p6j3tw', 'test_02@qq.com', 'test_02', NULL, 1, 0, NULL, 1, '2022-12-07 04:09:54', '2022-12-07 04:09:54', NULL);
INSERT INTO `users` (`id`, `invite_id`, `relations`, `username`, `password`, `auth`, `email`, `nickname`, `avatar`, `level`, `traffic`, `reset_traffic`, `status`, `updated_at`, `created_at`, `deleted_at`) VALUES (3, 0, NULL, 'test_03', '$2y$10$OVQzAbwuW5UKjccRZU/tzeksVGkCmR0c0lGhtcEDIYiWN42hNhblm', '8apsbj:qqmyms', 'test_03@qq.com', 'test_03', NULL, 1, 0, NULL, 1, '2022-12-09 04:37:56', '2022-12-09 04:37:56', NULL);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
