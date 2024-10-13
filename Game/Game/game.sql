-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 21, 2023 at 11:41 PM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `game`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbdeveloper`
--

CREATE TABLE IF NOT EXISTS `tbdeveloper` (
  `id` int(15) NOT NULL DEFAULT '0',
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbdeveloper`
--

INSERT INTO `tbdeveloper` (`id`, `username`, `password`) VALUES
(0, 'rahmatreza830@gmail.com', 'rahmatreza830@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `tbplayer`
--

CREATE TABLE IF NOT EXISTS `tbplayer` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(20) NOT NULL,
  `player_score` int(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `tbplayer`
--

INSERT INTO `tbplayer` (`id`, `username`, `password`, `player_score`) VALUES
(1, 'Reza', 'reza', 99999),
(2, 'Budi', 'Budi', 2890),
(3, 'Nearu', '1234', 4000),
(4, 'Tom', 'tom', 2000),
(5, 'Azuki', 'alex1234', 5000),
(6, 'shatosi', 'shatosi1234', 4870),
(7, 'ElonMusk', 'ElonMusk', 6789),
(8, 'X I N X', '12345678', 3456),
(9, 'BudiGmg', 'budigmg', 2609),
(10, 'Jerry', 'Jerry', 1391);
