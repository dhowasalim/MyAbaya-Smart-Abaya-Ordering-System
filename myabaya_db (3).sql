-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 16, 2026 at 12:33 AM
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
-- Database: `myabaya_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `Admin`
--

CREATE TABLE `Admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Admin`
--

INSERT INTO `Admin` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `Products`
--

CREATE TABLE `Products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `image` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `size` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Products`
--

INSERT INTO `Products` (`id`, `name`, `price`, `stock`, `image`, `description`, `color`, `size`) VALUES
(1, 'Classic Black Abaya', 149.99, 17, 'abaya1.jpg', 'قماش العباية : جورجيت ميلانو غير مبطن\r\n  \r\n\r\nقماش الطرحة : شيفون اندونسي\r\n\r\n\r\nالقصة :  كلوش ', 'Black', 'S,M,L'),
(3, 'Marble Abaya', 179.99, 34, 'abaya3.jpg', 'قماش العباية : جورجيت ميلانو غير مبطن |  \r\n\r\nقماش الطرحة : شيفون اندونسي\r\n\r\nالقصة :  كلوش ', 'Black & White', 'M,L'),
(4, 'Royal Blue Abaya', 220.00, 8, 'abaya4.jpg', 'قماش العباية : جورجيت ميلانو غير مبطن |  \r\n\r\nقماش الطرحة : شيفون اندونسي\r\n\r\nالقصة :  كلوش ', 'Black', 'L,XL'),
(5, 'Olive Pattern Abaya', 160.00, 23, 'abaya5.jpg', 'قماش العباية : جورجيت ميلانو غير مبطن |  \r\n\r\nقماش الطرحة : شيفون اندونسي\r\n\r\nالقصة :  كلوش ', 'Beige', 'S,M,XL'),
(6, 'Ruba Abaya', 189.99, 161, 'abaya6.jpg', 'قماش العباية : جورجيت ميلانو غير مبطن |  \r\n\r\nقماش الطرحة : شيفون اندونسي\r\n\r\nالقصة :  كلوش', 'Black', 'M,L,XL'),
(9, 'Lunar Rose', 300.00, 34, '1778882607_abaya11.jpg', 'قماش العباية : جورجيت ميلانو غير مبطن |  \r\n\r\nقماش الطرحة : شيفون اندونسي\r\n\r\nالقصة :  كلوش ', 'Black,Rose Pink', 'S,M,L,XL'),
(11, 'Midnight', 189.00, 16, '1778882860_abaya12.jpg', 'قماش العباية : جورجيت ميلانو غير مبطن |  \r\n\r\nقماش الطرحة : شيفون اندونسي\r\n\r\nالقصة :  كلوش ', 'Dark Navy Blue', 'M,L'),
(12, 'Storm Abaya', 299.00, 23, '1778882939_abaya13.jpg', 'قماش العباية : جورجيت ميلانو غير مبطن |  \r\n\r\nقماش الطرحة : شيفون اندونسي\r\n\r\nالقصة :  كلوش ', 'Black & White', 'S,M,L,XL'),
(13, 'Bloom Abaya', 399.00, 28, '1778883082_abaya14.jpg', 'قماش العباية : جورجيت ميلانو غير مبطن |  \r\n\r\nقماش الطرحة : شيفون اندونسي\r\n\r\nالقصة :  كلوش ', 'Deep Navy & Dusty Rose', 'M,L,XL'),
(14, 'Eclipse Green', 299.00, 23, '1778883312_Screenshot 2026-05-16 at 1.14.16 AM.png', 'قماش العباية : جورجيت ميلانو غير مبطن |  \r\n\r\nقماش الطرحة : شيفون اندونسي\r\n\r\nالقصة :  كلوش ', 'Black,Green', 'S,M,L,XL'),
(15, 'Shadow Petal', 300.00, 123, '1778883486_Screenshot 2026-05-16 at 1.16.28 AM.png', 'قماش العباية : جورجيت ميلانو غير مبطن |  \r\n\r\nقماش الطرحة : شيفون اندونسي\r\n\r\nالقصة :  كلوش ', 'Black & Burgundy', ''),
(16, 'Moonlit Abaya', 179.99, 27, '1778883631_Screenshot 2026-05-16 at 1.19.55 AM.png', '(تصميم وطباعة خاصة ببراند MyAbaya) القماش : شيفون مبطن بشيفون القصة : بشت -عبايات النص فراشه والبشت تأتي بعرض موحد وثابت- -درجه الطرحة افتح من العباية- غسيل الحرير المغسول: لا تُغسل بالماء. يُكتفى بمسحها من الخارج بفوطة قطنية جافة', 'Black & White', 'S,M,L,XL');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Admin`
--
ALTER TABLE `Admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Products`
--
ALTER TABLE `Products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Admin`
--
ALTER TABLE `Admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Products`
--
ALTER TABLE `Products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
