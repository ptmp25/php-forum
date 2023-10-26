-- MySQL dump 10.14  Distrib 5.5.68-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: phpforum
-- ------------------------------------------------------
-- Server version	5.5.68-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `role` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `admin_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `recipient_admin_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (30,27,'admin','root123',NULL,0,'aaaa','2023-10-26 11:19:50',27),(31,27,'admin','root123',NULL,0,'1111','2023-10-26 11:19:52',28),(32,27,'admin','root123',NULL,0,'ádasdasdasd','2023-10-26 11:25:30',27),(33,27,'admin','root123',NULL,0,'ádasdasdasd','2023-10-26 11:31:28',27),(34,28,'admin','admin123',NULL,0,'123123','2023-10-26 11:34:38',28),(35,28,'admin','admin123',NULL,0,'asd','2023-10-26 12:24:38',27),(36,28,'admin','admin123',NULL,0,'asd','2023-10-26 12:28:47',27),(37,28,'admin','admin123',NULL,0,'asd','2023-10-26 12:28:48',27),(38,28,'admin','admin123',NULL,0,'asd','2023-10-26 12:28:48',27),(39,29,'','123123',NULL,0,'123','2023-10-26 12:56:39',28),(40,29,'','123123',NULL,0,'123','2023-10-26 12:56:45',28),(41,29,'','123123',NULL,0,'111','2023-10-26 12:56:49',28),(42,29,'','123123',NULL,0,'1111111111111111111111111','2023-10-26 12:57:38',27),(43,29,'','123123',NULL,0,'22222222222222222222222222','2023-10-26 12:57:40',28);
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `topic_id` (`topic_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`),
  CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text,
  `user_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `image_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `fk_topic` (`topic_id`),
  CONSTRAINT `fk_topic` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`),
  CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`),
  CONSTRAINT `questions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questions`
--

LOCK TABLES `questions` WRITE;
/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
INSERT INTO `questions` VALUES (78,29,'asdasd','asdasd',28,'2023-10-26 07:41:12',''),(79,29,'123','123',27,'2023-10-26 08:08:07',''),(80,29,'asdasd','asdasd',27,'2023-10-26 08:21:42',''),(81,29,'dasd','asdasd',29,'2023-10-26 08:23:21',''),(82,29,'123123','123123',27,'2023-10-26 08:27:26',''),(83,29,'ád','ád',28,'2023-10-26 12:38:28','post upload/1698323908_01h - 0en23ot.png'),(84,29,'sdas','dasd',27,'2023-10-26 13:19:42','post upload/1698326382_01h - 0en23ot.png'),(85,29,'dfs','sdf',27,'2023-10-26 13:40:18','post upload/1698327618_01h - 0en23ot.png'),(86,29,'cdsdsd','csdd',27,'2023-10-26 13:43:51',''),(87,29,'ads','csdc',27,'2023-10-26 13:46:57',''),(88,29,'ads','csdc',27,'2023-10-26 13:49:07',''),(89,29,'ads','csdc',27,'2023-10-26 13:50:50',''),(90,29,'sdffd','ssdfsdfsdf',27,'2023-10-26 14:07:11','post upload/1698329231_00h - 0f6rgh9.png'),(91,29,'qwe','qwe',27,'2023-10-26 14:44:03','post upload/1698331443_logo.png');
/*!40000 ALTER TABLE `questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `replies`
--

DROP TABLE IF EXISTS `replies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `replies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reply_content` text,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `replies_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`),
  CONSTRAINT `replies_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `replies`
--

LOCK TABLES `replies` WRITE;
/*!40000 ALTER TABLE `replies` DISABLE KEYS */;
INSERT INTO `replies` VALUES (78,78,28,'Deleted reply','2023-10-26 07:45:06'),(79,78,28,'Deleted reply','2023-10-26 07:48:23'),(80,78,28,'asdasd','2023-10-26 07:58:01'),(81,78,28,'asdasda','2023-10-26 07:58:03'),(82,78,28,'asdasd','2023-10-26 07:58:04'),(83,78,28,'asdasd','2023-10-26 07:58:06'),(84,78,28,'asdasd','2023-10-26 07:58:08'),(85,78,27,'123123','2023-10-26 08:08:11'),(86,79,27,'Deleted reply','2023-10-26 08:13:26'),(87,80,29,'asdasd','2023-10-26 08:23:24'),(88,82,27,'123123123123','2023-10-26 08:27:29');
/*!40000 ALTER TABLE `replies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `topics`
--

DROP TABLE IF EXISTS `topics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `topics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `topics`
--

LOCK TABLES `topics` WRITE;
/*!40000 ALTER TABLE `topics` DISABLE KEYS */;
INSERT INTO `topics` VALUES (29,'asd',NULL,27),(30,'123',NULL,27);
/*!40000 ALTER TABLE `topics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` longtext NOT NULL,
  `password` longtext NOT NULL,
  `email` mediumtext NOT NULL,
  `date` datetime NOT NULL,
  `replies` int(11) NOT NULL,
  `topics` int(11) NOT NULL,
  `profile_pic` varchar(9999) NOT NULL DEFAULT '',
  `topic_count` int(11) NOT NULL DEFAULT '0',
  `role` varchar(20) NOT NULL,
  `question_count` int(11) DEFAULT '0',
  `reply_count` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (27,'root123','$2y$10$ddB97APN5mG9mgqLPmDoy.ZVp3EvwaBd0lPdt2qtGHt1Vhzhqws6C','loroqua0@gmail.com','2023-10-26 05:42:09',0,0,'purple.png',0,'admin',32,14),(28,'admin123','$2y$10$ymG.Rv0p.kERuv4Q6Fo.P.Grr4Uo950/shEfihFq.pEW8d6sieb5O','loroqua0@gmail.com','2023-10-26 05:45:22',0,0,'profilepic/test.png',0,'admin',6,21),(29,'123123','$2y$10$mncPLRbetZsjukCWuH4Do.EH1uqto9pudohmk.LgmJuscN03JVari','loroqua0@gmail.com','2023-10-26 08:59:55',0,0,'profilepic/test.png',0,'',1,2),(30,'baola','$2y$10$HUyr43Pgks6o19zXLZFD8ewBoPfZpGzHuOpQfasszzT.8lmiKTc.O','ptmaiphuong91@gmail.com','2023-10-27 00:13:28',0,0,'profilepic/test.png',0,'admin',0,0);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-10-27 10:13:33
