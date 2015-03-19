-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 19-03-2015 a las 04:03:05
-- Versión del servidor: 5.6.21
-- Versión de PHP: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
`id` int(11) NOT NULL,
  `action` varchar(45) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `createuser` varchar(45) NOT NULL,
  `cratedate` datetime NOT NULL,
  `modifyuser` varchar(45) NOT NULL,
  `modifydate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `curricullum`
--

CREATE TABLE IF NOT EXISTS `curricullum` (
`id` int(11) NOT NULL,
  `name` varchar(60) DEFAULT NULL,
  `maintext` tinytext,
  `aboutme` text,
  `contactdetails` tinytext,
  `mainskills` tinytext,
  `createuser` varchar(45) NOT NULL,
  `createdate` datetime NOT NULL,
  `modifyuser` varchar(45) NOT NULL,
  `modifydate` datetime NOT NULL,
  `filename` varchar(80) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `curricullum`
--

INSERT INTO `curricullum` (`id`, `name`, `maintext`, `aboutme`, `contactdetails`, `mainskills`, `createuser`, `createdate`, `modifyuser`, `modifydate`, `filename`) VALUES
(3, 'Andr&eacute;s Franco', 'I am a information systems engineer and live in Panama, I\r\n have experience as a systems consultant and have participated in the \r\ndevelopment and implementation of web systems for companies in the \r\nbanking, telecommunication, and services.', 'Andres Franco is a System Consultant with five years of experence in CRM implementation and Web development\r\n                ,usig technologies like Genexus and Open source languages based in PHP and My SQL.\r\n                Recently he has been working  in his personal projects using the PHP framework symfony2.\r\n            ', 'Andr&eacute;s Franco\r\n\r\n(507)6981-0649\r\n\r\nandres@andresmfranco.info', 'Implementation and development of CRM Saleslogix\r\n\r\nFull Stack Development in PHP and MySQL\r\n\r\nResponsive Web Design\r\n\r\nCustomer care', 'admin', '2015-03-17 03:06:38', 'admin', '2015-03-17 05:42:41', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `education`
--

CREATE TABLE IF NOT EXISTS `education` (
`id` int(11) NOT NULL,
  `curricullumid` int(11) NOT NULL,
  `institution` varchar(60) DEFAULT NULL,
  `degree` varchar(60) DEFAULT NULL,
  `datechar` varchar(45) DEFAULT NULL,
  `createuser` varchar(45) NOT NULL,
  `cratedate` datetime NOT NULL,
  `modifyuser` varchar(45) NOT NULL,
  `modifydate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `modifydate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `language`
--

INSERT INTO `language` (`code`, `language`, `createuser`, `createdate`, `modifyuser`, `modifydate`) VALUES
('en', 'English', 'admin', '0000-00-00 00:00:00', 'admin', '2015-03-16 23:12:10'),
('es', 'Spanish', 'admin', '0000-00-00 00:00:00', 'admin', '2015-03-16 05:56:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `multiparam`
--

CREATE TABLE IF NOT EXISTS `multiparam` (
`id` int(11) NOT NULL,
  `sysparamid` int(11) NOT NULL,
  `value` varchar(900) DEFAULT NULL,
  `valuedesc` varchar(900) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `multiparam`
--

INSERT INTO `multiparam` (`id`, `sysparamid`, `value`, `valuedesc`) VALUES
(1, 3, 'cv', 'Curricullum'),
(2, 3, 'ed', 'Education'),
(3, 3, 'sk', 'Skill'),
(4, 3, 'wo', 'Work'),
(5, 3, 'pr', 'Project'),
(6, 3, 'pt', 'Project Tag');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `project`
--

CREATE TABLE IF NOT EXISTS `project` (
  `id` int(11) NOT NULL,
  `curricullumid` int(11) NOT NULL,
  `name` varchar(80) DEFAULT NULL,
  `description` tinytext,
  `link` varchar(1000) DEFAULT NULL,
  `createuser` varchar(45) NOT NULL,
  `createdate` datetime NOT NULL,
  `modifyuser` varchar(45) NOT NULL,
  `modifydate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `project_tag`
--

CREATE TABLE IF NOT EXISTS `project_tag` (
`id` int(11) NOT NULL,
  `projectid` int(11) NOT NULL,
  `tagname` varchar(60) DEFAULT NULL,
  `createuser` varchar(45) NOT NULL,
  `createdate` datetime NOT NULL,
  `modifyuser` varchar(45) NOT NULL,
  `modifydate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role`
--

CREATE TABLE IF NOT EXISTS `role` (
`id` int(11) NOT NULL,
  `role` varchar(45) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `createuser` varchar(45) NOT NULL,
  `cratedate` datetime NOT NULL,
  `modifyuser` varchar(45) NOT NULL,
  `modifydate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `modifydate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `skill`
--

CREATE TABLE IF NOT EXISTS `skill` (
  `idskill` int(11) NOT NULL,
  `curricullumid` int(11) NOT NULL,
  `type` varchar(20) DEFAULT NULL,
  `skill` varchar(60) DEFAULT NULL,
  `percentage` int(4) DEFAULT NULL,
  `createuser` varchar(45) NOT NULL,
  `createdate` datetime NOT NULL,
  `modifyuser` varchar(45) NOT NULL,
  `modifydate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sysparam`
--

CREATE TABLE IF NOT EXISTS `sysparam` (
`id` int(11) NOT NULL,
  `code` varchar(45) DEFAULT NULL,
  `value` varchar(900) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `sysparam`
--

INSERT INTO `sysparam` (`id`, `code`, `value`, `description`) VALUES
(1, 'lang', 'eng', 'Default Language'),
(2, 'cvname', 'Andrés Franco', 'Default Curricullum Name'),
(3, 'objcode', 'objcode', 'Translation object codes');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `systemuser`
--

CREATE TABLE IF NOT EXISTS `systemuser` (
`id` int(11) NOT NULL,
  `username` varchar(45) DEFAULT NULL,
  `salt` varchar(100) DEFAULT NULL,
  `password` varchar(900) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `crerateuser` varchar(45) NOT NULL,
  `createdate` datetime NOT NULL,
  `modifyuser` varchar(45) NOT NULL,
  `modifydate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `translation`
--

CREATE TABLE IF NOT EXISTS `translation` (
`id` int(11) NOT NULL,
  `objectcode` varchar(8) DEFAULT NULL,
  `objectid` int(11) DEFAULT NULL,
  `languagecode` varchar(10) NOT NULL,
  `field` varchar(45) DEFAULT NULL,
  `content` longtext,
  `createuser` varchar(45) NOT NULL,
  `createdate` datetime NOT NULL,
  `modifyuser` varchar(45) NOT NULL,
  `modifydate` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `translation`
--

INSERT INTO `translation` (`id`, `objectcode`, `objectid`, `languagecode`, `field`, `content`, `createuser`, `createdate`, `modifyuser`, `modifydate`) VALUES
(1, 'cv', 3, 'en', 'name', 'Andr&eacute;s Franco&lt;br&gt;', 'admin', '2015-03-19 02:37:48', 'admin', '2015-03-19 02:37:48'),
(2, 'cv', 3, 'es', 'name', 'Andr&eacute;s Franco&lt;br&gt;', 'admin', '2015-03-19 02:39:04', 'admin', '2015-03-19 02:39:04'),
(3, 'cv', 3, 'es', 'maintext', 'Soy Ingeniero en sistemas de informaci&oacute;n he trabajado en desarrollo de software y en consultor&iacute;a para empresas del sector bancario y servicios.&lt;br&gt;', 'admin', '2015-03-19 02:41:48', 'admin', '2015-03-19 02:41:48'),
(4, 'cv', 3, 'en', 'maintext', '&lt;span id=&quot;result_box&quot; class=&quot;&quot; lang=&quot;en&quot;&gt;&lt;span class=&quot;hps&quot;&gt;I&lt;/span&gt; &lt;span class=&quot;hps&quot;&gt;am engineer&lt;/span&gt; &lt;span class=&quot;hps&quot;&gt;in information systems&lt;/span&gt; &lt;span class=&quot;hps&quot;&gt;have worked in&lt;/span&gt; &lt;span class=&quot;hps&quot;&gt;software development and&lt;/span&gt; &lt;span class=&quot;hps&quot;&gt;consulting&lt;/span&gt; &lt;span class=&quot;hps&quot;&gt;for&lt;/span&gt; &lt;span class=&quot;hps&quot;&gt;companies in banking&lt;/span&gt; &lt;span class=&quot;hps&quot;&gt;and services.&lt;/span&gt;&lt;/span&gt;', 'admin', '2015-03-19 02:44:27', 'admin', '2015-03-19 02:44:27');

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
  `modifydate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `work`
--

CREATE TABLE IF NOT EXISTS `work` (
  `idwork` int(11) NOT NULL,
  `curricullumid` int(11) NOT NULL,
  `company` varchar(60) DEFAULT NULL,
  `position` varchar(60) DEFAULT NULL,
  `from` varchar(45) DEFAULT NULL,
  `to` varchar(45) DEFAULT NULL,
  `createuser` varchar(45) NOT NULL,
  `creratedate` datetime NOT NULL,
  `modifyuser` varchar(45) NOT NULL,
  `modifydate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `action`
--
ALTER TABLE `action`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `curricullum`
--
ALTER TABLE `curricullum`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `education`
--
ALTER TABLE `education`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_education_curricullum1_idx` (`curricullumid`);

--
-- Indices de la tabla `language`
--
ALTER TABLE `language`
 ADD PRIMARY KEY (`code`);

--
-- Indices de la tabla `multiparam`
--
ALTER TABLE `multiparam`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_multiparam_sysparam1_idx` (`sysparamid`);

--
-- Indices de la tabla `project`
--
ALTER TABLE `project`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_project_curricullum1_idx` (`curricullumid`);

--
-- Indices de la tabla `project_tag`
--
ALTER TABLE `project_tag`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_project_tag_project1_idx` (`projectid`);

--
-- Indices de la tabla `role`
--
ALTER TABLE `role`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `roleaction`
--
ALTER TABLE `roleaction`
 ADD PRIMARY KEY (`roleid`,`actionid`), ADD KEY `fk_roleaction_role1_idx` (`roleid`), ADD KEY `fk_roleaction_action1_idx` (`actionid`);

--
-- Indices de la tabla `skill`
--
ALTER TABLE `skill`
 ADD PRIMARY KEY (`idskill`), ADD KEY `fk_skill_curricullum1_idx` (`curricullumid`);

--
-- Indices de la tabla `sysparam`
--
ALTER TABLE `sysparam`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `systemuser`
--
ALTER TABLE `systemuser`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `translation`
--
ALTER TABLE `translation`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_translation_language1_idx` (`languagecode`);

--
-- Indices de la tabla `userrole`
--
ALTER TABLE `userrole`
 ADD PRIMARY KEY (`systemuserid`,`roleid`), ADD KEY `fk_userrole_systemuser1_idx` (`systemuserid`), ADD KEY `fk_userrole_role1_idx` (`roleid`);

--
-- Indices de la tabla `work`
--
ALTER TABLE `work`
 ADD PRIMARY KEY (`idwork`), ADD KEY `fk_work_curricullum1_idx` (`curricullumid`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `action`
--
ALTER TABLE `action`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `curricullum`
--
ALTER TABLE `curricullum`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `education`
--
ALTER TABLE `education`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `multiparam`
--
ALTER TABLE `multiparam`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `project_tag`
--
ALTER TABLE `project_tag`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `role`
--
ALTER TABLE `role`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `sysparam`
--
ALTER TABLE `sysparam`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `systemuser`
--
ALTER TABLE `systemuser`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `translation`
--
ALTER TABLE `translation`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
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
