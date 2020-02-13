-- phpMyAdmin SQL Dump
-- version 4.8.0-dev
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 12, 2020 at 02:56 PM
-- Server version: 5.7.26
-- PHP Version: 7.3.14-5+0~20200202.52+debian9~1.gbpa71879

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_base`
--

-- --------------------------------------------------------

--
-- Table structure for table `action`
--

CREATE TABLE `action` (
  `action_id` int(11) NOT NULL COMMENT 'Actions',
  `controller_id` int(11) NOT NULL COMMENT 'Controller',
  `action_name` char(100) CHARACTER SET utf8 NOT NULL COMMENT 'Name',
  `action_description` char(80) CHARACTER SET utf8 NOT NULL COMMENT 'Description',
  `active` tinyint(1) NOT NULL COMMENT 'Active',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'date created',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'date updated'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Actions';

-- --------------------------------------------------------

--
-- Table structure for table `blocked`
--

CREATE TABLE `blocked` (
  `id` int(11) NOT NULL COMMENT 'id',
  `ipv4_address` char(20) CHARACTER SET utf8 NOT NULL COMMENT 'IPV4 address',
  `ipv4_address_int` bigint(20) NOT NULL COMMENT 'IPV4 address integer',
  `date` datetime NOT NULL COMMENT 'date time',
  `status_id` int(11) NOT NULL COMMENT 'Status',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'created at',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'updated at'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Ipv4 Blocked ';

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `company_id` int(11) NOT NULL COMMENT 'Company',
  `company_name` char(60) NOT NULL COMMENT 'Name',
  `address` char(100) NOT NULL COMMENT 'Address',
  `contact_person` char(80) NOT NULL COMMENT 'Contact person',
  `contact_phone_1` char(20) DEFAULT NULL COMMENT 'Contact phone',
  `contact_phone_2` char(20) DEFAULT NULL COMMENT 'Phone additional',
  `contact_phone_3` char(20) DEFAULT NULL COMMENT 'Phone additional',
  `contact_email` char(254) DEFAULT NULL COMMENT 'Contact email',
  `webpage` char(254) DEFAULT NULL COMMENT 'URL Webpage',
  `created_at` datetime NOT NULL COMMENT 'Created at',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Updated at',
  `active` tinyint(1) NOT NULL COMMENT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Company';

-- --------------------------------------------------------

--
-- Table structure for table `controllers`
--

CREATE TABLE `controllers` (
  `controller_id` int(11) NOT NULL COMMENT 'Controller',
  `controller_name` char(100) CHARACTER SET utf8 NOT NULL COMMENT 'Name',
  `controller_description` char(80) CHARACTER SET utf8 NOT NULL COMMENT 'Description',
  `menu_boolean_private` tinyint(1) NOT NULL COMMENT 'Menu is private',
  `menu_boolean_visible` tinyint(1) NOT NULL COMMENT 'Menu is visible',
  `active` tinyint(1) NOT NULL COMMENT 'Active',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'date created',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'date updated'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Controllers';

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `logs_id` int(11) NOT NULL COMMENT 'Logs',
  `date` datetime NOT NULL COMMENT 'date',
  `status_id` int(11) NOT NULL COMMENT 'Status',
  `controller_id` int(11) NOT NULL COMMENT 'Controller',
  `action_id` int(11) NOT NULL COMMENT 'Action',
  `functionCode` char(60) COLLATE utf8_bin DEFAULT NULL COMMENT 'Function code',
  `event` char(250) CHARACTER SET utf8 NOT NULL COMMENT 'Activity / Event',
  `user_agent` char(250) CHARACTER SET utf8 NOT NULL COMMENT 'user agent browser',
  `ipv4_address` char(20) CHARACTER SET utf8 NOT NULL COMMENT 'ipv4_address',
  `ipv4_address_int` bigint(20) NOT NULL COMMENT 'ipv4_address integer',
  `confirmed` tinyint(1) NOT NULL COMMENT 'ipv4 address confirmed',
  `user_id` int(11) NOT NULL COMMENT 'User'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Logs (user bitacora)';

-- --------------------------------------------------------

--
-- Table structure for table `parameter`
--

CREATE TABLE `parameter` (
  `parameter_id` int(11) NOT NULL COMMENT 'Parameter',
  `key` char(60) NOT NULL COMMENT 'Key',
  `value` char(40) NOT NULL COMMENT 'Value',
  `description` char(80) NOT NULL COMMENT 'Description',
  `active` tinyint(1) NOT NULL COMMENT 'Active',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Created at',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Updated at'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Parameters';

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE `permission` (
  `permission_id` int(11) NOT NULL COMMENT 'Permission',
  `profile_id` int(11) NOT NULL COMMENT 'Profile',
  `controller_id` int(11) NOT NULL COMMENT 'Controller',
  `action_id` int(11) NOT NULL COMMENT 'Action Name',
  `action_permission` tinyint(1) NOT NULL COMMENT 'Action permission',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Created at',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Updated at'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Permission';

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE `profile` (
  `profile_id` int(11) NOT NULL COMMENT 'Profile',
  `profile_name` char(80) CHARACTER SET utf8 NOT NULL COMMENT 'Name',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date created',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'date updated',
  `active` tinyint(1) NOT NULL COMMENT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Profiles';

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`profile_id`, `profile_name`, `created_at`, `updated_at`, `active`) VALUES
(10, 'Invited', '2018-07-17 00:00:00', '2020-02-12 15:55:43', 1),
(20, 'user', '2018-07-17 00:00:00', '2019-03-13 16:50:10', 1),
(99, 'Administrator', '2018-07-18 00:00:00', '2018-07-18 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

CREATE TABLE `session` (
  `id` char(40) CHARACTER SET utf8 NOT NULL COMMENT 'id',
  `expire` int(11) DEFAULT NULL COMMENT 'date time expire session',
  `data` blob COMMENT 'data token'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Sessions of this web application';

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `status_id` int(11) NOT NULL COMMENT 'Status',
  `status_name` char(80) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Status message',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Created at',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Updated at',
  `active` tinyint(1) DEFAULT NULL COMMENT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Informative status of events in all the platform';

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`status_id`, `status_name`, `created_at`, `updated_at`, `active`) VALUES
(10, 'info', '2018-08-09 11:25:33', '2018-08-09 14:34:19', 1),
(20, 'success', '2018-08-09 11:25:40', '2018-08-09 11:25:40', 1),
(30, 'warning', '2018-08-09 11:25:33', '2018-08-09 11:25:33', 1),
(40, 'error', '2018-08-09 11:25:33', '2018-08-09 11:25:33', 1),
(50, 'Security issue', '2018-09-26 18:29:22', '2018-09-26 18:29:22', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL COMMENT 'User',
  `username` char(20) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'User account',
  `auth_key` char(32) CHARACTER SET utf8 NOT NULL COMMENT 'key auth',
  `password_hash` char(255) CHARACTER SET utf8 NOT NULL COMMENT 'password',
  `password_reset_token` char(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'password reset token',
  `password_reset_token_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'password reset token date creation',
  `email_confirmation_token` char(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Email token of confirmation ',
  `firstName` char(80) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'User name',
  `lastName` char(80) COLLATE utf8_bin NOT NULL COMMENT 'Last name',
  `email` char(254) CHARACTER SET utf8 NOT NULL COMMENT 'Email',
  `email_is_verified` tinyint(1) NOT NULL COMMENT 'Boolean is email verified ',
  `telephone` char(15) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Phone number 12 digits',
  `profile_id` int(11) NOT NULL COMMENT 'Profile',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date created',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'date updated',
  `active` tinyint(1) NOT NULL COMMENT 'Active',
  `ipv4_address_last_login` char(20) COLLATE utf8_bin NOT NULL COMMENT 'Last ipv4 address used'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Users';

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `password_reset_token_date`, `email_confirmation_token`, `firstName`, `lastName`, `email`, `email_is_verified`, `telephone`, `profile_id`, `created_at`, `updated_at`, `active`, `ipv4_address_last_login`) VALUES
(20, 'admin', 'eHCuQ7yHQ13Xsxwy9djir0k5FCbuYKcc', '$2y$13$jL2vB0tP3RGc1r483ETKaea3IZfEbcME.pM8A.xFuAgOp2A3e9X3a', '', '2018-07-17 23:18:18', '5hpbjIKd5FARfDsIsiVI31Vi9huaad7H_1538056684', 'Administrador', 'administrador', 'pro0@dev-master.local', 1, '', 99, '2018-07-17 23:18:18', '2020-01-02 19:22:07', 1, '127');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `action`
--
ALTER TABLE `action`
  ADD PRIMARY KEY (`action_id`);

--
-- Indexes for table `blocked`
--
ALTER TABLE `blocked`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_blocked_status1_idx` (`status_id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `controllers`
--
ALTER TABLE `controllers`
  ADD PRIMARY KEY (`controller_id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`logs_id`),
  ADD KEY `fk_logs_controllers1_idx` (`controller_id`),
  ADD KEY `fk_logs_status1_idx` (`status_id`),
  ADD KEY `fk_logs_action1_idx` (`action_id`);

--
-- Indexes for table `parameter`
--
ALTER TABLE `parameter`
  ADD PRIMARY KEY (`parameter_id`),
  ADD KEY `idx_key` (`key`);

--
-- Indexes for table `permission`
--
ALTER TABLE `permission`
  ADD PRIMARY KEY (`permission_id`),
  ADD KEY `fk_permission_controllers1_idx` (`controller_id`),
  ADD KEY `fk_permission_profile1` (`profile_id`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`profile_id`);

--
-- Indexes for table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `idx_usu_login` (`username`),
  ADD KEY `fk_user_profile_idx` (`profile_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `action`
--
ALTER TABLE `action`
  MODIFY `action_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Actions';

--
-- AUTO_INCREMENT for table `blocked`
--
ALTER TABLE `blocked`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Company';

--
-- AUTO_INCREMENT for table `controllers`
--
ALTER TABLE `controllers`
  MODIFY `controller_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Controller', AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `logs_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Logs';

--
-- AUTO_INCREMENT for table `parameter`
--
ALTER TABLE `parameter`
  MODIFY `parameter_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Parameter';

--
-- AUTO_INCREMENT for table `permission`
--
ALTER TABLE `permission`
  MODIFY `permission_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Permission';

--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
  MODIFY `profile_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Profile', AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Status', AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'User', AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blocked`
--
ALTER TABLE `blocked`
  ADD CONSTRAINT `fk_blocked_status1` FOREIGN KEY (`status_id`) REFERENCES `status` (`status_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `fk_logs_controllers1` FOREIGN KEY (`controller_id`) REFERENCES `controllers` (`controller_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_logs_status1` FOREIGN KEY (`status_id`) REFERENCES `status` (`status_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `permission`
--
ALTER TABLE `permission`
  ADD CONSTRAINT `fk_permission_controllers1` FOREIGN KEY (`controller_id`) REFERENCES `controllers` (`controller_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_permission_profile1` FOREIGN KEY (`profile_id`) REFERENCES `profile` (`profile_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_profile` FOREIGN KEY (`profile_id`) REFERENCES `profile` (`profile_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
