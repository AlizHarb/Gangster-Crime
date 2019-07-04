-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 04, 2019 at 01:05 PM
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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gangsters`
--
ALTER TABLE `gangsters`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gangsters`
--
ALTER TABLE `gangsters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
