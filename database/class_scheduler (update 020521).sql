-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 05, 2021 at 03:16 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `class_scheduler`
--
CREATE DATABASE IF NOT EXISTS `class_scheduler` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `class_scheduler`;

-- --------------------------------------------------------

--
-- Table structure for table `academicprog`
--

CREATE TABLE IF NOT EXISTS `academicprog` (
  `acadProgID` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `progCode` varchar(10) NOT NULL,
  `progName` varchar(50) NOT NULL,
  PRIMARY KEY (`acadProgID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Truncate table before insert `academicprog`
--

TRUNCATE TABLE `academicprog`;
--
-- Dumping data for table `academicprog`
--

INSERT INTO `academicprog` (`acadProgID`, `progCode`, `progName`) VALUES
(1, 'BSIT', 'Bachelor of Science in Information Technology'),
(2, 'BSED', 'Bachelor of Secondary Education'),
(3, 'BSHRM', 'Bachelor of Science in Hotel and Restaurant Mngmnt');

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE IF NOT EXISTS `account` (
  `accountID` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `FName` varchar(15) NOT NULL,
  `MName` varchar(15) DEFAULT NULL,
  `LName` varchar(15) NOT NULL,
  `idNum` varchar(10) NOT NULL,
  `dept` int(5) UNSIGNED NOT NULL,
  `rankID` int(5) UNSIGNED NOT NULL,
  `specializationID` int(5) UNSIGNED NOT NULL,
  `pw` varchar(20) NOT NULL,
  `accessLevel` varchar(7) NOT NULL,
  PRIMARY KEY (`accountID`),
  UNIQUE KEY `idNum` (`idNum`),
  UNIQUE KEY `idNum_2` (`idNum`),
  UNIQUE KEY `idNum_3` (`idNum`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

--
-- Truncate table before insert `account`
--

TRUNCATE TABLE `account`;
--
-- Dumping data for table `account`
--

INSERT INTO `account` (`accountID`, `FName`, `MName`, `LName`, `idNum`, `dept`, `rankID`, `specializationID`, `pw`, `accessLevel`) VALUES
(1, 'Vin', 'admin', 'admin', 'admin', 1, 1, 1, 'admin', 'admin'),
(2, 'Adan', 'Alvin', 'A.', 'admin2', 1, 2, 4, 'admin', 'admin'),
(3, 'prof', 'dweewf', 'prof', 'prof', 1, 3, 2, 'prof', 'prof'),
(4, 'student', 'student', 'qwert', 'student', 1, 1, 3, 'student', 'student'),
(5, 'Mary', 'Not', 'Doe', 'mary', 1, 2, 3, 'mary', 'prof');

-- --------------------------------------------------------

--
-- Table structure for table `classroom`
--

CREATE TABLE IF NOT EXISTS `classroom` (
  `classroomID` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `roomNum` int(3) UNSIGNED DEFAULT NULL,
  `buildingCode` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`classroomID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Truncate table before insert `classroom`
--

TRUNCATE TABLE `classroom`;
--
-- Dumping data for table `classroom`
--

INSERT INTO `classroom` (`classroomID`, `roomNum`, `buildingCode`) VALUES
(1, 301, 'IT Building'),
(2, 302, 'Educ Building'),
(3, 303, 'HS Building'),
(4, 304, 'Admin Building');

-- --------------------------------------------------------

--
-- Table structure for table `complab`
--

CREATE TABLE IF NOT EXISTS `complab` (
  `compLabID` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `isCompLab` varchar(3) NOT NULL,
  PRIMARY KEY (`compLabID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Truncate table before insert `complab`
--

TRUNCATE TABLE `complab`;
--
-- Dumping data for table `complab`
--

INSERT INTO `complab` (`compLabID`, `isCompLab`) VALUES
(1, 'Yes'),
(2, 'No');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE IF NOT EXISTS `course` (
  `courseID` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `courseCode` varchar(10) NOT NULL,
  `courseName` varchar(50) NOT NULL,
  `lec` float(3,2) UNSIGNED NOT NULL,
  `lab` float(3,2) UNSIGNED NOT NULL,
  `totalUnits` float(3,2) UNSIGNED NOT NULL,
  PRIMARY KEY (`courseID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

--
-- Truncate table before insert `course`
--

TRUNCATE TABLE `course`;
--
-- Dumping data for table `course`
--

INSERT INTO `course` (`courseID`, `courseCode`, `courseName`, `lec`, `lab`, `totalUnits`) VALUES
(1, 'Prog 1', 'Programming 1', 2.00, 1.00, 3.00),
(2, 'PE 1', 'Physical Health', 2.00, 1.00, 3.00),
(3, 'MobileComp', 'Mobile Computing', 2.00, 1.00, 3.00),
(5, 'WebDev', 'Web Development', 2.00, 1.00, 3.00);

-- --------------------------------------------------------

--
-- Table structure for table `coursescheduling`
--

CREATE TABLE IF NOT EXISTS `coursescheduling` (
  `crsSchedID` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `classroomID` int(5) UNSIGNED NOT NULL,
  `dayID` int(5) NOT NULL,
  `timeStartID` int(5) NOT NULL,
  `timeEndID` int(5) NOT NULL,
  `secID` int(5) NOT NULL,
  `curID` int(5) NOT NULL,
  PRIMARY KEY (`crsSchedID`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4;

--
-- Truncate table before insert `coursescheduling`
--

TRUNCATE TABLE `coursescheduling`;
--
-- Dumping data for table `coursescheduling`
--

INSERT INTO `coursescheduling` (`crsSchedID`, `classroomID`, `dayID`, `timeStartID`, `timeEndID`, `secID`, `curID`) VALUES
(6, 2, 1, 7, 10, 3, 2),
(21, 4, 1, 7, 10, 1, 3),
(23, 3, 5, 7, 10, 2, 3),
(24, 3, 5, 10, 11, 3, 3),
(25, 4, 5, 15, 17, 2, 3),
(26, 2, 4, 12, 13, 2, 3),
(27, 2, 2, 8, 9, 1, 3),
(28, 1, 1, 7, 10, 2, 3),
(29, 2, 5, 8, 10, 2, 3),
(30, 3, 4, 14, 17, 2, 3),
(31, 3, 6, 18, 20, 1, 3),
(32, 2, 4, 13, 16, 2, 3),
(33, 2, 3, 11, 13, 2, 3),
(34, 1, 5, 7, 9, 2, 3),
(35, 3, 4, 10, 12, 3, 3),
(36, 2, 5, 10, 11, 1, 3),
(37, 3, 6, 14, 14, 3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `courseschedulingtemp`
--

CREATE TABLE IF NOT EXISTS `courseschedulingtemp` (
  `crsSchedID` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `classroomID` int(5) UNSIGNED NOT NULL,
  `dayID` int(5) NOT NULL,
  `timeStartID` int(5) NOT NULL,
  `timeEndID` int(5) NOT NULL,
  `secID` int(5) NOT NULL,
  `curID` int(5) NOT NULL,
  PRIMARY KEY (`crsSchedID`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4;

--
-- Truncate table before insert `courseschedulingtemp`
--

TRUNCATE TABLE `courseschedulingtemp`;
--
-- Dumping data for table `courseschedulingtemp`
--

INSERT INTO `courseschedulingtemp` (`crsSchedID`, `classroomID`, `dayID`, `timeStartID`, `timeEndID`, `secID`, `curID`) VALUES
(6, 2, 1, 7, 10, 3, 2),
(25, 4, 5, 15, 17, 2, 3),
(29, 2, 5, 8, 10, 2, 3),
(34, 1, 5, 7, 9, 2, 3),
(38, 2, 6, 18, 19, 2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `curriculum`
--

CREATE TABLE IF NOT EXISTS `curriculum` (
  `curID` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `syID` int(5) UNSIGNED NOT NULL,
  `periodID` int(5) UNSIGNED NOT NULL,
  `levelID` int(5) UNSIGNED NOT NULL,
  `acadProgID` int(5) UNSIGNED NOT NULL,
  `courseID` int(10) UNSIGNED NOT NULL,
  `crsName` varchar(25) NOT NULL,
  `deptID` int(5) UNSIGNED NOT NULL,
  `lec` float(3,2) UNSIGNED NOT NULL,
  `lab` float(3,2) UNSIGNED NOT NULL,
  `units` float(3,2) UNSIGNED NOT NULL,
  `compLabID` int(5) UNSIGNED NOT NULL,
  `totalUnits` float(3,2) UNSIGNED NOT NULL,
  PRIMARY KEY (`curID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Truncate table before insert `curriculum`
--

TRUNCATE TABLE `curriculum`;
--
-- Dumping data for table `curriculum`
--

INSERT INTO `curriculum` (`curID`, `syID`, `periodID`, `levelID`, `acadProgID`, `courseID`, `crsName`, `deptID`, `lec`, `lab`, `units`, `compLabID`, `totalUnits`) VALUES
(1, 1, 1, 1, 1, 1, 'Programming 1', 1, 3.00, 3.00, 3.00, 1, 3.00),
(2, 1, 1, 1, 1, 2, 'PE 1', 2, 3.00, 3.00, 3.00, 2, 3.00),
(3, 1, 1, 1, 1, 3, 'MobileComp', 1, 2.00, 1.00, 3.00, 2, 3.00);

-- --------------------------------------------------------

--
-- Table structure for table `day`
--

CREATE TABLE IF NOT EXISTS `day` (
  `dayID` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `dayName` varchar(10) NOT NULL,
  PRIMARY KEY (`dayID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

--
-- Truncate table before insert `day`
--

TRUNCATE TABLE `day`;
--
-- Dumping data for table `day`
--

INSERT INTO `day` (`dayID`, `dayName`) VALUES
(1, 'Monday'),
(2, 'Tuesday'),
(3, 'Wednesday'),
(4, 'Thursday'),
(5, 'Friday'),
(6, 'Saturday');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE IF NOT EXISTS `department` (
  `deptID` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `deptCode` varchar(10) NOT NULL,
  `deptName` varchar(50) NOT NULL,
  PRIMARY KEY (`deptID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Truncate table before insert `department`
--

TRUNCATE TABLE `department`;
--
-- Dumping data for table `department`
--

INSERT INTO `department` (`deptID`, `deptCode`, `deptName`) VALUES
(1, 'CICT', 'College of Information & Communication Technology'),
(2, 'COED', 'College of Education');

-- --------------------------------------------------------

--
-- Table structure for table `facultyloading`
--

CREATE TABLE IF NOT EXISTS `facultyloading` (
  `facLoadID` int(5) NOT NULL AUTO_INCREMENT,
  `crsSchedID` int(5) UNSIGNED NOT NULL,
  `classroomID` int(5) UNSIGNED NOT NULL,
  `dayID` int(5) NOT NULL,
  `timeStartID` int(5) NOT NULL,
  `timeEndID` int(5) NOT NULL,
  `secID` int(5) NOT NULL,
  `curID` int(5) NOT NULL,
  `accountID` int(5) DEFAULT NULL,
  PRIMARY KEY (`facLoadID`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4;

--
-- Truncate table before insert `facultyloading`
--

TRUNCATE TABLE `facultyloading`;
--
-- Dumping data for table `facultyloading`
--

INSERT INTO `facultyloading` (`facLoadID`, `crsSchedID`, `classroomID`, `dayID`, `timeStartID`, `timeEndID`, `secID`, `curID`, `accountID`) VALUES
(1, 1, 2, 2, 7, 9, 3, 1, 5),
(8, 24, 3, 5, 10, 11, 3, 3, 3),
(27, 23, 3, 5, 7, 10, 2, 3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `lvl`
--

CREATE TABLE IF NOT EXISTS `lvl` (
  `levelID` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `levelDesc` varchar(30) NOT NULL,
  PRIMARY KEY (`levelID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Truncate table before insert `lvl`
--

TRUNCATE TABLE `lvl`;
--
-- Dumping data for table `lvl`
--

INSERT INTO `lvl` (`levelID`, `levelDesc`) VALUES
(1, '1st Year'),
(2, '2nd Year'),
(3, '3rd Year'),
(4, '4th Year');

-- --------------------------------------------------------

--
-- Table structure for table `period`
--

CREATE TABLE IF NOT EXISTS `period` (
  `periodID` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `periodDesc` varchar(30) NOT NULL,
  PRIMARY KEY (`periodID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Truncate table before insert `period`
--

TRUNCATE TABLE `period`;
--
-- Dumping data for table `period`
--

INSERT INTO `period` (`periodID`, `periodDesc`) VALUES
(1, '1st Semester'),
(2, '2nd Semester');

-- --------------------------------------------------------

--
-- Table structure for table `rnk`
--

CREATE TABLE IF NOT EXISTS `rnk` (
  `rankID` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `rankName` varchar(25) NOT NULL,
  PRIMARY KEY (`rankID`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;

--
-- Truncate table before insert `rnk`
--

TRUNCATE TABLE `rnk`;
--
-- Dumping data for table `rnk`
--

INSERT INTO `rnk` (`rankID`, `rankName`) VALUES
(1, 'Instructor I'),
(2, 'Instructor II'),
(3, 'Instructor III'),
(4, 'Assistant Professor I'),
(5, 'Assistant Professor II'),
(6, 'Assistant Professor III'),
(7, 'Assistant Professor IV'),
(8, 'Professor I'),
(9, 'Professor II'),
(10, 'Professor III'),
(11, 'Professor IV');

-- --------------------------------------------------------

--
-- Table structure for table `schoolyear`
--

CREATE TABLE IF NOT EXISTS `schoolyear` (
  `syID` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `schoolYR` varchar(10) NOT NULL,
  PRIMARY KEY (`syID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Truncate table before insert `schoolyear`
--

TRUNCATE TABLE `schoolyear`;
--
-- Dumping data for table `schoolyear`
--

INSERT INTO `schoolyear` (`syID`, `schoolYR`) VALUES
(1, '2020-2021');

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE IF NOT EXISTS `section` (
  `secID` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `acadProgID` int(5) UNSIGNED NOT NULL,
  `levelID` int(5) UNSIGNED NOT NULL,
  `section` varchar(10) NOT NULL,
  PRIMARY KEY (`secID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Truncate table before insert `section`
--

TRUNCATE TABLE `section`;
--
-- Dumping data for table `section`
--

INSERT INTO `section` (`secID`, `acadProgID`, `levelID`, `section`) VALUES
(1, 1, 1, '1A'),
(2, 1, 2, '2B'),
(3, 1, 2, '2C');

-- --------------------------------------------------------

--
-- Table structure for table `specialization`
--

CREATE TABLE IF NOT EXISTS `specialization` (
  `specializationID` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `specName` varchar(50) NOT NULL,
  PRIMARY KEY (`specializationID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

--
-- Truncate table before insert `specialization`
--

TRUNCATE TABLE `specialization`;
--
-- Dumping data for table `specialization`
--

INSERT INTO `specialization` (`specializationID`, `specName`) VALUES
(1, 'Information Systems Management'),
(2, 'Network Administration & Management'),
(3, 'Web & Application Development'),
(4, 'Education Administration'),
(5, 'Early Childhood Special Education');

-- --------------------------------------------------------

--
-- Table structure for table `timeend`
--

CREATE TABLE IF NOT EXISTS `timeend` (
  `timeEndID` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `timeEnd` varchar(10) NOT NULL,
  PRIMARY KEY (`timeEndID`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4;

--
-- Truncate table before insert `timeend`
--

TRUNCATE TABLE `timeend`;
--
-- Dumping data for table `timeend`
--

INSERT INTO `timeend` (`timeEndID`, `timeEnd`) VALUES
(8, '8 am'),
(9, '9 am'),
(10, '10 am'),
(11, '11 am'),
(12, '12 nn'),
(13, '1 pm'),
(14, '2 pm'),
(15, '3 pm'),
(16, '4 pm'),
(17, '5 pm'),
(18, '6 pm'),
(19, '7 pm'),
(20, '8 pm'),
(21, '9 pm');

-- --------------------------------------------------------

--
-- Table structure for table `timestart`
--

CREATE TABLE IF NOT EXISTS `timestart` (
  `timeStartID` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `timeStart` varchar(10) NOT NULL,
  PRIMARY KEY (`timeStartID`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;

--
-- Truncate table before insert `timestart`
--

TRUNCATE TABLE `timestart`;
--
-- Dumping data for table `timestart`
--

INSERT INTO `timestart` (`timeStartID`, `timeStart`) VALUES
(7, '7 am'),
(8, '8 am'),
(9, '9 am'),
(10, '10 am'),
(11, '11 am'),
(12, '12 nn'),
(13, '1 pm'),
(14, '2 pm'),
(15, '3 pm'),
(16, '4 pm'),
(17, '5 pm'),
(18, '6 pm'),
(19, '7 pm'),
(20, '8 pm');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
