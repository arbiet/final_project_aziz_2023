-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 18, 2024 at 02:22 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `final_project_aziz_2023`
--

-- --------------------------------------------------------

--
-- Table structure for table `Answers`
--

CREATE TABLE `Answers` (
  `AnswerID` int(11) NOT NULL,
  `AnswerText` text NOT NULL,
  `IsCorrect` tinyint(1) NOT NULL,
  `QuestionID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `AssignmentAttachments`
--

CREATE TABLE `AssignmentAttachments` (
  `AttachmentID` int(11) NOT NULL,
  `AssignmentID` int(11) DEFAULT NULL,
  `AttachmentFile` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Assignments`
--

CREATE TABLE `Assignments` (
  `AssignmentID` int(11) NOT NULL,
  `SubjectID` int(11) DEFAULT NULL,
  `MaterialID` int(11) DEFAULT NULL,
  `Title` varchar(255) NOT NULL,
  `Description` text DEFAULT NULL,
  `DueDate` date DEFAULT NULL,
  `AssignedDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `PriorityLevel` int(11) DEFAULT NULL,
  `Status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `AssignmentSubmissions`
--

CREATE TABLE `AssignmentSubmissions` (
  `SubmissionID` int(11) NOT NULL,
  `StudentID` int(11) DEFAULT NULL,
  `AssignmentID` int(11) DEFAULT NULL,
  `SubmissionText` text DEFAULT NULL,
  `SubmissionFile` varchar(255) DEFAULT NULL,
  `SubmissionDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `TeacherFeedback` text DEFAULT NULL,
  `Grade` int(11) DEFAULT NULL,
  `IsLateSubmission` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Classes`
--

CREATE TABLE `Classes` (
  `ClassID` int(11) NOT NULL,
  `ClassName` varchar(50) DEFAULT NULL,
  `EducationLevel` varchar(20) DEFAULT NULL,
  `HomeroomTeacher` varchar(50) DEFAULT NULL,
  `Curriculum` varchar(50) DEFAULT NULL,
  `AcademicYear` varchar(20) DEFAULT NULL,
  `ClassCode` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Classes`
--

INSERT INTO `Classes` (`ClassID`, `ClassName`, `EducationLevel`, `HomeroomTeacher`, `Curriculum`, `AcademicYear`, `ClassCode`) VALUES
(1, 'X TKJ 2 - (Teknik Komputer Jaringan)', 'SMK', '1', 'Kurikulum Merdeka', '2023', NULL),
(2, 'X TKJ 1 - (Teknik Komputer Jaringan)', 'SMK', '2', 'Kurikulum Merdeka', '2023', NULL),
(3, 'XI TKJ 2 - (Teknik Komputer Jaringan)', 'SMK', '3', 'Kurikulum Merdeka', '2023', NULL),
(5, 'XI TKJ 1 - (Teknik Komputer Jaringan)', 'SMK', '4', 'Kurikulum Merdeka', '2023', NULL),
(6, 'XII TKJ 1 - (Teknik Komputer Jaringan)', 'SMK', '6', 'Kurikulum Merdeka', '2023', NULL),
(7, 'XII TKJ 2 - (Teknik Komputer Jaringan)', 'SMK', '', 'Kurikulum Merdeka', '2023', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ClassSubjects`
--

CREATE TABLE `ClassSubjects` (
  `ClassSubjectID` int(11) NOT NULL,
  `ClassID` int(11) DEFAULT NULL,
  `SubjectID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ClassSubjects`
--

INSERT INTO `ClassSubjects` (`ClassSubjectID`, `ClassID`, `SubjectID`) VALUES
(9, 1, 1),
(10, 1, 2),
(11, 2, 2),
(14, 3, 2),
(15, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `LogActivity`
--

CREATE TABLE `LogActivity` (
  `LogID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `ActivityDescription` text DEFAULT NULL,
  `ActivityTimestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `LogActivity`
--

INSERT INTO `LogActivity` (`LogID`, `UserID`, `ActivityDescription`, `ActivityTimestamp`) VALUES
(1, 0, 'User logged in', '2023-10-29 12:35:16'),
(2, 0, 'User logged out', '2023-10-29 12:38:21'),
(3, 0, 'User logged in', '2023-10-29 12:38:44'),
(4, 0, 'User logged out', '2023-10-29 12:47:05'),
(5, 0, 'User logged in', '2023-10-29 12:47:14'),
(6, 0, 'User logged out', '2023-10-29 12:48:24'),
(7, 0, 'User logged in', '2023-10-29 12:48:36'),
(8, 0, 'User logged out', '2023-10-29 13:04:46'),
(9, 0, 'User logged in', '2023-10-29 13:04:55'),
(10, 0, 'Changed profile picture from 653e58a6edc33.png to 653e5a409b4fb.jpeg', '2023-10-29 13:12:32'),
(11, 0, 'User updated profile. Changes: Full Name, Email, Username, Date of Birth, Gender, Address, Phone Number', '2023-10-29 13:17:59'),
(12, 0, 'User updated profile. Changes: ', '2023-10-29 13:18:51'),
(13, 0, 'User updated profile. Changes: Address', '2023-10-29 13:19:00'),
(14, 0, 'User updated profile. Changes: Address', '2023-10-29 13:19:10'),
(15, 0, 'User logged out', '2023-10-29 13:26:42'),
(16, 137648118, 'User logged in', '2023-10-29 13:36:58'),
(17, 137648118, 'User logged in', '2023-10-30 18:31:34'),
(18, 137648118, 'User logged out', '2023-10-30 18:32:03'),
(19, 0, 'User logged in', '2023-10-30 18:40:08'),
(20, 0, 'User logged out', '2023-10-30 18:40:16'),
(21, 137648118, 'User logged in', '2023-10-30 18:40:26'),
(22, 137648118, 'User logged out', '2023-10-30 18:47:04'),
(23, 137648118, 'User logged in', '2023-10-30 18:48:04'),
(24, 137648118, 'User logged out', '2023-10-30 18:56:49'),
(25, 137648118, 'User logged in', '2023-10-30 18:57:08'),
(26, 137648118, 'User logged out', '2023-10-30 19:42:06'),
(27, 137648118, 'User logged in', '2023-10-30 19:42:20'),
(28, 137648118, 'User logged in', '2023-10-31 00:32:36'),
(29, 137648118, 'User with UserID: 0 has been deleted.', '2023-10-31 00:35:41'),
(30, 137648118, 'Class created: X TKJ 1 - (Teknik Komputer Jaringan), Academic Year: 2023', '2023-10-31 00:37:20'),
(31, 137648118, 'Class created: X TKJ 2 - (Teknik Komputer Jaringan), Academic Year: 2023', '2023-10-31 00:39:20'),
(32, 137648118, 'Class updated: X TKJ 2 - (Teknik Komputer Jaringan), Academic Year: 2023', '2023-10-31 00:42:40'),
(33, 137648118, 'Class updated: X TKJ 1 - (Teknik Komputer Jaringan), Academic Year: 2023', '2023-10-31 00:42:46'),
(34, 137648118, 'Class with ClassID: 2 has been deleted.', '2023-10-31 00:44:33'),
(35, 137648118, 'Class created: X TKJ 2 - (Teknik Komputer Jaringan), Academic Year: 2023', '2023-10-31 00:44:47'),
(36, 137648118, 'User with Username: abisatria has been created with the following details - Full Name: Abi Satria, Role: 3, Account Status: Active, Activation Status: Activated.', '2023-10-31 01:20:55'),
(37, 137648118, 'User with UserID: 2147483647 has been deleted.', '2023-10-31 01:21:37'),
(38, 137648118, 'Student created: Student Number: 123523542, Parent/Guardian: asdgasdg', '2023-10-31 01:25:34'),
(39, 137648118, 'Student created: Student Number: 235235234, Parent/Guardian: Paijo', '2023-10-31 01:45:31'),
(40, 137648118, 'Student created: Student Number: 324235235, Parent/Guardian: Paijo', '2023-10-31 01:49:30'),
(41, 137648118, 'User with UserID: 1698716970 has been deleted.', '2023-10-31 01:49:42'),
(42, 137648118, 'User with UserID: 65405 has been deleted.', '2023-10-31 01:50:05'),
(43, 137648118, 'User with Username: ahmadhasby has been update.', '2023-10-31 02:17:15'),
(44, 137648118, 'User with Username: akbarsandi has been update.', '2023-10-31 02:17:23'),
(45, 137648118, 'Student created: Student Number: 542436546, Parent/Guardian: Kartu Prakerja - Firmansyah Mukti Wijaya', '2023-10-31 02:22:15'),
(46, 137648118, 'Class updated: X TKJ 1 - (Teknik Komputer Jaringan), Academic Year: 2023', '2023-10-31 02:22:43'),
(47, 137648118, 'Class created: X TKJ 2 - (Teknik Komputer Jaringan), Academic Year: 2023', '2023-10-31 02:24:28'),
(48, 137648118, 'Class updated: XI TKJ 2 - (Teknik Komputer Jaringan), Academic Year: 2023', '2023-10-31 02:24:47'),
(49, 137648118, 'Student created: Student Number: 8345738488, Parent/Guardian: Paijo', '2023-10-31 02:30:01'),
(50, 137648118, 'User with Username: root has been updated.', '2023-10-31 02:43:54'),
(51, 137648118, 'Student with Username: ahmadhasby has been updated.', '2023-10-31 02:50:21'),
(52, 137648118, 'Student with Username: root has been updated.', '2023-10-31 02:50:30'),
(53, 137648118, 'Student with Username: abisatria has been updated.', '2023-10-31 02:50:48'),
(54, 137648118, 'Student with Username: abisatria has been updated.', '2023-10-31 02:59:38'),
(55, 137648118, 'Student with Username: abisatria1 has been updated.', '2023-10-31 03:02:52'),
(56, 137648118, 'Student with UserID: 3 has been deleted.', '2023-10-31 03:07:03'),
(57, 137648118, 'Student with UserID: 5 has been deleted.', '2023-10-31 03:07:25'),
(58, 137648118, 'Student with UserID: 3 has been deleted.', '2023-10-31 03:08:34'),
(59, 137648118, 'Student with UserID: 3 has been deleted.', '2023-10-31 03:08:49'),
(60, 137648118, 'Student with UserID: 3 has been deleted.', '2023-10-31 03:09:20'),
(61, 137648118, 'Student with StudentID: 2 has been removed from ClassID: 3.', '2023-10-31 03:40:57'),
(62, 137648118, 'Subject created: Jaringan Dasar', '2023-10-31 04:23:37'),
(63, 137648118, 'Subject created: Sistem Operasi', '2023-10-31 04:28:33'),
(64, 137648118, 'Subject created: Pemrograman Lanjut', '2023-10-31 04:29:48'),
(65, 137648118, 'User logged out', '2023-10-31 04:36:47'),
(66, 137648118, 'User logged in', '2023-10-31 04:53:10'),
(67, 137648118, 'User logged out', '2023-10-31 04:53:44'),
(68, 137648118, 'User logged in', '2023-10-31 04:53:53'),
(69, 137648118, 'User logged out', '2023-10-31 04:58:46'),
(70, 0, 'User logged in', '2023-10-31 04:58:56'),
(71, 0, 'User logged out', '2023-10-31 05:14:07'),
(72, 0, 'User logged in', '2023-10-31 05:14:14'),
(73, 0, 'User logged out', '2023-10-31 05:16:24'),
(74, 0, 'User logged in', '2023-10-31 05:16:31'),
(75, 0, 'User logged in', '2023-10-31 05:19:43'),
(76, 137648118, 'Student created: Student Number: 13466174142, Parent/Guardian: dfgsdg', '2023-11-03 10:17:23'),
(77, 137648118, 'User logged out', '2023-11-03 10:17:49'),
(78, 0, 'User logged in', '2023-11-03 10:17:58'),
(79, 0, 'User logged out', '2023-11-03 10:18:12'),
(80, 137648118, 'User logged in', '2023-11-03 10:18:17'),
(81, 137648118, 'Student with Username: ahmadhasby has been updated.', '2023-11-03 10:18:30'),
(82, 137648118, 'Teacher created: NIP: 24124123515, Full Name: Asadul Azis', '2023-11-03 10:39:19'),
(83, 137648118, 'Teacher created: NIP: 14125323523, Full Name: agadhsdh', '2023-11-03 10:41:57'),
(84, 137648118, 'Teacher created: NIP: 34235235, Full Name: fasdgsdgsg', '2023-11-03 10:46:30'),
(85, 137648118, 'Teacher created: NIP: 23582358253, Full Name: adgsapgiaoipdgh', '2023-11-03 10:55:06'),
(86, 137648118, 'User with Username: nanda has been created with the following details - Full Name: oufhoudhgouhd, Role: 2, Account Status: Active, Activation Status: Activated.', '2023-11-03 10:55:59'),
(87, 137648118, 'User with UserID: 1699008959 has been deleted.', '2023-11-03 10:56:03'),
(88, 137648118, 'Teacher created: NIP: 143225435, Full Name: ', '2023-11-03 11:02:16'),
(89, 137648118, 'Teacher with Username: aziz has been updated.', '2023-11-03 11:20:03'),
(90, 137648118, 'User logged in', '2023-11-06 18:36:19'),
(91, 137648118, 'Class updated: X TKJ 2 - (Teknik Komputer Jaringan), Academic Year: 2023', '2023-11-06 18:45:30'),
(92, 137648118, 'Class updated: X TKJ 1 - (Teknik Komputer Jaringan), Academic Year: 2023', '2023-11-06 18:47:49'),
(93, 137648118, 'Class updated: XI TKJ 2 - (Teknik Komputer Jaringan), Academic Year: 2023', '2023-11-06 18:47:55'),
(94, 137648118, 'Subject with SubjectID: 3 has been removed from ClassID: 3.', '2023-11-06 19:17:18'),
(95, 137648118, 'Subject with SubjectID: 1 has been removed from ClassID: 3.', '2023-11-06 19:18:56'),
(96, 137648118, 'Teacher with Username: renal has been updated.', '2023-11-06 19:19:38'),
(97, 137648118, 'Teacher with Username: agdag has been updated.', '2023-11-06 19:19:46'),
(98, 137648118, 'Teacher with Username: agdag has been updated.', '2023-11-06 19:20:04'),
(99, 137648118, 'Subject updated: Jaringan Dasar', '2023-11-06 19:25:52'),
(100, 137648118, 'Subject updated: Jaringan Dasar', '2023-11-06 19:26:14'),
(101, 137648118, 'Subject created: asf', '2023-11-06 19:28:16'),
(102, 137648118, 'Subject with SubjectID: 4 has been deleted.', '2023-11-06 19:28:19'),
(103, 137648118, 'User logged out', '2023-11-06 19:37:17'),
(104, 137648118, 'User logged in', '2023-11-06 19:37:23'),
(105, 137648118, 'User logged in', '2023-11-06 19:37:53'),
(106, 137648118, 'User logged in', '2023-11-06 23:54:22'),
(107, 137648118, 'Material created: asdgasgads', '2023-11-07 00:12:24'),
(108, 137648118, 'Material created: Material 1', '2023-11-07 00:20:23'),
(109, 137648118, 'Material created: Material 2', '2023-11-07 00:21:21'),
(110, 137648118, 'Material created: Material 3', '2023-11-07 00:23:16'),
(111, 137648118, 'Material created: Material 4', '2023-11-07 00:33:26'),
(112, 137648118, 'Material created: Material 1', '2023-11-07 00:51:49'),
(113, 137648118, 'Material created: Material 1', '2023-11-07 00:59:02'),
(114, 137648118, 'Material created: Material 2', '2023-11-07 00:59:56'),
(115, 137648118, 'Material created: Material2', '2023-11-07 01:07:29'),
(116, 137648118, 'Material created: Material 2', '2023-11-07 01:41:00'),
(117, 137648118, 'Material created: Material 1', '2023-11-07 01:57:44'),
(118, 137648118, 'User logged in', '2023-11-07 04:45:09'),
(119, 137648118, 'User logged out', '2023-11-07 04:59:48'),
(120, 137648118, 'User logged in', '2023-11-07 05:00:40'),
(121, 137648118, 'User logged out', '2023-11-07 05:01:19'),
(122, 137648118, 'User logged in', '2023-11-07 05:01:58'),
(123, 137648118, 'User logged out', '2023-11-07 05:08:52'),
(124, 137648118, 'User logged in', '2023-11-07 05:16:21'),
(125, 137648118, 'User logged out', '2023-11-07 05:16:31'),
(128, 137648118, 'User logged in', '2023-11-07 05:31:13'),
(135, 137648118, 'User logged in', '2023-11-07 12:39:04'),
(136, 137648118, 'Material created: A) Dasar Teori', '2023-11-07 12:49:39'),
(137, 137648118, 'Material created: B) Sejarah Jaringan Komputer', '2023-11-07 13:06:52'),
(138, 137648118, 'Material created: C) Tipe-Tipe Jaringan Komputer', '2023-11-07 13:12:22'),
(139, 137648118, 'Material created: D) Topologi Jaringan', '2023-11-07 13:17:46'),
(140, 137648118, 'Material created: A. Pendahuluan', '2023-11-07 13:41:54'),
(141, 137648118, 'Material created: B) Jenis-Jenis Arsitektur Jaringan', '2023-11-07 13:46:09'),
(142, 137648118, 'Material created: C) Keamanan Jaringan', '2023-11-07 13:49:45'),
(143, 137648118, 'Material created: D) Skalabilitas dan Ketersediaan', '2023-11-07 13:51:58'),
(144, 137648118, 'Material created: A. Pengertian Protokol Jaringan', '2023-11-07 13:54:15'),
(145, 137648118, 'Material created: B) Sejarah Protokol Jaringan', '2023-11-07 13:57:39'),
(146, 137648118, 'Material created: C) Pendalaman Protokol Jaringan', '2023-11-07 14:01:19'),
(147, 137648118, 'Material created: A) Pengertian IP Address', '2023-11-07 14:05:31'),
(148, 137648118, 'Material created: B) Jenis-Jenis IP Address', '2023-11-07 14:09:21'),
(149, 137648118, 'Material created: C) Penggunaan IP Address', '2023-11-07 21:03:02'),
(150, 137648118, 'Material created: D) Cara Menghitung IP Address dan Sunet Mask', '2023-11-07 21:05:12'),
(152, 137648118, 'User logged in', '2023-11-15 21:48:17'),
(153, 137648118, 'User logged out', '2023-11-15 21:49:03'),
(156, 137648118, 'User logged in', '2023-11-15 22:11:39'),
(157, 137648118, 'User logged in', '2023-11-17 11:47:16'),
(158, 137648118, 'User logged out', '2023-11-17 12:37:39'),
(159, 137648118, 'User logged in', '2023-11-17 12:37:57'),
(160, 137648118, 'User logged in', '2023-11-20 11:07:39'),
(161, 137648118, 'Question created for Test ID: 1', '2023-11-20 11:11:12'),
(162, 137648118, 'Question created for Test ID: 1', '2023-11-20 11:11:41'),
(163, 137648118, 'Question created for Test ID: 1', '2023-11-20 11:12:06'),
(164, 137648118, 'Question created for Test ID: 1', '2023-11-20 11:12:37'),
(165, 137648118, 'Question created for Test ID: 1', '2023-11-20 11:14:25'),
(166, 137648118, 'Question created for Test ID: 1', '2023-11-20 11:14:56'),
(167, 137648118, 'Question created for Test ID: 1', '2023-11-20 11:18:32'),
(168, 137648118, 'Question created for Test ID: 1', '2023-11-20 11:23:44'),
(169, 137648118, 'Question created for Test ID: 1', '2023-11-20 11:24:33'),
(170, 137648118, 'Question created for Test ID: 1', '2023-11-20 11:24:49'),
(171, 137648118, 'Question created for Test ID: 1', '2023-11-20 11:27:05'),
(172, 137648118, 'Question created for Test ID: 1', '2023-11-20 11:27:58'),
(173, 137648118, 'Question created for Test ID: 1', '2023-11-20 11:29:22'),
(174, 137648118, 'Question created for Test ID: 1', '2023-11-20 11:30:38'),
(175, 137648118, 'Question updated with ID: 5', '2023-11-20 22:15:53'),
(176, 137648118, 'Question updated with ID: 5', '2023-11-20 22:16:08'),
(177, 137648118, 'Question updated with ID: 5', '2023-11-20 22:16:15'),
(178, 137648118, 'Question updated with ID: 5', '2023-11-20 22:18:15'),
(179, 137648118, 'Question updated with ID: 5', '2023-11-20 22:18:20'),
(180, 137648118, 'Question updated with ID: 5', '2023-11-20 22:20:36'),
(181, 137648118, 'Question updated with ID: 5', '2023-11-20 22:22:14'),
(182, 137648118, 'Question updated with ID: 5', '2023-11-20 22:22:24'),
(183, 137648118, 'Question updated with ID: 5', '2023-11-20 22:22:29'),
(184, 137648118, 'Question updated with ID: 5', '2023-11-20 22:24:07'),
(185, 137648118, 'Question updated with ID: 5', '2023-11-20 22:26:51'),
(186, 137648118, 'Question updated with ID: 5', '2023-11-20 22:27:12'),
(187, 137648118, 'Question updated with ID: 5', '2023-11-20 22:29:15'),
(188, 137648118, 'Question updated with ID: 5', '2023-11-20 22:29:21'),
(189, 137648118, 'Question updated with ID: 5', '2023-11-20 22:29:42'),
(190, 137648118, 'Question updated with ID: 5', '2023-11-20 22:29:45'),
(191, 137648118, 'Question updated with ID: 4', '2023-11-20 22:29:53'),
(192, 137648118, 'Question updated with ID: 4', '2023-11-20 22:30:00'),
(193, 137648118, 'Question updated with ID: 14', '2023-11-20 22:30:14'),
(194, 137648118, 'Question with QuestionID: 20 has been deleted.', '2023-11-20 22:40:37'),
(195, 137648118, 'Question with QuestionID: 1 has been deleted.', '2023-11-20 22:43:14'),
(196, 137648118, 'Question with QuestionID: 1 has been deleted.', '2023-11-20 22:43:29'),
(197, 137648118, 'Question with QuestionID: 1 has been deleted.', '2023-11-20 22:43:36'),
(198, 137648118, 'Question with QuestionID: 1 has been deleted.', '2023-11-20 22:44:06'),
(199, 137648118, 'Question with QuestionID: 16 has been deleted.', '2023-11-20 22:44:49'),
(200, 137648118, 'Question with QuestionID: 15 has been deleted.', '2023-11-20 22:44:52'),
(201, 137648118, 'Question with QuestionID: 14 has been deleted.', '2023-11-20 22:44:55'),
(202, 137648118, 'Question with QuestionID: 11 has been deleted.', '2023-11-20 22:45:01'),
(203, 137648118, 'Test with TestID: 2 has been deleted.', '2023-11-20 22:49:19'),
(204, 137648118, 'Test with TestID: 1 has been deleted.', '2023-11-20 22:49:23'),
(205, 137648118, 'Exam created: 13, Type: Pretest, Name: Pretest Pengenalan Jaringan Dasar', '2023-11-20 23:01:04'),
(206, 137648118, 'Exam created: 14, Type: Pretest, Name: Pretest Sejarah Jaringan Komputer', '2023-11-20 23:03:02'),
(207, 137648118, 'User logged in', '2023-11-21 00:03:01'),
(208, 137648118, 'Question created for Test ID: 5', '2023-11-21 00:42:53'),
(209, 137648118, 'Question created for Test ID: 5', '2023-11-21 00:50:28'),
(210, 137648118, 'Question created for Test ID: 5', '2023-11-21 01:04:07'),
(211, 137648118, 'User logged out', '2023-11-21 01:07:53'),
(212, 137648118, 'User logged in', '2023-11-21 01:08:07'),
(213, 137648118, 'Question created for Test ID: 5', '2023-11-21 01:09:02'),
(214, 137648118, 'Question updated with ID: 27', '2023-11-21 01:20:47'),
(215, 137648118, 'Question updated with ID: 27', '2023-11-21 01:21:42'),
(216, 137648118, 'Question updated with ID: 27', '2023-11-21 01:24:44'),
(217, 137648118, 'Question updated with ID: 27', '2023-11-21 01:24:52'),
(218, 137648118, 'Question updated with ID: 27', '2023-11-21 01:27:02'),
(219, 137648118, 'Question updated with ID: 27', '2023-11-21 01:27:30'),
(220, 137648118, 'Question updated with ID: 27', '2023-11-21 01:28:10'),
(221, 137648118, 'Question updated with ID: 27', '2023-11-21 01:28:17'),
(222, 137648118, 'Question updated with ID: 27', '2023-11-21 01:28:30'),
(223, 137648118, 'Question updated with ID: 27', '2023-11-21 01:29:22'),
(224, 137648118, 'Question updated with ID: 27', '2023-11-21 01:29:30'),
(225, 137648118, 'Question updated with ID: 27', '2023-11-21 01:30:20'),
(226, 137648118, 'Question updated with ID: 27', '2023-11-21 01:30:27'),
(227, 137648118, 'Question updated with ID: 27', '2023-11-21 01:31:40'),
(228, 137648118, 'Question updated with ID: 27', '2023-11-21 01:31:44'),
(229, 137648118, 'Question updated with ID: 27', '2023-11-21 01:31:49'),
(230, 137648118, 'Question updated with ID: 27', '2023-11-21 01:31:55'),
(231, 137648118, 'Question with QuestionID: 27 has been deleted.', '2023-11-21 01:35:10'),
(232, 137648118, 'Question with QuestionID: 25 has been deleted.', '2023-11-21 01:36:01'),
(233, 137648118, 'Question with QuestionID: 24 has been deleted.', '2023-11-21 01:36:07'),
(234, 137648118, 'User logged out', '2023-11-21 01:37:00'),
(237, 137648118, 'User logged in', '2023-11-21 01:38:45'),
(238, 137648118, 'Question created for Test ID: 5', '2023-11-21 01:40:35'),
(239, 137648118, 'Question created for Test ID: 5', '2023-11-21 01:41:09'),
(240, 137648118, 'Question updated with ID: 29', '2023-11-21 01:41:23'),
(241, 137648118, 'Question created for Test ID: 5', '2023-11-21 01:42:53'),
(242, 137648118, 'Question created for Test ID: 5', '2023-11-21 01:43:09'),
(243, 137648118, 'Question created for Test ID: 5', '2023-11-21 01:43:22'),
(244, 137648118, 'Question updated with ID: 32', '2023-11-21 01:43:27'),
(245, 137648118, 'Question created for Test ID: 5', '2023-11-21 01:44:17'),
(246, 137648118, 'Question created for Test ID: 5', '2023-11-21 01:44:57'),
(247, 137648118, 'Question updated with ID: 29', '2023-11-21 01:45:15'),
(248, 137648118, 'Question updated with ID: 28', '2023-11-21 01:45:29'),
(249, 137648118, 'Test with TestID: 6 has been deleted.', '2023-11-21 02:00:17'),
(250, 137648118, 'Exam created: 24, Type: Post-test, Name: sfsdfsdf', '2023-11-21 02:38:07'),
(251, 137648118, 'Test with TestID: 14 has been deleted.', '2023-11-21 02:38:12'),
(252, 137648118, 'User logged out', '2023-11-21 02:38:22'),
(268, 137648118, 'User logged in', '2023-11-21 14:49:15'),
(269, 137648118, 'Question updated with ID: 28', '2023-11-21 14:49:54'),
(270, 137648118, 'Question updated with ID: 29', '2023-11-21 14:50:03'),
(271, 137648118, 'Question updated with ID: 34', '2023-11-21 14:50:11'),
(272, 137648118, 'User logged out', '2023-11-21 14:50:19'),
(276, 137648118, 'User logged in', '2023-11-22 02:11:56'),
(277, 137648118, 'User with Username: mukti has been created with the following details - Full Name: Firmansyah Mukti Wijaya, Role: 3, Account Status: Active, Activation Status: Activated.', '2023-11-22 02:12:37'),
(278, 137648118, 'User with UserID: 1700619157 has been deleted.', '2023-11-22 02:12:45'),
(279, 137648118, 'Student created: Student Number: 0124981284, Parent/Guardian: saffsa', '2023-11-22 02:13:30'),
(282, 137648118, 'Material created: TOPOLOGI', '2023-11-22 02:27:37'),
(283, 137648118, 'Question with QuestionID: 28 has been deleted.', '2023-11-22 02:28:28'),
(284, 137648118, 'Exam created: 20, Type: Post-test, Name: CEK PEMAHAMAN SISWA Skalabilitas dan Ketersediaan', '2023-11-22 02:30:07'),
(285, 137648118, 'Question created for Test ID: 15', '2023-11-22 02:31:17'),
(286, 137648118, 'User logged in', '2023-11-24 02:14:32'),
(287, 137648118, 'User logged out', '2023-11-24 02:14:44'),
(290, 137648118, 'User logged in', '2023-12-11 04:19:02'),
(291, 137648118, 'Assignment created: SubjectID: 1, MaterialID: 27, Title: Praktek Woe', '2023-12-11 05:03:09'),
(292, 137648118, 'Assignment created: SubjectID: 1, MaterialID: 26, Title: Praktek Yahud', '2023-12-11 05:04:02'),
(293, 137648118, 'Assignment with AssignmentID: 2 has been deleted.', '2023-12-11 06:18:34'),
(294, 137648118, 'User logged out', '2023-12-11 06:19:20'),
(295, 137648118, 'User logged in', '2023-12-11 06:19:29'),
(296, 137648118, 'Assignment created: SubjectID: 1, MaterialID: 16, Title: asdasfsfaedsfg', '2023-12-11 06:19:52'),
(297, 137648118, 'User logged out', '2023-12-11 06:19:57'),
(298, 137648118, 'User logged in', '2023-12-11 06:20:02'),
(299, 137648118, 'User logged out', '2023-12-11 06:20:05'),
(304, 137648118, 'User logged in', '2023-12-11 07:32:32'),
(305, 137648118, 'Assignment created: SubjectID: 1, MaterialID: 7, Title: asdasdgdsgsdg', '2023-12-11 07:33:13'),
(306, 137648118, 'User logged out', '2023-12-11 07:33:19'),
(309, 137648118, 'User logged in', '2023-12-11 08:24:30'),
(310, 137648118, 'User logged out', '2023-12-11 08:25:40'),
(313, 137648118, 'User logged in', '2023-12-11 08:28:29'),
(314, 137648118, 'User logged out', '2023-12-11 09:04:17'),
(315, 137648118, 'User logged in', '2023-12-11 09:04:41'),
(316, 137648118, 'User logged out', '2023-12-11 09:10:54'),
(318, 137648118, 'User with UserID: 2147483647 has been deleted.', '2024-01-18 00:35:11'),
(319, 137648118, 'User with UserID: 1698719401 has been deleted.', '2024-01-18 00:35:14'),
(320, 137648118, 'User with UserID: 1699006643 has been deleted.', '2024-01-18 00:35:18'),
(321, 137648118, 'User with UserID: 1698716970 has been deleted.', '2024-01-18 00:35:21'),
(322, 137648118, 'User with UserID: 65405 has been deleted.', '2024-01-18 00:35:24'),
(323, 137648118, 'Test with TestID: 5 has been deleted.', '2024-01-18 00:35:43'),
(324, 137648118, 'Test with TestID: 8 has been deleted.', '2024-01-18 00:35:45'),
(325, 137648118, 'Test with TestID: 9 has been deleted.', '2024-01-18 00:35:47'),
(326, 137648118, 'Test with TestID: 10 has been deleted.', '2024-01-18 00:35:49'),
(327, 137648118, 'Test with TestID: 11 has been deleted.', '2024-01-18 00:35:51'),
(328, 137648118, 'Test with TestID: 12 has been deleted.', '2024-01-18 00:35:53'),
(329, 137648118, 'Test with TestID: 13 has been deleted.', '2024-01-18 00:35:55'),
(330, 137648118, 'Test with TestID: 15 has been deleted.', '2024-01-18 00:35:57'),
(331, 137648118, 'Assignment with AssignmentID: 1 has been deleted.', '2024-01-18 00:36:00'),
(332, 137648118, 'Assignment with AssignmentID: 3 has been deleted.', '2024-01-18 00:36:02'),
(333, 137648118, 'Assignment with AssignmentID: 4 has been deleted.', '2024-01-18 00:36:04'),
(334, 137648118, 'Teacher with Username: aziz has been updated.', '2024-01-18 00:38:34'),
(335, 137648118, 'Teacher with Username: tika has been updated.', '2024-01-18 00:39:12'),
(336, 137648118, 'Teacher with Username: renal has been updated.', '2024-01-18 00:39:50'),
(337, 137648118, 'Teacher with Username: agdag has been updated.', '2024-01-18 00:40:31'),
(338, 137648118, 'Teacher with Username: renal has been updated.', '2024-01-18 00:40:40'),
(339, 137648118, 'Teacher created: NIP: 9835792345, Full Name: ', '2024-01-18 00:41:12'),
(340, 137648118, 'Teacher with Username: nanda has been updated.', '2024-01-18 00:41:32'),
(341, 137648118, 'Class created: XI TKJ 1 - (Teknik Komputer Jaringan), Academic Year: 2023', '2024-01-18 00:43:14'),
(342, 137648118, 'Class created: XII TKJ 1 - (Teknik Komputer Jaringan), Academic Year: 2023', '2024-01-18 00:44:00'),
(343, 137648118, 'Class created: XII TKJ 1 - (Teknik Komputer Jaringan), Academic Year: 2023', '2024-01-18 00:44:39'),
(344, 137648118, 'Class updated: XII TKJ 2 - (Teknik Komputer Jaringan), Academic Year: 2023', '2024-01-18 00:45:15');

-- --------------------------------------------------------

--
-- Table structure for table `Materials`
--

CREATE TABLE `Materials` (
  `MaterialID` int(11) NOT NULL,
  `SubjectID` int(11) DEFAULT NULL,
  `TitleMaterial` varchar(255) DEFAULT NULL,
  `Type` varchar(50) DEFAULT NULL,
  `Content` longtext DEFAULT NULL,
  `Link` varchar(255) DEFAULT NULL,
  `Sequence` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Materials`
--

INSERT INTO `Materials` (`MaterialID`, `SubjectID`, `TitleMaterial`, `Type`, `Content`, `Link`, `Sequence`) VALUES
(7, 1, 'Tujuan Pembelajaran', 'Informasi', 'Material 1', '../materials_data/1_TujuanPembelajaran.php', 1),
(13, 1, 'A) Dasar Teori', 'Pengenalan Jaringan', 'Test', '../materials_data/1_A)DasarTeori.php', 2),
(14, 1, 'B) Sejarah Jaringan Komputer', 'Pengenalan Jaringan', 'Test', '../materials_data/1_B)SejarahJaringanKomputer.php', 3),
(15, 1, 'C) Tipe-Tipe Jaringan Komputer', 'Pengenalan Jaringan', 'Test', '../materials_data/1_C)Tipe-TipeJaringanKomputer.php', 4),
(16, 1, 'D) Topologi Jaringan', 'Pengenalan Jaringan', 'Test', '../materials_data/1_D)TopologiJaringan.php', 5),
(17, 1, 'A) Pendahuluan', 'Arsitektur Jaringan', 'Test', '../materials_data/1_A)Pendahuluan.php', 6),
(18, 1, 'B) Jenis-Jenis Arsitektur Jaringan', 'Arsitektur Jaringan', 'Test', '../materials_data/1_B)Jenis-JenisArsitekturJaringan.php', 7),
(19, 1, 'C) Keamanan Jaringan', 'Arsitektur Jaringan', 'Test', '../materials_data/1_C)KeamananJaringan.php', 8),
(20, 1, 'D) Skalabilitas dan Ketersediaan', 'Arsitektur Jaringan', 'Test', '../materials_data/1_D)SkalabilitasdanKetersediaan.php', 9),
(21, 1, 'A) Pengertian Protokol Jaringan', 'Protokol Jaringan', 'Test', '../materials_data/1_A)PengertianProtokolJaringan.php', 10),
(22, 1, 'B) Sejarah Protokol Jaringan', 'Protokol Jaringan', 'Test', '../materials_data/1_B)SejarahProtokolJaringan.php', 11),
(23, 1, 'C) Pendalaman Protokol Jaringan', 'Protokol Jaringan', 'Test', '../materials_data/1_C)PendalamanProtokolJaringan.php', 12),
(24, 1, 'A) Pengertian IP Address', 'IP Address', 'Test', '../materials_data/1_A)PengertianIPAddress.php', 13),
(25, 1, 'B) Jenis-Jenis IP Address', 'IP Address', 'Test', '../materials_data/1_B)Jenis-JenisIPAddress.php', 14),
(26, 1, 'C) Penggunaan IP Address', 'IP Address', 'Test', '../materials_data/1_C)PenggunaanIPAddress.php', 15),
(27, 1, 'D) Cara Menghitung IP Address dan Subnet Mask', 'IP Address', 'Test', '../materials_data/1_D)CaraMenghitungIPAddressdanSubnetMask.php', 17);

-- --------------------------------------------------------

--
-- Table structure for table `Questions`
--

CREATE TABLE `Questions` (
  `QuestionID` int(11) NOT NULL,
  `QuestionText` text NOT NULL,
  `QuestionType` enum('multiple_choice','true_false','single_choice') NOT NULL,
  `TestID` int(11) DEFAULT NULL,
  `QuestionImage` varchar(1024) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Role`
--

CREATE TABLE `Role` (
  `RoleID` int(11) NOT NULL,
  `RoleName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Role`
--

INSERT INTO `Role` (`RoleID`, `RoleName`) VALUES
(1, 'Admin'),
(3, 'Student'),
(2, 'Teacher');

-- --------------------------------------------------------

--
-- Table structure for table `StudentResponses`
--

CREATE TABLE `StudentResponses` (
  `ResponseID` int(11) NOT NULL,
  `StudentID` int(11) DEFAULT NULL,
  `TestID` int(11) DEFAULT NULL,
  `QuestionID` int(11) DEFAULT NULL,
  `AnswerID` int(11) DEFAULT NULL,
  `IsCorrect` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Students`
--

CREATE TABLE `Students` (
  `StudentID` int(11) NOT NULL,
  `StudentNumber` varchar(20) DEFAULT NULL,
  `Religion` varchar(20) DEFAULT NULL,
  `ParentGuardianFullName` varchar(100) DEFAULT NULL,
  `ParentGuardianAddress` varchar(256) DEFAULT NULL,
  `ParentGuardianPhoneNumber` varchar(20) DEFAULT NULL,
  `ParentGuardianEmail` varchar(100) DEFAULT NULL,
  `ClassID` int(11) DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Students`
--

INSERT INTO `Students` (`StudentID`, `StudentNumber`, `Religion`, `ParentGuardianFullName`, `ParentGuardianAddress`, `ParentGuardianPhoneNumber`, `ParentGuardianEmail`, `ClassID`, `UserID`) VALUES
(1, 'SN001', 'Islam', 'Orang Tua Abi Satria', 'Kediri', '08123456789', 'orangtua.abi.satria@example.com', 5, 1705540077),
(2, 'SN002', 'Islam', 'Orang Tua Aditya Bagas Prasetyo', 'Kediri', '08123456788', 'orangtua.aditya.prasetyo@example.com', 5, 1705540078),
(3, 'SN003', 'Islam', 'Orang Tua Ahmad Hasby Maulana', 'Kediri', '08123456787', 'orangtua.ahmad.maulana@example.com', 5, 1705540079),
(4, 'SN004', 'Islam', 'Orang Tua Akbar Sandi Pratama', 'Kediri', '08123456786', 'orangtua.akbar.pratama@example.com', 5, 1705540080),
(5, 'SN005', 'Islam', 'Orang Tua Andrean Prasetyo', 'Kediri', '08123456785', 'orangtua.andrean.prasetyo@example.com', 5, 1705540081),
(6, 'SN006', 'Islam', 'Orang Tua Bintang Krisna', 'Kediri', '08123456784', 'orangtua.bintang.krisna@example.com', 5, 1705540082),
(7, 'SN007', 'Islam', 'Orang Tua Cantika Sari', 'Kediri', '08123456783', 'orangtua.cantika.sari@example.com', 5, 1705540186),
(8, 'SN008', 'Islam', 'Orang Tua Fairus Muzaki', 'Kediri', '08123456782', 'orangtua.fairus.muzaki@example.com', 5, 1705540187),
(9, 'SN009', 'Islam', 'Orang Tua Fajar Sahputra', 'Kediri', '08123456781', 'orangtua.fajar.sahputra@example.com', 5, 1705540188),
(10, 'SN010', 'Islam', 'Orang Tua Farrel Valea', 'Kediri', '08123456780', 'orangtua.farrel.valea@example.com', 5, 1705540189),
(11, 'SN011', 'Islam', 'Orang Tua Fayruz Suherman', 'Kediri', '08123456779', 'orangtua.fayruz.suherman@example.com', 5, 1705540190),
(12, 'SN012', 'Islam', 'Orang Tua Firjatullah Aptanta', 'Kediri', '08123456778', 'orangtua.firjatullah.aptanta@example.com', 5, 1705540191),
(13, 'SN013', 'Islam', 'Orang Tua Geo Pratama', 'Kediri', '08123456777', 'orangtua.geo.pratama@example.com', 5, 1705540192),
(14, 'SN014', 'Islam', 'Orang Tua Ika Listyani', 'Kediri', '08123456776', 'orangtua.ika.listyani@example.com', 5, 1705540193),
(15, 'SN015', NULL, NULL, 'Kediri', NULL, NULL, 5, 1705540194),
(16, 'SN016', 'Islam', 'Orang Tua Mochamad Fa\'izal Fanani', 'Kediri', '08123456775', 'orangtua.faizal.fanani@example.com', 5, 1705540421),
(17, 'SN017', 'Islam', 'Orang Tua Mochamad Nafi\' Abdulloh', 'Kediri', '08123456774', 'orangtua.nafi.abdulloh@example.com', 5, 1705540422),
(18, 'SN018', 'Islam', 'Orang Tua Mochammad Ilham Maulana', 'Kediri', '08123456773', 'orangtua.ilham.maulana@example.com', 5, 1705540423),
(19, 'SN019', 'Islam', 'Orang Tua Muhamad Farhan Habibi', 'Kediri', '08123456772', 'orangtua.farhan.habibi@example.com', 5, 1705540424),
(20, 'SN020', 'Islam', 'Orang Tua Muhammad Najahul Wafa', 'Kediri', '08123456771', 'orangtua.najahul.wafa@example.com', 5, 1705540425),
(21, 'SN021', 'Islam', 'Orang Tua Najdan Farizal Herwin', 'Kediri', '08123456770', 'orangtua.najdan.herwin@example.com', 5, 1705540426),
(22, 'SN022', 'Islam', 'Orang Tua Okta Nia Rahmadhani', 'Kediri', NULL, NULL, 5, 1705540427),
(23, 'SN023', NULL, 'Orang Tua Poppy Dean Zousa', 'Kediri', NULL, NULL, 5, 1705540428),
(24, 'SN024', 'Islam', 'Orang Tua Rama Wijaya', 'Kediri', '08123456769', 'orangtua.rama.wijaya@example.com', 5, 1705540480),
(25, 'SN025', 'Islam', 'Orang Tua Reval Isya Rasya Pratama', 'Kediri', '08123456768', 'orangtua.reval.pratama@example.com', 5, 1705540481),
(26, 'SN026', 'Islam', 'Orang Tua Rifky Prima Adi Saputra', 'Kediri', '08123456767', 'orangtua.rifky.saputra@example.com', 5, 1705540482),
(27, 'SN027', 'Islam', 'Orang Tua Septhia Salfa Egaputri', 'Kediri', NULL, NULL, 5, 1705540483),
(28, 'SN028', NULL, 'Orang Tua Tita Julia Agatha', 'Kediri', NULL, NULL, 5, 1705540484),
(29, 'SN029', 'Islam', 'Orang Tua Wahyu Dwi Cahyo', 'Kediri', '08123456766', 'orangtua.wahyu.cahyo@example.com', 5, 1705540485),
(30, 'SN030', 'Islam', 'Orang Tua Wildan Rangga Adi Putra', 'Kediri', '08123456765', 'orangtua.wildan.putra@example.com', 5, 1705540486),
(31, 'SN031', 'Islam', 'Orang Tua Afrizal Varellino Braga', 'Kediri', '08123456764', 'orangtua.afrizal.braga@example.com', 3, 1705540757),
(32, 'SN032', 'Islam', 'Orang Tua Ahsan Rizaqu Widodo', 'Kediri', '08123456763', 'orangtua.ahsan.widodo@example.com', 3, 1705540758),
(33, 'SN033', 'Islam', 'Orang Tua Alip Ash Shidhiq', 'Kediri', NULL, NULL, 3, 1705540759),
(34, 'SN034', 'Islam', 'Orang Tua Ariel Ferdiansyah Susanto', 'Kediri', '08123456762', 'orangtua.ariel.susanto@example.com', 3, 1705540760),
(35, 'SN035', 'Islam', 'Orang Tua Bagas Andrianto', 'Kediri', '08123456761', 'orangtua.bagas.andrianto@example.com', 3, 1705540761),
(36, 'SN036', 'Islam', 'Orang Tua Bima Ady Zuana', 'Kediri', '08123456760', 'orangtua.bima.zuana@example.com', 3, 1705540762),
(37, 'SN037', NULL, 'Orang Tua Clarintha Chelsy Novianto', 'Kediri', NULL, NULL, 3, 1705540831),
(38, 'SN038', 'Islam', 'Orang Tua Devin Nur Fauzan', 'Kediri', '08123456759', 'orangtua.devin.fauzan@example.com', 3, 1705540832),
(39, 'SN039', 'Islam', 'Orang Tua Dino Tri Laksana P.', 'Kediri', '08123456758', 'orangtua.dino.laksana@example.com', 3, 1705540833),
(40, 'SN040', 'Islam', 'Orang Tua Fathir Akbar Ardandy', 'Kediri', '08123456757', 'orangtua.fathir.ardandy@example.com', 3, 1705540834),
(41, 'SN041', 'Islam', 'Orang Tua Fauzan Alwafi Zufar', 'Kediri', '08123456756', 'orangtua.fauzan.zufar@example.com', 3, 1705540835),
(42, 'SN042', NULL, 'Orang Tua Geofani Wahyu Nur Pratama', 'Kediri', NULL, NULL, 3, 1705540836),
(43, 'SN043', 'Islam', 'Orang Tua Hendra Aditya Pratama', 'Kediri', '08123456755', 'orangtua.hendra.pratama@example.com', 3, 1705540837),
(44, 'SN044', NULL, 'Orang Tua Izza Nur Azizah', 'Kediri', NULL, NULL, 3, 1705540838),
(45, 'SN045', NULL, 'Orang Tua James Martyno Susanto', 'Kediri', NULL, NULL, 3, 1705540839),
(46, 'SN046', 'Islam', 'Orang Tua Moch. Fareed Azka Al-Farid', 'Kediri', '08123456754', 'orangtua.fareed.alfarid@example.com', 3, 1705540898),
(47, 'SN047', NULL, 'Orang Tua Mochammad Dharma Razha Saputra', 'Kediri', NULL, NULL, 3, 1705540899),
(48, 'SN048', 'Islam', 'Orang Tua Moh. Dwi Afandi', 'Kediri', NULL, NULL, 3, 1705540900),
(49, 'SN049', 'Islam', 'Orang Tua Muhamad Fikri Al-Fahrezi', 'Kediri', NULL, NULL, 3, 1705540901),
(50, 'SN050', 'Islam', 'Orang Tua Muhamad Nizar Nasruddin', 'Kediri', NULL, NULL, 3, 1705540902),
(51, 'SN051', 'Islam', 'Orang Tua Muhamad Nufail Fawwaz', 'Kediri', NULL, NULL, 3, 1705540903),
(52, 'SN052', 'Islam', 'Orang Tua Muhamad Rafi Kafka', 'Kediri', NULL, NULL, 3, 1705540904),
(53, 'SN053', 'Islam', 'Orang Tua Muhammad Surya Jamaluddin', 'Kediri', NULL, NULL, 3, 1705540905),
(54, 'SN054', 'Islam', 'Orang Tua Nur Azzhahra Deviani', 'Kediri', NULL, NULL, 3, 1705541001),
(55, 'SN055', NULL, 'Orang Tua Podang Telasih Putri Suwarno', 'Kediri', NULL, NULL, 3, 1705541002),
(56, 'SN056', 'Islam', 'Orang Tua Rohmad Wildan Salas Muchlisin', 'Kediri', NULL, NULL, 3, 1705541003),
(57, 'SN057', NULL, 'Orang Tua Shendy Pratama Nugraha', 'Kediri', NULL, NULL, 3, 1705541004),
(58, 'SN058', 'Islam', 'Orang Tua Siti Nur\'aisyah', 'Kediri', NULL, NULL, 3, 1705541005),
(59, 'SN059', NULL, 'Orang Tua Varel Putra Firmansyah', 'Kediri', NULL, NULL, 3, 1705541006),
(60, 'SN060', NULL, 'Orang Tua Vindi Karunia Hapsari', 'Kediri', NULL, NULL, 3, 1705541007);

-- --------------------------------------------------------

--
-- Table structure for table `Subjects`
--

CREATE TABLE `Subjects` (
  `SubjectID` int(11) NOT NULL,
  `SubjectName` varchar(255) DEFAULT NULL,
  `DifficultyLevel` varchar(50) DEFAULT NULL,
  `TeachingMethod` varchar(100) DEFAULT NULL,
  `LearningObjective` text DEFAULT NULL,
  `DurationHours` int(11) DEFAULT NULL,
  `CurriculumFramework` varchar(100) DEFAULT NULL,
  `AssessmentMethod` varchar(100) DEFAULT NULL,
  `StudentEngagement` varchar(100) DEFAULT NULL,
  `TeacherID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Subjects`
--

INSERT INTO `Subjects` (`SubjectID`, `SubjectName`, `DifficultyLevel`, `TeachingMethod`, `LearningObjective`, `DurationHours`, `CurriculumFramework`, `AssessmentMethod`, `StudentEngagement`, `TeacherID`) VALUES
(1, 'Jaringan Dasar', 'Fase E', 'Pembelajaran Daring', 'Pada akhir mata pelajaran Jaringan Dasar, siswa diharapkan mampu memahami prinsip dasar jaringan komputer, termasuk konsep dasar tentang protokol, alamat IP, topologi jaringan, dan perangkat jaringan. Siswa akan dapat merancang, mengonfigurasi, dan mengelola jaringan kecil hingga menengah. Mereka juga akan memahami keamanan jaringan dasar dan protokol yang digunakan untuk melindungi jaringan. Selain itu, siswa akan dapat menjelaskan aplikasi jaringan yang umumnya digunakan dalam konteks bisnis dan mengidentifikasi tantangan dan peluang yang terkait dengan jaringan komputer dalam dunia nyata.', 40, 'Kurikulum Merdeka', 'Ujian Tertulis, Ujian Praktek, Ujian Lisan', 'Diskusi Kelompok', 1),
(2, 'Sistem Operasi', 'Fase E', 'Pembelajaran daring', 'Pada akhir mata pelajaran Sistem Operasi, siswa diharapkan mampu memahami konsep dasar tentang sistem operasi, manajemen sumber daya, dan interaksi antara perangkat keras dan perangkat lunak dalam komputer. Mereka akan dapat menginstal, mengkonfigurasi, dan mengelola sistem operasi komputer, serta menyelesaikan masalah yang berkaitan dengan sistem operasi. Siswa juga akan memahami pentingnya keamanan sistem operasi dan prinsip-prinsip manajemen hak akses.', 50, 'Kurikulum Merdeka', 'Ujian Tertulis, Proyek Praktikum', 'Diskusi kelompok, Praktikum', 2),
(3, 'Pemrograman Lanjut', 'Fase F', 'Pembelajaran tatap muka', 'Pada akhir mata pelajaran Pemrograman Lanjut, siswa diharapkan mampu menguasai konsep dan teknik pemrograman yang lebih kompleks. Mereka akan dapat merancang dan mengembangkan perangkat lunak yang rumit, menggunakan bahasa pemrograman yang beragam seperti Java, Python, dan C++. Siswa akan memahami konsep berorientasi objek, pemrograman berbasis peristiwa, serta manajemen memori. Selain itu, mereka akan dapat menerapkan praktik-praktik pengujian dan pemecahan masalah yang efektif dalam pengembangan perangkat lunak.', 60, 'Kurikulum Merdeka', ' Proyek pengembangan perangkat lunak, Ujian praktikum', 'Diskusi kelompok, Proyek tim', 3);

-- --------------------------------------------------------

--
-- Table structure for table `Teachers`
--

CREATE TABLE `Teachers` (
  `TeacherID` int(11) NOT NULL,
  `NIP` varchar(20) NOT NULL,
  `AcademicDegree` varchar(50) DEFAULT NULL,
  `EducationLevel` varchar(50) DEFAULT NULL,
  `EmploymentStatus` varchar(50) DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Teachers`
--

INSERT INTO `Teachers` (`TeacherID`, `NIP`, `AcademicDegree`, `EducationLevel`, `EmploymentStatus`, `UserID`) VALUES
(1, '24124123515', 'S.Pd', 'Bachelor Degree', 'Active', 1699007959),
(2, '14125323523', 'S.Pd', 'Bachelor Degree', 'Active', 1699008117),
(3, '34235235', 'S.Pd', 'Bachelor Degree', 'Active', 1699008390),
(4, '23582358253', 'S.Pd', 'Banchelor Degree', 'Active', 1699008906),
(6, '9835792345', 'S.Pd', 'Banchelor Degree', 'Active', 1699008959);

-- --------------------------------------------------------

--
-- Table structure for table `TestResults`
--

CREATE TABLE `TestResults` (
  `ResultID` int(11) NOT NULL,
  `StudentID` int(11) DEFAULT NULL,
  `TestID` int(11) DEFAULT NULL,
  `IsCompleted` tinyint(1) DEFAULT NULL,
  `CorrectAnswers` int(11) DEFAULT NULL,
  `IncorrectAnswers` int(11) DEFAULT NULL,
  `Score` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `TestResults`
--

INSERT INTO `TestResults` (`ResultID`, `StudentID`, `TestID`, `IsCompleted`, `CorrectAnswers`, `IncorrectAnswers`, `Score`) VALUES
(1, 2, 5, 1, 5, 2, 71),
(2, 2, 8, 1, 3, 0, 100),
(3, 2, 11, 1, 1, 2, 33),
(4, 2, 15, 1, 2, -1, 200),
(5, 2, 9, 1, 1, 2, 33);

-- --------------------------------------------------------

--
-- Table structure for table `Tests`
--

CREATE TABLE `Tests` (
  `TestID` int(11) NOT NULL,
  `TestName` varchar(255) NOT NULL,
  `TestType` enum('Pretest','Post-test') NOT NULL,
  `DurationMins` int(11) DEFAULT NULL,
  `NumQuestions` int(11) DEFAULT NULL,
  `MaterialID` int(11) DEFAULT NULL,
  `SubjectID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `FullName` varchar(100) DEFAULT NULL,
  `DateOfBirth` date DEFAULT NULL,
  `Gender` varchar(10) DEFAULT NULL,
  `Address` text DEFAULT NULL,
  `PhoneNumber` varchar(20) DEFAULT NULL,
  `RoleID` int(11) DEFAULT NULL,
  `AccountCreationDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `LastLogin` datetime DEFAULT NULL,
  `AccountStatus` varchar(20) DEFAULT NULL,
  `ProfilePictureURL` varchar(255) DEFAULT NULL,
  `ActivationStatus` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`UserID`, `Username`, `Password`, `Email`, `FullName`, `DateOfBirth`, `Gender`, `Address`, `PhoneNumber`, `RoleID`, `AccountCreationDate`, `LastLogin`, `AccountStatus`, `ProfilePictureURL`, `ActivationStatus`) VALUES
(0, 'ikimukti', 'ef797c8118f02dfb649607dd5d3f8c7623048c9c063d532cc95c5ed7a898a64f', '19103020046@unpkediri.ac.id', 'Firmansyah Mukti Wijaya', '2023-10-12', 'Male', 'Nglaban 1111', '081216318022', 3, '2023-11-03 10:17:58', '2023-11-03 17:17:58', NULL, '653e5a409b4fb.jpeg', 'active'),
(137648118, 'admin', 'ef797c8118f02dfb649607dd5d3f8c7623048c9c063d532cc95c5ed7a898a64f', 'admin@ikimukti.com', 'Administrator', NULL, NULL, NULL, NULL, 1, '2023-12-11 09:04:41', '2023-12-11 16:04:41', NULL, 'default.png', 'active'),
(1699007959, 'aziz', '$2y$10$Gne2UkY5RR3C6zSTCuQW9uRyWRqPpiHZ8DLyXbHT0PxYurpGDOQkm', 'wahyuadi@gmail.com', 'Muhammad As’adul Azis Wahyuadi ', '2023-11-22', 'Male', 'Tuban', '+62 896-8736-8865', 2, '2024-01-18 00:38:34', NULL, 'active', NULL, 'active'),
(1699008117, 'tika', '$2y$10$XMghwn955y4Vr4J7bc5pju78Dx6.ikMeerd49FnFCvPPKQB1axXtS', 'tik@gmail.com', 'Febriana Mahabatika', '2023-11-16', 'Female', 'Kediri', '+62 858-9307-5772', 2, '2024-01-18 00:39:12', NULL, 'active', NULL, 'active'),
(1699008390, 'renal', '$2y$10$MPgdqVlT.pfxusRT.4xFbuwHE2YHCHPKEmyl/aiDeqXAcxr2fbV7y', 'renal@gmail.com', 'Renaldi Hariski Firdaus', '2023-11-17', 'Male', 'Nganjuk', '+62 813-5717-7521', 2, '2024-01-18 00:39:50', NULL, 'active', NULL, 'active'),
(1699008906, 'agdag', '$2y$10$qiodW.6G2b42N5akckDfr.PQqNFr6m/JEWZ2AoOhPonAUIEXqhvwG', 'nefira@gmail.com', 'Nefira Anastasya', '2023-11-08', 'Female', 'Sidoarjo', '+62 895-3672-40319', 2, '2024-01-18 00:40:31', NULL, 'active', NULL, 'active'),
(1699008959, 'nanda', '$2y$10$S.Lr0XU71eYd93gnHJYFkuNvwDlySLWMAa1kCobICvsOJUinbFDeq', 'nanda@gmail.com', 'Nanda Ajeng Listia', '0000-00-00', 'Male', 'Surabaya', '24534563546457', 2, '2024-01-18 00:41:32', NULL, 'Active', NULL, 'active'),
(1700619157, 'mukti', '$2y$10$OnMJVb7WWNtzU7StPb6dtupCd.VyrFWoWPCmP9wmCqgkbr.eNEpha', 'iki.mukti@gmail.com', 'Firmansyah Mukti Wijaya', NULL, 'Male', 'Nglaban', '081216318022', 3, '2023-11-22 02:12:45', NULL, 'Active', NULL, 'active'),
(1705540077, 'abi_satria', 'hashed_password', 'abi.satria@example.com', 'Abi Satria', '2005-01-01', 'L', 'Kediri', '08123456789', 3, '2024-01-18 01:07:57', NULL, 'Active', 'default.png', 'Activated'),
(1705540078, 'aditya_prasetyo', 'hashed_password', 'aditya.prasetyo@example.com', 'Aditya Bagas Prasetyo', '2004-02-15', 'L', 'Kediri', '08123456788', 3, '2024-01-18 01:07:57', NULL, 'Active', 'default.png', 'Activated'),
(1705540079, 'ahmad_maulana', 'hashed_password', 'ahmad.maulana@example.com', 'Ahmad Hasby Maulana', '2003-11-20', 'L', 'Kediri', '08123456787', 3, '2024-01-18 01:07:57', NULL, 'Active', 'default.png', 'Activated'),
(1705540080, 'akbar_pratama', 'hashed_password', 'akbar.pratama@example.com', 'Akbar Sandi Pratama', '2003-10-10', 'L', 'Kediri', '08123456786', 3, '2024-01-18 01:07:57', NULL, 'Active', 'default.png', 'Activated'),
(1705540081, 'andrean_prasetyo', 'hashed_password', 'andrean.prasetyo@example.com', 'Andrean Prasetyo', '2004-08-05', 'L', 'Kediri', '08123456785', 3, '2024-01-18 01:07:57', NULL, 'Active', 'default.png', 'Activated'),
(1705540082, 'bintang_krisna', 'hashed_password', 'bintang.krisna@example.com', 'Bintang Krisna', '2003-12-18', 'L', 'Kediri', '08123456784', 3, '2024-01-18 01:07:57', NULL, 'Active', 'default.png', 'Activated'),
(1705540186, 'cantika_sari', 'hashed_password', 'cantika.sari@example.com', 'Cantika Christin Novita Sari', '2003-05-20', 'P', 'Kediri', '08123456783', 3, '2024-01-18 01:09:40', NULL, 'Active', 'default.png', 'Activated'),
(1705540187, 'fairus_muzaki', 'hashed_password', 'fairus.muzaki@example.com', 'Fairus Ali Muzaki', '2004-07-12', 'L', 'Kediri', '08123456782', 3, '2024-01-18 01:09:40', NULL, 'Active', 'default.png', 'Activated'),
(1705540188, 'fajar_sahputra', 'hashed_password', 'fajar.sahputra@example.com', 'Fajar Imam Sahputra', '2003-09-15', 'L', 'Kediri', '08123456781', 3, '2024-01-18 01:09:40', NULL, 'Active', 'default.png', 'Activated'),
(1705540189, 'farrel_valea', 'hashed_password', 'farrel.valea@example.com', 'Farrel Tiffany Valea', '2004-01-30', 'L', 'Kediri', '08123456780', 3, '2024-01-18 01:09:40', NULL, 'Active', 'default.png', 'Activated'),
(1705540190, 'fayruz_suherman', 'hashed_password', 'fayruz.suherman@example.com', 'Fayruz Zakwan Suherman', '2003-03-25', 'L', 'Kediri', '08123456779', 3, '2024-01-18 01:09:40', NULL, 'Active', 'default.png', 'Activated'),
(1705540191, 'firjatullah_aptanta', 'hashed_password', 'firjatullah.aptanta@example.com', 'Firjatullah Wira Aptanta', '2004-06-10', 'L', 'Kediri', '08123456778', 3, '2024-01-18 01:09:40', NULL, 'Active', 'default.png', 'Activated'),
(1705540192, 'geo_pratama', 'hashed_password', 'geo.pratama@example.com', 'Geo Fany Putra Pratama', '2003-08-15', 'L', 'Kediri', '08123456777', 3, '2024-01-18 01:09:40', NULL, 'Active', 'default.png', 'Activated'),
(1705540193, 'ika_listyani', 'hashed_password', 'ika.listyani@example.com', 'Ika Dhealisna Listyani', '2004-02-20', 'P', 'Kediri', '08123456776', 3, '2024-01-18 01:09:40', NULL, 'Active', 'default.png', 'Activated'),
(1705540194, 'keytaro', 'hashed_password', 'keytaro@example.com', 'Keytaro', NULL, NULL, 'Kediri', NULL, 3, '2024-01-18 01:09:40', NULL, 'Active', 'default.png', 'Activated'),
(1705540421, 'faizal_fanani', 'hashed_password', 'faizal.fanani@example.com', 'Mochamad Fa\'izal Fanani', '2003-04-10', 'L', 'Kediri', '08123456775', 3, '2024-01-18 01:13:26', NULL, 'Active', 'default.png', 'Activated'),
(1705540422, 'nafi_abdulloh', 'hashed_password', 'nafi.abdulloh@example.com', 'Mochamad Nafi\' Abdulloh', '2003-08-25', 'L', 'Kediri', '08123456774', 3, '2024-01-18 01:13:26', NULL, 'Active', 'default.png', 'Activated'),
(1705540423, 'ilham_maulana', 'hashed_password', 'ilham.maulana@example.com', 'Mochammad Ilham Maulana', '2004-02-05', 'L', 'Kediri', '08123456773', 3, '2024-01-18 01:13:26', NULL, 'Active', 'default.png', 'Activated'),
(1705540424, 'farhan_habibi', 'hashed_password', 'farhan.habibi@example.com', 'Muhamad Farhan Habibi', '2003-12-20', 'L', 'Kediri', '08123456772', 3, '2024-01-18 01:13:26', NULL, 'Active', 'default.png', 'Activated'),
(1705540425, 'najahul_wafa', 'hashed_password', 'najahul.wafa@example.com', 'Muhammad Najahul Wafa', '2003-10-15', 'L', 'Kediri', '08123456771', 3, '2024-01-18 01:13:26', NULL, 'Active', 'default.png', 'Activated'),
(1705540426, 'najdan_herwin', 'hashed_password', 'najdan.herwin@example.com', 'Najdan Farizal Herwin', '2004-06-30', 'L', 'Kediri', '08123456770', 3, '2024-01-18 01:13:26', NULL, 'Active', 'default.png', 'Activated'),
(1705540427, 'okta_rahmadhani', 'hashed_password', 'okta.rahmadhani@example.com', 'Okta Nia Rahmadhani', NULL, 'P', 'Kediri', NULL, 3, '2024-01-18 01:13:26', NULL, 'Active', 'default.png', 'Activated'),
(1705540428, 'poppy_zousa', 'hashed_password', 'poppy.zousa@example.com', 'Poppy Dean Zousa', NULL, 'P', 'Kediri', NULL, 3, '2024-01-18 01:13:26', NULL, 'Active', 'default.png', 'Activated'),
(1705540480, 'rama_wijaya', 'hashed_password', 'rama.wijaya@example.com', 'Rama Wijaya', '2003-06-12', 'L', 'Kediri', '08123456769', 3, '2024-01-18 01:14:17', NULL, 'Active', 'default.png', 'Activated'),
(1705540481, 'reval_pratama', 'hashed_password', 'reval.pratama@example.com', 'Reval Isya Rasya Pratama', '2004-04-25', 'L', 'Kediri', '08123456768', 3, '2024-01-18 01:14:17', NULL, 'Active', 'default.png', 'Activated'),
(1705540482, 'rifky_saputra', 'hashed_password', 'rifky.saputra@example.com', 'Rifky Prima Adi Saputra', '2003-09-08', 'L', 'Kediri', '08123456767', 3, '2024-01-18 01:14:17', NULL, 'Active', 'default.png', 'Activated'),
(1705540483, 'septhia_egaputri', 'hashed_password', 'septhia.egaputri@example.com', 'Sephia Salfa Egaputri', NULL, 'P', 'Kediri', NULL, 3, '2024-01-18 01:14:17', NULL, 'Active', 'default.png', 'Activated'),
(1705540484, 'tita_agatha', 'hashed_password', 'tita.agatha@example.com', 'Tita Julia Agatha', NULL, 'P', 'Kediri', NULL, 3, '2024-01-18 01:14:17', NULL, 'Active', 'default.png', 'Activated'),
(1705540485, 'wahyu_cahyo', 'hashed_password', 'wahyu.cahyo@example.com', 'Wahyu Dwi Cahyo', '2004-02-10', 'L', 'Kediri', '08123456766', 3, '2024-01-18 01:14:17', NULL, 'Active', 'default.png', 'Activated'),
(1705540486, 'wildan_putra', 'hashed_password', 'wildan.putra@example.com', 'Wildan Rangga Adi Putra', '2003-11-18', 'L', 'Kediri', '08123456765', 3, '2024-01-18 01:14:17', NULL, 'Active', 'default.png', 'Activated'),
(1705540757, 'afrizal_braga', 'hashed_password', 'afrizal.braga@example.com', 'Afrizal Varellino Braga', '2003-07-15', 'L', 'Kediri', '08123456764', 3, '2024-01-18 01:18:46', NULL, 'Active', 'default.png', 'Activated'),
(1705540758, 'ahsan_widodo', 'hashed_password', 'ahsan.widodo@example.com', 'Ahsan Rizaqu Widodo', '2003-11-30', 'L', 'Kediri', '08123456763', 3, '2024-01-18 01:18:46', NULL, 'Active', 'default.png', 'Activated'),
(1705540759, 'alip_shidhiq', 'hashed_password', 'alip.shidhiq@example.com', 'Alip Ash Shidhiq', NULL, 'L', 'Kediri', NULL, 3, '2024-01-18 01:18:46', NULL, 'Active', 'default.png', 'Activated'),
(1705540760, 'ariel_susanto', 'hashed_password', 'ariel.susanto@example.com', 'Ariel Ferdiansyah Susanto', '2004-04-10', 'L', 'Kediri', '08123456762', 3, '2024-01-18 01:18:46', NULL, 'Active', 'default.png', 'Activated'),
(1705540761, 'bagas_andrianto', 'hashed_password', 'bagas.andrianto@example.com', 'Bagas Andrianto', '2003-09-25', 'L', 'Kediri', '08123456761', 3, '2024-01-18 01:18:46', NULL, 'Active', 'default.png', 'Activated'),
(1705540762, 'bima_zuana', 'hashed_password', 'bima.zuana@example.com', 'Bima Ady Zuana', '2004-02-05', 'L', 'Kediri', '08123456760', 3, '2024-01-18 01:18:46', NULL, 'Active', 'default.png', 'Activated'),
(1705540831, 'clarintha_novianto', 'hashed_password', 'clarintha.novianto@example.com', 'Clarintha Chelsy Novianto', NULL, 'P', 'Kediri', NULL, 3, '2024-01-18 01:19:54', NULL, 'Active', 'default.png', 'Activated'),
(1705540832, 'devin_fauzan', 'hashed_password', 'devin.fauzan@example.com', 'Devin Nur Fauzan', '2003-10-10', 'L', 'Kediri', '08123456759', 3, '2024-01-18 01:19:54', NULL, 'Active', 'default.png', 'Activated'),
(1705540833, 'dino_laksana', 'hashed_password', 'dino.laksana@example.com', 'Dino Tri Laksana P.', '2004-03-20', 'L', 'Kediri', '08123456758', 3, '2024-01-18 01:19:54', NULL, 'Active', 'default.png', 'Activated'),
(1705540834, 'fathir_ardandy', 'hashed_password', 'fathir.ardandy@example.com', 'Fathir Akbar Ardandy', '2003-12-05', 'L', 'Kediri', '08123456757', 3, '2024-01-18 01:19:54', NULL, 'Active', 'default.png', 'Activated'),
(1705540835, 'fauzan_zufar', 'hashed_password', 'fauzan.zufar@example.com', 'Fauzan Alwafi Zufar', '2003-08-15', 'L', 'Kediri', '08123456756', 3, '2024-01-18 01:19:54', NULL, 'Active', 'default.png', 'Activated'),
(1705540836, 'geofani_pratama', 'hashed_password', 'geofani.pratama@example.com', 'Geofani Wahyu Nur Pratama', NULL, 'L', 'Kediri', NULL, 3, '2024-01-18 01:19:54', NULL, 'Active', 'default.png', 'Activated'),
(1705540837, 'hendra_pratama', 'hashed_password', 'hendra.pratama@example.com', 'Hendra Aditya Pratama', '2004-02-28', 'L', 'Kediri', '08123456755', 3, '2024-01-18 01:19:54', NULL, 'Active', 'default.png', 'Activated'),
(1705540838, 'izza_azizah', 'hashed_password', 'izza.azizah@example.com', 'Izza Nur Azizah', NULL, 'P', 'Kediri', NULL, 3, '2024-01-18 01:19:54', NULL, 'Active', 'default.png', 'Activated'),
(1705540839, 'james_susanto', 'hashed_password', 'james.susanto@example.com', 'James Martyno Susanto', NULL, 'L', 'Kediri', NULL, 3, '2024-01-18 01:19:54', NULL, 'Active', 'default.png', 'Activated'),
(1705540898, 'fareed_alfarid', 'hashed_password', 'fareed.alfarid@example.com', 'Moch. Fareed Azka Al-Farid', '2003-09-12', 'L', 'Kediri', '08123456754', 3, '2024-01-18 01:20:52', NULL, 'Active', 'default.png', 'Activated'),
(1705540899, 'dharma_razha', 'hashed_password', 'dharma.razha@example.com', 'Mochammad Dharma Razha Saputra', NULL, 'L', 'Kediri', NULL, 3, '2024-01-18 01:20:52', NULL, 'Active', 'default.png', 'Activated'),
(1705540900, 'dwi_afandi', 'hashed_password', 'dwi.afandi@example.com', 'Moh. Dwi Afandi', NULL, 'L', 'Kediri', NULL, 3, '2024-01-18 01:20:52', NULL, 'Active', 'default.png', 'Activated'),
(1705540901, 'fikri_fahrezi', 'hashed_password', 'fikri.fahrezi@example.com', 'Muhamad Fikri Al-Fahrezi', NULL, 'L', 'Kediri', NULL, 3, '2024-01-18 01:20:52', NULL, 'Active', 'default.png', 'Activated'),
(1705540902, 'nizar_nasruddin', 'hashed_password', 'nizar.nasruddin@example.com', 'Muhamad Nizar Nasruddin', NULL, 'L', 'Kediri', NULL, 3, '2024-01-18 01:20:52', NULL, 'Active', 'default.png', 'Activated'),
(1705540903, 'nufail_fawwaz', 'hashed_password', 'nufail.fawwaz@example.com', 'Muhamad Nufail Fawwaz', NULL, 'L', 'Kediri', NULL, 3, '2024-01-18 01:20:52', NULL, 'Active', 'default.png', 'Activated'),
(1705540904, 'rafi_kafka', 'hashed_password', 'rafi.kafka@example.com', 'Muhamad Rafi Kafka', NULL, 'L', 'Kediri', NULL, 3, '2024-01-18 01:20:52', NULL, 'Active', 'default.png', 'Activated'),
(1705540905, 'surya_jamaluddin', 'hashed_password', 'surya.jamaluddin@example.com', 'Muhammad Surya Jamaluddin', NULL, 'L', 'Kediri', NULL, 3, '2024-01-18 01:20:52', NULL, 'Active', 'default.png', 'Activated'),
(1705541001, 'azzhahra_deviani', 'hashed_password', 'azzhahra.deviani@example.com', 'Nur Azzhahra Deviani', NULL, 'P', 'Kediri', NULL, 3, '2024-01-18 01:22:27', NULL, 'Active', 'default.png', 'Activated'),
(1705541002, 'podang_putri', 'hashed_password', 'podang.putri@example.com', 'Podang Telasih Putri Suwarno', NULL, 'P', 'Kediri', NULL, 3, '2024-01-18 01:22:27', NULL, 'Active', 'default.png', 'Activated'),
(1705541003, 'wildan_muchlisin', 'hashed_password', 'wildan.muchlisin@example.com', 'Rohmad Wildan Salas Muchlisin', NULL, 'L', 'Kediri', NULL, 3, '2024-01-18 01:22:27', NULL, 'Active', 'default.png', 'Activated'),
(1705541004, 'shendy_nugraha', 'hashed_password', 'shendy.nugraha@example.com', 'Shendy Pratama Nugraha', NULL, 'L', 'Kediri', NULL, 3, '2024-01-18 01:22:27', NULL, 'Active', 'default.png', 'Activated'),
(1705541005, 'siti_aisyah', 'hashed_password', 'siti.aisyah@example.com', 'Siti Nur\'aisyah', NULL, 'P', 'Kediri', NULL, 3, '2024-01-18 01:22:27', NULL, 'Active', 'default.png', 'Activated'),
(1705541006, 'varel_firmansyah', 'hashed_password', 'varel.firmansyah@example.com', 'Varel Putra Firmansyah', NULL, 'L', 'Kediri', NULL, 3, '2024-01-18 01:22:27', NULL, 'Active', 'default.png', 'Activated'),
(1705541007, 'vindi_hapsari', 'hashed_password', 'vindi.hapsari@example.com', 'Vindi Karunia Hapsari', NULL, 'P', 'Kediri', NULL, 3, '2024-01-18 01:22:27', NULL, 'Active', 'default.png', 'Activated');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Answers`
--
ALTER TABLE `Answers`
  ADD PRIMARY KEY (`AnswerID`),
  ADD KEY `QuestionID` (`QuestionID`);

--
-- Indexes for table `AssignmentAttachments`
--
ALTER TABLE `AssignmentAttachments`
  ADD PRIMARY KEY (`AttachmentID`),
  ADD KEY `FK_Attachment_Assignment` (`AssignmentID`);

--
-- Indexes for table `Assignments`
--
ALTER TABLE `Assignments`
  ADD PRIMARY KEY (`AssignmentID`),
  ADD KEY `FK_Assignment_Subject` (`SubjectID`),
  ADD KEY `FK_Assignment_Material` (`MaterialID`);

--
-- Indexes for table `AssignmentSubmissions`
--
ALTER TABLE `AssignmentSubmissions`
  ADD PRIMARY KEY (`SubmissionID`),
  ADD KEY `FK_Submission_Student` (`StudentID`),
  ADD KEY `FK_Submission_Assignment` (`AssignmentID`);

--
-- Indexes for table `Classes`
--
ALTER TABLE `Classes`
  ADD PRIMARY KEY (`ClassID`);

--
-- Indexes for table `ClassSubjects`
--
ALTER TABLE `ClassSubjects`
  ADD PRIMARY KEY (`ClassSubjectID`),
  ADD KEY `ClassID` (`ClassID`),
  ADD KEY `SubjectID` (`SubjectID`);

--
-- Indexes for table `LogActivity`
--
ALTER TABLE `LogActivity`
  ADD PRIMARY KEY (`LogID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `Materials`
--
ALTER TABLE `Materials`
  ADD PRIMARY KEY (`MaterialID`),
  ADD KEY `FK_Material_Subject` (`SubjectID`);

--
-- Indexes for table `Questions`
--
ALTER TABLE `Questions`
  ADD PRIMARY KEY (`QuestionID`),
  ADD KEY `TestID` (`TestID`);

--
-- Indexes for table `Role`
--
ALTER TABLE `Role`
  ADD PRIMARY KEY (`RoleID`),
  ADD UNIQUE KEY `RoleName` (`RoleName`);

--
-- Indexes for table `StudentResponses`
--
ALTER TABLE `StudentResponses`
  ADD PRIMARY KEY (`ResponseID`),
  ADD KEY `StudentID` (`StudentID`),
  ADD KEY `TestID` (`TestID`),
  ADD KEY `QuestionID` (`QuestionID`),
  ADD KEY `AnswerID` (`AnswerID`);

--
-- Indexes for table `Students`
--
ALTER TABLE `Students`
  ADD PRIMARY KEY (`StudentID`),
  ADD KEY `ClassID` (`ClassID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `Subjects`
--
ALTER TABLE `Subjects`
  ADD PRIMARY KEY (`SubjectID`),
  ADD KEY `TeacherID` (`TeacherID`);

--
-- Indexes for table `Teachers`
--
ALTER TABLE `Teachers`
  ADD PRIMARY KEY (`TeacherID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `TestResults`
--
ALTER TABLE `TestResults`
  ADD PRIMARY KEY (`ResultID`),
  ADD KEY `StudentID` (`StudentID`),
  ADD KEY `TestID` (`TestID`);

--
-- Indexes for table `Tests`
--
ALTER TABLE `Tests`
  ADD PRIMARY KEY (`TestID`),
  ADD KEY `MaterialID` (`MaterialID`),
  ADD KEY `SubjectID` (`SubjectID`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `users_ibfk_1` (`RoleID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Answers`
--
ALTER TABLE `Answers`
  MODIFY `AnswerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=244;

--
-- AUTO_INCREMENT for table `AssignmentAttachments`
--
ALTER TABLE `AssignmentAttachments`
  MODIFY `AttachmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `Assignments`
--
ALTER TABLE `Assignments`
  MODIFY `AssignmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `AssignmentSubmissions`
--
ALTER TABLE `AssignmentSubmissions`
  MODIFY `SubmissionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `Classes`
--
ALTER TABLE `Classes`
  MODIFY `ClassID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ClassSubjects`
--
ALTER TABLE `ClassSubjects`
  MODIFY `ClassSubjectID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `LogActivity`
--
ALTER TABLE `LogActivity`
  MODIFY `LogID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=345;

--
-- AUTO_INCREMENT for table `Materials`
--
ALTER TABLE `Materials`
  MODIFY `MaterialID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `Questions`
--
ALTER TABLE `Questions`
  MODIFY `QuestionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `StudentResponses`
--
ALTER TABLE `StudentResponses`
  MODIFY `ResponseID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=234;

--
-- AUTO_INCREMENT for table `Students`
--
ALTER TABLE `Students`
  MODIFY `StudentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `Subjects`
--
ALTER TABLE `Subjects`
  MODIFY `SubjectID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `Teachers`
--
ALTER TABLE `Teachers`
  MODIFY `TeacherID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `TestResults`
--
ALTER TABLE `TestResults`
  MODIFY `ResultID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `Tests`
--
ALTER TABLE `Tests`
  MODIFY `TestID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Answers`
--
ALTER TABLE `Answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`QuestionID`) REFERENCES `questions` (`QuestionID`);

--
-- Constraints for table `AssignmentAttachments`
--
ALTER TABLE `AssignmentAttachments`
  ADD CONSTRAINT `FK_Attachment_Assignment` FOREIGN KEY (`AssignmentID`) REFERENCES `Assignments` (`AssignmentID`);

--
-- Constraints for table `Assignments`
--
ALTER TABLE `Assignments`
  ADD CONSTRAINT `FK_Assignment_Material` FOREIGN KEY (`MaterialID`) REFERENCES `Materials` (`MaterialID`),
  ADD CONSTRAINT `FK_Assignment_Subject` FOREIGN KEY (`SubjectID`) REFERENCES `Subjects` (`SubjectID`);

--
-- Constraints for table `AssignmentSubmissions`
--
ALTER TABLE `AssignmentSubmissions`
  ADD CONSTRAINT `FK_Submission_Assignment` FOREIGN KEY (`AssignmentID`) REFERENCES `Assignments` (`AssignmentID`),
  ADD CONSTRAINT `FK_Submission_Student` FOREIGN KEY (`StudentID`) REFERENCES `Students` (`StudentID`) ON DELETE CASCADE;

--
-- Constraints for table `ClassSubjects`
--
ALTER TABLE `ClassSubjects`
  ADD CONSTRAINT `classsubjects_ibfk_1` FOREIGN KEY (`ClassID`) REFERENCES `classes` (`ClassID`),
  ADD CONSTRAINT `classsubjects_ibfk_2` FOREIGN KEY (`SubjectID`) REFERENCES `subjects` (`SubjectID`);

--
-- Constraints for table `Materials`
--
ALTER TABLE `Materials`
  ADD CONSTRAINT `FK_Material_Subject` FOREIGN KEY (`SubjectID`) REFERENCES `subjects` (`SubjectID`);

--
-- Constraints for table `Questions`
--
ALTER TABLE `Questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`TestID`) REFERENCES `tests` (`TestID`);

--
-- Constraints for table `Students`
--
ALTER TABLE `Students`
  ADD CONSTRAINT `students_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`),
  ADD CONSTRAINT `students_ibfk_3` FOREIGN KEY (`ClassID`) REFERENCES `classes` (`ClassID`);

--
-- Constraints for table `Subjects`
--
ALTER TABLE `Subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`TeacherID`) REFERENCES `teachers` (`TeacherID`);

--
-- Constraints for table `Teachers`
--
ALTER TABLE `Teachers`
  ADD CONSTRAINT `teachers_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`);

--
-- Constraints for table `Tests`
--
ALTER TABLE `Tests`
  ADD CONSTRAINT `tests_ibfk_1` FOREIGN KEY (`MaterialID`) REFERENCES `materials` (`MaterialID`),
  ADD CONSTRAINT `tests_ibfk_2` FOREIGN KEY (`SubjectID`) REFERENCES `subjects` (`SubjectID`);

--
-- Constraints for table `Users`
--
ALTER TABLE `Users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`RoleID`) REFERENCES `role` (`RoleID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
