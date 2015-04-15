-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-04-2015 a las 23:49:07
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
(2, 1, 'Instituto Pedag&oacute;gico', 'Bachiller en ciencias y letras', '2001', 'admin', '2015-03-31 22:42:41', 'admin', '2015-04-08 22:27:08');

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
('es', 'Spanish', 'admin', '2015-03-04 11:25:37', 'admin', '2015-03-04 11:25:37');

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
  `imagename` varchar(60) DEFAULT NULL,
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

INSERT INTO `project` (`id`, `curricullumid`, `name`, `description`, `link`, `imagename`, `createuser`, `createdate`, `modifyuser`, `modifydate`) VALUES
(1, 1, 'CV Maker', 'Multi language Responsive PHP Web app for make your own curricullum', '', NULL, 'admin', '2015-03-17 16:10:07', 'admin', '2015-03-17 16:10:07'),
(2, 1, 'Condo Handler', 'Multilanguage Symfony2 Responsive Web app for manage condominiums', '', NULL, 'admin', '2015-03-17 16:10:07', 'admin', '2015-03-17 16:10:07');

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
(1, 1, 'technical', 'PHP', 50, 'admin', '2015-03-17 16:10:07', 'admin', '2015-03-17 16:10:07'),
(3, 1, 'technical', 'JQUERY', 50, 'admin', '2015-03-17 16:10:07', 'admin', '2015-03-17 16:10:07'),
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
(1, 'lang', 'en', 'Default Language'),
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
-- Estructura de tabla para la tabla `translatetag`
--

CREATE TABLE IF NOT EXISTS `translatetag` (
  `languagecode` varchar(10) NOT NULL,
  `key` varchar(100) NOT NULL,
  `translation` mediumtext,
  PRIMARY KEY (`languagecode`,`key`),
  KEY `fk_translatetag_language1_idx` (`languagecode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `translatetag`
--

INSERT INTO `translatetag` (`languagecode`, `key`, `translation`) VALUES
('en', 'About', 'About'),
('en', 'aboutme.title', 'About me'),
('en', 'Contact', 'Contact'),
('en', 'contact.sidetitle.text', 'E-mail and Phone'),
('en', 'contactdetails.title', 'Contact Details'),
('en', 'contacttitle.text', 'If you have any comments or would like to contact me, you can fill the form below'),
('en', 'downloadresume.title', 'Download Resume'),
('en', 'education.title', 'Education'),
('en', 'Email', 'Email'),
('en', 'English', 'English'),
('en', 'Home', 'Home'),
('en', 'Message', 'Message'),
('en', 'Name', 'Name'),
('en', 'Projects', 'Projects'),
('en', 'projects.title', 'Personal Projects'),
('en', 'Resume', 'Resume'),
('en', 'sendmessage.text', 'Your message was sent, thank you!'),
('en', 'skills.title', 'Skills'),
('en', 'Spanish', 'Spanish'),
('en', 'Subject', 'Subject'),
('en', 'Submit', 'Submit'),
('en', 'work.title', 'Work Experience'),
('es', 'About', 'A Cerca de'),
('es', 'aboutme.title', 'A cerca de mi'),
('es', 'Contact', 'Contacto'),
('es', 'contact.sidetitle.text', 'Correo y Teléfono'),
('es', 'contactdetails.title', 'Detalles de contacto'),
('es', 'contacttitle.text', 'Si usted tiene algún comentario o desea ponerse en contacto conmigo, puedes llenar el siguiente formulario'),
('es', 'downloadresume.title', 'Descargue el Currícullum'),
('es', 'education.title', 'Educación'),
('es', 'Email', 'Correo'),
('es', 'English', 'Inglés'),
('es', 'Home', 'Inicio'),
('es', 'Message', 'Mensaje'),
('es', 'Name', 'Nombre'),
('es', 'Projects', 'Proyectos'),
('es', 'projects.title', 'Proyectos personales'),
('es', 'Resume', 'Currícullum'),
('es', 'sendmessage.text', 'Su mensaje ha sido enviado,gracias!'),
('es', 'skills.title', 'Habilidades'),
('es', 'Spanish', 'Español'),
('es', 'Subject', 'Asunto'),
('es', 'Submit', 'Enviar'),
('es', 'work.title', 'Experiencia');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=48 ;

--
-- Volcado de datos para la tabla `translation`
--

INSERT INTO `translation` (`id`, `objectcode`, `parentid`, `objectid`, `languagecode`, `field`, `content`, `createuser`, `createdate`, `modifyuser`, `modifydate`) VALUES
(4, 'cv', -1, 1, 'en', 'name', 'Andr&eacute;s Franco&lt;br&gt;', 'admin', '2015-03-30 19:51:28', 'admin', '2015-03-30 19:51:28'),
(5, 'cv', -1, 1, 'en', 'aboutme', 'About me text', 'admin', '2015-03-30 19:50:53', 'admin', '2015-03-30 19:50:53'),
(6, 'cv', -1, 1, 'en', 'maintext', '&lt;b&gt;Main Text&lt;/b&gt;&lt;br&gt;', 'admin', '2015-03-30 19:50:29', 'admin', '2015-03-30 19:50:29'),
(7, 'cv', -1, 1, 'en', 'contactdetails', '&lt;span&gt;Phone:(507) 6981-0649&lt;/span&gt;\r\n&lt;span&gt;Email:andresfranco@cableonda.net&lt;/span&gt;', 'admin', '2015-03-30 19:50:07', 'admin', '2015-03-30 19:50:07'),
(8, 'cv', -1, 1, 'en', 'mainskills', 'Main Skills Text2&lt;br&gt;', 'admin', '2015-03-30 19:49:04', 'admin', '2015-03-30 19:49:04'),
(23, 'ed', 1, 1, 'en', 'degree', 'System Information Engeneering', 'admin', '2015-03-31 18:24:02', 'admin', '2015-03-31 18:24:02'),
(24, 'ed', 1, 1, 'en', 'institution', 'Universidad Tecnol&oacute;gica de Panam&aacute;&lt;br&gt;', 'admin', '2015-04-08 21:33:41', 'admin', '2015-04-08 21:33:41'),
(25, 'ed', 1, 1, 'en', 'datechar', '2009&lt;br&gt;', 'admin', '2015-04-08 21:40:54', 'admin', '2015-04-08 21:40:54'),
(26, 'ed', 1, 2, 'en', 'institution', 'Instituto Pedag&oacute;gico de Panam&aacute;&lt;br&gt;', 'admin', '2015-04-08 22:28:10', 'admin', '2015-04-08 22:28:10'),
(27, 'ed', 1, 2, 'en', 'degree', '&lt;span id=&quot;result_box&quot; class=&quot;short_text&quot; lang=&quot;en&quot;&gt;&lt;span class=&quot;hps&quot;&gt;Arts and Sciences&lt;/span&gt;&lt;/span&gt;', 'admin', '2015-04-08 23:39:54', 'admin', '2015-04-08 23:39:54'),
(28, 'ed', 1, 2, 'en', 'datechar', '2001', 'admin', '2015-04-08 23:40:35', 'admin', '2015-04-08 23:40:35'),
(29, 'wo', 1, 1, 'en', 'company', 'Arango Software International&lt;br&gt;', 'admin', '2015-04-09 15:44:56', 'admin', '2015-04-09 15:44:56'),
(30, 'wo', 1, 1, 'en', 'position', 'Genexus Developer', 'admin', '2015-04-09 16:06:34', 'admin', '2015-04-09 16:06:34'),
(31, 'wo', 1, 1, 'en', 'from', '2010', 'admin', '2015-04-09 15:59:17', 'admin', '2015-04-09 15:59:17'),
(32, 'wo', 1, 1, 'en', 'to', '2011', 'admin', '2015-04-09 15:59:48', 'admin', '2015-04-09 15:59:48'),
(33, 'wo', 1, 2, 'en', 'company', 'ADR Technologies&lt;br&gt;', 'admin', '2015-04-09 16:08:21', 'admin', '2015-04-09 16:08:21'),
(34, 'wo', 1, 2, 'en', 'position', 'Systems consultant', 'admin', '2015-04-09 16:45:53', 'admin', '2015-04-09 16:45:53'),
(35, 'wo', 1, 2, 'en', 'from', '2011', 'admin', '2015-04-09 16:13:39', 'admin', '2015-04-09 16:13:39'),
(36, 'wo', 1, 2, 'en', 'to', 'Current Date&lt;br&gt;', 'admin', '2015-04-09 16:14:20', 'admin', '2015-04-09 16:14:20'),
(37, 'sk', 1, 1, 'en', 'skill', 'PHP', 'admin', '2015-04-09 18:06:20', 'admin', '2015-04-09 18:06:20'),
(38, 'sk', 1, 1, 'en', 'percentage', '60%', 'admin', '2015-04-09 18:18:03', 'admin', '2015-04-09 18:18:03'),
(39, 'sk', 1, 3, 'en', 'skill', 'JQUERY', 'admin', '2015-04-09 18:19:40', 'admin', '2015-04-09 18:19:40'),
(40, 'sk', 1, 3, 'en', 'percentage', '60%', 'admin', '2015-04-09 18:20:16', 'admin', '2015-04-09 18:20:16'),
(41, 'pr', 1, 1, 'en', 'name', 'CV Maker&lt;br&gt;', 'admin', '2015-04-09 20:07:09', 'admin', '2015-04-09 20:07:09'),
(42, 'pr', 1, 1, 'en', 'imagename', 'cvmaker.png', 'admin', '2015-04-09 20:44:07', 'admin', '2015-04-09 20:44:07'),
(43, 'pr', 1, 1, 'en', 'description', 'Web app build in Slim Framework for make your own web Curricullum&lt;br&gt;', 'admin', '2015-04-09 20:46:55', 'admin', '2015-04-09 20:46:55'),
(44, 'pt', 1, 1, 'en', 'tagname', 'Symfony2', 'admin', '2015-04-09 21:30:04', 'admin', '2015-04-09 21:30:04'),
(45, 'pt', 1, 2, 'en', 'tagname', 'PHP', 'admin', '2015-04-09 21:30:39', 'admin', '2015-04-09 21:30:39'),
(46, 'pr', 1, 2, 'en', 'name', 'Condo Handler&lt;br&gt;', 'admin', '2015-04-09 22:20:22', 'admin', '2015-04-09 22:20:22'),
(47, 'pr', 1, 2, 'en', 'imagename', 'condohandler.png', 'admin', '2015-04-09 22:22:11', 'admin', '2015-04-09 22:22:11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `url`
--

CREATE TABLE IF NOT EXISTS `url` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `curricullumid` int(11) NOT NULL,
  `name` varchar(60) DEFAULT NULL,
  `link` varchar(2000) DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_url_curricullum1_idx` (`curricullumid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `url`
--

INSERT INTO `url` (`id`, `curricullumid`, `name`, `link`, `type`) VALUES
(1, 1, 'github', 'https://github.com/andresfranco', 'socialnetwork'),
(2, 1, 'linkedin', 'https://www.linkedin.com/in/andresmfranco', 'socialnetwork'),
(3, 1, 'copy rigth content', 'Powered by CV MAKER and <a href="http://www.styleshout.com">Stylesout</a>', 'copyright');

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
-- Filtros para la tabla `translatetag`
--
ALTER TABLE `translatetag`
  ADD CONSTRAINT `fk_translatetag_language1` FOREIGN KEY (`languagecode`) REFERENCES `language` (`code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `translation`
--
ALTER TABLE `translation`
  ADD CONSTRAINT `fk_translation_language1` FOREIGN KEY (`languagecode`) REFERENCES `language` (`code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `url`
--
ALTER TABLE `url`
  ADD CONSTRAINT `fk_url_curricullum1` FOREIGN KEY (`curricullumid`) REFERENCES `curricullum` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

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
