-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 01, 2019 at 08:49 AM
-- Server version: 5.7.27-0ubuntu0.18.04.1
-- PHP Version: 7.2.21-1+ubuntu18.04.1+deb.sury.org+1

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
(1, 'Steal from street corner', 50, 100, 1, 2),
(2, 'Steel from 24hour car park', 35, 75, 3, 4),
(3, 'Steal from private car park', 25, 60, 5, 7),
(4, 'Steal from golf course', 18, 30, 8, 10),
(5, 'Steal from car dearlership', 10, 10, 10, 12);

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `id` int(11) NOT NULL,
  `C_name` varchar(50) NOT NULL,
  `C_price` int(11) NOT NULL DEFAULT '0',
  `C_theftChance` int(11) NOT NULL,
  `C_img` varchar(150) NOT NULL DEFAULT 'themes/new/assets/images/pages/theftauto/cars/'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`id`, `C_name`, `C_price`, `C_theftChance`, `C_img`) VALUES
(1, 'Lada 2105', 3000, 333, 'themes/new/assets/images/pages/theftauto/cars/'),
(2, 'Skoda Felicia', 3400, 333, 'themes/new/assets/images/pages/theftauto/cars/'),
(3, 'Tatra T700', 5500, 250, 'themes/new/assets/images/pages/theftauto/cars/'),
(4, 'GAZ Volga', 7000, 250, 'themes/new/assets/images/pages/theftauto/cars/'),
(5, 'UAZ Simba', 11000, 250, 'themes/new/assets/images/pages/theftauto/cars/'),
(6, 'Dacia Supernova', 12500, 200, 'themes/new/assets/images/pages/theftauto/cars/'),
(7, 'VAZ Kalina', 14000, 200, 'themes/new/assets/images/pages/theftauto/cars/'),
(8, 'ZIL 41041', 15500, 200, 'themes/new/assets/images/pages/theftauto/cars/'),
(9, 'Chevrolet Lanos', 17000, 100, 'themes/new/assets/images/pages/theftauto/cars/'),
(10, 'Nissan Infiniti', 20000, 100, 'themes/new/assets/images/pages/theftauto/cars/'),
(11, 'Audi A6 ', 23000, 5, 'themes/new/assets/images/pages/theftauto/cars/'),
(12, 'Mercedes-Benz C63 AMG', 28000, 5, 'themes/new/assets/images/pages/theftauto/cars/'),
(13, 'Bentley SII', 1000000, 0, 'themes/new/assets/images/pages/theftauto/cars/'),
(14, 'Chrysler SRT-8', 1000000, 0, 'themes/new/assets/images/pages/theftauto/cars/'),
(15, 'Range Rover Vogue', 1000000, 0, 'themes/new/assets/images/pages/theftauto/cars/'),
(16, 'Audi RS8', 1000000, 0, 'themes/new/assets/images/pages/theftauto/cars/'),
(17, 'Maserati Quattroporte', 1000000, 0, 'themes/new/assets/images/pages/theftauto/cars/'),
(18, 'Audi R8', 1000000, 0, 'themes/new/assets/images/pages/theftauto/cars/');

-- --------------------------------------------------------

--
-- Table structure for table `crimes`
--

CREATE TABLE `crimes` (
  `id` int(11) NOT NULL,
  `C_name` varchar(50) NOT NULL,
  `C_time` int(11) NOT NULL,
  `C_minMoney` int(11) NOT NULL DEFAULT '5',
  `C_maxMoney` int(11) NOT NULL DEFAULT '10',
  `C_exp` int(11) NOT NULL DEFAULT '1',
  `C_items` varchar(20) NOT NULL DEFAULT '0-0-0-0-0-0-0-0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `crimes`
--

INSERT INTO `crimes` (`id`, `C_name`, `C_time`, `C_minMoney`, `C_maxMoney`, `C_exp`, `C_items`) VALUES
(1, 'Debt Collection', 50, 5, 10, 1, '1-3'),
(2, 'Pickpocket on a train', 60, 10, 15, 1, '2-5'),
(3, 'Mug a tourist', 60, 15, 20, 1, '4-1'),
(4, 'Steel from a street trader', 70, 25, 30, 2, '1-3'),
(5, 'Break into a storage facility', 70, 25, 30, 2, '1-7');

-- --------------------------------------------------------

--
-- Table structure for table `gangsters`
--

CREATE TABLE `gangsters` (
  `id` int(11) NOT NULL,
  `G_name` varchar(20) NOT NULL,
  `G_password` varchar(200) NOT NULL,
  `G_email` varchar(30) NOT NULL,
  `G_avatar` varchar(155) NOT NULL DEFAULT 'public/assets/img/avatar_small.jpg',
  `G_salt` varchar(64) DEFAULT NULL,
  `G_group` int(11) NOT NULL DEFAULT '1',
  `G_joined` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gangsters`
--

INSERT INTO `gangsters` (`id`, `G_name`, `G_password`, `G_email`, `G_avatar`, `G_salt`, `G_group`, `G_joined`) VALUES
(6, 'Admin', '65376636326530333039376664356438323963366634303962383765326236346336376234323164666535396132373065316539376239386633313835363231', 'admin@admin.com', 'public/assets/img/avatar_small.jpg', '54cd838a7c49c35f95dc97a0', 1, '2019-07-04 00:00:14'),
(7, 'Gangster', '65376636326530333039376664356438323963366634303962383765326236346336376234323164666535396132373065316539376239386633313835363231', 'admin@admins.com', 'public/assets/img/avatar_small.jpg', '54cd838a7c49c35f95dc97a0', 1, '2019-07-04 06:53:55'),
(11, 'Newuser', '65646231373761356336373539303931663635373466303432316236353436303330306532616634666330623361393631313930366165663239643733623065', 'newuser@newuser.com', 'public/assets/img/avatar_small.jpg', 'c942b59555e9ed8c5e7232c3', 1, '2019-07-06 14:39:23');

-- --------------------------------------------------------

--
-- Table structure for table `gangstersStats`
--

CREATE TABLE `gangstersStats` (
  `id` int(11) NOT NULL,
  `GS_cash` int(11) NOT NULL DEFAULT '1500',
  `GS_bank` int(11) NOT NULL DEFAULT '0',
  `GS_exp` int(11) NOT NULL DEFAULT '0',
  `GS_health` int(11) NOT NULL DEFAULT '0',
  `GS_rank` int(11) NOT NULL DEFAULT '1',
  `GS_location` int(11) NOT NULL DEFAULT '1',
  `GS_bullets` int(11) NOT NULL DEFAULT '0',
  `GS_credits` int(11) NOT NULL DEFAULT '0',
  `GS_weapon` int(11) NOT NULL DEFAULT '0',
  `GS_protection` int(11) NOT NULL DEFAULT '0',
  `GS_crew` int(11) NOT NULL DEFAULT '0',
  `GS_crimes` varchar(150) NOT NULL DEFAULT '0-0-0-0-0-0-0-0',
  `GS_prisonSuccess` int(11) NOT NULL DEFAULT '0',
  `GS_prisonFailed` int(11) NOT NULL DEFAULT '0',
  `GS_prisonReason` varchar(50) NOT NULL,
  `GS_prisonReward` int(11) NOT NULL DEFAULT '0',
  `GS_bribe` int(11) NOT NULL DEFAULT '0',
  `GS_items` varchar(150) NOT NULL DEFAULT '0-0-0-0-0-0-0-0',
  `GS_autostolen` int(11) NOT NULL DEFAULT '0',
  `GS_theftExp` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gangstersStats`
--

INSERT INTO `gangstersStats` (`id`, `GS_cash`, `GS_bank`, `GS_exp`, `GS_health`, `GS_rank`, `GS_location`, `GS_bullets`, `GS_credits`, `GS_weapon`, `GS_protection`, `GS_crew`, `GS_crimes`, `GS_prisonSuccess`, `GS_prisonFailed`, `GS_prisonReason`, `GS_prisonReward`, `GS_bribe`, `GS_items`, `GS_autostolen`, `GS_theftExp`) VALUES
(6, 115323, 0, 683, 0, 2, 1, 0, 0, 0, 0, 0, '100-24-0-0-0-0-0-0', 11, 26, 'Attempted Crime', 500, 496, '2-0-5-0-0-0-0-0-1', 2, 8),
(7, 176, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, '0-0-0-0-0-0-0-0', 0, 0, '', 0, 0, '0-0-0-0-0-0-0-0', 0, 0),
(11, 1500, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, '0-0-0-0-0-0-0-0', 0, 0, '', 0, 0, '0-0-0-0-0-0-0-0', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `gangstersTimers`
--

CREATE TABLE `gangstersTimers` (
  `id` int(11) NOT NULL,
  `GT_name` varchar(20) NOT NULL,
  `GT_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gangstersTimers`
--

INSERT INTO `gangstersTimers` (`id`, `GT_name`, `GT_time`) VALUES
(6, 'bank', 0),
(6, 'prison', 1567316605),
(6, 'hospital', 0),
(6, 'travel', 0),
(6, 'autotheft', 0),
(6, 'crime', 0),
(6, 'crime-1', 1567316647),
(6, 'crime-2', 1567316660),
(6, 'crime-3', 0),
(6, 'crime-4', 0),
(6, 'crime-5', 0);

-- --------------------------------------------------------

--
-- Table structure for table `garage`
--

CREATE TABLE `garage` (
  `id` int(11) NOT NULL,
  `GA_user` int(11) NOT NULL,
  `GA_car` int(11) NOT NULL,
  `GA_damage` int(11) NOT NULL,
  `GA_currentLocation` int(11) NOT NULL,
  `GA_nowLocation` int(11) NOT NULL DEFAULT '0',
  `GA_shipTo` int(11) DEFAULT NULL,
  `GA_shipTime` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `garage`
--

INSERT INTO `garage` (`id`, `GA_user`, `GA_car`, `GA_damage`, `GA_currentLocation`, `GA_nowLocation`, `GA_shipTo`, `GA_shipTime`) VALUES
(1, 6, 2, 33, 1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `I_name` varchar(50) NOT NULL,
  `I_icon` varchar(50) NOT NULL DEFAULT 'fas fa-mask'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `I_name`, `I_icon`) VALUES
(1, 'Laptop', 'fas fa-laptop'),
(2, 'Balaclava', 'fas fa-theater-masks'),
(3, 'Mace', 'fas fa-mace'),
(4, 'Hammer', 'fas fa-hammer'),
(5, 'secret Document', 'fas fa-book'),
(6, 'Cell Phone', 'fas fa-mobile'),
(7, 'Two Way Radio', 'fas fa-phone-volume'),
(8, 'Police Scanner', 'fas fa-scanner-touchscreen'),
(9, 'Baseball Bat', 'fas fa-baseball'),
(10, 'Bolt Cutter', 'fas fa-ellipsis-v'),
(11, 'Gas Mask', 'fas fa-hockey-mask'),
(12, 'Tea Gas Canister', 'fas fa-home'),
(13, 'Vault Burner', 'fas fa-mask'),
(14, 'Custom Weapon', 'fas fa-mask'),
(15, 'Special Bullets', 'fas fa-ellipsis-h-alt');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `L_name` varchar(25) NOT NULL,
  `L_cost` int(11) NOT NULL DEFAULT '500',
  `L_time` int(11) NOT NULL DEFAULT '3600',
  `L_bullets` int(11) NOT NULL DEFAULT '0',
  `L_bulletsCost` int(11) NOT NULL DEFAULT '1000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `L_name`, `L_cost`, `L_time`, `L_bullets`, `L_bulletsCost`) VALUES
(1, 'Yerevan', 3500, 3600, 8911, 1000),
(2, 'Odessa', 2300, 3600, 0, 1000),
(3, 'Istanbul', 900, 3600, 0, 1000),
(4, 'St. Petersburg', 12000, 3600, 0, 1000),
(5, 'Moscow', 6200, 3600, 0, 1000);

-- --------------------------------------------------------

--
-- Table structure for table `mail`
--

CREATE TABLE `mail` (
  `id` int(11) NOT NULL,
  `M_fromUser` int(11) NOT NULL,
  `M_toUser` int(11) NOT NULL,
  `M_text` text NOT NULL,
  `M_read` int(11) NOT NULL DEFAULT '0',
  `M_saved` int(11) NOT NULL DEFAULT '0',
  `M_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mail`
--

INSERT INTO `mail` (`id`, `M_fromUser`, `M_toUser`, `M_text`, `M_read`, `M_saved`, `M_date`) VALUES
(1, 0, 6, 'You have been promoted to <b>Delinquent</b>. Keep up the good work!', 1, 1, '2019-07-25 09:53:24'),
(2, 6, 6, 'kjfaklsdjaklsdjla', 1, 0, '2019-07-25 10:04:40'),
(3, 0, 6, 'messageod work!', 1, 0, '2019-07-25 10:27:02'),
(4, 0, 6, 'messageod work!', 1, 0, '2019-07-25 10:27:04'),
(5, 0, 6, 'messageod work!', 1, 0, '2019-07-25 10:27:05'),
(6, 0, 6, 'messageod work!', 1, 0, '2019-07-25 10:27:06'),
(7, 0, 6, 'messageod work!', 1, 0, '2019-07-25 10:27:06'),
(8, 0, 6, 'messageod work!', 1, 0, '2019-07-25 10:27:07'),
(9, 0, 6, 'messageod work!', 1, 0, '2019-07-25 10:27:07'),
(10, 0, 6, 'messageod work!', 1, 0, '2019-07-25 10:27:08'),
(11, 0, 6, 'messageod work!', 1, 0, '2019-07-25 10:27:08'),
(12, 0, 6, 'messageod work!', 1, 0, '2019-07-25 10:27:09'),
(13, 0, 6, 'messageod work!', 1, 0, '2019-07-25 10:27:10'),
(14, 0, 6, 'messageod work!', 1, 0, '2019-07-25 10:27:10'),
(15, 0, 6, 'messageod work!', 1, 0, '2019-07-25 10:27:11'),
(16, 0, 6, 'messageod work!', 1, 0, '2019-07-25 10:27:12'),
(17, 0, 6, 'messageod work!', 1, 0, '2019-07-25 10:27:12'),
(18, 0, 6, 'messageod work!', 1, 0, '2019-07-25 10:27:13'),
(19, 0, 6, 'messageod work!', 1, 0, '2019-07-25 10:27:14'),
(20, 0, 6, 'messageod work!', 1, 0, '2019-07-25 10:27:14'),
(21, 0, 6, 'messageod work!', 1, 0, '2019-07-25 10:27:15'),
(22, 0, 6, 'messageod work!', 1, 0, '2019-07-25 10:27:16'),
(23, 0, 6, 'messageod work!', 1, 0, '2019-07-25 10:27:17'),
(24, 0, 6, 'messageod work!', 1, 0, '2019-07-25 10:27:17'),
(25, 0, 6, 'messageod work!', 1, 0, '2019-07-25 10:27:24'),
(26, 0, 6, 'Your savings plan with Centro Bank has recently expired. Under the terms of your account, Centro Bank has paid you 3% interest on the amount in your account at the time of your account expiry. <br><br>\n                                    In total, you have been credited with $650 by Centro Bank. <br><br>\n                                    Thank you for using Centro Bank.', 1, 0, '2019-07-26 07:36:01');

-- --------------------------------------------------------

--
-- Table structure for table `ranks`
--

CREATE TABLE `ranks` (
  `id` int(11) NOT NULL,
  `R_name` varchar(50) NOT NULL,
  `R_fromExp` int(11) NOT NULL DEFAULT '0',
  `R_toExp` int(11) NOT NULL DEFAULT '500',
  `R_health` int(11) NOT NULL DEFAULT '500'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ranks`
--

INSERT INTO `ranks` (`id`, `R_name`, `R_fromExp`, `R_toExp`, `R_health`) VALUES
(1, 'Loner', 0, 500, 500),
(2, 'Debt Collector', 501, 1500, 500),
(3, 'Delinquent', 1500, 2500, 500),
(4, 'Street Operator', 2500, 3500, 500),
(5, 'Embezzler', 3500, 4500, 500),
(6, 'Conman', 4500, 5500, 500),
(7, 'Ruffian', 5500, 6500, 500),
(8, 'Pillager', 6500, 7500, 500),
(9, 'Soldier', 8500, 9500, 500),
(10, 'Triggerman', 9500, 10500, 500),
(11, 'Cell Boss', 10500, 11500, 500),
(12, 'Enforcer', 11500, 12500, 500),
(13, 'Eliminator', 12500, 13500, 500),
(14, 'Pointman', 13500, 14500, 500),
(15, 'Captain', 14500, 15500, 500),
(16, 'Commandant', 15500, 16500, 500),
(17, 'Warlord', 16500, 17500, 500),
(18, 'Crime Boss', 17500, 18500, 500),
(19, 'Made Man', 18500, 19500, 500),
(20, 'Patriarch', 19500, 20500, 500);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_name` varchar(50) NOT NULL,
  `setting_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_name`, `setting_value`) VALUES
(1, 'website_name', 'Gangsters Crime'),
(2, 'website_url', 'http://localhost/crime/'),
(3, 'website_email', 'admin@gangsterscrime.com'),
(4, 'website_theme', 'default');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `T_from` int(11) NOT NULL,
  `T_to` int(11) NOT NULL,
  `T_amount` int(11) NOT NULL,
  `T_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `T_from`, `T_to`, `T_amount`, `T_date`) VALUES
(1, 6, 7, 500, '2019-07-26 07:18:27'),
(2, 7, 6, 200, '2019-07-26 07:18:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `autotheft`
--
ALTER TABLE `autotheft`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `crimes`
--
ALTER TABLE `crimes`
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
-- Indexes for table `garage`
--
ALTER TABLE `garage`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mail`
--
ALTER TABLE `mail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ranks`
--
ALTER TABLE `ranks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
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
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `crimes`
--
ALTER TABLE `crimes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `gangsters`
--
ALTER TABLE `gangsters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `gangstersStats`
--
ALTER TABLE `gangstersStats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `garage`
--
ALTER TABLE `garage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `mail`
--
ALTER TABLE `mail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `ranks`
--
ALTER TABLE `ranks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
