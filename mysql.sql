-- phpMyAdmin SQL Dump
-- version 4.1.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2014-01-07 09:59:53
-- 服务器版本： 5.6.15
-- PHP Version: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `xxxb`
--

-- --------------------------------------------------------

--
-- 表的结构 `zx_admin`
--

CREATE TABLE IF NOT EXISTS `zx_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL,
  `content` varchar(512) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `zx_category`
--

CREATE TABLE IF NOT EXISTS `zx_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `alias` varchar(128) NOT NULL COMMENT '别名，英文字符',
  `description` varchar(128) NOT NULL,
  `type` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `zx_category`
--

INSERT INTO `zx_category` (`id`, `name`, `alias`, `description`, `type`) VALUES
(1, '导航栏', 'home', '', 'link'),
(2, '教务教学', 'education', '', 'news'),
(3, '师资状况', 'teacher', '', 'news'),
(4, '新闻动态', 'news', '', 'news'),
(5, '友情链接', 'friends', '', 'link');

-- --------------------------------------------------------

--
-- 表的结构 `zx_count`
--

CREATE TABLE IF NOT EXISTS `zx_count` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(256) NOT NULL,
  `user` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(40) NOT NULL,
  `ua` varchar(256) NOT NULL,
  `from` varchar(256) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `type` varchar(10) NOT NULL DEFAULT 'view',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `zx_group`
--

CREATE TABLE IF NOT EXISTS `zx_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `des` varchar(1024) NOT NULL COMMENT '描述',
  `lader` int(11) NOT NULL DEFAULT '0' COMMENT '组长',
  `parent` int(11) NOT NULL DEFAULT '0' COMMENT '该分组的上级分组',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `zx_group`
--

INSERT INTO `zx_group` (`id`, `name`, `des`, `lader`, `parent`) VALUES
(1, '管理组', '', 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `zx_link`
--

CREATE TABLE IF NOT EXISTS `zx_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `description` varchar(128) NOT NULL DEFAULT '',
  `url` varchar(256) NOT NULL,
  `image` varchar(256) NOT NULL DEFAULT '',
  `flag` varchar(64) NOT NULL DEFAULT '' COMMENT '根据页面属性判断',
  `category` int(11) NOT NULL,
  `no` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;

--
-- 转存表中的数据 `zx_link`
--

INSERT INTO `zx_link` (`id`, `name`, `description`, `url`, `image`, `flag`, `category`, `no`) VALUES
(33, '城建学部', '', 'http://www.hustwenhua.net/cjxb', '', 'cjxb', 5, 2),
(32, '文华学院', '', 'http://www.hustwenhua.net', '', 'hustwenhua', 5, 1),
(5, '教务教学', '', 'http://127.0.0.1/TeamHome/education', '', 'education', 1, 4),
(4, '留言咨询', '', 'http://127.0.0.1/TeamHome/gust_book', '', 'gust_book', 1, 5),
(3, '师资状况', '', 'http://127.0.0.1/TeamHome/teacher', '', 'teacher', 1, 3),
(2, '新闻动态', '', 'http://127.0.0.1/TeamHome/news', '', 'news', 1, 2),
(1, '主页', '', 'http://127.0.0.1/TeamHome/', '', 'index', 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `zx_news`
--

CREATE TABLE IF NOT EXISTS `zx_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  `author` varchar(128) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `content` text NOT NULL,
  `category` varchar(128) NOT NULL COMMENT '分类',
  `type` varchar(64) NOT NULL COMMENT '类型，publish,draft,hidden',
  `comment` varchar(5) NOT NULL DEFAULT 'flase' COMMENT '是否允许评论',
  `user` int(11) NOT NULL DEFAULT '0' COMMENT '发布者ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- 转存表中的数据 `zx_news`
--

INSERT INTO `zx_news` (`id`, `title`, `author`, `time`, `content`, `category`, `type`, `comment`, `user`) VALUES
(1, '发展仍依靠教师', 'test', '2013-09-11 13:30:46', '<p style="font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	<strong>文新社讯</strong> \r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	&nbsp;&nbsp;&nbsp;&nbsp;9月9日下午，为庆祝我国第29个教师节，我校在学术报告厅召开了教师表彰大会。校党委书记于清双，董事会董事李俊，副院长严国萍、陈思中、汪元柱、程红，董事长助理肖行定等领导出席了大会，我校部分教职工及学生代表参加大会。\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	&nbsp;<span style="line-height:1.5;">大会伊始，严国萍宣读了我校对容太平等71名优秀教师的表彰决定。“全体教职工应以这些优秀教职工为榜样，努力探索实践，积极改革教育方法和手段，为促进学校的改革、发展以及培养出更多的人才做出突出贡献。”严国萍说道。</span> \r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	&nbsp;\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	&nbsp; &nbsp; &nbsp; &nbsp; 随后，学校领导依次为受表彰的教师颁发证书。谢柏林等2人获得“伯乐奖”，顾念念等7人获“三育人奖”，郭卓文等33人获“三育人积极分子”，黄龙等29人获“教学质量优秀职工奖”。\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	&nbsp;\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	&nbsp; &nbsp; &nbsp; &nbsp; 会上，谢柏林等5位获奖教师代表发言分享教学心得。“辅导员，就是这样一群人，他们与学生相伴相随，辅导并引导学生们通过自我教育和相互教育，充分的开发潜能，成为学生们大学生涯里一个有力的支点。”“三育人奖”获得者、信息学部辅导员顾念念感慨道。\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	&nbsp;\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	&nbsp; &nbsp; &nbsp; &nbsp; 最后，于清双在大会上总结道：“‘中国梦’归根结底取决于教育，我们广大教师要以自己的高尚品德和渊博学识，培养一代又一代中国特色社会主义的合格建设者和接班人。”他还根据我校10年发展历程总结出接下来的“十年发展”期：要实现学校的“优化结构，重点突破”，形成鲜明的学科特色、文化特色和服务特色的总体目标，仍然要依靠教师。\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	&nbsp;\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	&nbsp; &nbsp; &nbsp; &nbsp; 同时，于清双还强调我校要加强教师队伍建设，重视科研工作，鼓励教师与学生多参加实践活动。教师应以学生为中心，坚持上下求索、科研并举的进取精神，跟国家的需要结合起来，使学生更加适应社会。\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	&nbsp;\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	&nbsp; &nbsp; &nbsp; 来自2010级光电信息工程专业的董浩波作为此次大会的学生代表发言：“文华是放飞梦想的地方，学识渊博且平易近人的老师们给予我们飞翔的翅膀，我们定不负众望展翅飞翔。”\r\n</p>', '2', 'publish', 'false', 1),
(2, '为新生敲响警钟 我校举行安全教育报告会', 'test', '2013-09-11 23:31:24', '<p style="font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	&nbsp;&nbsp;&nbsp;&nbsp;9月10日晚，我校新生安全教育报告会在操场上举行，武汉市东湖高新关南派出所陈贵良警官、东湖高新消防大队李成忠警官为新生作了安全教育报告会，学生工作处处长赵荣昌主持此次报告会。\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	&nbsp; &nbsp; 陈贵良围绕盗窃和诈骗展开了此次报告会，他通过分析各类高校的盗窃诈骗案例，剖析了盗窃和诈骗的原因和特点。“盗窃的类型有三种：外盗、内盗和内外勾结。盗窃的手段一般有‘顺手牵羊’、‘趁虚而入’、‘窗边钓鱼’。而盗窃也有早有预谋、目标准确、技术娴熟的特点。”他解释道，“某些不法分子还会利用大学生的恻隐之心和贪小便宜的心理进行诈骗。”\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	&nbsp; &nbsp; 报告会上，陈贵良对新生提示道，高校盗窃诈骗的作案地点一般是操场、体育馆、食堂、银行和宿舍等人流量特别大的地方。对此，他向新生们提出了几点防盗防受骗的建议：“我们自身要加强防范意识，离开宿舍的时候注意关窗锁门，不要留宿外来人员，贵重物品不要随便乱放，不要随身携带过多现金，取钱时留意身边是否有人等等。”\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	&nbsp; &nbsp; 李成忠主要讲火灾以及如何防御，他向新生讲述了四例高校火灾案例。“发生火灾的基本条件是可燃物、助燃物和引火源。”李成忠说道：“平时在学校里，我们要了解学校的灭火器、消防栓以及紧急出口的位置，不要在宿舍里使用大功率电器。万一发生火灾，要沉着冷静，用打湿的毛巾捂住口鼻蹲在地上沿着墙角走出火灾发生地。”李成忠生动的讲解和有条不紊的思路赢得了在场学生的热烈掌声。\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	&nbsp; &nbsp; “每年新生入学，我们都会举办安全教育知识报告会，就是希望大家可以在一开始就提高自己的防范意识。社会是复杂的，有许多不安全因素，我们需要提高警惕以保证自身安全。希望大家可以把这些运用到实际生活中去。”赵荣昌在最后总结道。\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	&nbsp; &nbsp; “听了陈警官和李警官的报告后，我对防火防盗防骗有了更深一步的了解，知道危害出自身边。我以后会增强自我保护意识，把防范措施运用到实际生活中去。”2013级财务管理专业的钟美琪说道。\r\n</p>', '4', 'publish', 'false', 1),
(3, '为新生敲响警钟 我校举行安全教育报告会', 'test', '2013-09-11 23:31:33', '<p style="font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	&nbsp;&nbsp;&nbsp;&nbsp;9月10日晚，我校新生安全教育报告会在操场上举行，武汉市东湖高新关南派出所陈贵良警官、东湖高新消防大队李成忠警官为新生作了安全教育报告会，学生工作处处长赵荣昌主持此次报告会。\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	&nbsp; &nbsp; 陈贵良围绕盗窃和诈骗展开了此次报告会，他通过分析各类高校的盗窃诈骗案例，剖析了盗窃和诈骗的原因和特点。“盗窃的类型有三种：外盗、内盗和内外勾结。盗窃的手段一般有‘顺手牵羊’、‘趁虚而入’、‘窗边钓鱼’。而盗窃也有早有预谋、目标准确、技术娴熟的特点。”他解释道，“某些不法分子还会利用大学生的恻隐之心和贪小便宜的心理进行诈骗。”\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	&nbsp; &nbsp; 报告会上，陈贵良对新生提示道，高校盗窃诈骗的作案地点一般是操场、体育馆、食堂、银行和宿舍等人流量特别大的地方。对此，他向新生们提出了几点防盗防受骗的建议：“我们自身要加强防范意识，离开宿舍的时候注意关窗锁门，不要留宿外来人员，贵重物品不要随便乱放，不要随身携带过多现金，取钱时留意身边是否有人等等。”\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	&nbsp; &nbsp; 李成忠主要讲火灾以及如何防御，他向新生讲述了四例高校火灾案例。“发生火灾的基本条件是可燃物、助燃物和引火源。”李成忠说道：“平时在学校里，我们要了解学校的灭火器、消防栓以及紧急出口的位置，不要在宿舍里使用大功率电器。万一发生火灾，要沉着冷静，用打湿的毛巾捂住口鼻蹲在地上沿着墙角走出火灾发生地。”李成忠生动的讲解和有条不紊的思路赢得了在场学生的热烈掌声。\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	&nbsp; &nbsp; “每年新生入学，我们都会举办安全教育知识报告会，就是希望大家可以在一开始就提高自己的防范意识。社会是复杂的，有许多不安全因素，我们需要提高警惕以保证自身安全。希望大家可以把这些运用到实际生活中去。”赵荣昌在最后总结道。\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	&nbsp; &nbsp; “听了陈警官和李警官的报告后，我对防火防盗防骗有了更深一步的了解，知道危害出自身边。我以后会增强自我保护意识，把防范措施运用到实际生活中去。”2013级财务管理专业的钟美琪说道。\r\n</p>', '3', 'publish', 'false', 1),
(4, '为新生敲响警钟 我校举行安全教育报告会', 'test', '2013-09-11 23:31:37', '<p style="font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	&nbsp;&nbsp;&nbsp;&nbsp;9月10日晚，我校新生安全教育报告会在操场上举行，武汉市东湖高新关南派出所陈贵良警官、东湖高新消防大队李成忠警官为新生作了安全教育报告会，学生工作处处长赵荣昌主持此次报告会。\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	&nbsp; &nbsp; 陈贵良围绕盗窃和诈骗展开了此次报告会，他通过分析各类高校的盗窃诈骗案例，剖析了盗窃和诈骗的原因和特点。“盗窃的类型有三种：外盗、内盗和内外勾结。盗窃的手段一般有‘顺手牵羊’、‘趁虚而入’、‘窗边钓鱼’。而盗窃也有早有预谋、目标准确、技术娴熟的特点。”他解释道，“某些不法分子还会利用大学生的恻隐之心和贪小便宜的心理进行诈骗。”\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	&nbsp; &nbsp; 报告会上，陈贵良对新生提示道，高校盗窃诈骗的作案地点一般是操场、体育馆、食堂、银行和宿舍等人流量特别大的地方。对此，他向新生们提出了几点防盗防受骗的建议：“我们自身要加强防范意识，离开宿舍的时候注意关窗锁门，不要留宿外来人员，贵重物品不要随便乱放，不要随身携带过多现金，取钱时留意身边是否有人等等。”\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	&nbsp; &nbsp; 李成忠主要讲火灾以及如何防御，他向新生讲述了四例高校火灾案例。“发生火灾的基本条件是可燃物、助燃物和引火源。”李成忠说道：“平时在学校里，我们要了解学校的灭火器、消防栓以及紧急出口的位置，不要在宿舍里使用大功率电器。万一发生火灾，要沉着冷静，用打湿的毛巾捂住口鼻蹲在地上沿着墙角走出火灾发生地。”李成忠生动的讲解和有条不紊的思路赢得了在场学生的热烈掌声。\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	&nbsp; &nbsp; “每年新生入学，我们都会举办安全教育知识报告会，就是希望大家可以在一开始就提高自己的防范意识。社会是复杂的，有许多不安全因素，我们需要提高警惕以保证自身安全。希望大家可以把这些运用到实际生活中去。”赵荣昌在最后总结道。\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	&nbsp; &nbsp; “听了陈警官和李警官的报告后，我对防火防盗防骗有了更深一步的了解，知道危害出自身边。我以后会增强自我保护意识，把防范措施运用到实际生活中去。”2013级财务管理专业的钟美琪说道。\r\n</p>', '2', 'publish', 'false', 1),
(5, '为新生敲响警钟 我校举行安全教育报告会', 'test', '2013-09-11 23:31:40', '<p style="text-align:center;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	<img src="/attached/image/20130912/20130912073748_43302.jpg" alt="" />\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	&nbsp;&nbsp;9月10日晚，我校新生安全教育报告会在操场上举行，武汉市东湖高新关南派出所陈贵良警官、东湖高新消防大队李成忠警官为新生作了安全教育报告会，学生工作处处长赵荣昌主持此次报告会。\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	&nbsp; &nbsp; 陈贵良围绕盗窃和诈骗展开了此次报告会，他通过分析各类高校的盗窃诈骗案例，剖析了盗窃和诈骗的原因和特点。“盗窃的类型有三种：外盗、内盗和内外勾结。盗窃的手段一般有‘顺手牵羊’、‘趁虚而入’、‘窗边钓鱼’。而盗窃也有早有预谋、目标准确、技术娴熟的特点。”他解释道，“某些不法分子还会利用大学生的恻隐之心和贪小便宜的心理进行诈骗。”\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	&nbsp; &nbsp; 报告会上，陈贵良对新生提示道，高校盗窃诈骗的作案地点一般是操场、体育馆、食堂、银行和宿舍等人流量特别大的地方。对此，他向新生们提出了几点防盗防受骗的建议：“我们自身要加强防范意识，离开宿舍的时候注意关窗锁门，不要留宿外来人员，贵重物品不要随便乱放，不要随身携带过多现金，取钱时留意身边是否有人等等。”\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	&nbsp; &nbsp; 李成忠主要讲火灾以及如何防御，他向新生讲述了四例高校火灾案例。“发生火灾的基本条件是可燃物、助燃物和引火源。”李成忠说道：“平时在学校里，我们要了解学校的灭火器、消防栓以及紧急出口的位置，不要在宿舍里使用大功率电器。万一发生火灾，要沉着冷静，用打湿的毛巾捂住口鼻蹲在地上沿着墙角走出火灾发生地。”李成忠生动的讲解和有条不紊的思路赢得了在场学生的热烈掌声。\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	&nbsp; &nbsp; “每年新生入学，我们都会举办安全教育知识报告会，就是希望大家可以在一开始就提高自己的防范意识。社会是复杂的，有许多不安全因素，我们需要提高警惕以保证自身安全。希望大家可以把这些运用到实际生活中去。”赵荣昌在最后总结道。\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	&nbsp; &nbsp; “听了陈警官和李警官的报告后，我对防火防盗防骗有了更深一步的了解，知道危害出自身边。我以后会增强自我保护意识，把防范措施运用到实际生活中去。”2013级财务管理专业的钟美琪说道。\r\n</p>', '3', 'publish', 'false', 1),
(6, '发展仍依靠教师', 'test', '2013-09-13 07:01:56', '<span style="color:#0050A1;font-family:宋体;font-size:14px;line-height:25px;background-color:#FFFFFF;">&nbsp;&nbsp;&nbsp;&nbsp;华中科技大学文华学院信息科学与技术学部，现设光电信息工程、通信工程、电子科学与技术、软件工程、计算机科学与技术、自动化、电子与信息工程、计算机应用技术（专），8个专业，其中本科7个，专科１个。另设有计算机、电子线路2个教研室。信息技术创新基地、电工与电子教学基地、电子通信实训基地3个基地。嵌入式信息。。。</span>', '2', 'publish', 'false', 1),
(7, '发展仍依靠教师', 'test', '2013-09-13 07:01:59', '<span style="color:#0050A1;font-family:宋体;font-size:14px;line-height:25px;background-color:#FFFFFF;">&nbsp;&nbsp;&nbsp;&nbsp;华中科技大学文华学院信息科学与技术学部，现设光电信息工程、通信工程、电子科学与技术、软件工程、计算机科学与技术、自动化、电子与信息工程、计算机应用技术（专），8个专业，其中本科7个，专科１个。另设有计算机、电子线路2个教研室。信息技术创新基地、电工与电子教学基地、电子通信实训基地3个基地。嵌入式信息。。。</span>', '2', 'publish', 'false', 1),
(8, '发展仍依靠教师', 'test', '2013-09-13 07:02:01', '<span style="color:#0050A1;font-family:宋体;font-size:14px;line-height:25px;background-color:#FFFFFF;">&nbsp;&nbsp;&nbsp;&nbsp;华中科技大学文华学院信息科学与技术学部，现设光电信息工程、通信工程、电子科学与技术、软件工程、计算机科学与技术、自动化、电子与信息工程、计算机应用技术（专），8个专业，其中本科7个，专科１个。另设有计算机、电子线路2个教研室。信息技术创新基地、电工与电子教学基地、电子通信实训基地3个基地。嵌入式信息。。。</span>', '2', 'publish', 'false', 1),
(9, '发展仍依靠教师', 'test', '2013-09-13 07:02:03', '<span style="color:#0050A1;font-family:宋体;font-size:14px;line-height:25px;background-color:#FFFFFF;">&nbsp;&nbsp;&nbsp;&nbsp;华中科技大学文华学院信息科学与技术学部，现设光电信息工程、通信工程、电子科学与技术、软件工程、计算机科学与技术、自动化、电子与信息工程、计算机应用技术（专），8个专业，其中本科7个，专科１个。另设有计算机、电子线路2个教研室。信息技术创新基地、电工与电子教学基地、电子通信实训基地3个基地。嵌入式信息。。。</span>', '2', 'publish', 'false', 1),
(10, '发展仍依靠教师', 'test', '2013-09-13 07:02:05', '<span style="color:#0050A1;font-family:宋体;font-size:14px;line-height:25px;background-color:#FFFFFF;">&nbsp;&nbsp;&nbsp;&nbsp;华中科技大学文华学院信息科学与技术学部，现设光电信息工程、通信工程、电子科学与技术、软件工程、计算机科学与技术、自动化、电子与信息工程、计算机应用技术（专），8个专业，其中本科7个，专科１个。另设有计算机、电子线路2个教研室。信息技术创新基地、电工与电子教学基地、电子通信实训基地3个基地。嵌入式信息。。。</span>', '2', 'publish', 'false', 1);

-- --------------------------------------------------------

--
-- 表的结构 `zx_plugin`
--

CREATE TABLE IF NOT EXISTS `zx_plugin` (
  `name` varchar(50) NOT NULL,
  `type` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `zx_plugin`
--

INSERT INTO `zx_plugin` (`name`, `type`) VALUES
('home', 'init');

-- --------------------------------------------------------

--
-- 表的结构 `zx_setting`
--

CREATE TABLE IF NOT EXISTS `zx_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(128) NOT NULL,
  `value` text NOT NULL,
  `load` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- 转存表中的数据 `zx_setting`
--

INSERT INTO `zx_setting` (`id`, `name`, `value`, `load`) VALUES
(1, 'site_name', '计算机系', '1'),
(2, 'site_url', 'http://127.0.0.1/TeamHome/', '1'),
(3, 'site_key', '1584ajf73htst21afao9gf8', '1'),
(4, 'site_cookie_time', '144', '1'),
(5, 'site_style', 'default', '1'),
(6, 'mail_config', 'useragent:zhixing;type:smtp;smtp_host:smtp.qq.com;smtp_port:25;smtp_user:admin;smtp_pass:admin;smtp_auth:true;smtp_debug:0;from_email:admin@admin.com;from_name:admin', '0'),
(7, 'site_register', '1', '1'),
(8, 'admin_email', '386372049@qq.com', '1'),
(9, 'user_manage_one_page_number', '20', '1'),
(10, 'news_one_page_number', '3', '1'),
(11, 'team_about', '<span style="color:#0050A1;font-family:宋体;font-size:14px;line-height:25px;background-color:#FFFFFF;">&nbsp;&nbsp;&nbsp;&nbsp;华中科技大学文华学院信息科学与技术学部，现设光电信息工程、通信工程、电子科学与技术、软件工程、计算机科学与技术、自动化、电子与信息工程、计算机应用技术（专），8个专业，其中本科7个，专科１个。另设有计算机、电子线路2个教研室。信息技术创新基地、电工与电子教学基地、电子通信实训基地3个基地。嵌入式信息。。。</span>', '1'),
(12, 'super_password', 'dcdcb2e232da0d10516807a8c04d1c8ebd939479', '1'),
(13, 'nav_link', '1', '1'),
(14, 'friend_link', '5', '1'),
(15, 'team_proclamation', '', '1'),
(16, 'PCount', 'count', '1'),
(17, 'PHome', '[{"query":"news","file":"news","param":{"id":["2","3","4"]}},{"query":"teacher","file":"teacher","param":[]},{"query":"education","file":"education","param":{"id":["2","3"]}},{"query":"gust_book","file":"gust_book","param":[]},{"query":"{number}.html","file":"post","param":{"id":"{number}"}},{"query":"cat\\/{number}","file":"cat","param":{"id":"{number}"}}]', '1'),
(18, 'site_description', '华中科技大学文华学院信息学部计算机系网站', '1');

-- --------------------------------------------------------

--
-- 表的结构 `zx_user`
--

CREATE TABLE IF NOT EXISTS `zx_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(128) NOT NULL COMMENT '用户邮箱',
  `username` varchar(128) NOT NULL DEFAULT 'NULL' COMMENT '昵称(唯一)',
  `password` varchar(40) NOT NULL COMMENT '加密后的密码',
  `lock` varchar(5) NOT NULL DEFAULT 'false' COMMENT '用户是否锁定登陆默认false',
  `active` varchar(5) NOT NULL DEFAULT 'false' COMMENT '是否激活',
  `verify` varchar(6) NOT NULL DEFAULT 'false',
  `power` int(2) NOT NULL DEFAULT '2' COMMENT '用户权限 0 最高',
  `name` varchar(128) NOT NULL DEFAULT 'NULL' COMMENT '名字',
  `sex` varchar(10) NOT NULL DEFAULT 'NULL' COMMENT '性别',
  `class` int(2) NOT NULL DEFAULT '0' COMMENT '班级',
  `tel` varchar(11) NOT NULL DEFAULT 'NULL' COMMENT '电话',
  `mtel` varchar(11) NOT NULL DEFAULT 'NULL' COMMENT '短号',
  `qq` varchar(12) NOT NULL DEFAULT 'NULL' COMMENT 'QQ号',
  `grade` int(5) NOT NULL DEFAULT '0' COMMENT '年级',
  `subject` varchar(128) NOT NULL DEFAULT 'NULL' COMMENT '专业',
  `faculty` varchar(128) NOT NULL DEFAULT 'NULL' COMMENT '学部',
  `group1` int(4) NOT NULL DEFAULT '0' COMMENT '一级分组',
  `group2` int(4) NOT NULL DEFAULT '0' COMMENT '二级分组',
  `describe` varchar(1024) NOT NULL DEFAULT 'NULL' COMMENT '个人描述',
  `sign` varchar(512) NOT NULL DEFAULT 'NULL' COMMENT '签名',
  `specialty` varchar(1024) NOT NULL DEFAULT 'NULL',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `zx_user`
--

INSERT INTO `zx_user` (`id`, `user`, `username`, `password`, `lock`, `active`, `verify`, `power`, `name`, `sex`, `class`, `tel`, `mtel`, `qq`, `grade`, `subject`, `faculty`, `group1`, `group2`, `describe`, `sign`, `specialty`) VALUES
(1, 'admin@admin.com', 'admin', 'f4433fdffb37b20c80032baf91832e2b0070adf8', 'false', 'true', 'true', 0, '', '男', 0, '', '', '', 0, '', '', 0, 0, 'NULL', 'NULL', 'NULL');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
