-- phpMyAdmin SQL Dump
-- version 4.2.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2014-09-22 18:21:45
-- 服务器版本： 5.6.19
-- PHP Version: 5.5.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `wx_infos`
--
CREATE DATABASE IF NOT EXISTS `wx_infos` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `wx_infos`;

-- --------------------------------------------------------

--
-- 表的结构 `wx_article`
--

DROP TABLE IF EXISTS `wx_article`;
CREATE TABLE IF NOT EXISTS `wx_article` (
`id` int(11) NOT NULL COMMENT '主键id',
  `uid` int(11) NOT NULL COMMENT '公众号id',
  `wzurl` varchar(200) NOT NULL DEFAULT '' COMMENT '文章url',
  `imgurl` varchar(200) NOT NULL DEFAULT '' COMMENT '文章列表图片地址',
  `wztitle` varchar(100) NOT NULL DEFAULT '' COMMENT '文章标题',
  `wzcontent` text COMMENT '文章内容源码',
  `description` varchar(300) NOT NULL DEFAULT '' COMMENT '文章描述',
  `wzreads` int(11) NOT NULL DEFAULT '0' COMMENT '阅读数',
  `wzsuports` int(11) NOT NULL DEFAULT '0' COMMENT '点赞数',
  `ctime` int(11) NOT NULL DEFAULT '0' COMMENT '文章创建创建时间',
  `gtime` int(11) NOT NULL DEFAULT '0' COMMENT '开始时间，默认立即开始',
  `ntime` int(11) NOT NULL DEFAULT '0',
  `numbers` int(11) NOT NULL DEFAULT '1' COMMENT '一天采集次数',
  `days` int(11) NOT NULL DEFAULT '1' COMMENT '采集天数，默认当天',
  `state` int(11) NOT NULL DEFAULT '0' COMMENT '状态，默认为采集中',
  `uctime` int(11) NOT NULL DEFAULT '0' COMMENT '链接创建时间'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='文章信息表' AUTO_INCREMENT=1234 ;

-- --------------------------------------------------------

--
-- 表的结构 `wx_keys`
--

DROP TABLE IF EXISTS `wx_keys`;
CREATE TABLE IF NOT EXISTS `wx_keys` (
`id` int(11) NOT NULL,
  `keys` varchar(300) NOT NULL DEFAULT '',
  `ctime` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wx_pinfo`
--

DROP TABLE IF EXISTS `wx_pinfo`;
CREATE TABLE IF NOT EXISTS `wx_pinfo` (
`id` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  `gname` varchar(45) NOT NULL DEFAULT '' COMMENT '公众号名称',
  `gnumber` varchar(45) NOT NULL DEFAULT '' COMMENT '公众号微信号',
  `wzurl` varchar(200) NOT NULL DEFAULT '' COMMENT '采集指定文章url地址',
  `ctime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `cname` varchar(45) NOT NULL DEFAULT '' COMMENT '创建人',
  `days` int(11) NOT NULL DEFAULT '1' COMMENT '采集天数，默认当天',
  `gtime` int(11) NOT NULL DEFAULT '0' COMMENT '开始时间，默认立即开始',
  `ntime` int(11) NOT NULL DEFAULT '0',
  `numbers` int(11) NOT NULL DEFAULT '1' COMMENT '一天采集次数，默认一次',
  `state` int(11) NOT NULL DEFAULT '0' COMMENT '采集状态，0为采集中，1为完成',
  `updates` int(1) NOT NULL DEFAULT '0' COMMENT '是否自动更新'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='公账号信息表' AUTO_INCREMENT=60 ;

-- --------------------------------------------------------

--
-- 表的结构 `wx_type`
--

DROP TABLE IF EXISTS `wx_type`;
CREATE TABLE IF NOT EXISTS `wx_type` (
`id` int(11) NOT NULL COMMENT '主键id',
  `wx_type` varchar(45) NOT NULL DEFAULT '' COMMENT '公账号类型',
  `ctime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `cname` varchar(45) NOT NULL DEFAULT '' COMMENT '创建人'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='微信公众号类型表' AUTO_INCREMENT=26 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wx_article`
--
ALTER TABLE `wx_article`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `wzurl` (`wzurl`), ADD KEY `uid` (`uid`);

--
-- Indexes for table `wx_keys`
--
ALTER TABLE `wx_keys`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wx_pinfo`
--
ALTER TABLE `wx_pinfo`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `gnumber` (`gnumber`), ADD KEY `tid` (`tid`);

--
-- Indexes for table `wx_type`
--
ALTER TABLE `wx_type`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `wx_type` (`wx_type`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wx_article`
--
ALTER TABLE `wx_article`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id',AUTO_INCREMENT=1234;
--
-- AUTO_INCREMENT for table `wx_keys`
--
ALTER TABLE `wx_keys`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `wx_pinfo`
--
ALTER TABLE `wx_pinfo`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=60;
--
-- AUTO_INCREMENT for table `wx_type`
--
ALTER TABLE `wx_type`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id',AUTO_INCREMENT=26;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
