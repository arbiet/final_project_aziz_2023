-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 07, 2023 at 12:15 AM
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
(3, 'XI TKJ 2 - (Teknik Komputer Jaringan)', 'SMK', '3', 'Kurikulum Merdeka', '2023', NULL);

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
(14, 3, 2);

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
(105, 137648118, 'User logged in', '2023-11-06 19:37:53');

-- --------------------------------------------------------

--
-- Table structure for table `Material`
--

CREATE TABLE `Material` (
  `MaterialID` int(11) NOT NULL,
  `SubjectID` int(11) DEFAULT NULL,
  `TitleMaterial` varchar(255) DEFAULT NULL,
  `Type` varchar(50) DEFAULT NULL,
  `Content` longtext DEFAULT NULL,
  `Link` varchar(255) DEFAULT NULL,
  `Sequence` int(11) DEFAULT NULL
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
(1, '123523542', 'asdasf', 'asdgasdg', 'asdgasg', 'asgasg', 'asgasg@gmail.com', 1, 2147483647),
(2, '235235234', 'Agnostik', 'Paijo', 'Paijo', 'Paijo', 'paijo@gmail.com', 1, 65405),
(4, '542436546', 'Agnostik', 'Kartu Prakerja - Firmansyah Mukti Wijaya', 'Jl. Ahmad Dahlan No.76, Mojoroto, Kec. Mojoroto, Kota Kediri, Jawa Timur 64112', '+6281216318022', 'iki.mukti@gmail.com', 3, 1698716970),
(5, '8345738488', 'Agnostik', 'Paijo', 'Paijo', 'Paijo', 'paijo@gmail.com', 2, 0),
(6, '13466174142', 'fsdgsfdh', 'dfgsdg', 'sdgsdgsd', 'sdgsdgsfg', 'dsfhsdufh@jbsfbdfb.s', 3, 0);

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
  `StudentEngagement` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Subjects`
--

INSERT INTO `Subjects` (`SubjectID`, `SubjectName`, `DifficultyLevel`, `TeachingMethod`, `LearningObjective`, `DurationHours`, `CurriculumFramework`, `AssessmentMethod`, `StudentEngagement`) VALUES
(1, 'Jaringan Dasar', 'Fase E', 'Pembelajaran Daring', 'Pada akhir mata pelajaran Jaringan Dasar, siswa diharapkan mampu memahami prinsip dasar jaringan komputer, termasuk konsep dasar tentang protokol, alamat IP, topologi jaringan, dan perangkat jaringan. Siswa akan dapat merancang, mengonfigurasi, dan mengelola jaringan kecil hingga menengah. Mereka juga akan memahami keamanan jaringan dasar dan protokol yang digunakan untuk melindungi jaringan. Selain itu, siswa akan dapat menjelaskan aplikasi jaringan yang umumnya digunakan dalam konteks bisnis dan mengidentifikasi tantangan dan peluang yang terkait dengan jaringan komputer dalam dunia nyata.', 40, 'Kurikulum Merdeka', 'Ujian Tertulis, Ujian Praktek, Ujian Lisan', 'Diskusi Kelompok'),
(2, 'Sistem Operasi', 'Fase E', 'Pembelajaran daring', 'Pada akhir mata pelajaran Sistem Operasi, siswa diharapkan mampu memahami konsep dasar tentang sistem operasi, manajemen sumber daya, dan interaksi antara perangkat keras dan perangkat lunak dalam komputer. Mereka akan dapat menginstal, mengkonfigurasi, dan mengelola sistem operasi komputer, serta menyelesaikan masalah yang berkaitan dengan sistem operasi. Siswa juga akan memahami pentingnya keamanan sistem operasi dan prinsip-prinsip manajemen hak akses.', 50, 'Kurikulum Merdeka', 'Ujian Tertulis, Proyek Praktikum', 'Diskusi kelompok, Praktikum'),
(3, 'Pemrograman Lanjut', 'Fase F', 'Pembelajaran tatap muka', 'Pada akhir mata pelajaran Pemrograman Lanjut, siswa diharapkan mampu menguasai konsep dan teknik pemrograman yang lebih kompleks. Mereka akan dapat merancang dan mengembangkan perangkat lunak yang rumit, menggunakan bahasa pemrograman yang beragam seperti Java, Python, dan C++. Siswa akan memahami konsep berorientasi objek, pemrograman berbasis peristiwa, serta manajemen memori. Selain itu, mereka akan dapat menerapkan praktik-praktik pengujian dan pemecahan masalah yang efektif dalam pengembangan perangkat lunak.', 60, 'Kurikulum Merdeka', ' Proyek pengembangan perangkat lunak, Ujian praktikum', 'Diskusi kelompok, Proyek tim');

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
(1, '24124123515', 'S.Kom', 'Bachelor Degree', 'Active', 1699007959),
(2, '14125323523', 'S.Pd', 'Bachelor Degree', 'Active', 1699008117),
(3, '34235235', 'S. Kom', 'asgasg', 'Active', 1699008390),
(4, '23582358253', 'S.T.', 'Banchelor Degree', 'active', 1699008906);

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
(65405, 'ahmadhasby', '$2y$10$cDLCzK6Rq0IISeN.nI46pOs3PzPgmREX0rbhPlvtPg7iPuw2sRGNK', 'ahmadhasby@gmail.com', 'Ahmad Hasby Maulana', '2023-10-10', 'Male', 'DSN NGLABAN, RT 003 RW 003, MARON, BANYAKAN, KAB. KEDIRI', '+6281216318022', 3, '2023-10-31 02:17:15', NULL, NULL, NULL, 'active'),
(137648118, 'admin', 'ef797c8118f02dfb649607dd5d3f8c7623048c9c063d532cc95c5ed7a898a64f', 'admin@ikimukti.com', 'Administrator', NULL, NULL, NULL, NULL, 1, '2023-11-06 19:37:53', '2023-11-07 02:37:53', NULL, 'default.png', 'active'),
(1698716970, 'akbarsandi', '$2y$10$GzsUjuYCcfymGzNusQgul.fUn42ETSFy71ECQpYe8NTVRi1z45SoS', 'akbarsandi@gmail.com', 'Akbar Sandi Pratama', '2023-10-12', 'Male', 'DSN NGLABAN, RT 003 RW 003, MARON, BANYAKAN, KAB. KEDIRI', '+6281216318022', 3, '2023-10-31 02:17:23', NULL, NULL, NULL, 'active'),
(1698719401, 'andrean', '$2y$10$hELFb0BIW5L8uwyVqMLmd.hG7L2avzq/dojKCui.XW1XJOffghcma', 'andreanprasetyo@gmail.com', 'Andrean Prasetyo', '2023-02-07', 'Male', 'DSN NGLABAN, RT 003 RW 003, MARON, BANYAKAN, KAB. KEDIRI', '+6281216318022', 3, '2023-10-31 02:30:01', NULL, 'active', NULL, 'active'),
(1699006643, 'paijo', '$2y$10$sYyoVnssegJ91BQO8RC6qOFr3XWgUAvNFKcI/WPO8s63Yi8KmGIMu', 'sdhgushg@hfugihdf.d', 'isdhgouhsduog', '2023-11-22', 'Male', 'agdsgdfghdfhg', 'dgsdfhgdf', 3, '2023-11-03 10:17:23', NULL, 'active', NULL, 'active'),
(1699007959, 'aziz', '$2y$10$Gne2UkY5RR3C6zSTCuQW9uRyWRqPpiHZ8DLyXbHT0PxYurpGDOQkm', 'azizasadul@gmail.com', 'Asadul Azis', '2023-11-22', 'Male', 'agadhsfhdgh', '34236346457', 2, '2023-11-03 11:20:03', NULL, 'active', NULL, 'active'),
(1699008117, 'tika', '$2y$10$XMghwn955y4Vr4J7bc5pju78Dx6.ikMeerd49FnFCvPPKQB1axXtS', 'tik@gfljsdghs.asfsdg', 'Tika', '2023-11-16', 'Female', 'agdsgsfd', '2134124125', 2, '2023-11-03 10:54:16', NULL, 'active', NULL, 'active'),
(1699008390, 'renal', '$2y$10$MPgdqVlT.pfxusRT.4xFbuwHE2YHCHPKEmyl/aiDeqXAcxr2fbV7y', 'asfasgag@afasfa', 'Renal', '2023-11-17', 'Male', 'asgadads', '12481285712', 2, '2023-11-03 10:54:11', NULL, 'active', NULL, 'active'),
(1699008906, 'agdag', '$2y$10$qiodW.6G2b42N5akckDfr.PQqNFr6m/JEWZ2AoOhPonAUIEXqhvwG', 'aoghouah@ojfouadhsd', 'Nefira', '2023-11-08', 'Female', 'asgagasg', '3532456346', 2, '2023-11-03 10:55:25', NULL, 'active', NULL, 'active'),
(1699008959, 'nanda', '$2y$10$S.Lr0XU71eYd93gnHJYFkuNvwDlySLWMAa1kCobICvsOJUinbFDeq', 'sedhgisdfghi@olfosdhf', 'Nanda', NULL, 'Male', 'sdhgedfhdryxj', '24534563546457', 2, '2023-11-03 11:01:59', NULL, 'Active', NULL, 'active'),
(2147483647, 'abisatria1', '$2y$10$lHoNtWimVtfPR7WomlzRx.KN4P08K1LhlUHWgF4L.xz0ziNjqGyOS', 'abisatria@gmail.com', 'Abi Satria', '2023-10-27', 'Male', 'DSN NGLABAN, RT 003 RW 003, MARON, BANYAKAN, KAB. KEDIRI', '+6281216318022', 3, '2023-10-31 03:02:52', NULL, 'Active', NULL, 'active');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `Material`
--
ALTER TABLE `Material`
  ADD PRIMARY KEY (`MaterialID`),
  ADD KEY `FK_Material_Subject` (`SubjectID`);

--
-- Indexes for table `Role`
--
ALTER TABLE `Role`
  ADD PRIMARY KEY (`RoleID`),
  ADD UNIQUE KEY `RoleName` (`RoleName`);

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
  ADD PRIMARY KEY (`SubjectID`);

--
-- Indexes for table `Teachers`
--
ALTER TABLE `Teachers`
  ADD PRIMARY KEY (`TeacherID`),
  ADD KEY `UserID` (`UserID`);

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
-- AUTO_INCREMENT for table `Classes`
--
ALTER TABLE `Classes`
  MODIFY `ClassID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ClassSubjects`
--
ALTER TABLE `ClassSubjects`
  MODIFY `ClassSubjectID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `LogActivity`
--
ALTER TABLE `LogActivity`
  MODIFY `LogID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `Material`
--
ALTER TABLE `Material`
  MODIFY `MaterialID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Students`
--
ALTER TABLE `Students`
  MODIFY `StudentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `Subjects`
--
ALTER TABLE `Subjects`
  MODIFY `SubjectID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `Teachers`
--
ALTER TABLE `Teachers`
  MODIFY `TeacherID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ClassSubjects`
--
ALTER TABLE `ClassSubjects`
  ADD CONSTRAINT `classsubjects_ibfk_1` FOREIGN KEY (`ClassID`) REFERENCES `Classes` (`ClassID`),
  ADD CONSTRAINT `classsubjects_ibfk_2` FOREIGN KEY (`SubjectID`) REFERENCES `Subjects` (`SubjectID`);

--
-- Constraints for table `LogActivity`
--
ALTER TABLE `LogActivity`
  ADD CONSTRAINT `LogActivity_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`);

--
-- Constraints for table `Material`
--
ALTER TABLE `Material`
  ADD CONSTRAINT `FK_Material_Subject` FOREIGN KEY (`SubjectID`) REFERENCES `subjects` (`SubjectID`);

--
-- Constraints for table `Students`
--
ALTER TABLE `Students`
  ADD CONSTRAINT `students_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`),
  ADD CONSTRAINT `students_ibfk_3` FOREIGN KEY (`ClassID`) REFERENCES `classes` (`ClassID`);

--
-- Constraints for table `Teachers`
--
ALTER TABLE `Teachers`
  ADD CONSTRAINT `teachers_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `Users` (`UserID`);

--
-- Constraints for table `Users`
--
ALTER TABLE `Users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`RoleID`) REFERENCES `role` (`RoleID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
