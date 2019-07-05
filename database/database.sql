-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 05, 2019 at 07:38 AM
-- Server version: 5.7.26-0ubuntu0.18.04.1
-- PHP Version: 7.2.19-1+ubuntu18.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `autotheft`
--

CREATE TABLE `autotheft` (
  `id` int(11) NOT NULL,
  `AT_name` varchar(255) NOT NULL,
  `AT_chance` int(11) NOT NULL,
  `AT_maxDamage` int(11) NOT NULL,
  `AT_worstCar` int(11) NOT NULL,
  `AT_bestCar` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `autotheft`
--

INSERT INTO `autotheft` (`id`, `AT_name`, `AT_chance`, `AT_maxDamage`, `AT_worstCar`, `AT_bestCar`) VALUES
(1, 'Steal from street corner', 50, 100, 1, 1000),
(2, 'Steel from 24hour car park', 35, 75, 1, 1000),
(3, 'Steal from private car park', 25, 60, 1, 2000),
(4, 'Steal from golf course', 18, 30, 500, 20000),
(5, 'Steal from car dearlership', 10, 10, 1000, 50000);

-- --------------------------------------------------------

--
-- Table structure for table `gangsters`
--

CREATE TABLE `gangsters` (
  `id` int(11) NOT NULL,
  `G_name` varchar(20) NOT NULL,
  `G_password` varchar(200) NOT NULL,
  `G_email` varchar(30) NOT NULL,
  `G_salt` varchar(64) DEFAULT NULL,
  `G_group` int(11) NOT NULL DEFAULT '1',
  `G_joined` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gangsters`
--

INSERT INTO `gangsters` (`id`, `G_name`, `G_password`, `G_email`, `G_salt`, `G_group`, `G_joined`) VALUES
(6, 'Admin', '65376636326530333039376664356438323963366634303962383765326236346336376234323164666535396132373065316539376239386633313835363231', 'admin@admin.com', '54cd838a7c49c35f95dc97a0', 1, '2019-07-04 00:00:14'),
(7, 'Gangster', '36396535663433313437303362636131396333373831653630376330356438663334333838613763373733656333643738633533396466353661366538653932', 'admin@admins.com', 'ef0543d9d86ab113f7a514dc', 1, '2019-07-04 06:53:55');

-- --------------------------------------------------------

--
-- Table structure for table `gangstersStats`
--

CREATE TABLE `gangstersStats` (
  `id` int(11) NOT NULL,
  `GS_cash` int(11) NOT NULL DEFAULT '2500',
  `GS_location` int(11) NOT NULL DEFAULT '1',
  `GS_autostolen` int(11) NOT NULL DEFAULT '0',
  `GS_crew` int(11) NOT NULL DEFAULT '0',
  `GS_prisonCrime` varchar(50) NOT NULL,
  `GS_prisonReward` int(11) NOT NULL DEFAULT '0',
  `GS_prisonSuccess` int(11) NOT NULL DEFAULT '0',
  `GS_prisonFailed` int(11) NOT NULL DEFAULT '0',
  `GS_moneyBust` int(11) NOT NULL DEFAULT '0',
  `GS_bribe` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gangstersStats`
--

INSERT INTO `gangstersStats` (`id`, `GS_cash`, `GS_location`, `GS_autostolen`, `GS_crew`, `GS_prisonCrime`, `GS_prisonReward`, `GS_prisonSuccess`, `GS_prisonFailed`, `GS_moneyBust`, `GS_bribe`) VALUES
(6, 395400, 1, 19, 0, 'Auto Theft', 501, 14, 27, 0, 87876),
(7, 246000, 1, 0, 0, 'Attempt crime', 1000, 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `gangstersTimer`
--

CREATE TABLE `gangstersTimer` (
  `id` int(11) NOT NULL,
  `GT_name` varchar(20) NOT NULL,
  `GT_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gangstersTimer`
--

INSERT INTO `gangstersTimer` (`id`, `GT_name`, `GT_time`) VALUES
(7, 'prison', 1562273806),
(6, 'travel', 1562276148),
(6, 'prison', 1562273834),
(6, 'autotheft', 1562276152);

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `L_name` varchar(25) NOT NULL,
  `L_cost` int(11) NOT NULL DEFAULT '500',
  `L_time` int(11) NOT NULL DEFAULT '3600'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `L_name`, `L_cost`, `L_time`) VALUES
(1, 'Yerevan', 3500, 3600),
(2, 'Odessa', 2300, 3600),
(3, 'Istanbul', 900, 3600),
(4, 'St. Petersburg', 12000, 3600),
(5, 'Moscow', 6200, 3600);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `autotheft`
--
ALTER TABLE `autotheft`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gangsters`
--
ALTER TABLE `gangsters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gangstersStats`
--
ALTER TABLE `gangstersStats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `autotheft`
--
ALTER TABLE `autotheft`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `gangsters`
--
ALTER TABLE `gangsters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `gangstersStats`
--
ALTER TABLE `gangstersStats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
