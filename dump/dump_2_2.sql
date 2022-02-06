-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Feb 02, 2021 at 07:15 AM
-- Server version: 5.7.30
-- PHP Version: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `intranet`
--

-- --------------------------------------------------------

--
-- Table structure for table `mrbs_attendees`
--

CREATE TABLE `mrbs_attendees` (
  `id` int(11) NOT NULL,
  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `org_id` int(11) NOT NULL,
  `type` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `mobile` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `mrbs_attendees`
--

INSERT INTO `mrbs_attendees` (`id`, `name`, `org_id`, `type`, `mobile`, `email`, `created_at`, `updated_at`) VALUES
(8, 'dadadjhjhj', 2, 'VIP', '55655', 'ggggsdsds', '2021-02-02 11:38:48', '2021-02-02 11:38:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mrbs_attendees`
--
ALTER TABLE `mrbs_attendees`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mrbs_attendees`
--
ALTER TABLE `mrbs_attendees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
