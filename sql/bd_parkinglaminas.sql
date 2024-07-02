CREATE DATABASE  IF NOT EXISTS `db_parkinglaminas` /*!40100 DEFAULT CHARACTER SET utf8mb3 */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `db_parkinglaminas`;
-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: localhost    Database: db_parkinglaminas
-- ------------------------------------------------------
-- Server version	8.0.37

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `access_levels`
--

DROP TABLE IF EXISTS `access_levels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `access_levels` (
  `access_level_id` int NOT NULL AUTO_INCREMENT,
  `level_name` varchar(50) NOT NULL,
  PRIMARY KEY (`access_level_id`),
  UNIQUE KEY `level_name` (`level_name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `access_levels`
--

LOCK TABLES `access_levels` WRITE;
/*!40000 ALTER TABLE `access_levels` DISABLE KEYS */;
INSERT INTO `access_levels` VALUES (1,'admin'),(2,'usuario');
/*!40000 ALTER TABLE `access_levels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `financial_reports`
--

DROP TABLE IF EXISTS `financial_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `financial_reports` (
  `report_id` int NOT NULL AUTO_INCREMENT,
  `report_date` date NOT NULL,
  `total_income` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`report_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `financial_reports`
--

LOCK TABLES `financial_reports` WRITE;
/*!40000 ALTER TABLE `financial_reports` DISABLE KEYS */;
/*!40000 ALTER TABLE `financial_reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parking_spots`
--

DROP TABLE IF EXISTS `parking_spots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `parking_spots` (
  `spot_id` int NOT NULL AUTO_INCREMENT,
  `spot_number` varchar(10) NOT NULL,
  `is_available` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`spot_id`),
  UNIQUE KEY `spot_number` (`spot_number`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parking_spots`
--

LOCK TABLES `parking_spots` WRITE;
/*!40000 ALTER TABLE `parking_spots` DISABLE KEYS */;
INSERT INTO `parking_spots` VALUES (1,'A1',0),(2,'A2',0),(3,'A3',1),(4,'A4',1),(5,'A5',1),(6,'A6',1),(7,'A7',1),(8,'A8',1),(9,'A9',1),(10,'A10',1),(11,'B1',1),(12,'B2',1),(13,'B3',1),(14,'B4',1),(15,'B5',1),(16,'B6',1),(17,'B7',1),(18,'B8',1),(19,'B9',1),(20,'B10',1),(21,'C1',1),(22,'C2',1),(23,'C3',1),(24,'C4',1),(25,'C5',1),(26,'C6',1),(27,'C7',0),(28,'C8',1),(29,'C9',1),(30,'C10',1),(31,'D1',1),(32,'D2',1),(33,'D3',1),(34,'D4',1),(35,'D5',1),(36,'D6',1),(37,'D7',1),(38,'D8',1),(39,'D9',1),(40,'D10',1),(41,'E1',1),(42,'E2',1),(43,'E3',1),(44,'E4',1),(45,'E5',1),(46,'E6',1),(47,'E7',1),(48,'E8',1),(49,'E9',1),(50,'E10',1);
/*!40000 ALTER TABLE `parking_spots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plans`
--

DROP TABLE IF EXISTS `plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `plans` (
  `plan_id` int NOT NULL AUTO_INCREMENT,
  `plan_name` varchar(50) NOT NULL,
  `plan_type` enum('pre-pago','pos-pago') NOT NULL,
  `rate` decimal(10,2) NOT NULL,
  PRIMARY KEY (`plan_id`),
  UNIQUE KEY `plan_name` (`plan_name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plans`
--

LOCK TABLES `plans` WRITE;
/*!40000 ALTER TABLE `plans` DISABLE KEYS */;
INSERT INTO `plans` VALUES (1,'Pré-pago','pre-pago',6.50),(2,'Pós-pago','pos-pago',8.00);
/*!40000 ALTER TABLE `plans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `psusage`
--

DROP TABLE IF EXISTS `psusage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `psusage` (
  `usage_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `spot_id` int NOT NULL,
  `check_in_time` timestamp NOT NULL,
  `check_out_time` timestamp NULL DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`usage_id`),
  KEY `user_id` (`user_id`),
  KEY `spot_id` (`spot_id`),
  CONSTRAINT `psusage_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `psusage_ibfk_2` FOREIGN KEY (`spot_id`) REFERENCES `parking_spots` (`spot_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `psusage`
--

LOCK TABLES `psusage` WRITE;
/*!40000 ALTER TABLE `psusage` DISABLE KEYS */;
INSERT INTO `psusage` VALUES (1,4,1,'2024-07-03 19:00:00','2024-07-03 19:30:00',0.00),(2,4,1,'2024-07-02 19:15:00','2024-07-02 19:30:00',0.00),(3,4,2,'2024-07-24 10:00:00','2024-07-25 22:00:00',306.00),(4,5,27,'2024-07-24 10:00:00','2024-07-25 22:00:00',360.00);
/*!40000 ALTER TABLE `psusage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(80) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `access_level_id` int NOT NULL,
  `plan_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  KEY `access_level_id` (`access_level_id`),
  KEY `plan_id` (`plan_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`access_level_id`) REFERENCES `access_levels` (`access_level_id`),
  CONSTRAINT `users_ibfk_2` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`plan_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (4,'1','alteracaoteste@teste.com','$2y$10$rZ6i.uZr12ZlVS.PCHksXOkeX1U1Vs0GulydLgLpFIJBo0Gm0I0QO',2,1,'2024-06-26 18:10:23'),(5,'2','teste2@teste.com','$2y$10$.bkA56Yq8IcCnET/H746BuhWaYKSe5XsvnDzvCCHEnV6gl3Df8wSa',2,2,'2024-06-26 18:38:48'),(6,'3','teste3@teste.com','$2y$10$arnQWk3QdP.fJwxQr8Tpge78UcJ8L49vx9z6nA4TFDKcOx8D31fYm',2,1,'2024-06-26 18:39:18'),(8,'fulano','fulano@gmail.com','$2y$10$XiuGswmyDnymRn2pSCBUq.UC6BPizlvKYkiGbrVjBgtHdklf50v8C',2,2,'2024-06-26 19:21:17'),(9,'123','123@123.com','$2y$10$lk89ZqHXFdT0LS6sru3zcukZl06ZztaGUF/OF.54T/hXnDtfGEz8a',2,2,'2024-06-26 19:51:54'),(10,'4','4@4.com','$2y$10$6o7BMJ1BHx9mN7lfMtXx6O9PuoNJHQwCNAj4wJap3YWHzxcQnzLwS',2,1,'2024-06-26 20:39:34'),(12,'testeplanos','teste5@teste.com','$2y$10$1HXNYprarvynzRDRQ.EXnuu4D3URAQO.9T1zqeSgDXqvYKJsBT2Oy',2,1,'2024-07-01 18:59:27'),(13,'teste','teste@123.com','$2y$10$NWs1/1jNCM/KzOYwShLqNOlvaZ/F8Jk4xiLD5n.e8mRlumQt./tSa',2,2,'2024-07-01 20:47:20'),(21,'admin','admin@admin.com','$2y$10$8MLaptZjSJ0D.7KHgHKsLOf.2LOldcy1MhfqcaaIn5kEJE/wkKW/C',1,1,'2024-07-02 20:29:00');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicle_flow`
--

DROP TABLE IF EXISTS `vehicle_flow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vehicle_flow` (
  `flow_id` int NOT NULL AUTO_INCREMENT,
  `vehicle_plate` varchar(10) NOT NULL,
  `check_in_time` timestamp NOT NULL,
  `check_out_time` timestamp NULL DEFAULT NULL,
  `spot_id` int DEFAULT NULL,
  PRIMARY KEY (`flow_id`),
  KEY `spot_id` (`spot_id`),
  CONSTRAINT `vehicle_flow_ibfk_1` FOREIGN KEY (`spot_id`) REFERENCES `parking_spots` (`spot_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicle_flow`
--

LOCK TABLES `vehicle_flow` WRITE;
/*!40000 ALTER TABLE `vehicle_flow` DISABLE KEYS */;
/*!40000 ALTER TABLE `vehicle_flow` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `vw_usuario`
--

DROP TABLE IF EXISTS `vw_usuario`;
/*!50001 DROP VIEW IF EXISTS `vw_usuario`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_usuario` AS SELECT 
 1 AS `username`,
 1 AS `plan_name`,
 1 AS `level_name`*/;
SET character_set_client = @saved_cs_client;

--
-- Final view structure for view `vw_usuario`
--

/*!50001 DROP VIEW IF EXISTS `vw_usuario`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_usuario` AS select `users`.`username` AS `username`,`plans`.`plan_name` AS `plan_name`,`access_levels`.`level_name` AS `level_name` from ((`users` join `plans` on((`plans`.`plan_id` = `users`.`plan_id`))) join `access_levels` on((`access_levels`.`access_level_id` = `users`.`access_level_id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-07-02 19:58:10
