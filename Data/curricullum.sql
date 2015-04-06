-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-04-2015 a las 23:18:40
-- Versión del servidor: 5.5.27
-- Versión de PHP: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `curricullum`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `action`
--

CREATE TABLE IF NOT EXISTS `action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(45) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `createuser` varchar(45) NOT NULL,
  `cratedate` datetime NOT NULL,
  `modifyuser` varchar(45) NOT NULL,
  `modifydate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `curricullum`
--

CREATE TABLE IF NOT EXISTS `curricullum` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) DEFAULT NULL,
  `maintext` tinytext,
  `aboutme` text,
  `contactdetails` tinytext,
  `mainskills` tinytext,
  `createuser` varchar(45) NOT NULL,
  `createdate` datetime NOT NULL,
  `modifyuser` varchar(45) NOT NULL,
  `modifydate` datetime NOT NULL,
  `filename` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `curricullum`
--

INSERT INTO `curricullum` (`id`, `name`, `maintext`, `aboutme`, `contactdetails`, `mainskills`, `createuser`, `createdate`, `modifyuser`, `modifydate`, `filename`) VALUES
(1, 'Andr&eacute;s Franco', 'Maintext&lt;br&gt;', 'Aboutme text&lt;br&gt;', 'Contact Details Text&lt;br&gt;', 'Main Skills Text&lt;br&gt;', 'admin', '2015-03-17 16:10:07', 'admin', '2015-03-17 16:13:56', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `education`
--

CREATE TABLE IF NOT EXISTS `education` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `curricullumid` int(11) NOT NULL,
  `institution` varchar(60) DEFAULT NULL,
  `degree` varchar(60) DEFAULT NULL,
  `datechar` varchar(45) DEFAULT NULL,
  `createuser` varchar(45) NOT NULL,
  `createdate` datetime NOT NULL,
  `modifyuser` varchar(45) NOT NULL,
  `modifydate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_education_curricullum1_idx` (`curricullumid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `education`
--

INSERT INTO `education` (`id`, `curricullumid`, `institution`, `degree`, `datechar`, `createuser`, `createdate`, `modifyuser`, `modifydate`) VALUES
(1, 1, 'Universidad Tecnologica de Panamá', 'System Information engeeniering', '2009', 'admin', '2015-03-17 16:10:07', 'admin', '2015-03-17 16:10:07'),
(2, 1, 'sdsdd', 'sdds', 'sddsds', 'admin', '2015-03-31 22:42:41', 'admin', '2015-03-31 22:42:41');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `language`
--

CREATE TABLE IF NOT EXISTS `language` (
  `code` varchar(10) NOT NULL,
  `language` varchar(60) DEFAULT NULL,
  `createuser` varchar(45) NOT NULL,
  `createdate` datetime NOT NULL,
  `modifyuser` varchar(45) NOT NULL,
  `modifydate` datetime NOT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `language`
--

INSERT INTO `language` (`code`, `language`, `createuser`, `createdate`, `modifyuser`, `modifydate`) VALUES
('en', 'English', 'admin', '2015-03-04 11:25:23', 'admin', '2015-03-04 11:25:23'),
('es', 'Spanish', 'admin', '2015-03-04 11:25:37', 'admin', '2015-03-04 11:25:37'),
('fr', 'french', 'admin', '2015-03-25 17:52:52', 'admin', '2015-03-25 17:52:52'),
('ge', 'German', 'admin', '2015-03-25 20:35:36', 'admin', '2015-03-25 20:35:36'),
('jp', 'Japanese', 'admin', '2015-03-25 17:54:57', 'admin', '2015-03-28 01:52:11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `multiparam`
--

CREATE TABLE IF NOT EXISTS `multiparam` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sysparamid` int(11) NOT NULL,
  `value` varchar(900) DEFAULT NULL,
  `valuedesc` varchar(900) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_multiparam_sysparam1_idx` (`sysparamid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Volcado de datos para la tabla `multiparam`
--

INSERT INTO `multiparam` (`id`, `sysparamid`, `value`, `valuedesc`) VALUES
(1, 3, 'cv', 'Curricullum'),
(2, 3, 'ed', 'Education'),
(3, 3, 'sk', 'Skill'),
(4, 3, 'wo', 'Work'),
(5, 3, 'pr', 'Project'),
(6, 3, 'pt', 'Project Tag'),
(7, 4, 'personal', 'Personal Skill'),
(8, 4, 'technical', 'Technical Skill');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `project`
--

CREATE TABLE IF NOT EXISTS `project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `curricullumid` int(11) NOT NULL,
  `name` varchar(80) DEFAULT NULL,
  `description` tinytext,
  `link` varchar(1000) DEFAULT NULL,
  `createuser` varchar(45) NOT NULL,
  `createdate` datetime NOT NULL,
  `modifyuser` varchar(45) NOT NULL,
  `modifydate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_project_curricullum1_idx` (`curricullumid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `project`
--

INSERT INTO `project` (`id`, `curricullumid`, `name`, `description`, `link`, `createuser`, `createdate`, `modifyuser`, `modifydate`) VALUES
(1, 1, 'CV Maker', 'Multi language Responsive PHP Web app for make your own curricullum', '', 'admin', '2015-03-17 16:10:07', 'admin', '2015-03-17 16:10:07'),
(2, 1, 'Condo Handler', 'Multilanguage Symfony2 Responsive Web app for manage condominiums', '', 'admin', '2015-03-17 16:10:07', 'admin', '2015-03-17 16:10:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `project_tag`
--

CREATE TABLE IF NOT EXISTS `project_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `projectid` int(11) NOT NULL,
  `tagname` varchar(60) DEFAULT NULL,
  `createuser` varchar(45) NOT NULL,
  `createdate` datetime NOT NULL,
  `modifyuser` varchar(45) NOT NULL,
  `modifydate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_project_tag_project1_idx` (`projectid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Volcado de datos para la tabla `project_tag`
--

INSERT INTO `project_tag` (`id`, `projectid`, `tagname`, `createuser`, `createdate`, `modifyuser`, `modifydate`) VALUES
(1, 1, 'Symfony', 'admin', '2015-03-17 16:10:07', 'admin', '2015-03-17 16:10:07'),
(2, 1, 'PHP', 'admin', '2015-03-17 16:10:07', 'admin', '2015-03-17 16:10:07'),
(3, 1, 'My Sql', 'admin', '2015-03-17 16:10:07', 'admin', '2015-03-17 16:10:07'),
(4, 2, 'Symfony', 'admin', '2015-03-17 16:10:07', 'admin', '2015-03-17 16:10:07'),
(5, 2, 'PHP', 'admin', '2015-03-17 16:10:07', 'admin', '2015-03-17 16:10:07'),
(6, 2, 'My Sql', 'admin', '2015-03-17 16:10:07', 'admin', '2015-03-17 16:10:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(45) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `createuser` varchar(45) NOT NULL,
  `cratedate` datetime NOT NULL,
  `modifyuser` varchar(45) NOT NULL,
  `modifydate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roleaction`
--

CREATE TABLE IF NOT EXISTS `roleaction` (
  `roleid` int(11) NOT NULL,
  `actionid` int(11) NOT NULL,
  `createuser` varchar(45) NOT NULL,
  `createdate` datetime NOT NULL,
  `modifyuser` varchar(45) NOT NULL,
  `modifydate` datetime NOT NULL,
  PRIMARY KEY (`roleid`,`actionid`),
  KEY `fk_roleaction_action1_idx` (`actionid`),
  KEY `fk_roleaction_role1_idx` (`roleid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `skill`
--

CREATE TABLE IF NOT EXISTS `skill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `curricullumid` int(11) NOT NULL,
  `type` varchar(20) DEFAULT NULL,
  `skill` varchar(60) DEFAULT NULL,
  `percentage` int(4) DEFAULT NULL,
  `createuser` varchar(45) NOT NULL,
  `createdate` datetime NOT NULL,
  `modifyuser` varchar(45) NOT NULL,
  `modifydate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_skill_curricullum1_idx` (`curricullumid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Volcado de datos para la tabla `skill`
--

INSERT INTO `skill` (`id`, `curricullumid`, `type`, `skill`, `percentage`, `createuser`, `createdate`, `modifyuser`, `modifydate`) VALUES
(1, 1, 'programming', 'PHP', 50, 'admin', '2015-03-17 16:10:07', 'admin', '2015-03-17 16:10:07'),
(3, 1, 'programming', 'JQUERY', 50, 'admin', '2015-03-17 16:10:07', 'admin', '2015-03-17 16:10:07'),
(6, 1, 'test2', 'test2', 45, 'admin', '2015-04-06 21:06:42', 'admin', '2015-04-06 21:07:00'),
(8, 1, 'personal', 'test', 56, 'admin', '2015-04-06 21:55:40', 'admin', '2015-04-06 21:57:56');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sysparam`
--

CREATE TABLE IF NOT EXISTS `sysparam` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(45) DEFAULT NULL,
  `value` varchar(900) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `sysparam`
--

INSERT INTO `sysparam` (`id`, `code`, `value`, `description`) VALUES
(1, 'lang', 'eng', 'Default Language'),
(2, 'cvname', 'Andrés Franco', 'Default Curricullum Name'),
(3, 'objcode', 'objcode', 'Translation object codes'),
(4, 'skilltype', 'skilltype', 'Skills Types');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `systemuser`
--

CREATE TABLE IF NOT EXISTS `systemuser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) DEFAULT NULL,
  `salt` varchar(100) DEFAULT NULL,
  `password` varchar(900) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `crerateuser` varchar(45) NOT NULL,
  `createdate` datetime NOT NULL,
  `modifyuser` varchar(45) NOT NULL,
  `modifydate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `systemuser`
--

INSERT INTO `systemuser` (`id`, `username`, `salt`, `password`, `email`, `crerateuser`, `createdate`, `modifyuser`, `modifydate`) VALUES
(1, 'admin', '8d5', '3e32e55d986dc2b14f44df2bdfa6fd334bf771e2d8fbf1238ab05be1aa8a60aa', 'andresfranco@cableonda.net', 'admin', '2015-03-04 11:25:23', 'admin', '2015-03-04 11:25:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `translation`
--

CREATE TABLE IF NOT EXISTS `translation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `objectcode` varchar(8) DEFAULT NULL,
  `parentid` int(11) DEFAULT NULL,
  `objectid` int(11) DEFAULT NULL,
  `languagecode` varchar(10) NOT NULL,
  `field` varchar(45) DEFAULT NULL,
  `content` longtext,
  `createuser` varchar(45) NOT NULL,
  `createdate` datetime NOT NULL,
  `modifyuser` varchar(45) NOT NULL,
  `modifydate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_translation_language1_idx` (`languagecode`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

--
-- Volcado de datos para la tabla `translation`
--

INSERT INTO `translation` (`id`, `objectcode`, `parentid`, `objectid`, `languagecode`, `field`, `content`, `createuser`, `createdate`, `modifyuser`, `modifydate`) VALUES
(4, 'cv', -1, 1, 'en', 'name', 'Andr&eacute;s Franco&lt;br&gt;', 'admin', '2015-03-30 19:51:28', 'admin', '2015-03-30 19:51:28'),
(5, 'cv', -1, 1, 'en', 'aboutme', 'About me text', 'admin', '2015-03-30 19:50:53', 'admin', '2015-03-30 19:50:53'),
(6, 'cv', -1, 1, 'en', 'maintext', '&lt;b&gt;Main Text&lt;/b&gt;&lt;br&gt;', 'admin', '2015-03-30 19:50:29', 'admin', '2015-03-30 19:50:29'),
(7, 'cv', -1, 1, 'en', 'contactdetails', '&lt;span&gt;Phone:(507) 6981-0649&lt;/span&gt;\r\n&lt;span&gt;Email:andresfranco@cableonda.net&lt;/span&gt;', 'admin', '2015-03-30 19:50:07', 'admin', '2015-03-30 19:50:07'),
(8, 'cv', -1, 1, 'en', 'mainskills', 'Main Skills Text2&lt;br&gt;', 'admin', '2015-03-30 19:49:04', 'admin', '2015-03-30 19:49:04'),
(23, 'ed', 1, 1, 'en', 'degree', 'System Information Engeneering', 'admin', '2015-03-31 18:24:02', 'admin', '2015-03-31 18:24:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `userrole`
--

CREATE TABLE IF NOT EXISTS `userrole` (
  `systemuserid` int(11) NOT NULL,
  `roleid` int(11) NOT NULL,
  `createuser` varchar(45) NOT NULL,
  `createdate` datetime NOT NULL,
  `modifyuser` varchar(45) NOT NULL,
  `modifydate` datetime NOT NULL,
  PRIMARY KEY (`systemuserid`,`roleid`),
  KEY `fk_userrole_systemuser1_idx` (`systemuserid`),
  KEY `fk_userrole_role1_idx` (`roleid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `work`
--

CREATE TABLE IF NOT EXISTS `work` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `curricullumid` int(11) NOT NULL,
  `company` varchar(60) DEFAULT NULL,
  `position` varchar(60) DEFAULT NULL,
  `from` varchar(45) DEFAULT NULL,
  `to` varchar(45) DEFAULT NULL,
  `createuser` varchar(45) NOT NULL,
  `createdate` datetime NOT NULL,
  `modifyuser` varchar(45) NOT NULL,
  `modifydate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_work_curricullum1_idx` (`curricullumid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `work`
--

INSERT INTO `work` (`id`, `curricullumid`, `company`, `position`, `from`, `to`, `createuser`, `createdate`, `modifyuser`, `modifydate`) VALUES
(1, 1, 'Arango Software International', 'Genexus Developer', '2008', '2009', 'admin', '2015-03-17 16:10:07', 'admin', '2015-03-17 16:10:07'),
(2, 1, 'ADR Technologies', 'System Consultant', '2010', 'Present', 'admin', '2015-03-17 16:10:07', 'admin', '2015-03-17 16:10:07');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `education`
--
ALTER TABLE `education`
  ADD CONSTRAINT `fk_education_curricullum1` FOREIGN KEY (`curricullumid`) REFERENCES `curricullum` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `multiparam`
--
ALTER TABLE `multiparam`
  ADD CONSTRAINT `fk_multiparam_sysparam1` FOREIGN KEY (`sysparamid`) REFERENCES `sysparam` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `fk_project_curricullum1` FOREIGN KEY (`curricullumid`) REFERENCES `curricullum` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `project_tag`
--
ALTER TABLE `project_tag`
  ADD CONSTRAINT `fk_project_tag_project1` FOREIGN KEY (`projectid`) REFERENCES `project` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `roleaction`
--
ALTER TABLE `roleaction`
  ADD CONSTRAINT `fk_roleaction_action1` FOREIGN KEY (`actionid`) REFERENCES `action` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_roleaction_role1` FOREIGN KEY (`roleid`) REFERENCES `role` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `skill`
--
ALTER TABLE `skill`
  ADD CONSTRAINT `fk_skill_curricullum1` FOREIGN KEY (`curricullumid`) REFERENCES `curricullum` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `translation`
--
ALTER TABLE `translation`
  ADD CONSTRAINT `fk_translation_language1` FOREIGN KEY (`languagecode`) REFERENCES `language` (`code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `userrole`
--
ALTER TABLE `userrole`
  ADD CONSTRAINT `fk_userrole_role1` FOREIGN KEY (`roleid`) REFERENCES `role` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_userrole_systemuser1` FOREIGN KEY (`systemuserid`) REFERENCES `systemuser` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `work`
--
ALTER TABLE `work`
  ADD CONSTRAINT `fk_work_curricullum1` FOREIGN KEY (`curricullumid`) REFERENCES `curricullum` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
