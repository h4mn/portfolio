-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Máquina: localhost
-- Data de Criação: 09-Mar-2016 às 18:34
-- Versão do servidor: 5.5.47-0ubuntu0.14.04.1
-- versão do PHP: 5.5.9-1ubuntu4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de Dados: `portfolio`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `jobs`
--

CREATE TABLE IF NOT EXISTS `jobs` (
  `idjobs` int(11) NOT NULL,
  `jobsname` varchar(45) DEFAULT NULL,
  `jobsclient` varchar(45) DEFAULT NULL,
  `jobsfilepreviewport` varchar(45) DEFAULT NULL,
  `jobsfilepreviewportmini` varchar(45) DEFAULT NULL,
  `jobsdesc` longtext,
  `jobsdateinit` timestamp NULL DEFAULT NULL,
  `jobsusedtechs` varchar(45) DEFAULT NULL,
  `jobsurlsource` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idjobs`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
