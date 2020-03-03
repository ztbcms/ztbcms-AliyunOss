SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cms_aliyun_oss_conf
-- ----------------------------
DROP TABLE IF EXISTS `cms_aliyun_oss_conf`;
CREATE TABLE `cms_aliyun_oss_conf`  (
  `id` int(15) UNSIGNED NOT NULL AUTO_INCREMENT,
  `accesskey_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `accesskey_secret` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `edit_time` int(15) NOT NULL COMMENT '最后编辑时间',
  `bucket` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'bucket 列表',
  `validity` int(255) NULL DEFAULT NULL COMMENT '有效期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cms_aliyun_oss_style
-- ----------------------------
DROP TABLE IF EXISTS `cms_aliyun_oss_style`;
CREATE TABLE `cms_aliyun_oss_style`  (
  `id` int(15) UNSIGNED NOT NULL AUTO_INCREMENT,
  `watermarkenable` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `watermarkminwidth` double NULL DEFAULT NULL,
  `watermarkminheight` double NULL DEFAULT NULL,
  `watermarkimg` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `watermarkpct` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `watermarkquality` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `watermarkpos` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `edit_time` int(15) NULL DEFAULT NULL COMMENT '最后编辑时间',
  `pictures_length` int(15) NULL DEFAULT NULL COMMENT '图片长度',
  `pictures_width` int(15) NULL DEFAULT NULL COMMENT '图片宽度',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '样式标题',
  `is_display` int(1) UNSIGNED NULL DEFAULT NULL,
  `is_delete` int(1) UNSIGNED NULL DEFAULT NULL,
  `listorder` int(20) UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
