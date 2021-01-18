-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 17, 2021 at 10:32 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `class_scheduler`
--

-- --------------------------------------------------------

--
-- Table structure for table `academicprog`
--

CREATE TABLE `academicprog` (
  `acadProgID` int(5) UNSIGNED NOT NULL,
  `progCode` varchar(10) NOT NULL,
  `progName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `academicprog`
--

INSERT INTO `academicprog` (`acadProgID`, `progCode`, `progName`) VALUES
(1, 'BSIT', 'Bachelor of Science in Information Technology'),
(2, 'BSED', 'Bachelor of Secondary Education');

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `accountID` int(5) UNSIGNED NOT NULL,
  `FName` varchar(15) NOT NULL,
  `MName` varchar(15) DEFAULT NULL,
  `LName` varchar(15) NOT NULL,
  `idNum` varchar(10) NOT NULL,
  `dept` int(5) UNSIGNED NOT NULL,
  `email` varchar(20) NOT NULL,
  `rankID` int(5) UNSIGNED NOT NULL,
  `specializationID` int(5) UNSIGNED NOT NULL,
  `pw` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`accountID`, `FName`, `MName`, `LName`, `idNum`, `dept`, `email`, `rankID`, `specializationID`, `pw`) VALUES
(1, 'admin', NULL, 'admin', '18500783', 1, 'alvin.adan.12@gmail.', 2, 3, 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `classroom`
--

CREATE TABLE `classroom` (
  `classroomID` int(5) UNSIGNED NOT NULL,
  `roomNum` int(3) UNSIGNED NOT NULL,
  `buildingCode` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `classroom`
--

INSERT INTO `classroom` (`classroomID`, `roomNum`, `buildingCode`) VALUES
(1, 301, 'IT Building'),
(2, 302, 'Educ Building');

-- --------------------------------------------------------

--
-- Table structure for table `complab`
--

CREATE TABLE `complab` (
  `compLabID` int(5) UNSIGNED NOT NULL,
  `isCompLab` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

CREATE TABLE `course` (
  `courseID` int(5) UNSIGNED NOT NULL,
  `courseCode` varchar(10) NOT NULL,
  `courseName` varchar(50) NOT NULL,
  `lec` float(3,2) UNSIGNED NOT NULL,
  `lab` float(3,2) UNSIGNED NOT NULL,
  `totalUnits` float(3,2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `coursescheduling`
--

CREATE TABLE `coursescheduling` (
  `crsSchedID` int(5) UNSIGNED NOT NULL,
  `acadProgID` int(5) UNSIGNED NOT NULL,
  `levelID` int(5) UNSIGNED NOT NULL,
  `periodID` int(5) UNSIGNED NOT NULL,
  `curID` int(5) UNSIGNED NOT NULL,
  `dayID` int(5) UNSIGNED NOT NULL,
  `timeStartID` int(5) UNSIGNED NOT NULL,
  `timeEndID` int(5) UNSIGNED NOT NULL,
  `classroomID` int(5) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `curriculum`
--

CREATE TABLE `curriculum` (
  `curID` int(5) UNSIGNED NOT NULL,
  `syID` int(5) UNSIGNED NOT NULL,
  `periodID` int(5) UNSIGNED NOT NULL,
  `levelID` int(5) UNSIGNED NOT NULL,
  `acadProgID` int(5) UNSIGNED NOT NULL,
  `crsCode` int(10) UNSIGNED NOT NULL,
  `crsName` varchar(25) NOT NULL,
  `deptID` int(5) UNSIGNED NOT NULL,
  `lec` float(3,2) UNSIGNED NOT NULL,
  `lab` float(3,2) UNSIGNED NOT NULL,
  `units` float(3,2) UNSIGNED NOT NULL,
  `compLabID` int(5) UNSIGNED NOT NULL,
  `totalUnits` float(3,2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `day`
--

CREATE TABLE `day` (
  `dayID` int(5) UNSIGNED NOT NULL,
  `dayName` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

CREATE TABLE `department` (
  `deptID` int(5) UNSIGNED NOT NULL,
  `deptCode` varchar(10) NOT NULL,
  `deptName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

CREATE TABLE `facultyloading` (
  `facLoadID` int(5) UNSIGNED NOT NULL,
  `deptID` int(5) UNSIGNED NOT NULL,
  `levelID` int(5) UNSIGNED NOT NULL,
  `accountID` int(5) UNSIGNED NOT NULL,
  `crsSched` int(5) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `lvl`
--

CREATE TABLE `lvl` (
  `levelID` int(5) UNSIGNED NOT NULL,
  `levelDesc` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

CREATE TABLE `period` (
  `periodID` int(5) UNSIGNED NOT NULL,
  `periodDesc` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

CREATE TABLE `rnk` (
  `rankID` int(5) UNSIGNED NOT NULL,
  `rankName` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

CREATE TABLE `schoolyear` (
  `syID` int(5) UNSIGNED NOT NULL,
  `schoolYR` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `schoolyear`
--

INSERT INTO `schoolyear` (`syID`, `schoolYR`) VALUES
(1, '2020-2021');

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE `section` (
  `secID` int(5) UNSIGNED NOT NULL,
  `acadProgID` int(5) UNSIGNED NOT NULL,
  `levelID` int(5) UNSIGNED NOT NULL,
  `section` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`secID`, `acadProgID`, `levelID`, `section`) VALUES
(1, 1, 2, '2A'),
(2, 1, 2, '2B'),
(3, 1, 2, '2C');

-- --------------------------------------------------------

--
-- Table structure for table `specialization`
--

CREATE TABLE `specialization` (
  `specializationID` int(5) UNSIGNED NOT NULL,
  `specName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

CREATE TABLE `timeend` (
  `timeEndID` int(5) UNSIGNED NOT NULL,
  `timeEnd` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `timeend`
--

INSERT INTO `timeend` (`timeEndID`, `timeEnd`) VALUES
(1, '7 am'),
(2, '8 am'),
(3, '9 am'),
(4, '10 am'),
(5, '11 am'),
(6, '12 nn'),
(7, '1 pm'),
(8, '2 pm'),
(9, '3 pm'),
(10, '4 pm'),
(11, '5 pm'),
(12, '6 pm'),
(13, '7 pm'),
(14, '8 pm'),
(15, '9 pm');

-- --------------------------------------------------------

--
-- Table structure for table `timestart`
--

CREATE TABLE `timestart` (
  `timeStartID` int(5) UNSIGNED NOT NULL,
  `timeStart` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `timestart`
--

INSERT INTO `timestart` (`timeStartID`, `timeStart`) VALUES
(1, '7 am'),
(2, '8 am'),
(3, '9 am'),
(4, '10 am'),
(5, '11 am'),
(6, '12 nn'),
(7, '1 pm'),
(8, '2 pm'),
(9, '3 pm'),
(10, '4 pm'),
(11, '5 pm'),
(12, '6 pm'),
(13, '7 pm'),
(14, '8 pm');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academicprog`
--
ALTER TABLE `academicprog`
  ADD PRIMARY KEY (`acadProgID`);

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`accountID`),
  ADD UNIQUE KEY `idNum` (`idNum`);

--
-- Indexes for table `classroom`
--
ALTER TABLE `classroom`
  ADD PRIMARY KEY (`classroomID`);

--
-- Indexes for table `complab`
--
ALTER TABLE `complab`
  ADD PRIMARY KEY (`compLabID`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`courseID`);

--
-- Indexes for table `coursescheduling`
--
ALTER TABLE `coursescheduling`
  ADD PRIMARY KEY (`crsSchedID`);

--
-- Indexes for table `curriculum`
--
ALTER TABLE `curriculum`
  ADD PRIMARY KEY (`curID`);

--
-- Indexes for table `day`
--
ALTER TABLE `day`
  ADD PRIMARY KEY (`dayID`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`deptID`);

--
-- Indexes for table `facultyloading`
--
ALTER TABLE `facultyloading`
  ADD PRIMARY KEY (`facLoadID`);

--
-- Indexes for table `lvl`
--
ALTER TABLE `lvl`
  ADD PRIMARY KEY (`levelID`);

--
-- Indexes for table `period`
--
ALTER TABLE `period`
  ADD PRIMARY KEY (`periodID`);

--
-- Indexes for table `rnk`
--
ALTER TABLE `rnk`
  ADD PRIMARY KEY (`rankID`);

--
-- Indexes for table `schoolyear`
--
ALTER TABLE `schoolyear`
  ADD PRIMARY KEY (`syID`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`secID`);

--
-- Indexes for table `specialization`
--
ALTER TABLE `specialization`
  ADD PRIMARY KEY (`specializationID`);

--
-- Indexes for table `timeend`
--
ALTER TABLE `timeend`
  ADD PRIMARY KEY (`timeEndID`);

--
-- Indexes for table `timestart`
--
ALTER TABLE `timestart`
  ADD PRIMARY KEY (`timeStartID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academicprog`
--
ALTER TABLE `academicprog`
  MODIFY `acadProgID` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `accountID` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `classroom`
--
ALTER TABLE `classroom`
  MODIFY `classroomID` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `complab`
--
ALTER TABLE `complab`
  MODIFY `compLabID` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `coursescheduling`
--
ALTER TABLE `coursescheduling`
  MODIFY `crsSchedID` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `curriculum`
--
ALTER TABLE `curriculum`
  MODIFY `curID` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `day`
--
ALTER TABLE `day`
  MODIFY `dayID` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `deptID` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `facultyloading`
--
ALTER TABLE `facultyloading`
  MODIFY `facLoadID` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lvl`
--
ALTER TABLE `lvl`
  MODIFY `levelID` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `period`
--
ALTER TABLE `period`
  MODIFY `periodID` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rnk`
--
ALTER TABLE `rnk`
  MODIFY `rankID` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `schoolyear`
--
ALTER TABLE `schoolyear`
  MODIFY `syID` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `section`
--
ALTER TABLE `section`
  MODIFY `secID` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `specialization`
--
ALTER TABLE `specialization`
  MODIFY `specializationID` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `timeend`
--
ALTER TABLE `timeend`
  MODIFY `timeEndID` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `timestart`
--
ALTER TABLE `timestart`
  MODIFY `timeStartID` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
