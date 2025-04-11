-- phpMyAdmin SQL Dump
-- version 4.4.15.9
-- https://www.phpmyadmin.net
--
-- Host: 
-- Generation Time: Apr 9, 2025 at 02:23 PM
-- Server version: 5.6.37
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cinema_db`
--

-- --------------------------------------------------------

-- Table structure for table `scheduletable`

	CREATE TABLE `scheduletable` (
 `scheduleID` int(11) NOT NULL AUTO_INCREMENT,
 `movieID` int(11) NOT NULL,
 `theatre` varchar(50) NOT NULL,
 `scheduleDate` date NOT NULL,
 `scheduleTime` time NOT NULL,
 PRIMARY KEY (`scheduleID`),
 KEY `movieID` (`movieID`),
 CONSTRAINT `scheduletable_ibfk_1` FOREIGN KEY (`movieID`) REFERENCES `movietable` (`movieID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

--
-- Add nullable user_id column first
ALTER TABLE `bookingTable` 
ADD COLUMN `user_id` INT(11) NULL AFTER `bookingPNumber`;

-- Set default user ID (1 for admin) for existing bookings
UPDATE `bookingTable` SET `user_id` = 1 WHERE `user_id` IS NULL;

-- Modify column to be NOT NULL
ALTER TABLE `bookingTable` 
MODIFY COLUMN `user_id` INT(11) NOT NULL;

-- Add foreign key constraint with CASCADE delete
ALTER TABLE `bookingTable` 
ADD CONSTRAINT `fk_booking_user`
FOREIGN KEY (`user_id`) 
REFERENCES `users`(`id`)
ON DELETE CASCADE
ON UPDATE CASCADE;

-- Table structure for table `bookingTable`
--

CREATE TABLE IF NOT EXISTS `bookingTable` (
  `bookingID` int(11) NOT NULL,
  `movieName` varchar(100) DEFAULT NULL,
  `bookingTheatre` varchar(100) NOT NULL,
  `bookingType` varchar(100) DEFAULT NULL,
  `bookingDate` varchar(50) NOT NULL,
  `bookingTime` varchar(50) NOT NULL,
  `bookingFName` varchar(100) NOT NULL,
  `bookingLName` varchar(100) DEFAULT NULL,
  `bookingPNumber` varchar(12) NOT NULL,
  `user_id` int(11) NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bookingTable`
--

INSERT INTO `bookingTable` (`bookingID`, `movieName`, `bookingTheatre`, `bookingType`, `bookingDate`, `bookingTime`, `bookingFName`, `bookingLName`, `bookingPNumber`) VALUES
(19, 'Captain Marvel', 'main-hall', '3d', '13-3', '15-00', 'mandip', 'gyawali', '010152658930'),
(22, 'The Lego Movie', 'vip-hall', 'imax', '13-3', '18-00', 'megh', 'raj', '01589965');

-- --------------------------------------------------------
--
-- Table structure for table `users`
--


	CREATE TABLE `users` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `username` varchar(50) NOT NULL,
 `email` varchar(100) NOT NULL,
 `password` varchar(255) NOT NULL,
 `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
 `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
 PRIMARY KEY (`id`),
 UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
--
-- Table structure for table `feedbackTable`
--

CREATE TABLE IF NOT EXISTS `feedbackTable` (
  `msgID` int(12) NOT NULL,
  `senderfName` varchar(50) NOT NULL,
  `senderlName` varchar(50) DEFAULT NULL,
  `sendereMail` varchar(100) NOT NULL,
  `senderfeedback` varchar(500) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `feedbackTable`
--

INSERT INTO `feedbackTable` (`msgID`, `senderfName`, `senderlName`, `sendereMail`, `senderfeedback`) VALUES
(1, 'Ram', 'magar', 'ram@mail.com', 'Hello '),
(2, 'hari', 'rai', 'hari@gmail.com', 'world');

-- --------------------------------------------------------

--
-- Table structure for table `movieTable`
--

CREATE TABLE IF NOT EXISTS `movieTable` (
  `movieID` int(11) NOT NULL,
  `movieImg` varchar(150) NOT NULL,
  `movieTitle` varchar(100) NOT NULL,
  `movieGenre` varchar(50) NOT NULL,
  `movieDuration` int(11) NOT NULL,
  `movieRelDate` date NOT NULL,
  `movieDirector` varchar(50) NOT NULL,
  `movieActors` varchar(150) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;


ALTER TABLE movieTable 
ADD COLUMN movieDescription TEXT AFTER movieGenre,
ADD COLUMN movieTrailerLink VARCHAR(255) AFTER movieDescription;

--
-- Dumping data for table `movieTable`
--

INSERT INTO `movieTable` (`movieID`, `movieImg`, `movieTitle`, `movieGenre`, `movieDuration`, `movieRelDate`, `movieDirector`, `movieActors`) VALUES
(1, 'img/movie-poster-1.jpg', 'Captain Marvel', ' Action, Adventure, Sci-Fi ', 220, '2018-10-18', 'Anna Boden, Ryan Fleck', 'Brie Larson, Samuel L. Jackson, Ben Mendelsohn'),
(2, 'img/movie-poster-2.jpg', 'Qarmat Bitamrmat  ', 'Comedy', 110, '2018-10-18', 'Assad Fouladkar', 'Ahmed Adam, Bayyumy Fouad, Salah Abdullah , Entsar, Dina Fouad '),
(3, 'img/movie-poster-3.jpg', 'The Lego Movie', 'Animation, Action, Adventure', 110, '2014-02-07', 'Phil Lord, Christopher Miller', 'Chris Pratt, Will Ferrell, Elizabeth Banks'),
(4, 'img/movie-poster-4.jpg', 'Nadi Elregal Elserri ', 'Comedy', 105, '2019-01-23', ' Ayman Uttar', 'Karim Abdul Aziz, Ghada Adel, Maged El Kedwany, Nesreen Tafesh, Bayyumy Fouad, Moataz El Tony '),
(5, 'img/movie-poster-5.jpg', 'VICE', 'Biography, Comedy, Drama', 132, '2018-12-25', 'Adam McKay', 'Christian Bale, Amy Adams, Steve Carell'),
(6, 'img/movie-poster-6.jpg', 'The Vanishing', 'Crime, Mystery, Thriller', 107, '2019-01-04', 'Kristoffer Nyholm', 'Gerard Butler, Peter Mullan, Connor Swindells');


INSERT INTO `movieTable` (`movieID`, `movieImg`, `movieTitle`, `movieGenre`, `movieDuration`, `movieDescription`, `movieTrailerLink`, `movieRelDate`, `movieDirector`, `movieActors`) 
VALUES
(1, 'image_url_1.jpg', 'The Shawshank Redemption', 'Drama', 142, 'Two imprisoned men form a deep bond while serving time at Shawshank State Prison.', 'https://www.youtube.com/watch?v=6hB3S9bIaco', '1994-09-23', 'Frank Darabont', 'Tim Robbins, Morgan Freeman'),
(2, 'image_url_2.jpg', 'The Godfather', 'Crime, Drama', 175, 'The aging patriarch of an organized crime dynasty transfers control of his clandestine empire to his reluctant son.', 'https://www.youtube.com/watch?v=sY1S34973zA', '1972-03-24', 'Francis Ford Coppola', 'Marlon Brando, Al Pacino'),
(3, 'image_url_3.jpg', 'The Dark Knight', 'Action, Crime, Drama', 152, 'Batman faces the Joker, a criminal mastermind who seeks to bring Gotham City to its knees.', 'https://www.youtube.com/watch?v=EXeTwQWrcwY', '2008-07-18', 'Christopher Nolan', 'Christian Bale, Heath Ledger'),
(4, 'image_url_4.jpg', 'Inception', 'Action, Adventure, Sci-Fi', 148, 'A thief who steals corporate secrets through the use of dream-sharing technology is given the chance to have his criminal history erased.', 'https://www.youtube.com/watch?v=YoHD9XEInc0', '2010-07-16', 'Christopher Nolan', 'Leonardo DiCaprio, Joseph Gordon-Levitt'),
(5, 'image_url_5.jpg', 'Forrest Gump', 'Drama, Romance', 142, 'The presidencies of Kennedy and Johnson, the Vietnam War, the civil rights movement, and other historical events unfold from the perspective of an Alabama man.', 'https://www.youtube.com/watch?v=bLvqoHBptjg', '1994-07-06', 'Robert Zemeckis', 'Tom Hanks, Robin Wright'),
(6, 'image_url_6.jpg', 'Fight Club', 'Drama', 139, 'An insomniac office worker and a soap salesman build a global organization to help vent male aggression.', 'https://www.youtube.com/watch?v=O8Kk7XJfZyA', '1999-10-15', 'David Fincher', 'Brad Pitt, Edward Norton'),
(7, 'image_url_7.jpg', 'The Matrix', 'Action, Sci-Fi', 136, 'A computer hacker learns from mysterious rebels about the true nature of his reality and his role in the war against its controllers.', 'https://www.youtube.com/watch?v=vKQi3bBA1y8', '1999-03-31', 'The Wachowskis', 'Keanu Reeves, Laurence Fishburne'),
(8, 'image_url_8.jpg', 'The Lord of the Rings: The Return of the King', 'Action, Adventure, Drama', 201, 'Gandalf and Aragorn lead the World of Men against Sauron’s forces in the ultimate battle for Middle-earth.', 'https://www.youtube.com/watch?v=3K9lNH0JbVg', '2003-12-17', 'Peter Jackson', 'Elijah Wood, Ian McKellen'),
(9, 'image_url_9.jpg', 'Pulp Fiction', 'Crime, Drama', 154, 'The lives of two mob hitmen, a boxer, a gangster’s wife, and a pair of diner bandits intertwine in four tales of violence and redemption.', 'https://www.youtube.com/watch?v=s7EdQ4FqbhY', '1994-10-14', 'Quentin Tarantino', 'John Travolta, Uma Thurman'),
(10, 'image_url_10.jpg', 'Schindler\'s List', 'Biography, Drama, History', 195, 'In German-occupied Poland during World War II, Oskar Schindler gradually becomes concerned for his Jewish workforce after witnessing their persecution by the Nazis.', 'https://www.youtube.com/watch?v=JdQfZmgXplA', '1993-12-15', 'Steven Spielberg', 'Liam Neeson, Ben Kingsley'),
(11, 'image_url_11.jpg', 'The Silence of the Lambs', 'Crime, Drama, Thriller', 118, 'A young FBI cadet must confide in an incarcerated and manipulative killer to receive his help on catching another serial killer who skins his victims.', 'https://www.youtube.com/watch?v=RuX2WcJSS3I', '1991-02-14', 'Jonathan Demme', 'Jodie Foster, Anthony Hopkins'),
(12, 'image_url_12.jpg', 'Gladiator', 'Action, Adventure, Drama', 155, 'A betrayed Roman general sets out to exact revenge against the corrupt emperor who murdered his family and sent him into slavery.', 'https://www.youtube.com/watch?v=owK1qxDselE', '2000-05-05', 'Ridley Scott', 'Russell Crowe, Joaquin Phoenix'),
(13, 'image_url_13.jpg', 'Titanic', 'Drama, Romance', 195, 'A seventeen-year-old aristocrat falls in love with a kind but poor artist aboard the luxurious, ill-fated R.M.S. Titanic.', 'https://www.youtube.com/watch?v=kVrqfYjkTdQ', '1997-12-19', 'James Cameron', 'Leonardo DiCaprio, Kate Winslet'),
(14, 'image_url_14.jpg', 'Avatar', 'Action, Adventure, Fantasy', 162, 'A paraplegic Marine dispatched to the moon Pandora on a unique mission becomes torn between following his orders and protecting the world he feels is his home.', 'https://www.youtube.com/watch?v=5PSNL1qE6VY', '2009-12-18', 'James Cameron', 'Sam Worthington, Zoe Saldana'),
(15, 'image_url_15.jpg', 'The Lion King', 'Animation, Adventure, Drama', 88, 'Lion cub and future king Simba searches for his identity. His journey takes him to the African savanna where he must face his past.', 'https://www.youtube.com/watch?v=4sj1MT05lAA', '1994-06-15', 'Roger Allers, Rob Minkoff', 'Matthew Broderick, James Earl Jones');


-- Table structure for table `auth_tokens`
CREATE TABLE IF NOT EXISTS `auth_tokens` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `token_hash` VARCHAR(255) NOT NULL,
    `expires_at` DATETIME NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    INDEX (`token_hash`),
    INDEX (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookingTable`
--
ALTER TABLE `bookingTable`
  ADD PRIMARY KEY (`bookingID`),
  ADD UNIQUE KEY `bookingID` (`bookingID`),
  ADD KEY `bookingID_2` (`bookingID`),
  ADD KEY `bookingID_3` (`bookingID`),
  ADD KEY `bookingID_4` (`bookingID`);

--
-- Indexes for table `feedbackTable`
--
ALTER TABLE `feedbackTable`
  ADD PRIMARY KEY (`msgID`),
  ADD UNIQUE KEY `msgID` (`msgID`);

--
-- Indexes for table `movieTable`
--
ALTER TABLE `movieTable`
  ADD PRIMARY KEY (`movieID`),
  ADD UNIQUE KEY `movieID` (`movieID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookingTable`
--
ALTER TABLE `bookingTable`
  MODIFY `bookingID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `feedbackTable`
--
ALTER TABLE `feedbackTable`
  MODIFY `msgID` int(12) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `movieTable`
--
ALTER TABLE `movieTable`
  MODIFY `movieID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
