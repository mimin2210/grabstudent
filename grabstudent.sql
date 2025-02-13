-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 13, 2025 at 10:38 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `grabstudent`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `ADMIN_ID` int(3) NOT NULL,
  `ADMIN_NAME` varchar(50) NOT NULL,
  `ADMIN_MATRICS` varchar(10) NOT NULL,
  `ADMIN_PHONE` varchar(13) NOT NULL,
  `ADMIN_EMAIL` varchar(50) NOT NULL,
  `ADMIN_PASSWORD` varchar(255) NOT NULL,
  `ADMIN_PROFILE` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`ADMIN_ID`, `ADMIN_NAME`, `ADMIN_MATRICS`, `ADMIN_PHONE`, `ADMIN_EMAIL`, `ADMIN_PASSWORD`, `ADMIN_PROFILE`) VALUES
(1, 'Uki', 'AD1', '012-3456789', 'admin@gmail.com', '$2y$10$iAHGno2tjiMNQLmjCARK5Oha9Gk5z441hrHw832a12Pv/qjpQWx3u', 'admin_1.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `building`
--

CREATE TABLE `building` (
  `BUILDING_ID` int(3) NOT NULL,
  `BUILDING_NAME` varchar(100) NOT NULL,
  `BUILDING_LOCATION` varchar(10) NOT NULL,
  `BUILDING_LOGO` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `building`
--

INSERT INTO `building` (`BUILDING_ID`, `BUILDING_NAME`, `BUILDING_LOCATION`, `BUILDING_LOGO`) VALUES
(1, 'Library', 'INDUK', 'LIBRARY.jpeg'),
(2, 'FTMK', 'TEKNOLOGI', 'FTMK.png'),
(3, 'FPTT', 'TEKNOLOGI', 'FPTT.png');

-- --------------------------------------------------------

--
-- Table structure for table `driver`
--

CREATE TABLE `driver` (
  `DRIVER_ID` int(3) NOT NULL,
  `DRIVER_NAME` varchar(50) NOT NULL,
  `DRIVER_MATRICS` varchar(10) NOT NULL,
  `DRIVER_PHONE` varchar(13) NOT NULL,
  `DRIVER_CAR` varchar(50) NOT NULL,
  `DRIVER_EMAIL` varchar(50) NOT NULL,
  `DRIVER_PASSWORD` varchar(255) NOT NULL,
  `DRIVER_PROFILE` varchar(50) NOT NULL,
  `DRIVER_CURRENT` varchar(100) NOT NULL,
  `DRIVER_WARNING` int(1) NOT NULL,
  `DRIVER_STATUS` varchar(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `driver`
--

INSERT INTO `driver` (`DRIVER_ID`, `DRIVER_NAME`, `DRIVER_MATRICS`, `DRIVER_PHONE`, `DRIVER_CAR`, `DRIVER_EMAIL`, `DRIVER_PASSWORD`, `DRIVER_PROFILE`, `DRIVER_CURRENT`, `DRIVER_WARNING`, `DRIVER_STATUS`) VALUES
(1, 'Enna', 'DR1', '012-3456789', 'Proton Saga', 'driver@gmail.com', '$2y$10$q9HhFYXVGGLMFQU46/WuZ.ucCAX8X3TfgCoyEXDoFAg7qHqatsIS6', 'driver_1.jpg', 'Library', 0, NULL),
(2, 'Luca', 'DR2', '012-3456789', 'Red Myvi', 'aisyahhannes10@gmail.com', '$2y$10$ExDoKSFRdRB5QMkk3offEOaflU6DM5wwYZy9d6SQU1jECGDYgXMSy', 'driver_2.jpg', 'FPTT', 2, 'BANNED');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `FEEDBACK_ID` int(3) NOT NULL,
  `DRIVER_ID` int(3) NOT NULL,
  `PASSENGER_ID` int(3) NOT NULL,
  `FEEDBACK_RATING` int(1) NOT NULL,
  `FEEDBACK_COMMENT` varchar(255) DEFAULT NULL,
  `FEEDBACK_DATE` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`FEEDBACK_ID`, `DRIVER_ID`, `PASSENGER_ID`, `FEEDBACK_RATING`, `FEEDBACK_COMMENT`, `FEEDBACK_DATE`) VALUES
(1, 1, 1, 3, '', '2024-11-01'),
(2, 1, 1, 1, '', '2024-11-15'),
(3, 1, 1, 4, '', '2024-12-12'),
(4, 1, 1, 5, '', '2024-12-25'),
(5, 2, 1, 2, '', '2025-01-14'),
(6, 2, 1, 3, '', '2025-01-28'),
(7, 1, 1, 4, '', '2025-02-13'),
(8, 1, 1, 5, 'excellent drive. thank you', '2025-02-13'),
(9, 1, 1, 2, 'drive safely!', '2025-02-13'),
(10, 1, 1, 2, '', '2025-02-13');

-- --------------------------------------------------------

--
-- Table structure for table `passenger`
--

CREATE TABLE `passenger` (
  `PASSENGER_ID` int(3) NOT NULL,
  `PASSENGER_NAME` varchar(50) NOT NULL,
  `PASSENGER_PHONE` varchar(13) NOT NULL,
  `PASSENGER_EMAIL` varchar(50) NOT NULL,
  `PASSENGER_USERNAME` varchar(20) NOT NULL,
  `PASSENGER_PASSWORD` varchar(255) NOT NULL,
  `PASSENGER_PROFILE` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `passenger`
--

INSERT INTO `passenger` (`PASSENGER_ID`, `PASSENGER_NAME`, `PASSENGER_PHONE`, `PASSENGER_EMAIL`, `PASSENGER_USERNAME`, `PASSENGER_PASSWORD`, `PASSENGER_PROFILE`) VALUES
(1, 'Shu', '012-3456789', 'passenger1@gmail.com', 'shu', '$2y$10$fLoJehGe21Joye9qFDDL9OisbOrkxa6otG3fTYtMwtVVwy46lJLpq', 'passenger_1.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `REPORT_ID` int(3) NOT NULL,
  `RIDE_DATE` date NOT NULL,
  `RIDE_TIME` time NOT NULL,
  `REPORT_SUBJECT` varchar(50) NOT NULL,
  `REPORT_COMMENT` varchar(255) NOT NULL,
  `REPORT_FILE` varchar(50) DEFAULT NULL,
  `REPORT_STATUS` varchar(20) NOT NULL,
  `REPORT_TIME` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `PASSENGER_ID` int(3) NOT NULL,
  `DRIVER_ID` int(3) NOT NULL,
  `ADMIN_ID` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`REPORT_ID`, `RIDE_DATE`, `RIDE_TIME`, `REPORT_SUBJECT`, `REPORT_COMMENT`, `REPORT_FILE`, `REPORT_STATUS`, `REPORT_TIME`, `PASSENGER_ID`, `DRIVER_ID`, `ADMIN_ID`) VALUES
(1, '2025-02-02', '14:06:00', 'Driver is acting innapropriate', 'asking weird question', NULL, 'ACCEPT', '2025-02-13 06:09:11', 1, 2, 1),
(2, '2025-02-01', '14:06:00', 'Drive dangerously', 'please drive safely', NULL, 'ACCEPT', '2025-02-13 06:09:15', 1, 2, 1),
(3, '2025-02-04', '14:07:00', 'Broken aircond', 'it\'s too hot', NULL, 'REJECT', '2025-02-13 06:09:20', 1, 1, 1),
(4, '2025-02-07', '14:07:00', 'Take long route', 'i got scold by my lecturer', NULL, 'REJECT', '2025-02-13 06:10:45', 1, 2, 1),
(5, '2025-02-05', '14:09:00', 'Driving really fast', 'drive ignoring speedbumps', NULL, 'ACCEPT', '2025-02-13 06:10:24', 1, 2, 1),
(6, '2025-02-06', '14:10:00', 'Dirty Car', 'car is too dirty. please clean it', 'uploads/REPORT.png', 'ACCEPT', '2025-02-13 06:13:27', 1, 1, 1),
(7, '2025-02-13', '14:20:00', 'Driving really fast', 'please drive slowly', NULL, 'ACCEPT', '2025-02-13 06:22:38', 1, 1, 1),
(8, '2025-02-13', '16:28:00', 'Broken aircond', 'please fix your aircond!', NULL, '', '2025-02-13 08:28:27', 1, 1, NULL),
(9, '2025-02-13', '16:34:00', 'Driving really fast', 'please drive safely', NULL, '', '2025-02-13 08:34:20', 1, 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ride_order`
--

CREATE TABLE `ride_order` (
  `ORDER_ID` int(3) NOT NULL,
  `ORDER_FROM` varchar(100) NOT NULL,
  `ORDER_TO` varchar(100) NOT NULL,
  `ORDER_PAX` int(1) NOT NULL,
  `ORDER_PRICE` int(1) NOT NULL,
  `ORDER_DATE` date NOT NULL,
  `ORDER_TIME` time NOT NULL,
  `ORDER_STATUS` varchar(20) DEFAULT NULL,
  `PASSENGER_ID` int(3) NOT NULL,
  `DRIVER_ID` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ride_order`
--

INSERT INTO `ride_order` (`ORDER_ID`, `ORDER_FROM`, `ORDER_TO`, `ORDER_PAX`, `ORDER_PRICE`, `ORDER_DATE`, `ORDER_TIME`, `ORDER_STATUS`, `PASSENGER_ID`, `DRIVER_ID`) VALUES
(1, 'Library', 'FTMK', 1, 4, '2024-11-01', '13:53:00', 'DONE', 1, 1),
(2, 'Library', 'FPTT', 2, 7, '2024-11-15', '13:53:00', 'DONE', 1, 1),
(4, 'FTMK', 'Library', 3, 4, '2024-12-12', '13:55:00', 'DONE', 1, 1),
(5, 'FTMK', 'FPTT', 4, 7, '2024-12-25', '13:55:00', 'DONE', 1, 1),
(6, 'FPTT', 'FTMK', 1, 7, '2025-01-14', '14:01:00', 'DONE', 1, 2),
(7, 'FPTT', 'Library', 2, 7, '2025-01-28', '14:01:00', 'DONE', 1, 2),
(8, 'FTMK', 'FPTT', 2, 7, '2025-02-13', '14:03:00', 'DONE', 1, 1),
(9, 'FTMK', 'FPTT', 3, 7, '2025-02-13', '14:18:00', 'DONE', 1, 1),
(10, 'FTMK', 'FPTT', 4, 7, '2025-02-13', '16:26:00', 'DONE', 1, 1),
(11, 'Library', 'FPTT', 2, 7, '2025-02-13', '16:32:00', 'DONE', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`ADMIN_ID`);

--
-- Indexes for table `building`
--
ALTER TABLE `building`
  ADD PRIMARY KEY (`BUILDING_ID`);

--
-- Indexes for table `driver`
--
ALTER TABLE `driver`
  ADD PRIMARY KEY (`DRIVER_ID`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`FEEDBACK_ID`),
  ADD KEY `FK_FEEDBACK_PASSENGER` (`PASSENGER_ID`),
  ADD KEY `FK_FEEDBACK_DRIVER` (`DRIVER_ID`);

--
-- Indexes for table `passenger`
--
ALTER TABLE `passenger`
  ADD PRIMARY KEY (`PASSENGER_ID`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`REPORT_ID`),
  ADD KEY `FK_REPORT_PASSENGER` (`PASSENGER_ID`),
  ADD KEY `FK_REPORT_DRIVER` (`DRIVER_ID`),
  ADD KEY `FK_REPORT_ADMIN` (`ADMIN_ID`);

--
-- Indexes for table `ride_order`
--
ALTER TABLE `ride_order`
  ADD PRIMARY KEY (`ORDER_ID`),
  ADD KEY `FK_ORDER_PASSENGER` (`PASSENGER_ID`),
  ADD KEY `FK_ORDER_DRIVER` (`DRIVER_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `ADMIN_ID` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `building`
--
ALTER TABLE `building`
  MODIFY `BUILDING_ID` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `driver`
--
ALTER TABLE `driver`
  MODIFY `DRIVER_ID` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `FEEDBACK_ID` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `passenger`
--
ALTER TABLE `passenger`
  MODIFY `PASSENGER_ID` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `REPORT_ID` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `ride_order`
--
ALTER TABLE `ride_order`
  MODIFY `ORDER_ID` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `FK_FEEDBACK_DRIVER` FOREIGN KEY (`DRIVER_ID`) REFERENCES `driver` (`DRIVER_ID`),
  ADD CONSTRAINT `FK_FEEDBACK_PASSENGER` FOREIGN KEY (`PASSENGER_ID`) REFERENCES `passenger` (`PASSENGER_ID`);

--
-- Constraints for table `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `FK_REPORT_ADMIN` FOREIGN KEY (`ADMIN_ID`) REFERENCES `admin` (`ADMIN_ID`),
  ADD CONSTRAINT `FK_REPORT_DRIVER` FOREIGN KEY (`DRIVER_ID`) REFERENCES `driver` (`DRIVER_ID`),
  ADD CONSTRAINT `FK_REPORT_PASSENGER` FOREIGN KEY (`PASSENGER_ID`) REFERENCES `passenger` (`PASSENGER_ID`);

--
-- Constraints for table `ride_order`
--
ALTER TABLE `ride_order`
  ADD CONSTRAINT `FK_ORDER_DRIVER` FOREIGN KEY (`DRIVER_ID`) REFERENCES `driver` (`DRIVER_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_ORDER_PASSENGER` FOREIGN KEY (`PASSENGER_ID`) REFERENCES `passenger` (`PASSENGER_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
