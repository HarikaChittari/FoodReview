-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 02, 2024 at 03:38 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dinescout_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `admin_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `email`, `password`, `first_name`, `last_name`) VALUES
(1, 'bsmith', 'bsmith@example.com', '$2y$10$O/462ujCBpg0GxD9NdXHCOL.n.9HQVBGWQ7EHxt6wKwoo2.MB4Dry', 'Bob', 'Smith'),
(3, 'KyleS', 'kyle@dinescout.com', '$2y$10$u03Ui3OhaATmqCs73BeIxu1Rqb4CNjcuUMPshG1e8hVZHi75gSQti', 'Kyle', 'Stucki');

-- --------------------------------------------------------

--
-- Table structure for table `followership`
--

DROP TABLE IF EXISTS `followership`;
CREATE TABLE IF NOT EXISTS `followership` (
  `follow_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `restaurant_id` int DEFAULT NULL,
  `follow_date` date DEFAULT NULL,
  PRIMARY KEY (`follow_id`),
  KEY `user_id` (`user_id`),
  KEY `restaurant_id` (`restaurant_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `followership`
--

INSERT INTO `followership` (`follow_id`, `user_id`, `restaurant_id`, `follow_date`) VALUES
(1, 1, 1, '2024-11-06'),
(2, 1, 2, '2024-11-06'),
(3, 1, 3, '2024-11-06'),
(4, 1, 4, '2024-11-06'),
(5, 1, 5, '2024-11-06');

-- --------------------------------------------------------

--
-- Table structure for table `food_item`
--

DROP TABLE IF EXISTS `food_item`;
CREATE TABLE IF NOT EXISTS `food_item` (
  `food_item_id` int NOT NULL AUTO_INCREMENT,
  `restaurant_id` int DEFAULT NULL,
  `dish_name` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `description` text,
  `ingredients` text,
  `nutritional_information` text,
  PRIMARY KEY (`food_item_id`),
  KEY `restaurant_id` (`restaurant_id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `food_item`
--

INSERT INTO `food_item` (`food_item_id`, `restaurant_id`, `dish_name`, `price`, `description`, `ingredients`, `nutritional_information`) VALUES
(1, 1, 'Burger Deluxe', 10.99, 'A delicious burger with cheese and veggies', 'Beef, Cheese, Lettuce, Tomato', '500 kcal'),
(2, 1, 'Veggie Salad', 7.99, 'Fresh mix of vegetables', 'Lettuce, Tomato, Cucumber, Carrot', '150 kcal'),
(3, 1, 'Spaghetti', 12.99, 'Classic Italian spaghetti with tomato sauce', 'Pasta, Tomato Sauce, Parmesan', '600 kcal'),
(4, 1, 'Chicken Wings', 9.99, 'Spicy grilled chicken wings', 'Chicken, Spices, Sauce', '300 kcal'),
(5, 1, 'Tacos', 8.99, 'Authentic Mexican style tacos', 'Tortilla, Beef, Salsa, Cheese', '350 kcal'),
(6, 2, 'Sushi Roll', 12.99, 'Fresh sushi with rice and seaweed', 'Rice, Fish, Seaweed', '250 kcal'),
(7, 2, 'Tempura', 9.99, 'Crispy tempura shrimp', 'Shrimp, Batter, Oil', '200 kcal'),
(8, 2, 'Miso Soup', 4.99, 'Traditional miso soup', 'Miso, Seaweed, Tofu', '100 kcal'),
(9, 2, 'Sashimi Platter', 14.99, 'Assorted fresh sashimi', 'Assorted Fish', '300 kcal'),
(10, 2, 'Teriyaki Chicken', 11.99, 'Grilled chicken with teriyaki sauce', 'Chicken, Teriyaki Sauce', '400 kcal'),
(11, 3, 'Margherita Pizza', 10.99, 'Classic pizza with tomatoes and mozzarella', 'Tomato, Mozzarella, Basil', '550 kcal'),
(12, 3, 'Pasta Carbonara', 13.99, 'Creamy pasta with bacon', 'Pasta, Bacon, Cream, Parmesan', '700 kcal'),
(13, 3, 'Caesar Salad', 8.99, 'Caesar salad with croutons', 'Lettuce, Croutons, Caesar Dressing', '200 kcal'),
(14, 3, 'Garlic Bread', 5.99, 'Bread with garlic butter', 'Bread, Garlic, Butter', '150 kcal'),
(15, 3, 'Lamb Chops', 19.99, 'Grilled lamb chops with herbs', 'Lamb, Herbs', '600 kcal'),
(16, 4, 'Steak Frites', 21.99, 'Steak with fries', 'Beef, Potatoes, Salt', '800 kcal'),
(17, 4, 'French Onion Soup', 7.99, 'Classic French onion soup', 'Onions, Cheese, Bread, Beef Broth', '250 kcal'),
(18, 4, 'Coq au Vin', 17.99, 'Chicken braised with wine', 'Chicken, Wine, Mushrooms', '700 kcal'),
(19, 4, 'Creme Brulee', 6.99, 'Vanilla custard with caramelized sugar', 'Cream, Vanilla, Sugar', '350 kcal'),
(20, 4, 'Escargot', 9.99, 'Snails with garlic butter', 'Snails, Garlic, Butter', '200 kcal'),
(21, 5, 'Chicken Sandwich', 8.99, 'Grilled chicken sandwich with lettuce and tomato', 'Chicken, Lettuce, Tomato, Bread', '400 kcal'),
(22, 5, 'Cheeseburger', 9.99, 'Burger with cheese and pickles', 'Beef, Cheese, Pickles', '500 kcal'),
(23, 5, 'Fries', 3.99, 'Crispy golden fries', 'Potatoes, Salt', '300 kcal'),
(24, 5, 'Milkshake', 4.99, 'Vanilla milkshake', 'Milk, Ice Cream, Vanilla', '400 kcal'),
(25, 5, 'Pancakes', 5.99, 'Fluffy pancakes with syrup', 'Flour, Eggs, Milk, Syrup', '500 kcal');

-- --------------------------------------------------------

--
-- Table structure for table `membership`
--

DROP TABLE IF EXISTS `membership`;
CREATE TABLE IF NOT EXISTS `membership` (
  `membership_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`membership_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `membership`
--

INSERT INTO `membership` (`membership_id`, `username`, `email`, `password`, `first_name`, `last_name`) VALUES
(1, 'foodcorner_member', 'member1@foodcorner.com', '$2y$10$1xb.hkls8vj1n9V60o94EeNP2pmIdiAduHEl3v5fsC2CPiR9G9R6y', 'John', 'Doe'),
(2, 'gourmethouse_member', 'member2@gourmethouse.com', '$2y$10$nhuPIUUcpbXcFcDemshYy.SjUD.VfYt.oBMhvJzHTx207q8lt3Ttm', 'Jane', 'Smith'),
(3, 'tastebuds_member', 'member3@tastebuds.com', '$2y$10$QjuqNqVdqFB9qhJnNrxHcebM0MnQ95kkeAFuDGbq3L/nQQvBz/3Im', 'Alex', 'Brown'),
(4, 'dinewine_member', 'member4@dinewine.com', '$2y$10$nk.LbFSyrTDiam3E2WzjZ.EtBO2UPQQw/ygKBpSUWM9zmUX37SHsy', 'Chris', 'White'),
(5, 'quickbites_member', 'member5@quickbites.com', '$2y$10$TgBbRdpGvbPPiCGLMC8C8.e4pxhdwaMpwqwAkfQ9EApHhnJWhFxWK', 'Sam', 'Green');

-- --------------------------------------------------------

--
-- Table structure for table `restaurant`
--

DROP TABLE IF EXISTS `restaurant`;
CREATE TABLE IF NOT EXISTS `restaurant` (
  `restaurant_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `operating_hours` varchar(100) DEFAULT NULL,
  `description` text,
  `membership_id` int DEFAULT NULL,
  PRIMARY KEY (`restaurant_id`),
  KEY `membership_id` (`membership_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `restaurant`
--

INSERT INTO `restaurant` (`restaurant_id`, `name`, `location`, `website`, `phone_number`, `email`, `operating_hours`, `description`, `membership_id`) VALUES
(1, 'The Food Corner', '123 Food St.', 'www.foodcorner.com', '123-456-7890', 'info@foodcorner.com', '9 AM - 9 PM', 'A place for great food.', 1),
(2, 'Gourmet House', '456 Gourmet Blvd', 'www.gourmethouse.com', '987-654-3210', 'contact@gourmethouse.com', '10 AM - 10 PM', 'Exquisite dishes and ambience.', 2),
(3, 'Taste Buds', '789 Taste Ave.', 'www.tastebuds.com', '321-654-9870', 'hello@tastebuds.com', '8 AM - 8 PM', 'Delight your taste buds here.', 3),
(4, 'Dine & Wine', '101 Dine Dr.', 'www.dinewine.com', '654-321-0987', 'support@dinewine.com', '12 PM - 12 AM', 'Fine dining experience.', 4),
(5, 'Quick Bites', '202 Quick Rd.', 'www.quickbites.com', '432-109-8765', 'service@quickbites.com', '7 AM - 7 PM', 'Quick and delicious meals.', 5);

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

DROP TABLE IF EXISTS `review`;
CREATE TABLE IF NOT EXISTS `review` (
  `review_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `food_item_id` int DEFAULT NULL,
  `rating` int DEFAULT NULL,
  `comment` text,
  `review_date` date DEFAULT NULL,
  PRIMARY KEY (`review_id`),
  KEY `user_id` (`user_id`),
  KEY `food_item_id` (`food_item_id`)
) ENGINE=MyISAM AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`review_id`, `user_id`, `food_item_id`, `rating`, `comment`, `review_date`) VALUES
(1, 1, 1, 5, 'Amazing burger! Will order again.', '2024-11-06'),
(2, 1, 2, 4, 'Very fresh salad, loved it!', '2024-11-06'),
(3, 1, 3, 4, 'Great spaghetti, very filling.', '2024-11-06'),
(4, 1, 4, 5, 'The wings are a must-try!', '2024-11-06'),
(5, 1, 5, 3, 'Tacos were decent, but could be spicier.', '2024-11-06'),
(6, 2, 1, 4, 'Good burger but could use more cheese.', '2024-11-06'),
(7, 2, 2, 5, 'Best salad I have ever had!', '2024-11-06'),
(8, 2, 3, 4, 'Spaghetti was delicious.', '2024-11-06'),
(9, 2, 4, 5, 'Absolutely loved the wings!', '2024-11-06'),
(10, 2, 5, 3, 'Tacos could be improved.', '2024-11-06'),
(11, 1, 6, 5, 'Fresh sushi and great taste.', '2024-11-06'),
(12, 1, 7, 4, 'Loved the crispy tempura.', '2024-11-06'),
(13, 1, 8, 3, 'Miso soup was okay.', '2024-11-06'),
(14, 1, 9, 5, 'Best sashimi I’ve ever had.', '2024-11-06'),
(15, 1, 10, 4, 'Teriyaki chicken was tasty.', '2024-11-06'),
(16, 2, 6, 4, 'Sushi was good, but a bit pricey.', '2024-11-06'),
(17, 2, 7, 5, 'Tempura was fantastic!', '2024-11-06'),
(18, 2, 8, 4, 'Miso soup was nice and warm.', '2024-11-06'),
(19, 2, 9, 5, 'Sashimi was fresh and tasty.', '2024-11-06'),
(20, 2, 10, 4, 'Chicken was tender and flavorful.', '2024-11-06'),
(21, 1, 11, 5, 'Best pizza I’ve had in ages!', '2024-11-06'),
(22, 1, 12, 4, 'Pasta was creamy and delicious.', '2024-11-06'),
(23, 1, 13, 3, 'Caesar salad was okay.', '2024-11-06'),
(24, 1, 14, 4, 'Garlic bread was delicious.', '2024-11-06'),
(25, 1, 15, 5, 'Lamb chops were amazing!', '2024-11-06'),
(26, 2, 11, 5, 'Loved the pizza!', '2024-11-06'),
(27, 2, 12, 4, 'Pasta was very filling.', '2024-11-06'),
(28, 2, 13, 3, 'Salad could use more dressing.', '2024-11-06'),
(29, 2, 14, 5, 'Garlic bread was perfect.', '2024-11-06'),
(30, 2, 15, 5, 'Lamb chops were cooked to perfection.', '2024-11-06'),
(31, 1, 16, 5, 'Steak was tender and flavorful.', '2024-11-06'),
(32, 1, 17, 4, 'Soup was warm and tasty.', '2024-11-06'),
(33, 1, 18, 5, 'Coq au Vin was amazing.', '2024-11-06'),
(34, 1, 19, 5, 'Creme Brulee was the perfect dessert.', '2024-11-06'),
(35, 1, 20, 3, 'Escargot was not my favorite.', '2024-11-06'),
(36, 2, 16, 5, 'Steak was excellent.', '2024-11-06'),
(37, 2, 17, 4, 'Soup had a great flavor.', '2024-11-06'),
(38, 2, 18, 5, 'Loved the Coq au Vin.', '2024-11-06'),
(39, 2, 19, 4, 'Creme Brulee was delightful.', '2024-11-06'),
(40, 2, 20, 3, 'Escargot was a bit too garlicky for me.', '2024-11-06'),
(41, 1, 21, 4, 'Chicken sandwich was great!', '2024-11-06'),
(42, 1, 22, 5, 'Cheeseburger was juicy and delicious.', '2024-11-06'),
(43, 1, 23, 4, 'Fries were crispy and golden.', '2024-11-06'),
(44, 1, 24, 3, 'Milkshake was a bit too sweet.', '2024-11-06'),
(45, 1, 25, 5, 'Pancakes were fluffy and perfect.', '2024-11-06'),
(46, 2, 21, 5, 'Loved the chicken sandwich!', '2024-11-06'),
(47, 2, 22, 4, 'Cheeseburger was good but too salty.', '2024-11-06'),
(48, 2, 23, 5, 'Fries were perfect!', '2024-11-06'),
(49, 2, 24, 4, 'Milkshake was refreshing.', '2024-11-06'),
(50, 2, 25, 5, 'Best pancakes I have had!', '2024-11-06');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `email`, `password`, `first_name`, `last_name`) VALUES
(1, 'pjones', 'pjones@example.com', '$2y$10$RK86i4fPUtKwCOaMACOWde1w57igcjruOVqFbqwLb5j5hqYqstad6', 'Patricia', 'Jones'),
(3, 'rFrandsen', 'rachel@user.com', '$2y$10$M.jO4uk84OuEazQSgJpu1eNrnkAbf2LhWMcsywLTa6HfGoxN3x54C', 'Rachel', 'Frandsen');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
