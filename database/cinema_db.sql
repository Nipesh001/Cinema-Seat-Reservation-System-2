-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Apr 19, 2025 at 07:45 AM
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
-- Database: `cinema_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admintable`
--

CREATE TABLE `admintable` (
  `adminID` int(11) NOT NULL,
  `adminUsername` varchar(50) NOT NULL,
  `adminPassword` varchar(255) NOT NULL,
  `adminEmail` varchar(100) NOT NULL,
  `adminFullName` varchar(100) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `isSuperAdmin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admintable`
--

INSERT INTO `admintable` (`adminID`, `adminUsername`, `adminPassword`, `adminEmail`, `adminFullName`, `createdAt`, `isSuperAdmin`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@example.com', 'System Administrator', '2025-04-11 09:15:57', 1),
(2, 'admin1', '$2y$10$I5AbQTpgPiyctP0vg.21CeNNg4hP2gtxFqwGrjxBwPlRlbJ.KbSui', 'admin1@dev.com', 'admin1', '2025-04-11 09:21:10', 0);

-- --------------------------------------------------------

--
-- Table structure for table `auth_tokens`
--

CREATE TABLE `auth_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token_hash` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_tokens`
--

INSERT INTO `auth_tokens` (`id`, `user_id`, `token_hash`, `expires_at`, `created_at`) VALUES
(8, 16, '$2y$10$RZTzP1xXxNL855lqm1DLV.APzzSsIJJHIjiPv4EkyuYBnxkifIBLW', '0000-00-00 00:00:00', '2025-04-11 04:11:21');

-- --------------------------------------------------------

--
-- Table structure for table `bookingtable`
--

CREATE TABLE `bookingtable` (
  `bookingID` int(11) NOT NULL,
  `movieName` varchar(100) DEFAULT NULL,
  `bookingTheatre` varchar(100) NOT NULL,
  `bookingType` varchar(100) DEFAULT NULL,
  `bookingDate` varchar(50) NOT NULL,
  `bookingTime` varchar(50) NOT NULL,
  `bookingFName` varchar(100) NOT NULL,
  `bookingLName` varchar(100) DEFAULT NULL,
  `bookingPNumber` varchar(12) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 1,
  `bookingSeats` varchar(255) NOT NULL DEFAULT 'N/A'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `bookingtable`
--

INSERT INTO `bookingtable` (`bookingID`, `movieName`, `bookingTheatre`, `bookingType`, `bookingDate`, `bookingTime`, `bookingFName`, `bookingLName`, `bookingPNumber`, `user_id`, `bookingSeats`) VALUES
(62, 'movie211', 'VIP Hall', '3d', '2025-04-12', '06:00', 'nipesh', 'giri', '7687687686', 16, 'N/A'),
(63, 'The Vanishing', 'VIP Hall', 'imax', '2025-04-12', '17:00', 'nip', 'giri', '7687687686', 16, 'N/A'),
(64, 'movie211', 'VIP Hall', '3d', '2025-04-12', '06:00', 'nipesh', 'ok', '7687687686', 16, 'N/A'),
(65, 'movie211', 'VIP Hall', 'imax', '2025-04-12', '06:00', 'nipesh', 'giri', '7687687686', 16, 'N/A'),
(67, 'movie111', 'Main Hall', '3d', '2025-04-12', '18:05', 'nipesh', 'giri', '9876543210', 16, 'N/A'),
(68, 'movie111', 'Main Hall', 'imax', '2025-04-12', '18:05', 'nipesh', 'giri', '7687687686', 16, 'N/A'),
(69, 'Inception', 'Main Hall', 'imax', '2025-04-14', '08:00', 'nipesh', 'giri', '9876543210', 16, 'N/A'),
(70, 'The Dark Knight', 'Secondary Hall', 'imax', '2025-04-13', '19:57', 'nipesh', 'giri', '7687687686', 16, 'N/A'),
(71, 'Gladiator', 'Secondary Hall', 'imax', '2025-04-15', '17:30', 'nipesh', 'giri', '9876543210', 16, 'N/A'),
(72, 'The Dark Knight', 'Secondary Hall', '2d', '2025-04-13', '19:57', 'nipesh', 'giri', '9876543210', 16, 'N/A'),
(73, 'The Lion King', 'Main Hall', 'imax', '2025-04-16', '09:10', 'nipesh', 'giri', '7687687686', 16, 'N/A'),
(74, 'The Godfather', 'Main Hall', '2d', '2025-04-13', '14:00', 'nipesh', 'ok', '9876543210', 16, 'N/A');

-- --------------------------------------------------------

--
-- Table structure for table `feedbacktable`
--

CREATE TABLE `feedbacktable` (
  `msgID` int(12) NOT NULL,
  `senderfName` varchar(50) NOT NULL,
  `senderlName` varchar(50) DEFAULT NULL,
  `sendereMail` varchar(100) NOT NULL,
  `senderfeedback` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `feedbacktable`
--

INSERT INTO `feedbacktable` (`msgID`, `senderfName`, `senderlName`, `sendereMail`, `senderfeedback`) VALUES
(22, 'nipesh', 'giri', 'nip@dev.com', 'what\'s up beautiful people, what\'s going on...'),
(36, 'nip', 'giri', 'nip@dev.com', 'hello world is everything all right'),
(39, 'nipesh', 'giri', 'nip@dev.com', 'what\'s up'),
(40, 'nipesh', 'ok', 'nip@dev.com', 'guess what?'),
(49, 'nipesh', 'giri', 'nip@dev.com', 'hey what\'s up what\'s going onnnn'),
(52, 'Ram', 'magar', 'Ram@gmail.com', 'very nice , beautiful , and very very good');

-- --------------------------------------------------------

--
-- Table structure for table `movietable`
--

CREATE TABLE `movietable` (
  `movieID` int(11) NOT NULL,
  `movieImg` varchar(150) NOT NULL,
  `movieTitle` varchar(100) NOT NULL,
  `movieGenre` varchar(50) NOT NULL,
  `movieDescription` text DEFAULT NULL,
  `movieTrailerLink` varchar(255) DEFAULT NULL,
  `movieDuration` int(11) NOT NULL,
  `movieRelDate` date NOT NULL,
  `movieDirector` varchar(50) NOT NULL,
  `movieActors` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `movietable`
--

INSERT INTO `movietable` (`movieID`, `movieImg`, `movieTitle`, `movieGenre`, `movieDescription`, `movieTrailerLink`, `movieDuration`, `movieRelDate`, `movieDirector`, `movieActors`) VALUES
(1, 'img/ShawShank.jpg', 'The Shawshank Redemption', 'Drama', 'Two imprisoned men form a deep bond while serving time at Shawshank State Prison.', 'https://www.youtube.com/watch?v=6hB3S9bIaco', 142, '1994-09-23', 'Frank Darabont', 'Tim Robbins, Morgan Freeman'),
(2, 'img/The Godfather.jpg', 'The Godfather', 'Crime, Drama', 'The aging patriarch of an organized crime dynasty transfers control of his clandestine empire to his reluctant son.', 'https://www.youtube.com/watch?v=sY1S34973zA', 175, '1972-03-24', 'Francis Ford Coppola', 'Marlon Brando, Al Pacino'),
(3, 'img/The Dark Knight.jpg', 'The Dark Knight', 'Action, Crime, Drama', 'Batman faces the Joker, a criminal mastermind who seeks to bring Gotham City to its knees.', 'https://www.youtube.com/watch?v=EXeTwQWrcwY', 152, '2008-07-18', 'Christopher Nolan', 'Christian Bale, Heath Ledger'),
(4, 'img/inception.jpg', 'Inception', 'Action, Adventure, Sci-Fi', 'A thief who steals corporate secrets through the use of dream-sharing technology is given the chance to have his criminal history erased.', 'https://www.youtube.com/watch?v=YoHD9XEInc0', 148, '2010-07-16', 'Christopher Nolan', 'Leonardo DiCaprio, Joseph Gordon-Levitt'),
(5, 'img/Forrest Gump.jpg', 'Forrest Gump', 'Drama, Romance', 'The presidencies of Kennedy and Johnson, the Vietnam War, the civil rights movement, and other historical events unfold from the perspective of an Alabama man.', 'https://www.youtube.com/watch?v=bLvqoHBptjg', 142, '1994-07-06', 'Robert Zemeckis', 'Tom Hanks, Robin Wright'),
(6, 'img/Fight Club.jpg', 'Fight Club', 'Drama', 'An insomniac office worker and a soap salesman build a global organization to help vent male aggression.', 'https://www.youtube.com/watch?v=O8Kk7XJfZyA', 139, '1999-10-15', 'David Fincher', 'Brad Pitt, Edward Norton'),
(7, 'img/The Matrix.jpg', 'The Matrix', 'Action, Sci-Fi', 'A computer hacker learns from mysterious rebels about the true nature of his reality and his role in the war against its controllers.', 'https://www.youtube.com/watch?v=vKQi3bBA1y8', 136, '1999-03-31', 'The Wachowskis', 'Keanu Reeves, Laurence Fishburne'),
(8, 'img/The Lord of the Rings.jpg', 'The Lord of the Rings: The Return of the King', 'Action, Adventure, Drama', 'Gandalf and Aragorn lead the World of Men against Sauron’s forces in the ultimate battle for Middle-earth.', 'https://www.youtube.com/watch?v=3K9lNH0JbVg', 201, '2003-12-17', 'Peter Jackson', 'Elijah Wood, Ian McKellen'),
(9, 'img/Pulp Fiction.jpg', 'Pulp Fiction', 'Crime, Drama', 'The lives of two mob hitmen, a boxer, a gangster’s wife, and a pair of diner bandits intertwine in four tales of violence and redemption.', 'https://www.youtube.com/watch?v=s7EdQ4FqbhY', 154, '1994-10-14', 'Quentin Tarantino', 'John Travolta, Uma Thurman'),
(10, 'img/Schindler\'s List.jpg', 'Schindler\'s List', 'Biography, Drama, History', 'In German-occupied Poland during World War II, Oskar Schindler gradually becomes concerned for his Jewish workforce after witnessing their persecution by the Nazis.', 'https://www.youtube.com/watch?v=JdQfZmgXplA', 195, '1993-12-15', 'Steven Spielberg', 'Liam Neeson, Ben Kingsley'),
(11, 'img/The Silence of the Lambs.jpg', 'The Silence of the Lambs', 'Crime, Drama, Thriller', 'A young FBI cadet must confide in an incarcerated and manipulative killer to receive his help on catching another serial killer who skins his victims.', 'https://www.youtube.com/watch?v=RuX2WcJSS3I', 118, '1991-02-14', 'Jonathan Demme', 'Jodie Foster, Anthony Hopkins'),
(12, 'img/Gladiator.jpg', 'Gladiator', 'Action, Adventure, Drama', 'A betrayed Roman general sets out to exact revenge against the corrupt emperor who murdered his family and sent him into slavery.', 'https://www.youtube.com/watch?v=owK1qxDselE', 155, '2000-05-05', 'Ridley Scott', 'Russell Crowe, Joaquin Phoenix'),
(13, 'img/Titanic.jpg', 'Titanic', 'Drama, Romance', 'A seventeen-year-old aristocrat falls in love with a kind but poor artist aboard the luxurious, ill-fated R.M.S. Titanic.', 'https://www.youtube.com/watch?v=kVrqfYjkTdQ', 195, '1997-12-19', 'James Cameron', 'Leonardo DiCaprio, Kate Winslet'),
(14, 'img/Avatar.jpg', 'Avatar', 'Action, Adventure, Fantasy', 'A paraplegic Marine dispatched to the moon Pandora on a unique mission becomes torn between following his orders and protecting the world he feels is his home.', 'https://www.youtube.com/watch?v=5PSNL1qE6VY', 162, '2009-12-18', 'James Cameron', 'Sam Worthington, Zoe Saldana'),
(15, 'img/The Lion King.jpg', 'The Lion King', 'Animation, Adventure, Drama', 'Lion cub and future king Simba searches for his identity. His journey takes him to the African savanna where he must face his past.', 'https://www.youtube.com/watch?v=4sj1MT05lAA', 88, '1994-06-15', 'Roger Allers, Rob Minkoff', 'Matthew Broderick, James Earl Jones'),
(25, 'img/Pulp Fiction.jpg', 'movie', 'action', 'jhjjijlk', 'https://www.youtube.com/watch?v=5PSNL1qE6VY', 22, '2025-04-03', ' Tim Miller', 'Keanu Reeves, Laurence Fishburne');

-- --------------------------------------------------------

--
-- Table structure for table `scheduletable`
--

CREATE TABLE `scheduletable` (
  `scheduleID` int(11) NOT NULL,
  `movieID` int(11) NOT NULL,
  `theatre` varchar(50) NOT NULL,
  `scheduleDate` date NOT NULL,
  `scheduleTime` time NOT NULL,
  `total_seats` int(11) DEFAULT 100,
  `seat_layout` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `scheduletable`
--

INSERT INTO `scheduletable` (`scheduleID`, `movieID`, `theatre`, `scheduleDate`, `scheduleTime`, `total_seats`, `seat_layout`) VALUES
(23, 1, 'VIP Hall', '2025-04-12', '16:00:00', 100, NULL),
(24, 1, 'Main Hall', '2025-04-12', '17:00:00', 100, NULL),
(25, 2, 'Main Hall', '2025-04-13', '14:00:00', 100, NULL),
(26, 3, 'Secondary Hall', '2025-04-13', '19:57:00', 100, NULL),
(27, 4, 'Main Hall', '2025-04-14', '08:00:00', 100, NULL),
(28, 4, 'VIP Hall', '2025-04-15', '04:00:00', 100, NULL),
(29, 8, 'VIP Hall', '2025-04-12', '17:15:00', 100, NULL),
(30, 12, 'Secondary Hall', '2025-04-15', '17:30:00', 100, NULL),
(31, 14, 'Secondary Hall', '2025-04-16', '08:00:00', 100, NULL),
(32, 15, 'Main Hall', '2025-04-16', '09:10:00', 100, NULL),
(33, 14, 'VIP Hall', '2025-04-14', '17:05:00', 100, NULL),
(35, 1, 'Main Hall', '2025-04-15', '17:20:00', 100, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `seatbookings`
--

CREATE TABLE `seatbookings` (
  `id` int(11) NOT NULL,
  `schedule_id` int(11) DEFAULT NULL,
  `seat_number` varchar(10) DEFAULT NULL,
  `is_booked` tinyint(1) DEFAULT 0,
  `booking_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seatbookings`
--

INSERT INTO `seatbookings` (`id`, `schedule_id`, `seat_number`, `is_booked`, `booking_id`) VALUES
(54, 19, '88', 1, 62),
(55, 19, '89', 1, 62),
(56, 18, '97', 1, 63),
(57, 18, '98', 1, 63),
(58, 19, '1', 1, 64),
(59, 19, '2', 1, 64),
(60, 19, '5', 1, 65),
(61, 19, '6', 1, 65),
(64, 20, '96', 1, 67),
(65, 20, '7', 1, 68),
(66, 20, '8', 1, 68),
(67, 27, '2', 1, 69),
(68, 27, '3', 1, 69),
(69, 27, '4', 1, 69),
(70, 27, '12', 1, 69),
(71, 26, '77', 1, 70),
(72, 26, '78', 1, 70),
(73, 26, '87', 1, 70),
(74, 26, '88', 1, 70),
(75, 30, '98', 1, 71),
(76, 30, '99', 1, 71),
(77, 30, '100', 1, 71),
(78, 26, '79', 1, 72),
(79, 26, '89', 1, 72),
(80, 26, '90', 1, 72),
(81, 32, '77', 1, 73),
(82, 32, '87', 1, 73),
(83, 32, '97', 1, 73),
(84, 25, '7', 1, 74),
(85, 25, '97', 1, 74);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `updated_at`) VALUES
(16, 'user1', 'user1@gmail.com', '$2y$10$Fr6E/ZroSsJ6bnp7TkJ6YuZJ8XhLNHmXSDr9uabUNGiFJhgR6Hdl6', '2025-04-11 03:22:53', '2025-04-11 03:22:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admintable`
--
ALTER TABLE `admintable`
  ADD PRIMARY KEY (`adminID`),
  ADD UNIQUE KEY `adminUsername` (`adminUsername`),
  ADD UNIQUE KEY `adminEmail` (`adminEmail`);

--
-- Indexes for table `auth_tokens`
--
ALTER TABLE `auth_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `token_hash` (`token_hash`),
  ADD KEY `expires_at` (`expires_at`);

--
-- Indexes for table `bookingtable`
--
ALTER TABLE `bookingtable`
  ADD PRIMARY KEY (`bookingID`),
  ADD UNIQUE KEY `bookingID` (`bookingID`),
  ADD KEY `bookingID_2` (`bookingID`),
  ADD KEY `bookingID_3` (`bookingID`),
  ADD KEY `bookingID_4` (`bookingID`),
  ADD KEY `fk_booking_user` (`user_id`);

--
-- Indexes for table `feedbacktable`
--
ALTER TABLE `feedbacktable`
  ADD PRIMARY KEY (`msgID`),
  ADD UNIQUE KEY `msgID` (`msgID`);

--
-- Indexes for table `movietable`
--
ALTER TABLE `movietable`
  ADD PRIMARY KEY (`movieID`),
  ADD UNIQUE KEY `movieID` (`movieID`);

--
-- Indexes for table `scheduletable`
--
ALTER TABLE `scheduletable`
  ADD PRIMARY KEY (`scheduleID`),
  ADD KEY `scheduletable_ibfk_1` (`movieID`);

--
-- Indexes for table `seatbookings`
--
ALTER TABLE `seatbookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seatbookings_ibfk_1` (`booking_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admintable`
--
ALTER TABLE `admintable`
  MODIFY `adminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `auth_tokens`
--
ALTER TABLE `auth_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `bookingtable`
--
ALTER TABLE `bookingtable`
  MODIFY `bookingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `feedbacktable`
--
ALTER TABLE `feedbacktable`
  MODIFY `msgID` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `movietable`
--
ALTER TABLE `movietable`
  MODIFY `movieID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `scheduletable`
--
ALTER TABLE `scheduletable`
  MODIFY `scheduleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `seatbookings`
--
ALTER TABLE `seatbookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_tokens`
--
ALTER TABLE `auth_tokens`
  ADD CONSTRAINT `auth_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bookingtable`
--
ALTER TABLE `bookingtable`
  ADD CONSTRAINT `fk_booking_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `scheduletable`
--
ALTER TABLE `scheduletable`
  ADD CONSTRAINT `scheduletable_ibfk_1` FOREIGN KEY (`movieID`) REFERENCES `movietable` (`movieID`) ON DELETE CASCADE;

--
-- Constraints for table `seatbookings`
--
ALTER TABLE `seatbookings`
  ADD CONSTRAINT `seatbookings_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookingtable` (`bookingID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
