-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 24, 2025 at 02:01 PM
-- Server version: 10.11.13-MariaDB-cll-lve
-- PHP Version: 8.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `conairli_maindata`
--

-- --------------------------------------------------------

--
-- Table structure for table `activeflights`
--

CREATE TABLE `activeflights` (
  `id` int(11) NOT NULL,
  `planeOWNER` int(11) NOT NULL,
  `startLat` decimal(51,9) NOT NULL,
  `startLon` decimal(51,9) NOT NULL,
  `endLat` decimal(51,9) NOT NULL,
  `endLon` decimal(51,9) NOT NULL,
  `flightStartTime` varchar(255) NOT NULL,
  `flightEndTime` varchar(255) NOT NULL,
  `planeID` int(11) NOT NULL,
  `planePathPoints` mediumtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `airplanes`
--

CREATE TABLE `airplanes` (
  `planeID` int(11) NOT NULL,
  `planeMODEL` varchar(255) NOT NULL,
  `planeMAKE` varchar(255) NOT NULL,
  `planeIMAGE` varchar(255) NOT NULL,
  `planePASSENGER` int(11) NOT NULL,
  `planeFUEL` int(11) NOT NULL,
  `planeSPEED` int(11) NOT NULL,
  `planeDISTANCE` int(11) NOT NULL,
  `planeCOST` bigint(65) NOT NULL,
  `planeWEIGHT` int(11) NOT NULL,
  `planeCONSUMPTIONRATE` int(11) NOT NULL,
  `planeACTIVE` int(11) NOT NULL DEFAULT 0,
  `premiumcost` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `airports`
--

CREATE TABLE `airports` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `city` varchar(266) NOT NULL,
  `iata_faa` varchar(255) NOT NULL,
  `icao` varchar(255) NOT NULL,
  `lat` decimal(65,10) NOT NULL,
  `lng` decimal(65,10) NOT NULL,
  `alt` varchar(255) NOT NULL,
  `tz` varchar(255) NOT NULL,
  `airportpop` bigint(255) NOT NULL DEFAULT 0,
  `citypop` bigint(11) NOT NULL DEFAULT 1000000
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `alliance`
--

CREATE TABLE `alliance` (
  `allianceID` int(11) NOT NULL,
  `allianceNAME` varchar(255) NOT NULL DEFAULT '',
  `allianceDESC` mediumtext NOT NULL,
  `allianceIMAGE` varchar(255) NOT NULL,
  `alliancePREF` varchar(5) NOT NULL DEFAULT '',
  `alliancePRESIDENT` int(11) NOT NULL DEFAULT 0,
  `allianceVICEPRES` int(11) NOT NULL DEFAULT 0,
  `allianceWELL` varchar(255) NOT NULL DEFAULT 'Not Set',
  `allianceMONEY` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `allianceapply`
--

CREATE TABLE `allianceapply` (
  `id` int(11) NOT NULL,
  `applying` int(11) NOT NULL,
  `appliedfor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `alliancemoney`
--

CREATE TABLE `alliancemoney` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `outin` enum('in','out') NOT NULL,
  `amount` int(11) NOT NULL,
  `item` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `type` enum('bucks','airbucks') NOT NULL DEFAULT 'bucks',
  `allianceID` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `avpilots`
--

CREATE TABLE `avpilots` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `rank` varchar(255) DEFAULT NULL,
  `pay` decimal(11,2) DEFAULT NULL,
  `qualifications` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bank`
--

CREATE TABLE `bank` (
  `id` int(11) NOT NULL,
  `bank` varchar(255) NOT NULL,
  `interest` decimal(11,1) NOT NULL,
  `min` int(11) NOT NULL,
  `max` int(11) NOT NULL,
  `repreq` decimal(11,5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bankloans`
--

CREATE TABLE `bankloans` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `perday` int(11) NOT NULL,
  `bank` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bugs`
--

CREATE TABLE `bugs` (
  `id` int(11) NOT NULL,
  `reporterid` int(11) DEFAULT NULL,
  `bugtype` varchar(255) DEFAULT NULL,
  `bug` mediumtext DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `changelog`
--

CREATE TABLE `changelog` (
  `id` int(11) NOT NULL,
  `changed` mediumtext NOT NULL,
  `who` int(11) NOT NULL,
  `date` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `channels`
--

CREATE TABLE `channels` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `type` enum('public','private','group') DEFAULT 'public'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course` varchar(255) DEFAULT NULL,
  `coursetime` int(11) DEFAULT NULL,
  `courseoutcome` varchar(255) NOT NULL,
  `coursecost` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dailyrewards`
--

CREATE TABLE `dailyrewards` (
  `id` int(11) NOT NULL,
  `day` int(11) DEFAULT NULL,
  `week` int(11) NOT NULL DEFAULT 1,
  `prize` varchar(255) DEFAULT NULL,
  `prizedisplay` varchar(255) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mail`
--

CREATE TABLE `mail` (
  `mail_id` int(11) NOT NULL,
  `mail_read` int(11) NOT NULL DEFAULT 0,
  `mail_from` int(11) NOT NULL DEFAULT 0,
  `mail_to` int(11) NOT NULL DEFAULT 0,
  `mail_time` int(11) NOT NULL DEFAULT 0,
  `mail_subject` varchar(255) NOT NULL DEFAULT '',
  `mail_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `money`
--

CREATE TABLE `money` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `outin` enum('in','out') NOT NULL,
  `amount` int(11) NOT NULL,
  `item` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `type` enum('bucks','airbucks') NOT NULL DEFAULT 'bucks'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mutes`
--

CREATE TABLE `mutes` (
  `user_id` int(11) NOT NULL,
  `muted_until` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pilots`
--

CREATE TABLE `pilots` (
  `id` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `rank` varchar(255) DEFAULT NULL,
  `pay` decimal(11,2) DEFAULT NULL,
  `qualifications` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `conf_id` int(11) NOT NULL,
  `conf_name` varchar(255) NOT NULL DEFAULT '',
  `conf_value` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`conf_id`, `conf_name`, `conf_value`) VALUES
(1, 'gamename', 'Con Airline'),
(2, 'fuelcost', '0.97'),
(3, 'version', '0.2.4 Alpha'),
(4, 'fuelcosttrack', '0.4,0.38,0.4,0.42,0.42,0.44,0.44,0.47,0.5,0.51,0.51,0.51,0.5,0.5,0.48,0.48,0.42,0.46,0.46,0.46,0.48,0.5,0.53,0.56,0.56,0.58,0.70,0.98,1.14,1.21,1.37,1.23,1.25,1.03,1.02,1.02,1.05,0.97,0.98,0.94,0.92,0.90,0.89,0.88,0.89,0.86,0.82,0.95,0.97'),
(5, 'alliancecost', '100'),
(6, 'sale', '0'),
(7, 'saleperc', '30'),
(8, 'salestart', '1667307725'),
(9, 'saleend', '1667307725'),
(10, 'saleevent', 'Halloween'),
(11, 'staff_pad', 'StaffPad'),
(12, 'gamedesc', 'REMOVED SYSTEM'),
(13, 'space1', '#;images/holder.png'),
(14, 'space2', '#;images/holder.png'),
(15, 'spacecost', '20');

-- --------------------------------------------------------

--
-- Table structure for table `staff_log`
--

CREATE TABLE `staff_log` (
  `id` int(11) NOT NULL,
  `who` int(11) NOT NULL,
  `action` mediumtext NOT NULL,
  `date` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `userairplanes`
--

CREATE TABLE `userairplanes` (
  `id` int(11) NOT NULL,
  `planeOWNER` int(11) NOT NULL,
  `planeID` int(11) NOT NULL,
  `planePASSENGER` int(11) NOT NULL,
  `planePASSENGERCURRENT` int(11) NOT NULL,
  `planeMAXFUEL` int(11) NOT NULL,
  `planeFUELCURRENT` int(11) NOT NULL,
  `planeSPEED` int(11) NOT NULL,
  `planeMAXDISTANCE` int(11) NOT NULL,
  `planeDISTANCETRAVELLED` int(11) NOT NULL,
  `planeMAXWEIGHT` int(11) NOT NULL,
  `planeCURRENTWEIGHT` int(11) NOT NULL,
  `planeCONSUMPTIONRATE` int(11) NOT NULL,
  `planeLOCATIONLAT` decimal(61,10) NOT NULL,
  `planeLOCATIONLON` decimal(61,10) NOT NULL,
  `planeACTIVE` int(11) NOT NULL DEFAULT 0,
  `planeMONEYMADE` bigint(56) NOT NULL DEFAULT 0,
  `planeUname` varchar(255) NOT NULL DEFAULT 'Not Set',
  `planeHEALTH` decimal(11,1) NOT NULL DEFAULT 100.0,
  `planeMAXHEALTH` decimal(11,1) NOT NULL DEFAULT 100.0,
  `pilot` int(11) NOT NULL DEFAULT 0,
  `copilot` int(11) NOT NULL DEFAULT 0,
  `flighttime` int(11) NOT NULL DEFAULT 0,
  `totalflights` int(11) NOT NULL DEFAULT 0,
  `servicelog` mediumtext NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` int(11) NOT NULL,
  `username` varchar(255) DEFAULT '',
  `userpass` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `login_name` varchar(255) NOT NULL DEFAULT '',
  `pass_salt` varchar(8) NOT NULL DEFAULT '',
  `staff` int(11) NOT NULL DEFAULT 0,
  `level` int(11) NOT NULL DEFAULT 1,
  `bucks` bigint(54) NOT NULL DEFAULT 15000000,
  `airbucks` int(11) NOT NULL DEFAULT 0,
  `profileimage` varchar(255) NOT NULL DEFAULT 'https://www.w3schools.com/bootstrap4/img_avatar1.png',
  `airlineimage` varchar(255) NOT NULL DEFAULT '../images/companies/Company1.png',
  `airlinename` varchar(255) NOT NULL,
  `airlinecolour` varchar(255) NOT NULL DEFAULT 'black',
  `joineddate` varchar(255) NOT NULL,
  `accountblock` int(11) NOT NULL DEFAULT 0,
  `airlinehq` int(11) NOT NULL DEFAULT 0,
  `latitude` decimal(11,7) DEFAULT NULL,
  `longitude` decimal(11,7) DEFAULT NULL,
  `fuelstorage` int(11) NOT NULL DEFAULT 0,
  `fuelstoragemax` int(11) NOT NULL DEFAULT 20000,
  `premiumdays` int(11) NOT NULL DEFAULT 0,
  `theme` enum('light','dark','primary','secondary','success','info','warning','danger') NOT NULL DEFAULT 'light',
  `airlinetraininghq` int(11) NOT NULL DEFAULT 0,
  `tlatitude` decimal(11,7) DEFAULT 0.0000000,
  `tlongitude` decimal(11,7) NOT NULL DEFAULT 0.0000000,
  `tickets1` int(11) NOT NULL DEFAULT 68,
  `reputation` decimal(11,5) NOT NULL DEFAULT 30.00000,
  `reputationa` mediumtext NOT NULL DEFAULT '30.00000',
  `laston` varchar(25) NOT NULL DEFAULT '1665332524',
  `loan` int(11) NOT NULL DEFAULT 0,
  `fav` varchar(255) NOT NULL DEFAULT 'XXXX',
  `box` int(11) NOT NULL DEFAULT 0,
  `day` int(11) NOT NULL DEFAULT 1,
  `alliance` int(11) NOT NULL DEFAULT 0,
  `allianceperc` int(11) NOT NULL DEFAULT 0,
  `totalmoney` bigint(56) NOT NULL DEFAULT 0,
  `totaldistance` bigint(56) NOT NULL DEFAULT 0,
  `chatcount` int(11) NOT NULL DEFAULT 0,
  `chatblock` int(11) NOT NULL DEFAULT 0,
  `chatblockby` int(11) NOT NULL DEFAULT 0,
  `chatblockreason` mediumtext NOT NULL DEFAULT 'none',
  `roles` varchar(255) NOT NULL DEFAULT 'none',
  `newmsg` int(11) NOT NULL DEFAULT 0,
  `new_mail` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activeflights`
--
ALTER TABLE `activeflights`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `airplanes`
--
ALTER TABLE `airplanes`
  ADD PRIMARY KEY (`planeID`);

--
-- Indexes for table `airports`
--
ALTER TABLE `airports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `alliance`
--
ALTER TABLE `alliance`
  ADD PRIMARY KEY (`allianceID`);

--
-- Indexes for table `allianceapply`
--
ALTER TABLE `allianceapply`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `alliancemoney`
--
ALTER TABLE `alliancemoney`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `avpilots`
--
ALTER TABLE `avpilots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bank`
--
ALTER TABLE `bank`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bankloans`
--
ALTER TABLE `bankloans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bugs`
--
ALTER TABLE `bugs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `changelog`
--
ALTER TABLE `changelog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `channels`
--
ALTER TABLE `channels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dailyrewards`
--
ALTER TABLE `dailyrewards`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `mail`
--
ALTER TABLE `mail`
  ADD PRIMARY KEY (`mail_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `channel_id` (`channel_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Indexes for table `money`
--
ALTER TABLE `money`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mutes`
--
ALTER TABLE `mutes`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `pilots`
--
ALTER TABLE `pilots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`conf_id`);

--
-- Indexes for table `staff_log`
--
ALTER TABLE `staff_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userairplanes`
--
ALTER TABLE `userairplanes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `userid` (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activeflights`
--
ALTER TABLE `activeflights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `airplanes`
--
ALTER TABLE `airplanes`
  MODIFY `planeID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `airports`
--
ALTER TABLE `airports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `alliance`
--
ALTER TABLE `alliance`
  MODIFY `allianceID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `allianceapply`
--
ALTER TABLE `allianceapply`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `alliancemoney`
--
ALTER TABLE `alliancemoney`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `avpilots`
--
ALTER TABLE `avpilots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bank`
--
ALTER TABLE `bank`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bankloans`
--
ALTER TABLE `bankloans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bugs`
--
ALTER TABLE `bugs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `changelog`
--
ALTER TABLE `changelog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `channels`
--
ALTER TABLE `channels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mail`
--
ALTER TABLE `mail`
  MODIFY `mail_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `money`
--
ALTER TABLE `money`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pilots`
--
ALTER TABLE `pilots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_log`
--
ALTER TABLE `staff_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `userairplanes`
--
ALTER TABLE `userairplanes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`channel_id`) REFERENCES `channels` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`userid`) ON DELETE CASCADE;

--
-- Constraints for table `mutes`
--
ALTER TABLE `mutes`
  ADD CONSTRAINT `mutes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`userid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
