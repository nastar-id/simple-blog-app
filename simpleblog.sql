-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 17, 2023 at 09:04 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simpleblog`
--

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `image` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `id_user`, `image`) VALUES
(6, 1, 'IMG_6410b48133510.jpg'),
(7, 2, 'IMG_6410bea7d252b.png'),
(8, 3, 'IMG_6411e49ad954b.png'),
(9, 1, 'IMG_64141eb2412ab.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(90) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `nama`, `email`, `username`, `password`, `image`) VALUES
(1, 'Muhammad Rasyad', 'naxtarrr@gmail.com', 'nastar', '$2y$10$LiQeCYMLLi.9g3T6ZytTveyx2uuhsrVjdvmNErNtJP8v04fdV1gU6', '641173229546b.jpg'),
(2, 'Saman Brembo', 'brembo@gmail.com', 'brembo', '$2y$10$u9.K61xcGFtKdc5Qszkud.pX4lV4WatkW4Xg6ZJJV5uWAasXRS0X6', 'nophoto.png'),
(3, 'Muhammad Sumbul', 'sumbul@gmail.com', 'sumbul', '$2y$10$QDCroLWuuscaDw.LuWmc7utSp2fEqWX6sVK9.5hi7xgJ.YbXIaVE2', '6411e3ffe7d62.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `post` text NOT NULL,
  `category` varchar(70) NOT NULL,
  `image` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `id_user`, `title`, `post`, `category`, `image`) VALUES
(1, 2, 'Conjuring The Dead', 'It\'s the devil who speaks at her.\r\nShe is a witch and speaks the satan\'s words,\r\nIn this, holy place.\r\nHollow – virgin eyes ripped\r\nTorn – just like your throat\r\nFester – obsession incarnate\r\nAnother grave – another sacrifice\r\nI am misanthropic, sin, sin\r\nConjuring the dead – ancient tongue of shadows\r\nConjuring the dead – give yourself to Satan\r\nTransmit – this burning torment\r\nBleeding – clawing at the walls\r\nTransformed – invoking his commands\r\nManifest – ascend before the fall\r\nI am crowned by the obscene\r\nConjuring the dead – secretion sick in sin\r\nConjuring the dead – give yourself to Satan\r\nI am the grinning ecstasy\r\nI am the lord of all poison\r\nI am misanthropic, sin, sin\r\nConjuring the dead – priest collapsing dogma\r\nConjuring the dead – give yourself to Satan', 'Ritual', '6411881c8d5ef.jpg'),
(3, 1, 'Erika', 'Auf der Heide blüht ein kleines Blümelein\r\nUnd das heißt\r\nErika\r\nHeiß von hunderttausend kleinen Bienelein\r\nWird umschwärmt\r\nErika\r\n\r\n<img src=\"http://localhost/test/admin/uploads/gallery/IMG_6410b4a257629.jpg\">\r\n\r\nDenn ihr Herz ist voller Süßigkeit\r\nZarter Duft entströmt dem Blütenkleid\r\nAuf der Heide blüht ein kleines Blümelein\r\nUnd das heißt\r\nErika', 'Speech', '641188dd1b992.jpg'),
(4, 1, '忘れてやらない', 'ぜんぶ天気のせいでいいよ\r\nこの気まずさも倦怠感も\r\n太陽は隠れながら知らんぷり\r\n\r\n\r\nガタゴト揺れる満員電車\r\nすれ違うのは準急列車\r\n輪郭のない雲の　表情を探してみる\r\n\r\n\r\n「作者の気持ちを答えなさい」\r\nいったいなにが　正解なんだい？\r\n予定調和の　シナリオ踏み抜いて\r\n\r\n\r\n青い春なんてもんは\r\n僕には似合わないんだ\r\nそれでも知ってるから　一度しかない瞬間は\r\n儚さを孕んでる\r\n絶対忘れてやらないよ\r\nいつか死ぬまで何回だって\r\nこんなこともあったって　笑ってやんのさ', 'Song', '6411897235f8c.png'),
(9, 3, 'Laravel Debugbar Get Admin User Pass', 'Get Admin User Pass from Laravel Debugbar Package.\r\n\r\nThis works if the website installed Laravel Debugbar Package, usually the website is still under development.\r\n\r\nDork : intext:PHP DEBUGBAR DATA inurl:/login\r\nPoC :\r\n\r\nClick on the folder icon\r\n\r\n<img src=\"http://localhost/test/admin/uploads/gallery/IMG_6411e49ad954b.png\">\r\n\r\nScroll down and select POST in the Method and click search.\r\nSearch the login url. For example /login or /auth/login and click the login url then go to the request button.', 'Tutorial', '6411e484b8be0.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `user_token`
--

CREATE TABLE `user_token` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `token` int(10) NOT NULL,
  `date` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_token`
--
ALTER TABLE `user_token`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user_token`
--
ALTER TABLE `user_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
