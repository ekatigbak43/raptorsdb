-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 21, 2024 at 01:52 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `raptorsdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `article_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`article_id`, `title`, `content`, `author_id`, `created_at`) VALUES
(5, 'Top 10 Raptors Players of All Time', '1. Kyle Lowry\r\n2. Vince Carter\r\n3. Kawhi Leonard\r\n4. Demar DeRozan\r\n5. Pascal Siakam\r\n6. Chris Bosh\r\n7. Fred VanVleet\r\n8. Marc Gasol\r\n9. Damon Stoudamire\r\n10. Tracy McGrady', 2, '2024-11-16 23:46:24'),
(6, 'Top 5 Toronto Raptors Jerseys', 'The Toronto Raptors have worn \r\n\r\n1. &quot;Dinosaur&quot; Uniform worn (1995 to 1999)\r\n2.  Red North chevron (2019 NBA Championship)\r\n3.  Toronto Huskies throwbacks (2009, 2016)\r\n4. Purple/Black Vince Carter era (1999 to 2003)\r\n5.  Canadian Armed Forces Camo(2011, 2012)', 2, '2024-11-18 04:31:12'),
(7, 'Vince Carter Jersey Retirement Ceremony', 'On November 2, 2024 the Toronto Raptors officially retired Vince Carter&#039;s #15 in the rafters of Scotiabank Arena. He is the player in the Toronto Raptors history to have his jersey retired. A well deserving honour, despite the love/hate relationship throughout the years.', 2, '2024-11-18 04:34:06'),
(8, 'Latest Scottie Barnes Injury Update', 'Scottie Barnes spotted wearing a protective mask while participating in shootaround with the team on November 14, 2024.\r\n\r\nThere are still no current timeline for his return, from the injury he suffered back in October 28 against the Denver Nuggets.', 2, '2024-11-18 04:36:06'),
(9, 'Struggling Raptors lose to the Celtics in OT to a Tatum last-minute game-winner', 'Boston Celtics star Jayson Tatum had 24 points, 11 rebounds and 9 assists in the 126-123 victory against the Toronto Raptors.\r\n\r\nRJ Barrett recorded his first triple-double in the loss with 25 points, 15 assists (career-high) and 10 rebounds.', 2, '2024-11-18 04:38:48'),
(10, 'Game Preview: Indianapolis Pacers at Toronto Raptors', 'The Indiana Pacers (6-7) will travel to Toronto to take on the Raptors (2-12) on November 18, 2024. Former Toronto Raptors star Pascal Siakam expected to be playing as well. \r\n\r\nLast time they met was on April 9, 2024 where Tyrese Haliburton scored 30 points to lead the Pacers to a 140-123 win.', 2, '2024-11-18 05:22:02'),
(11, 'Looking back at the Pascal Siakam Trade', 'In January 17, 2024 the Toronto Raptors traded the last piece in their championship team - Pascal Siakam. The Raptors received three first-round draft picks and a trade-bait player in Bruce Brown. Some critics feel that the Raptors did not get a big enough return for their former all-NBA player but time will tell to see who won the trade.\r\n\r\nAs of writing, the Pacers are currently 6-7 in the 2024-2025 campaign and we have yet to see what type of picks the Toronto Raptors make and what return they can get out of the injury-ridden Bruce Brown, who has proven himself to be a significant role player with his contributions to the Denver Nuggets NBA Championship campaign a few years ago.', 2, '2024-11-18 05:28:28'),
(12, 'Barrett scores 39 points to lift Raptors against the Pacers', 'Canadian RJ Barrett scored 39 points in 35 minutes to lift the Raptors against the Indiana Pacers at home. He also added 9 rebounds and 5 assists in his efforts. Jakob Poeltl continues his hot-streak by contributing 30 points and 15 rounds. \r\n\r\nFormer Raptor, Pascal Siakam played well despite the loss scoring 25 points, 10 rebounds and 5 assists. \r\n\r\nThe Toronto Raptors are now 3-12, and will host the Anthony Edwards and the Minnesota Timberwolves on Thursday November 21, 2024.\r\n', 2, '2024-11-19 23:12:59');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `article_id`, `user_id`, `comment`, `created_at`) VALUES
(1, 12, 2, 'Hello ', '2024-11-19 23:31:35'),
(2, 12, 3, 'RJ the goat', '2024-11-19 23:45:48');

-- --------------------------------------------------------

--
-- Table structure for table `game_logs`
--

CREATE TABLE `game_logs` (
  `log_id` int(11) NOT NULL,
  `player_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `points` int(11) DEFAULT NULL,
  `rebounds` int(11) DEFAULT NULL,
  `assists` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE `players` (
  `player_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `position` varchar(10) NOT NULL,
  `height` varchar(10) NOT NULL,
  `weight` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `team_id` int(11) NOT NULL,
  `team_name` varchar(50) NOT NULL,
  `city` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','editor','user','guest') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `role`) VALUES
(2, 'ekatigbak43', '$2y$10$h1o2qv4EmktRAHkZrbgsoO5xOV2n8Wzf0DYR8YTfmDQJXiEGJEeRi', 'admin'),
(3, 'raptorsfan1', '$2y$10$BMtloV61eW2F3S3RkiVfEe/Je372uLCzo3q6YXcTa0G5MHijDlRam', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`article_id`),
  ADD KEY `author_id` (`author_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `article_id` (`article_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `game_logs`
--
ALTER TABLE `game_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `player_id` (`player_id`);

--
-- Indexes for table `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`player_id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`team_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `article_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `game_logs`
--
ALTER TABLE `game_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `team_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`article_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `game_logs`
--
ALTER TABLE `game_logs`
  ADD CONSTRAINT `game_logs_ibfk_1` FOREIGN KEY (`player_id`) REFERENCES `players` (`player_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
