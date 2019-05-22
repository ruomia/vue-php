/*
 Navicat Premium Data Transfer

 Source Server         : localhost_3306
 Source Server Type    : MySQL
 Source Server Version : 50721
 Source Host           : localhost:3306
 Source Schema         : myadmin

 Target Server Type    : MySQL
 Target Server Version : 50721
 File Encoding         : 65001

 Date: 07/05/2019 21:35:11
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户名',
  `nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '昵称',
  `password` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '密码',
  `avatar` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '头像',
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '电子邮箱',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态:0=隐藏,1=正常',
  `create_time` datetime(0) NULL DEFAULT NULL,
  `update_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 28 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES (1, 'admin', '总管理员', '4297f44b13955235245b2497399d7a93', NULL, '123@163.com', 1, NULL, NULL);
INSERT INTO `admin` VALUES (23, 'aaa', 'fadf', '4297f44b13955235245b2497399d7a93', '/assets/img/avatar.png', '1213@163.com', 1, NULL, NULL);
INSERT INTO `admin` VALUES (26, 'abc', '管理员', '4297f44b13955235245b2497399d7a93', '/assets/img/avatar.png', '123@163.com', 1, NULL, NULL);
INSERT INTO `admin` VALUES (27, 'axx', '管理员', '4297f44b13955235245b2497399d7a93', '/assets/img/avatar.png', '123@163.com', 1, NULL, NULL);

-- ----------------------------
-- Table structure for auth_role
-- ----------------------------
DROP TABLE IF EXISTS `auth_role`;
CREATE TABLE `auth_role`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '角色名称',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父角色ID',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '状态',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '备注',
  `listorder` int(3) NOT NULL DEFAULT 0 COMMENT '排序，优先级，越小优先级越高',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '角色表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of auth_role
-- ----------------------------
INSERT INTO `auth_role` VALUES (1, '超级管理员', 0, 1, '拥有网站最高管理员权限！', 0);
INSERT INTO `auth_role` VALUES (4, '二级管理员', 1, 1, '', 999);

-- ----------------------------
-- Table structure for auth_role_admin
-- ----------------------------
DROP TABLE IF EXISTS `auth_role_admin`;
CREATE TABLE `auth_role_admin`  (
  `role_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '角色 id',
  `admin_id` int(10) NOT NULL DEFAULT 0 COMMENT '管理员id'
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户角色对应表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of auth_role_admin
-- ----------------------------
INSERT INTO `auth_role_admin` VALUES (1, 1);
INSERT INTO `auth_role_admin` VALUES (4, 23);
INSERT INTO `auth_role_admin` VALUES (4, 26);
INSERT INTO `auth_role_admin` VALUES (4, 27);

-- ----------------------------
-- Table structure for auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE `auth_rule`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NULL DEFAULT 1 COMMENT '认证类型',
  `pid` int(10) NOT NULL DEFAULT 0 COMMENT '父ID',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '规则名称',
  `title` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '规则名称',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态:0=禁用,1=正常',
  `condition` char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '条件\r\n条件',
  `ismenu` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否为菜单',
  `icon` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '图标',
  `listorder` int(10) NULL DEFAULT 999,
  `path` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '-' COMMENT '所有上级分类的ID',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `name`(`name`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 59 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of auth_rule
-- ----------------------------
INSERT INTO `auth_rule` VALUES (1, 1, 0, 'auth', '权限管理', 1, '', 1, 'fa fa-stumbleupon', 999, '-');
INSERT INTO `auth_rule` VALUES (2, 1, 1, 'auth/rule/index', '菜单规则', 1, '', 1, 'fa fa-bars fa-fw ', 999, '-1-');
INSERT INTO `auth_rule` VALUES (4, 1, 2, 'auth/rule/add', '添加规则', 1, '', 0, 'fa fa-circle-o', 999, '-1-2-');
INSERT INTO `auth_rule` VALUES (5, 1, 2, 'auth/rule/edit', 'Edit', 1, '', 0, 'fa fa-circle-o', 999, '-1-2-');
INSERT INTO `auth_rule` VALUES (6, 1, 2, 'auth/rule/del', 'Del', 1, '', 0, 'fa fa-circle-o', 999, '-1-2-');
INSERT INTO `auth_rule` VALUES (7, 1, 1, 'auth/admin/index', '管理员管理', 1, '', 1, 'fa fa-user', 999, '-1-');
INSERT INTO `auth_rule` VALUES (9, 1, 7, 'auth/admin/add', '添加管理员', 1, '', 0, 'fa fa-circle-o', 999, '-1-7-');
INSERT INTO `auth_rule` VALUES (10, 1, 7, 'auth/admin/edit', 'Edit', 1, '', 0, 'fa fa-circle-o', 999, '-1-7-');
INSERT INTO `auth_rule` VALUES (11, 1, 7, 'auth/admin/del', 'Del', 1, '', 0, 'fa fa-circle-o', 999, '-1-7-');
INSERT INTO `auth_rule` VALUES (12, 1, 1, 'auth/role/index', '角色组', 1, '', 1, 'fa fa-group', 999, '-1-');
INSERT INTO `auth_rule` VALUES (42, 1, 12, 'auth/role/authList', '获取授权列表', 1, '', 0, NULL, 999, '-1-12-');
INSERT INTO `auth_rule` VALUES (14, 1, 12, 'auth/role/add', '添加角色', 1, '', 0, 'fa fa-circle-o', 999, '-1-12-');
INSERT INTO `auth_rule` VALUES (15, 1, 12, 'auth/role/edit', 'Edit', 1, '', 0, 'fa fa-circle-o', 999, '-1-12-');
INSERT INTO `auth_rule` VALUES (16, 1, 12, 'auth/role/del', 'Del', 1, '', 0, 'fa fa-circle-o', 999, '-1-12-');
INSERT INTO `auth_rule` VALUES (17, 1, 0, 'room/index', '宿舍管理', 1, '', 1, 'fa fa-user', 999, '-');
INSERT INTO `auth_rule` VALUES (18, 1, 17, 'room/save', '添加宿舍', 1, '', 1, 'fa fa-user', 999, '-17-');
INSERT INTO `auth_rule` VALUES (46, 1, 17, 'room/delete', '宿舍删除', 1, '', 0, NULL, 999, '-17-');
INSERT INTO `auth_rule` VALUES (52, 1, 0, 'user/index', '员工管理', 1, '', 0, NULL, 999, '-');
INSERT INTO `auth_rule` VALUES (49, 1, 47, 'branch/save', '添加部门', 1, '', 0, NULL, 999, '-47-');
INSERT INTO `auth_rule` VALUES (50, 1, 47, 'branch/edit', '编辑部门', 1, '', 0, NULL, 999, '-47-');
INSERT INTO `auth_rule` VALUES (51, 1, 47, 'branch/delete', '删除部门', 1, '', 0, NULL, 999, '-47-');
INSERT INTO `auth_rule` VALUES (53, 1, 52, 'user/save', '添加员工', 1, '', 0, NULL, 999, '-52-');
INSERT INTO `auth_rule` VALUES (54, 1, 52, 'user/edit', '员工编辑', 1, '', 0, NULL, 999, '-52-');
INSERT INTO `auth_rule` VALUES (55, 1, 52, 'user/delete', '删除员工', 1, '', 0, NULL, 999, '-52-');
INSERT INTO `auth_rule` VALUES (56, 1, 0, 'report/index', '报表中心', 1, '', 0, NULL, 999, '0');
INSERT INTO `auth_rule` VALUES (58, 1, 56, 'report/getUser', '获取用户列表', 1, '', 0, NULL, 999, '056-');
INSERT INTO `auth_rule` VALUES (47, 1, 0, 'branch/index', '部门管理', 1, '', 0, NULL, 999, '-');
INSERT INTO `auth_rule` VALUES (45, 1, 17, 'room/edit', '宿舍编辑', 1, '', 0, NULL, 999, '-17-');
INSERT INTO `auth_rule` VALUES (44, 1, 12, 'auth/role/auth', '授权', 1, '', 0, NULL, 999, '-1-12-');

-- ----------------------------
-- Table structure for auth_rule_role
-- ----------------------------
DROP TABLE IF EXISTS `auth_rule_role`;
CREATE TABLE `auth_rule_role`  (
  `rule_id` int(10) NOT NULL COMMENT '规则ID',
  `role_id` int(10) NOT NULL COMMENT '角色ID'
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of auth_rule_role
-- ----------------------------
INSERT INTO `auth_rule_role` VALUES (1, 3);
INSERT INTO `auth_rule_role` VALUES (2, 3);
INSERT INTO `auth_rule_role` VALUES (4, 3);
INSERT INTO `auth_rule_role` VALUES (5, 3);
INSERT INTO `auth_rule_role` VALUES (6, 3);
INSERT INTO `auth_rule_role` VALUES (7, 3);
INSERT INTO `auth_rule_role` VALUES (9, 3);
INSERT INTO `auth_rule_role` VALUES (10, 3);
INSERT INTO `auth_rule_role` VALUES (11, 3);
INSERT INTO `auth_rule_role` VALUES (12, 3);
INSERT INTO `auth_rule_role` VALUES (14, 3);
INSERT INTO `auth_rule_role` VALUES (15, 3);
INSERT INTO `auth_rule_role` VALUES (16, 3);
INSERT INTO `auth_rule_role` VALUES (42, 3);
INSERT INTO `auth_rule_role` VALUES (1, 4);
INSERT INTO `auth_rule_role` VALUES (2, 4);
INSERT INTO `auth_rule_role` VALUES (4, 4);
INSERT INTO `auth_rule_role` VALUES (5, 4);
INSERT INTO `auth_rule_role` VALUES (6, 4);
INSERT INTO `auth_rule_role` VALUES (7, 4);
INSERT INTO `auth_rule_role` VALUES (9, 4);
INSERT INTO `auth_rule_role` VALUES (10, 4);
INSERT INTO `auth_rule_role` VALUES (11, 4);
INSERT INTO `auth_rule_role` VALUES (12, 4);
INSERT INTO `auth_rule_role` VALUES (14, 4);
INSERT INTO `auth_rule_role` VALUES (15, 4);
INSERT INTO `auth_rule_role` VALUES (16, 4);
INSERT INTO `auth_rule_role` VALUES (42, 4);
INSERT INTO `auth_rule_role` VALUES (44, 4);
INSERT INTO `auth_rule_role` VALUES (17, 4);
INSERT INTO `auth_rule_role` VALUES (18, 4);
INSERT INTO `auth_rule_role` VALUES (45, 4);
INSERT INTO `auth_rule_role` VALUES (46, 4);
INSERT INTO `auth_rule_role` VALUES (52, 4);
INSERT INTO `auth_rule_role` VALUES (53, 4);
INSERT INTO `auth_rule_role` VALUES (54, 4);
INSERT INTO `auth_rule_role` VALUES (55, 4);

SET FOREIGN_KEY_CHECKS = 1;
