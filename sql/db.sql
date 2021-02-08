-- MySQL dump 10.13  Distrib 5.7.33, for Linux (x86_64)
--
-- Host: localhost    Database: duolingros
-- ------------------------------------------------------
-- Server version	5.7.33-0ubuntu0.16.04.1

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
-- Table structure for table `book_lesson`
--

DROP TABLE IF EXISTS `book_lesson`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `book_lesson` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) DEFAULT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `subtitle` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D1A37F7E591CC992` (`course_id`),
  CONSTRAINT `FK_D1A37F7E591CC992` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `book_lesson`
--

LOCK TABLES `book_lesson` WRITE;
/*!40000 ALTER TABLE `book_lesson` DISABLE KEYS */;
INSERT INTO `book_lesson` VALUES (1,1,'First lesson','L\'eau est sale papi'),(2,1,'Leçon 2','Travailleur heiiin ?'),(3,1,'Leçon 3','C ki la meuf là'),(4,1,'Leçon 4','Moi j\'aime bien le café'),(5,1,'Leçon 5','Le médicament sera sucré. C\'est ouf'),(6,1,'Leçon 6','Malade be le zazakely'),(7,1,'Leçon 7','Le zébu est anorexique'),(18,2,'Slt c cora','Lafo be les radis aussi làààà'),(19,2,'Miriam la pétasse','Cora');
/*!40000 ALTER TABLE `book_lesson` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `complaint`
--

DROP TABLE IF EXISTS `complaint`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `complaint` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `translation_id` int(11) DEFAULT NULL,
  `proposition_text` varchar(400) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5F2732B59CAA2B25` (`translation_id`),
  CONSTRAINT `FK_5F2732B59CAA2B25` FOREIGN KEY (`translation_id`) REFERENCES `translation` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `complaint`
--

LOCK TABLES `complaint` WRITE;
/*!40000 ALTER TABLE `complaint` DISABLE KEYS */;
INSERT INTO `complaint` VALUES (35,525,'l\'eau et la maison ne sont pas sales','in progress'),(38,523,'non, il n\'est pas travailleur','in progress'),(40,57,'tsara ny rano sy trano madio','in progress');
/*!40000 ALTER TABLE `complaint` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `course`
--

DROP TABLE IF EXISTS `course`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `course` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `subtitle` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course`
--

LOCK TABLES `course` WRITE;
/*!40000 ALTER TABLE `course` DISABLE KEYS */;
INSERT INTO `course` VALUES (1,'Apprendre le malgache','avec Prosper'),(2,'Cora','et ses cheveux');
/*!40000 ALTER TABLE `course` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fos_user`
--

DROP TABLE IF EXISTS `fos_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fos_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `confirmation_token` varchar(180) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_957A647992FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_957A6479A0D96FBF` (`email_canonical`),
  UNIQUE KEY `UNIQ_957A6479C05FB297` (`confirmation_token`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fos_user`
--

LOCK TABLES `fos_user` WRITE;
/*!40000 ALTER TABLE `fos_user` DISABLE KEYS */;
INSERT INTO `fos_user` VALUES (35,'simon','simon','simon.ballu@gmail.com','simon.ballu@gmail.com',1,NULL,'$2y$13$DvDfHS/RyLX9.9MZLQ6vEOL3ZtpJ7aKf2MHntTeZbAL1TUG5l4Voq','2021-02-08 21:00:01',NULL,NULL,'a:1:{i:0;s:16:\"ROLE_SUPER_ADMIN\";}'),(36,'2','2','simon.ballu2@gmail.com','simon.ballu2@gmail.com',1,NULL,'$2y$13$DvDfHS/RyLX9.9MZLQ6vEOL3ZtpJ7aKf2MHntTeZbAL1TUG5l4Voq','2021-02-07 15:34:39',NULL,NULL,'a:1:{i:0;s:16:\"ROLE_SUPER_ADMIN\";}');
/*!40000 ALTER TABLE `fos_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `learning`
--

DROP TABLE IF EXISTS `learning`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `learning` (
  `user_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `good_streak` int(11) NOT NULL,
  `last_practice` datetime NOT NULL,
  `last_scores` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `difficulty_reached` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`lesson_id`),
  KEY `IDX_CF05A4C2A76ED395` (`user_id`),
  KEY `IDX_CF05A4C2CDF80196` (`lesson_id`),
  CONSTRAINT `FK_CF05A4C2A76ED395` FOREIGN KEY (`user_id`) REFERENCES `fos_user` (`id`),
  CONSTRAINT `FK_CF05A4C2CDF80196` FOREIGN KEY (`lesson_id`) REFERENCES `lesson` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `learning`
--

LOCK TABLES `learning` WRITE;
/*!40000 ALTER TABLE `learning` DISABLE KEYS */;
INSERT INTO `learning` VALUES (35,2,7,'2020-12-12 19:19:20','a:3:{i:0;s:6:\"100.00\";i:1;s:5:\"92.31\";i:2;s:5:\"92.86\";}',5),(35,3,0,'2019-11-26 22:47:59','a:1:{i:0;s:5:\"43.48\";}',0),(35,4,1,'2019-11-26 22:49:13','a:1:{i:0;s:6:\"100.00\";}',1),(35,5,0,'2019-11-26 22:57:53','a:1:{i:0;s:5:\"71.43\";}',0),(35,48,1,'2019-11-20 23:48:22','a:1:{i:0;s:6:\"100.00\";}',1);
/*!40000 ALTER TABLE `learning` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `learning_session`
--

DROP TABLE IF EXISTS `learning_session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `learning_session` (
  `user_id` int(11) DEFAULT NULL,
  `lesson_id` int(11) DEFAULT NULL,
  `difficulty` int(11) NOT NULL,
  `status` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `answers` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `started_at` datetime NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `submitted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_14E36D27A76ED395` (`user_id`),
  KEY `IDX_14E36D27CDF80196` (`lesson_id`),
  CONSTRAINT `FK_14E36D27A76ED395` FOREIGN KEY (`user_id`) REFERENCES `fos_user` (`id`),
  CONSTRAINT `FK_14E36D27CDF80196` FOREIGN KEY (`lesson_id`) REFERENCES `lesson` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=301 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `learning_session`
--

LOCK TABLES `learning_session` WRITE;
/*!40000 ALTER TABLE `learning_session` DISABLE KEYS */;
INSERT INTO `learning_session` VALUES (35,48,1,'started','N;','2021-02-08 20:57:59',294,NULL),(35,48,1,'started','N;','2021-02-08 20:58:06',295,NULL),(35,4,1,'started','N;','2021-02-08 20:58:08',296,NULL),(35,6,1,'started','N;','2021-02-08 20:58:11',297,NULL),(35,48,1,'started','N;','2021-02-08 20:58:24',298,NULL),(35,48,1,'started','N;','2021-02-08 20:59:45',299,NULL),(35,4,1,'started','N;','2021-02-08 20:59:46',300,NULL);
/*!40000 ALTER TABLE `learning_session` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lesson`
--

DROP TABLE IF EXISTS `lesson`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lesson` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_lesson_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(2000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lesson_order` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F87474F3DF5617D0` (`book_lesson_id`),
  KEY `IDX_F87474F3727ACA70` (`parent_id`),
  CONSTRAINT `FK_F87474F3727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `lesson` (`id`),
  CONSTRAINT `FK_F87474F3DF5617D0` FOREIGN KEY (`book_lesson_id`) REFERENCES `book_lesson` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lesson`
--

LOCK TABLES `lesson` WRITE;
/*!40000 ALTER TABLE `lesson` DISABLE KEYS */;
INSERT INTO `lesson` VALUES (2,1,'Vocabulaire','Vocabulaire 1 : on y croit',3,48),(3,1,'Ta mère','Une leçon pour savoir dire que l\'eau est propre',4,2),(4,2,'Vocabulaire 2','Vocabulaire 2',0,3),(5,2,'Mpiasa ve ny couz\' ?','Pour savoir comment demander si le mec là est un travailleur ou pas',0,4),(6,3,'Vocabulaire 3','Vocabulaire 3',0,5),(7,3,'C ki la meuf','Pour savoir demander c\'est qui le chaton là',0,6),(8,4,'Vocabulaire 4','t,Vocabulaire 4',0,7),(9,4,'C bon le kafé tu c','Pour être un pro du thé sucré kikoo',0,8),(10,5,'Vocabulaire 5',',Vocabulaire 5',0,9),(11,5,'Le lait sera froid','Le lait était beau mais le temps sera sucré',0,10),(12,6,'Vocabulaire 6',',Vocabulaire 6',0,11),(13,6,'Malade le zazakely','Parce que le lait chaud n\'est pas bon pour les enfant. Eh !',0,12),(14,7,'Vocabulaire 7','Vocabulaire 7',0,13),(15,7,'Comparatifs be','Pour comparer le zébu et le livre',0,14),(47,18,'Vocabulaire','Vocabulaire',0,NULL),(48,1,'Intro','Intro',1,NULL),(75,1,'New lesson',NULL,2,NULL),(81,19,'New lesson',NULL,2,NULL);
/*!40000 ALTER TABLE `lesson` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `progress`
--

DROP TABLE IF EXISTS `progress`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `progress` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `lesson_id` int(11) DEFAULT NULL,
  `difficulty` int(11) NOT NULL,
  `completed` tinyint(1) NOT NULL,
  `cycle_progression` int(11) NOT NULL,
  `book_lesson_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2201F246A76ED395` (`user_id`),
  KEY `IDX_2201F246CDF80196` (`lesson_id`),
  KEY `IDX_2201F246DF5617D0` (`book_lesson_id`),
  CONSTRAINT `FK_2201F246A76ED395` FOREIGN KEY (`user_id`) REFERENCES `fos_user` (`id`),
  CONSTRAINT `FK_2201F246CDF80196` FOREIGN KEY (`lesson_id`) REFERENCES `lesson` (`id`),
  CONSTRAINT `FK_2201F246DF5617D0` FOREIGN KEY (`book_lesson_id`) REFERENCES `book_lesson` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `progress`
--

LOCK TABLES `progress` WRITE;
/*!40000 ALTER TABLE `progress` DISABLE KEYS */;
/*!40000 ALTER TABLE `progress` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `question`
--

DROP TABLE IF EXISTS `question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(225) COLLATE utf8_unicode_ci NOT NULL,
  `lesson_id` int(11) DEFAULT NULL,
  `right_answer` int(11) DEFAULT NULL,
  `no_pictures` tinyint(1) DEFAULT NULL,
  `difficulty` int(11) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `IDX_B6F7494ECDF80196` (`lesson_id`),
  KEY `IDX_B6F7494EDA25A0BE` (`right_answer`),
  CONSTRAINT `FK_B6F7494ECDF80196` FOREIGN KEY (`lesson_id`) REFERENCES `lesson` (`id`),
  CONSTRAINT `FK_B6F7494EDA25A0BE` FOREIGN KEY (`right_answer`) REFERENCES `question_proposition` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=258 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `question`
--

LOCK TABLES `question` WRITE;
/*!40000 ALTER TABLE `question` DISABLE KEYS */;
INSERT INTO `question` VALUES (247,'',48,NULL,0,4),(252,'2 - 1',75,NULL,0,1),(253,'2 - 1',75,NULL,0,1),(254,'3 - 1',2,NULL,0,1),(255,'3 - 1',2,NULL,0,1),(256,'4 - 1',3,NULL,0,1),(257,'4 - 1',3,NULL,0,1);
/*!40000 ALTER TABLE `question` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `question_proposition`
--

DROP TABLE IF EXISTS `question_proposition`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `question_proposition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `text` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `image` mediumtext COLLATE utf8_unicode_ci,
  `right_answer` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_24C91CDE1E27F6BF` (`question_id`),
  CONSTRAINT `FK_24C91CDE1E27F6BF` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=216 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `question_proposition`
--

LOCK TABLES `question_proposition` WRITE;
/*!40000 ALTER TABLE `question_proposition` DISABLE KEYS */;
INSERT INTO `question_proposition` VALUES (182,247,'','',0),(183,247,'','',0),(184,247,'','',0),(198,252,'1','',0),(199,252,'2','',1),(200,252,'3aui','',0),(201,253,'a','',0),(202,253,'ie','',1),(203,253,'uie','',0),(204,254,'aa','',0),(205,254,'aa','',1),(206,254,'aa','',0),(207,255,'aa','',0),(208,255,'aa','',1),(209,255,'aa','',0),(210,256,'aa','',0),(211,256,'a','',1),(212,256,'aa','',0),(213,257,'a','',0),(214,257,'a','',1),(215,257,'aa','',0);
/*!40000 ALTER TABLE `question_proposition` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `translation`
--

DROP TABLE IF EXISTS `translation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `translation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lesson_id` int(11) DEFAULT NULL,
  `text` varchar(225) COLLATE utf8_unicode_ci NOT NULL,
  `answers` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `difficulty` int(11) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `IDX_B469456FCDF80196` (`lesson_id`),
  CONSTRAINT `FK_AEDAD51CCDF80196` FOREIGN KEY (`lesson_id`) REFERENCES `lesson` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=621 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `translation`
--

LOCK TABLES `translation` WRITE;
/*!40000 ALTER TABLE `translation` DISABLE KEYS */;
INSERT INTO `translation` VALUES (17,12,'amin\'ny','a:3:{i:0;s:2:\"à\";i:1;s:4:\"vers\";i:2;s:4:\"pour\";}',0),(20,10,'Androany','a:1:{i:0;s:11:\"Aujourd\'hui\";}',0),(21,4,'Angamba','a:1:{i:0;s:10:\"Peut-être\";}',0),(22,10,'Anio','a:1:{i:0;s:11:\"Aujourd\'hui\";}',0),(39,4,'Asa','a:2:{i:0;s:19:\"Je ne peux pas dire\";i:1;s:16:\"Je peux pas dire\";}',0),(40,5,'Asa, mangidy angamba izy io','a:1:{i:0;s:65:\"(Je ne sais pas|Je ne peux pas dire), (il |c\')est peut-être amer\";}',0),(41,5,'Asa, marary angamba izy','a:2:{i:0;s:40:\"Je ne sais pas, il est peut-être malade\";i:1;s:45:\"Je ne peux pas dire, il est peut-être malade\";}',0),(42,5,'Asa, mpiasa angamba izy','a:2:{i:0;s:62:\"Je ne peux pas dire, c\'est peut-être un (travailleur|ouvrier)\";i:1;s:57:\"Je ne sais pas, c\'est peut-être un (travailleur|ouvrier)\";}',0),(46,10,'Aujourd\'hui','a:1:{i:0;s:15:\"(Anio|Androany)\";}',0),(51,12,'aza matahotra','a:2:{i:0;s:24:\"(n\'aies|n\'ayez) pas peur\";i:1;s:35:\"ne (t\'inquiète|vous inquietez) pas\";}',0),(53,2,'Be','a:1:{i:0;s:25:\"Très|gros|grand|beaucoup\";}',9),(54,5,'Beaucoup de gens sont malades','a:1:{i:0;s:18:\"Be ny olona marary\";}',0),(56,14,'boky','a:1:{i:0;s:14:\"(le|un|) livre\";}',0),(57,5,'C\'est bien que l\'eau soit propre et que la maison soit propre','a:1:{i:1;s:40:\"tsara ny rano (|madio) sy ny trano madio\";}',0),(59,9,'C\'est le café avec du lait et du sucre qui est bon','a:1:{i:0;s:41:\"Ny kafé misy ronono sy siramamy no tsara\";}',0),(63,10,'C\'était propre','a:1:{i:0;s:5:\"Nadio\";}',0),(65,7,'Ce sont des bagages','a:1:{i:0;s:38:\"Entana (izy|) (irery|ireto|ireny|ireo)\";}',0),(66,7,'Ce sont des écoliers','a:1:{i:0;s:35:\" Mpianatra (irery|ireto|ireny|ireo)\";}',0),(67,7,'Ces hommes sont des ouvriers et des menuisiers','a:1:{i:0;s:41:\"Mpiasa sy mpandrafitra ireo lehilahy ireo\";}',0),(68,7,'Cet objet (tout proche) est propre','a:1:{i:0;s:30:\"Madio ity (zavatra|entana) ity\";}',0),(69,5,'Cet ouvrier est-il malade ?','a:1:{i:0;s:61:\"Marary ve io mpiasa io ?|tsy fantatro raha marary izy na tsia\";}',0),(70,7,'Cette bouteille-là est un médicament sucré','a:1:{i:0;s:42:\"Fanafody mamy (io|iry) tavoahangy (io|iry)\";}',0),(71,5,'Cette eau est-elle amer ?','a:1:{i:0;s:23:\"Mangidy ve io rano io ?\";}',0),(72,5,'Cette personne et l\'enfant sont peut-être malades','a:1:{i:0;s:41:\"Marary angamba io olona io sy ny zazakely\";}',0),(81,9,'De l\'eau froide','a:1:{i:0;s:16:\"Rano mangatsiaka\";}',0),(82,10,'Déjà','a:1:{i:0;s:3:\"Efa\";}',0),(83,10,'Demain','a:1:{i:0;s:10:\"Rahampitso\";}',0),(88,8,'Dite','a:1:{i:0;s:10:\"(Du|) thé\";}',0),(89,12,'dokotera','a:1:{i:0;s:27:\"(le|un|) (médecin|docteur)\";}',0),(90,13,'donnez à la femme du lait chaud','a:1:{i:0;s:37:\"omeo ronono mafana (ny|ilay) vehivahy\";}',0),(95,10,'Efa','a:1:{i:0;s:6:\"Déjà\";}',0),(98,13,'efa nantsoinareo ve ny dokotera ?','a:1:{i:0;s:65:\"(est-ce que|) vous avez (déjà|) appelé le (docteur|médecin) ?\";}',0),(99,13,'efa nomenay ronono mafana izy tompoko','a:1:{i:0;s:66:\"Nous lui avons déjà donné (du|un) lait chaud, (monsieur|madame)\";}',0),(103,6,'Entana','a:6:{i:0;s:6:\"Objets\";i:1;s:8:\"Un objet\";i:2;s:9:\"Un bagage\";i:3;s:11:\"Des bagages\";i:4;s:9:\"Des trucs\";i:5;s:7:\"Un truc\";}',0),(109,13,'eny tompoko, ary efa nanome fanafody izy','a:1:{i:0;s:82:\"oui (monsieur|madame), et il a (déjà|) donné (un|des) (médicament|remède)(s|)\";}',0),(112,3,'Eny, maloto be ny rana','a:1:{i:0;s:32:\"Oui, l\'eau est (très|bien) sale\";}',0),(113,3,'Eny, maloto ny rano','a:1:{i:0;s:19:\"Oui, l\'eau est sale\";}',0),(114,5,'Eny, mpiasa izy','a:1:{i:0;s:35:\"Oui, c\'est un (travailleur|ouvrier)\";}',0),(115,3,'Eny, tsy madio ny trano','a:3:{i:0;s:31:\"Oui, la maison n\'est pas propre\";i:1;s:26:\"La maison n\'est pas propre\";i:2;s:31:\"Non, la maison n\'est pas propre\";}',0),(116,3,'Eny, tsy maloto be ny rano','a:3:{i:0;s:38:\"Oui, l\'eau n\'est pas (très|bien) sale\";i:1;s:38:\"Non, l\'eau n\'est pas (très|bien) sale\";i:2;s:33:\"L\'eau n\'est pas (très|bien) sale\";}',0),(117,3,'Eny, tsy maloto ny rano','a:3:{i:0;s:25:\"Oui, l\'eau n\'est pas sale\";i:1;s:25:\"Non, l\'eau n\'est pas sale\";i:2;s:20:\"L\'eau n\'est pas sale\";}',0),(121,10,'Était malade','a:1:{i:0;s:6:\"Narary\";}',0),(122,12,'fa','a:1:{i:0;s:4:\"mais\";}',0),(127,4,'Fanafody','a:2:{i:0;s:11:\"Médicament\";i:1;s:7:\"Remède\";}',0),(128,7,'Fanafody ireo tavoahangy ireo','a:1:{i:0;s:46:\"Ces bouteilles sont des (médicaments|remède)\";}',0),(130,5,'Fanafody mangidy','a:2:{i:0;s:26:\"(remède|Médicament) amer\";i:1;s:29:\"Un (médicament|remède) amer\";}',0),(131,6,'Fanaka','a:2:{i:0;s:6:\"Meuble\";i:1;s:9:\"Un meuble\";}',0),(132,7,'Fanaka irery entana irery','a:1:{i:1;s:77:\"(Ces|Ce) (bagage|truc|chose|affaire)(|s) là bas (est un|sont des) meuble(s|)\";}',0),(134,11,'Fera-t-il beau temps ?','a:1:{i:0;s:22:\"Ho tsara ve ny andro ?\";}',0),(135,11,'Fera-t-il beau temps aussi demain ?','a:1:{i:0;s:37:\"Ho tsara koa ve ny andro rahampitso ?\";}',0),(136,11,'Fera-t-il beau temps demain ?','a:1:{i:0;s:33:\"Ho tsara ve ny andro rahampitso ?\";}',0),(138,7,'Ginette iry verivavy iry','a:2:{i:0;s:34:\"Cette femme (là-bas|) est Ginette\";i:1;s:36:\"C\'est Ginette cette femme (là-bas|)\";}',0),(139,10,'Guéri','a:1:{i:0;s:7:\"Sitrana\";}',0),(140,10,'Hadio','a:1:{i:0;s:11:\"Sera propre\";}',0),(141,11,'Hadio indray ny trano rahampitso','a:1:{i:0;s:44:\"La maison sera (à|de) nouveau propre demain\";}',0),(142,10,'Haloto','a:1:{i:0;s:22:\"(Sera|Seront) sale(s|)\";}',0),(146,12,'handriny','a:1:{i:0;s:9:\"son front\";}',0),(147,10,'Hangatsiaka','a:1:{i:0;s:10:\"Sera froid\";}',0),(148,11,'Hangatsiaka ny ronono','a:1:{i:0;s:18:\"Le lait sera froid\";}',0),(152,10,'Hier','a:1:{i:0;s:5:\"Omaly\";}',0),(161,11,'Ho mamy ny fanafody','a:1:{i:0;s:43:\"Le (médicament|remède) sera (sucré|doux)\";}',0),(162,13,'ho sitrana ny zazakely fa aza matahotra','a:1:{i:0;s:175:\"((l\'enfant va|les enfants vont) guérir|(l\'enfant sera|les enfants seront) guéri(|s)) (alors|donc|) (ne vous inquietez pas|n\'ayez pas peur|ne t\'inquiète pas|n\'aies pas peur)\";}',0),(163,11,'Ho tsara ny andro rahampitso','a:2:{i:0;s:44:\"(Le temps sera|Il fera) beau (temps|) demain\";i:1;s:29:\"La journée sera belle demain\";}',0),(167,11,'Il a fait très froid hier','a:1:{i:0;s:20:\"Nangatsiaka be omaly\";}',0),(172,11,'Il fera beau temps aujourd\'hui','a:1:{i:0;s:22:\"Ho tsara ny andro anio\";}',0),(177,10,'Indray','a:1:{i:0;s:21:\"(De nouveau|Prochain)\";}',0),(179,6,'Inona','a:1:{i:0;s:4:\"quoi\";}',0),(181,7,'Inona io zavatra io ?','a:2:{i:0;s:37:\"(Quelle est|c\'est quoi) cette chose ?\";i:1;s:37:\"Qu\'est-ce que c\'est que cette chose ?\";}',0),(182,7,'Inona ireo tavoahangy ireo ?','a:1:{i:1;s:81:\"(C\'est quoi|Que sont|Qu\'est-ce que sont|Qu\'est-ce que c\'est que) ces bouteilles ?\";}',0),(183,7,'Inona irery entana irery ?','a:1:{i:0;s:84:\"(Que sont|C\'est quoi|Qu\'est-ce que c\'est que) ces (choses|objets|bagages) (là-bas|)\";}',0),(184,7,'Inona iry fanaka iry ?','a:3:{i:0;s:31:\"Quel est ce meuble (là-bas|) ?\";i:1;s:46:\"Qu\'est-ce que c\'est que ce meuble (là-bas|) ?\";i:2;s:22:\"c\'est quoi ce meuble ?\";}',0),(185,9,'Inona koa no tsara ?','a:1:{i:0;s:42:\"(Qu\'est-ce|C\'est quoi) qui est bon aussi ?\";}',0),(187,9,'Inona no madio ?','a:1:{i:0;s:26:\"Qu\'est-ce qui est propre ?\";}',0),(189,9,'Inona no mafana ?','a:1:{i:0;s:42:\"(Qu\'est-ce qui|C\'est quoi qui) est chaud ?\";}',0),(191,9,'Inona no mangatsiaka ?','a:1:{i:0;s:38:\"(Qu\'est-ce|C\'est quoi) qui est froid ?\";}',0),(192,9,'Inona no mangidy ?','a:1:{i:0;s:24:\"Qu\'est-ce qui est amer ?\";}',0),(194,9,'Inona no tsara ?','a:1:{i:0;s:48:\"(Qu\'est-ce|C\'est quoi) qui est (bon|beau|bien) ?\";}',0),(204,4,'Io','a:3:{i:0;s:2:\"Ce\";i:1;s:5:\"Cette\";i:2;s:3:\"Ces\";}',0),(205,9,'Io fanafody io no mangidy','a:1:{i:0;s:49:\"(C\'est|) ce (médicament|remède) (qui|) est amer\";}',0),(208,6,'Ireo','a:3:{i:0;s:3:\"Ces\";i:1;s:8:\"Ceux-là\";i:2;s:4:\"ceux\";}',0),(211,9,'Iry lehilahy iry no mpandrafitra','a:1:{i:0;s:50:\"(C\'est|) cet homme(-ci|-là|) (qui|) est menuisier\";}',0),(212,6,'Iza','a:1:{i:0;s:3:\"qui\";}',0),(213,7,'Iza io lehilahy io ?','a:1:{i:0;s:19:\"Qui est cet homme ?\";}',0),(214,7,'Iza ireo olona ireo ?','a:2:{i:0;s:31:\"qui sont ces (personnes|gens) ?\";i:1;s:32:\"C\'est qui ces (personnes|gens) ?\";}',0),(215,7,'Iza irery zazakely irery ?','a:2:{i:0;s:43:\"Qui sont ces (enfants|bébés) (là-bas|) ?\";i:1;s:44:\"C\'est qui ces (enfants|bébés) (là-bas|) ?\";}',0),(216,7,'Iza iry vehivavy iry ?','a:1:{i:0;s:32:\"Qui est cette femme (|là-bas) ?\";}',0),(217,9,'Iza no marary ?','a:1:{i:0;s:40:\"(C\'est qui|Qui est-ce|) qui est malade ?\";}',0),(219,9,'Iza no mpandrafitra ?','a:1:{i:0;s:33:\"Qui (est-ce qui|) est menuisier ?\";}',0),(220,10,'Izao','a:1:{i:0;s:10:\"Maintenant\";}',0),(226,5,'Je ne sais pas si il est malade ou non','a:1:{i:1;s:44:\"Tsy fantatro (raha|na) marary (|izy) na tsia\";}',0),(233,8,'Kafe','a:1:{i:0;s:11:\"(Du|) Café\";}',0),(234,14,'kahie','a:1:{i:0;s:6:\"cahier\";}',0),(236,14,'kisoa','a:1:{i:0;s:22:\"(un|le|) (cochon|porc)\";}',0),(237,8,'Koa','a:1:{i:0;s:5:\"Aussi\";}',0),(239,3,'L\'eau est bien propre','a:1:{i:0;s:19:\"Madio tsara ny rano\";}',0),(240,3,'L\'eau est propre','a:1:{i:0;s:13:\"Madio ny rano\";}',0),(241,3,'L\'eau et la maison ne sont pas propres','a:1:{i:0;s:29:\"Tsy madio ny rano sy ny trano\";}',0),(242,11,'L\'eau était sale','a:1:{i:0;s:14:\"Naloto ny rano\";}',0),(243,3,'L\'eau n\'est-elle pas bien propre ?','a:1:{i:0;s:28:\"Tsy madio tsara ve ny rano ?\";}',0),(244,13,'l\'enfant est-il guéri ?','a:1:{i:0;s:24:\"sitrana ve ny zazakely ?\";}',0),(245,11,'L\'enfant ne sera pas malade','a:1:{i:0;s:22:\"Tsy harary ny zazakely\";}',0),(246,13,'la femme malade sera guérie aussi','a:1:{i:0;s:40:\"ho sitrana koa (ilay|ny) vehivahy marary\";}',0),(247,11,'La maison n\'était pas propre','a:1:{i:0;s:18:\"Tsy nadio ny trano\";}',0),(248,11,'La maison sera-t-elle propre ?','a:1:{i:0;s:19:\"Hadio ve ny trano ?\";}',0),(250,7,'La table et la chaise sont des meubles','a:1:{i:0;s:30:\"Fanaka ny latabatra sy ny seza\";}',0),(260,6,'Latabatra','a:4:{i:0;s:5:\"Table\";i:1;s:9:\"Une table\";i:2;s:8:\"la table\";i:3;s:10:\"des tables\";}',0),(261,7,'Latabatra io zavatra io','a:2:{i:0;s:25:\"Cette chose est une table\";i:1;s:15:\"C\'est une table\";}',0),(265,9,'Le café sans lait ni sucre','a:1:{i:0;s:37:\"(Ny) kafe tsy misy ronono sy siramamy\";}',0),(266,11,'Le café sera froid','a:1:{i:0;s:19:\"Hangatsiaka ny kafe\";}',0),(271,10,'Le jour','a:1:{i:0;s:8:\"ny andro\";}',0),(272,13,'le médecin a déjà donné un médicament','a:1:{i:0;s:31:\"efa nanome fanafody ny dokotera\";}',0),(275,9,'Le thé sucré est bon aussi','a:1:{i:0;s:46:\"Tsara koa ny (dite (|misy) siramamy|dite mamy)\";}',0),(276,6,'Lehilahy','a:2:{i:0;s:5:\"Homme\";i:1;s:8:\"Un homme\";}',0),(281,3,'Les maisons sont-elles sales ?','a:1:{i:0;s:20:\"Maloto ve ny trano ?\";}',0),(289,3,'Madio ny rano','a:1:{i:0;s:16:\"L\'eau est propre\";}',0),(290,3,'Madio ny trano','a:1:{i:0;s:20:\"La maison est propre\";}',0),(291,3,'Madio tsara ny trano','a:2:{i:0;s:33:\"La maison est (très|bien) propre\";i:1;s:25:\"La maison est bien propre\";}',0),(292,7,'Madio tsara ve ireo fanaka ireo ?','a:1:{i:0;s:65:\"(Est-ce que ces meubles sont|Ces meubles sont-ils) bien propres ?\";}',0),(293,3,'Madio tsara ve ny trano ?','a:3:{i:0;s:38:\"Est-ce que la maison est bien propre ?\";i:1;s:32:\"La maison est-elle bien propre ?\";i:2;s:27:\"La maison est bien propre ?\";}',0),(294,3,'Madio ve ny trano ?','a:3:{i:0;s:33:\"Est-ce que la maison est propre ?\";i:1;s:27:\"La maison est-elle propre ?\";i:2;s:22:\"La maison est propre ?\";}',0),(295,8,'Mafana','a:1:{i:0;s:5:\"Chaud\";}',0),(296,13,'mafana be tompoko','a:1:{i:0;s:39:\"(Il est |)très chaud (monsieur|madame)\";}',0),(298,13,'mafana ve ny handriny ?','a:1:{i:0;s:35:\"(Est-ce que |)son front est chaud ?\";}',0),(300,14,'mahia','a:1:{i:0;s:6:\"maigre\";}',0),(302,15,'mahia ny omby','a:1:{i:0;s:19:\"le zébu est maigre\";}',0),(305,10,'Maintenant','a:1:{i:0;s:4:\"Izao\";}',0),(306,10,'Mais','a:1:{i:0;s:4:\"Nefa\";}',0),(307,14,'maivana','a:1:{i:0;s:6:\"léger\";}',0),(308,15,'maivana ny taratasy','a:2:{i:0;s:20:\"le papier est léger\";i:1;s:36:\"la feuille (de papier|) est légère\";}',0),(312,3,'Maloto be ny rano','a:1:{i:0;s:27:\"L\'eau est (très|bien) sale\";}',0),(313,3,'Maloto be ve ny rano ?','a:3:{i:0;s:40:\"Est-ce que l\'eau est (très|bien) sale ?\";i:1;s:34:\"L\'eau est-elle (très|bien) sale ?\";i:2;s:29:\"L\'eau est (très|bien) sale ?\";}',0),(315,3,'Maloto ny rano','a:1:{i:0;s:14:\"L\'eau est sale\";}',0),(316,3,'Maloto ve ny rano','a:2:{i:0;s:27:\"Est-ce que l\'eau est sale ?\";i:1;s:38:\"L\'eau est-elle sale ?:L\'eau est sale ?\";}',0),(317,3,'Maloto ve ny trano ?','a:3:{i:0;s:31:\"Est-ce que la maison est sale ?\";i:1;s:25:\"La maison est-elle sale ?\";i:2;s:20:\"La maison est sale ?\";}',0),(318,4,'Mamy','a:2:{i:0;s:6:\"Sucré\";i:1;s:4:\"Doux\";}',0),(322,15,'manao ahoana ny boky ?','a:1:{i:0;s:22:\"comment est le livre ?\";}',0),(323,3,'Manao ahoana ny trano ?','a:1:{i:0;s:23:\"Comment est la maison ?\";}',0),(326,12,'manavy','a:2:{i:0;s:20:\"être fiévreu(x|se)\";i:1;s:19:\"avoir de la fièvre\";}',0),(330,8,'Mangatsiaka','a:1:{i:0;s:5:\"Froid\";}',0),(332,4,'Mangidy','a:2:{i:0;s:6:\"Amère\";i:1;s:4:\"Amer\";}',0),(333,5,'Mangidy angamba izy io','a:2:{i:0;s:22:\"Il est peut-être amer\";i:1;s:21:\"C\'est peut-être amer\";}',0),(336,5,'Mangidy ve io fanafody io ?','a:3:{i:0;s:46:\"Est-ce que ce (médicament|remède) est amer ?\";i:1;s:38:\"Ce (médicament|remède) est-il amer ?\";i:2;s:35:\"Ce (médicament|remède) est amer ?\";}',0),(337,14,'manify','a:2:{i:0;s:3:\"fin\";i:1;s:5:\"mince\";}',0),(338,15,'manify kokoa noho ny boky ny kahie','a:1:{i:0;s:43:\"le cahier est plus (fin|mince) que le livre\";}',0),(339,15,'manify ny kahie','a:1:{i:0;s:25:\"le cahier est (fin|mince)\";}',0),(344,4,'Marary','a:2:{i:0;s:6:\"Malade\";i:1;s:7:\"Blessé\";}',0),(345,5,'Marary angamba izy','a:1:{i:0;s:24:\"Il est peut-être malade\";}',0),(346,13,'marary tompoko ny zazakely','a:1:{i:0;s:50:\"(L\'enfant|Le bébé) est malade, (monsieur|madame)\";}',0),(347,5,'Marary ve io zazakely io ?','a:3:{i:1;s:59:\"(|Est-ce que) ((cet |l\')enfant|(ce|le) bébé) est malade ?\";i:2;s:35:\"Cet (enfant|bébé) est-il malade ?\";i:3;s:32:\"Cet (enfant|bébé) est malade ?\";}',0),(351,12,'matahotra','a:3:{i:0;s:10:\"avoir peur\";i:1;s:13:\"être inquiet\";i:2;s:12:\"s\'inquiéter\";}',0),(354,14,'matavy','a:1:{i:0;s:4:\"gras\";}',0),(355,15,'matavy ny kisoa','a:1:{i:0;s:25:\"le (porc|cochon) est gras\";}',0),(357,14,'matevina','a:1:{i:0;s:6:\"épais\";}',0),(358,15,'matevina kokoa noho ny kahie ny boky','a:1:{i:0;s:38:\"Le livre est plus épais que le cahier\";}',0),(359,15,'matevina ny boky','a:1:{i:0;s:19:\"le livre est épais\";}',0),(363,14,'mavesatra','a:1:{i:0;s:5:\"lourd\";}',0),(364,15,'mavesatra kokoa noho ny taratasy ny vato','a:1:{i:0;s:88:\"(le caillou|la pierre) est plus lourd(e) que (le papier|la feuille|la feuille de papier)\";}',0),(365,15,'mavesatra ny vato','a:1:{i:0;s:35:\"(la pierre|le caillou) est lourd(e)\";}',0),(387,6,'Mpandrafitra','a:2:{i:0;s:9:\"Menuisier\";i:1;s:12:\"Un menuisier\";}',0),(388,7,'Mpandrafitra io lehilahy io','a:2:{i:0;s:29:\"Cet homme est (un|) menuisier\";i:1;s:18:\"C\'est un menuisier\";}',0),(389,6,'Mpianatra','a:7:{i:0;s:8:\"Écolier\";i:1;s:7:\"Élève\";i:2;s:9:\"Étudiant\";i:3;s:9:\"Apprenant\";i:4;s:11:\"Un écolier\";i:5;s:10:\"Un élève\";i:6;s:12:\"Un étudiant\";}',0),(390,7,'Mpianatra irery zazakely irery','a:3:{i:1;s:86:\"(Ces|Cet) enfant(|s) (là-bas|) (est|sont) (|des|un) (écolier|apprenti|étudiant)(|s)\";i:2;s:40:\"Ces enfants (là-bas|) sont des élèves\";i:3;s:27:\"Ce ont des élèves là bas\";}',0),(391,4,'Mpiasa','a:2:{i:0;s:11:\"Travailleur\";i:1;s:7:\"Ouvrier\";}',0),(392,5,'Mpiasa angamba izy','a:1:{i:0;s:41:\"C\'est peut-être un (travailleur|ouvrier)\";}',0),(393,7,'Mpiasa ireo olona ireo','a:2:{i:0;s:26:\"Ces gens sont des ouvriers\";i:1;s:40:\"Ces gens sont des (travailleur|ouvrier)s\";}',0),(394,5,'Mpiasa ve io olona io ?','a:4:{i:0;s:56:\"Est-ce que cette personne est un (travailleur|ouvrier) ?\";i:1;s:50:\"Cette personne est-elle un (travailleur|ouvrier) ?\";i:2;s:45:\"Cette personne est un (travailleur|ouvrier) ?\";i:3;s:32:\"C\'est un (travailleur|ouvrier) ?\";}',0),(401,10,'Nadio','a:1:{i:0;s:18:\"(C\'|)était propre\";}',0),(402,11,'Nadio ny trano omaly nefa haloto anio','a:1:{i:0;s:63:\"La maison était propre hier mais (elle|) sera sale aujourd\'hui\";}',0),(403,11,'Nafana ny kafe nefa mangatsiaka izao','a:1:{i:0;s:70:\"Le café était chaud mais (maintenant|) (il|) est froid (|maintenant)\";}',0),(405,11,'Nangidy ny kafe','a:1:{i:0;s:20:\"Le café était amer\";}',0),(406,12,'nanome izy','a:1:{i:0;s:18:\"(il|elle) a donné\";}',0),(407,12,'nantsoinareo','a:1:{i:0;s:17:\"vous avez appelé\";}',0),(408,10,'Narary','a:1:{i:0;s:13:\"Était malade\";}',0),(409,11,'Narary ny zazakely omaly nefa efa sitrana izao','a:1:{i:0;s:72:\"(Le bébé|L\'enfant) était malade hier mais (il|) est guéri maintenant\";}',0),(410,10,'Nefa','a:1:{i:0;s:4:\"Mais\";}',0),(412,12,'nomenay','a:1:{i:0;s:17:\"nous avons donné\";}',0),(414,5,'Non, cette eau n\'est pas amère','a:1:{i:0;s:28:\"Tsia, tsy mangidy io rano io\";}',0),(415,7,'Non, ils sont sales','a:1:{i:0;s:24:\"Tsia, maloto (izy|) ireo\";}',0),(417,3,'Non, les maisons ne sont pas sales','a:1:{i:0;s:25:\"Tsia, tsy maloto ny trano\";}',0),(423,10,'Ny andro','a:1:{i:0;s:15:\"Le (temps|jour)\";}',0),(424,9,'Ny dite misy siramamy koa no tsara','a:1:{i:0;s:65:\"(C\'est|) le thé (sucré|avec du sucre) (qui est|c\'est) bon aussi\";}',0),(425,9,'Ny kafe mafana misy ronono no tsara','a:1:{i:0;s:56:\"(C\'est|) le café chaud (au|avec du) lait (qui|) est bon\";}',0),(426,9,'Ny kafe no mafana','a:1:{i:0;s:34:\"(C\'est|) le café (qui|) est chaud\";}',0),(429,9,'Ny ronono no mangatsiaka','a:1:{i:0;s:33:\"(C\'est|) le lait (qui|) est froid\";}',0),(432,9,'Ny trano no madio','a:1:{i:0;s:36:\"(C\'est|) la maison (qui|) est propre\";}',0),(433,9,'Ny zazakely no marary','a:1:{i:0;s:35:\"(C\'est|) l\'enfant (qui|) est malade\";}',0),(437,4,'Olona','a:3:{i:0;s:8:\"Personne\";i:1;s:12:\"Une personne\";i:2;s:9:\"Quelqu\'un\";}',0),(438,10,'Omaly','a:1:{i:0;s:4:\"Hier\";}',0),(439,14,'omby','a:1:{i:0;s:14:\"(un|le|) zébu\";}',0),(442,12,'omeo','a:1:{i:0;s:11:\"donne(s|z|)\";}',0),(443,13,'omeo dite mafana misy siramamy','a:1:{i:0;s:38:\"Donnez lui du thé chaud avec du sucre\";}',0),(447,13,'oui, monsieur, l\'enfant est guéri','a:1:{i:0;s:32:\"eny tompoko, sitrana ny zazakely\";}',0),(450,11,'Peut-être fera-t-il beau demain','a:1:{i:0;s:27:\"Angamba ho tsara rahampitso\";}',0),(454,9,'Qu\'est-ce qu\'il y a dans cette bouteille ?','a:1:{i:0;s:41:\"Misy inona (ity|io) tavoahangy (ity|io) ?\";}',0),(455,9,'Qu\'est-ce qui est amer ?','a:1:{i:0;s:18:\"Inona no mangidy ?\";}',0),(456,9,'Qu\'est-ce qui est bon ?','a:1:{i:0;s:16:\"Inona no tsara ?\";}',0),(457,9,'Qu\'est-ce qui est bon aussi ?','a:1:{i:0;s:29:\"Inona (|koa) no tsara (|koa)?\";}',0),(460,7,'Que sont ces objets là-bas ?','a:1:{i:1;s:36:\"Inona ireny (entana|zavatra) ireny ?\";}',0),(463,7,'Qui sont ces enfants ?','a:1:{i:0;s:24:\"Iza ireo zazakely ireo ?\";}',0),(470,10,'Rahampitso','a:1:{i:0;s:6:\"Demain\";}',0),(473,5,'Rano maloto','a:2:{i:0;s:8:\"Eau sale\";i:1;s:13:\"De l\'eau sale\";}',0),(481,8,'Ronono','a:1:{i:0;s:10:\"(Du|) lait\";}',0),(483,10,'Sera froid','a:1:{i:0;s:11:\"Hangatsiaka\";}',0),(484,10,'Sera propre','a:1:{i:0;s:5:\"Hadio\";}',0),(485,10,'Sera sale','a:1:{i:0;s:6:\"Haloto\";}',0),(486,6,'Seza','a:2:{i:0;s:15:\"(une|la|)chaise\";i:1;s:17:\"(le|un|) fauteuil\";}',0),(487,7,'Seza iry fanaka iry','a:2:{i:0;s:35:\"Ce meuble (là-bas|) est une chaise\";i:1;s:38:\"C\'est une chaise, ce meuble (là-bas|)\";}',0),(491,8,'Siramamy','a:1:{i:0;s:11:\"(Du|) sucre\";}',0),(492,10,'Sitrana','a:1:{i:0;s:6:\"Guéri\";}',0),(497,14,'taratasy','a:2:{i:0;s:18:\"(un|le|du|) papier\";i:1;s:17:\"(une|la|) feuille\";}',0),(498,6,'Tavoahangy','a:2:{i:0;s:9:\"Bouteille\";i:1;s:13:\"Une bouteille\";}',0),(502,12,'Tompoko','a:2:{i:0;s:8:\"monsieur\";i:1;s:6:\"madame\";}',0),(505,5,'Trano madio','a:2:{i:0;s:13:\"Maison propre\";i:1;s:17:\"Une maison propre\";}',0),(506,13,'tsara izany','a:2:{i:0;s:17:\"c\'est bien (ça|)\";i:1;s:0:\"\";}',0),(507,9,'Tsara koa ny dite misy siramamy','a:2:{i:0;s:61:\"Le thé (sucré|avec du sucre) (aussi|) (|c\')est bon (aussi|)\";i:1;s:46:\"C\'est bon aussi le thé (avec du sucre|sucré)\";}',0),(510,9,'Tsara ny kafe manafa misy ronono','a:2:{i:0;s:42:\"Le café (avec du|au) lait (est|c\'est) bon\";i:1;s:36:\"C\'est bon le café (avec du|au) lait\";}',0),(516,3,'Tsia, madio ny rano','a:1:{i:0;s:25:\"Non, la maison est propre\";}',0),(517,3,'Tsia, maloto ny trano','a:1:{i:0;s:23:\"Non, la maison est sale\";}',0),(518,5,'Tsia, tsy mpiasa izy','a:1:{i:0;s:56:\"Non, ((ça|ce) n\'est|c\'est) pas un (travailleur|ouvrier)\";}',0),(519,4,'Tsy fantatro','a:2:{i:0;s:14:\"Je ne sais pas\";i:1;s:11:\"Je sais pas\";}',0),(521,5,'Tsy fantatro na mangidy izy io na mamy','a:2:{i:0;s:36:\"Je ne sais pas si il est amer ou non\";i:1;s:49:\"Je ne sais pas si il est amer ou (non|pas|sucré)\";}',0),(522,5,'Tsy fantatro na marary izy na tsia','a:2:{i:0;s:38:\"Je ne sais pas si il est malade ou pas\";i:1;s:38:\"Je ne sais pas si il est malade ou non\";}',0),(523,5,'Tsy fantatro na mpiasa izy na tsia','a:1:{i:0;s:55:\"Je ne sais pas si c\'est un (travailleur|ouvrier) ou pas\";}',0),(525,3,'Tsy madio ny trano sy ny rano','a:1:{i:0;s:38:\"La maison et l\'eau ne sont pas propres\";}',0),(526,3,'Tsy madio tsara ny trano sy ny rano','a:2:{i:0;s:43:\"La maison et l\'eau ne sont pas bien propres\";i:1;s:51:\"La maison et l\'eau ne sont pas (très|bien) propres\";}',0),(527,3,'Tsy madio ve ny trano ?','a:3:{i:0;s:28:\"La maison n\'est pas propre ?\";i:1;s:33:\"La maison n\'est-elle pas propre ?\";i:2;s:39:\"Est-ce que la maison n\'est pas propre ?\";}',0),(528,3,'Tsy maloto be ny rano','a:1:{i:0;s:33:\"L\'eau n\'est pas (très|bien) sale\";}',0),(529,3,'Tsy maloto be ve ny rano ?','a:3:{i:0;s:62:\"Est-ce que l\'eau (n\'est(-t-elle|)|est) pas (très|bien) sale ?\";i:1;s:35:\"L\'eau n\'est pas (très|bien) sale ?\";i:2;s:40:\"L\'eau n\'est-elle pas (très|bien) sale ?\";}',0),(530,3,'Tsy maloto ny rano','a:1:{i:0;s:20:\"L\'eau n\'est pas sale\";}',0),(531,3,'Tsy maloto ve ny rano ?','a:3:{i:0;s:33:\"Est-ce que l\'eau n\'est pas sale ?\";i:1;s:27:\"L\'eau n\'est-elle pas sale ?\";i:2;s:22:\"L\'eau n\'est pas sale ?\";}',0),(532,15,'tsy mavesatra kokoa noho ny vato ny taratasy','a:1:{i:0;s:117:\"(le papier|la feuille de papier|la feuille) (est plus léger que|n\'est pas plus lourd)(e|) que (la pierre|le caillou)\";}',0),(534,13,'tsy tsara amin\'ny zazakely manavay ny ronono','a:2:{i:1;s:119:\"(Le|Du) lait (n\'est pas bon) pour (les|un) enfant(s) (malade|fiévreux|avec de la fièvre) (ça n\'est|c\'est) (pas bien)\";i:2;s:104:\"(c\'est|ça n\'est) pas (bon|bien) pour (les|un) enfant(|s) (malade|fiévreux|avec de la fièvre), le lait\";}',0),(535,14,'vato','a:2:{i:0;s:16:\"(le|un|) caillou\";i:1;s:16:\"(la|une|) pierre\";}',0),(538,6,'Vehivavy','a:2:{i:0;s:5:\"Femme\";i:1;s:9:\"Une femme\";}',0),(550,13,'vous avez appelé le médecin','a:1:{i:0;s:31:\"(efa|) nantsoinareo ny dokotera\";}',0),(552,6,'Zavatra','a:4:{i:0;s:5:\"Chose\";i:1;s:5:\"Objet\";i:2;s:9:\"Une chose\";i:3;s:8:\"Un objet\";}',0),(553,4,'Zazakely','a:2:{i:0;s:6:\"Enfant\";i:1;s:6:\"Bébé\";}',0),(554,5,'Zazakely marary','a:2:{i:0;s:22:\"(enfant|bébé) malade\";i:1;s:25:\"Un (enfant|bébé) malade\";}',0),(557,47,'ny anarana','a:1:{i:0;s:23:\"(le|un|des) prénom(|s)\";}',0),(558,47,'ny ankizivavy','a:1:{i:0;s:29:\"(une|la) (jeune|petite) fille\";}',0),(559,47,'ny anarako','a:1:{i:0;s:11:\"mon prénom\";}',0),(560,47,'ny anaranao','a:1:{i:0;s:11:\"ton prénom\";}',0),(561,47,'laza','a:1:{i:0;s:22:\"(parler|discuter|dire)\";}',0),(562,47,'lazaiko','a:1:{i:0;s:3:\"où\";}',0),(563,47,'ny tantara','a:1:{i:0;s:21:\"(l\'histoire|le conte)\";}',0),(564,47,'ny tantaranao','a:1:{i:0;s:20:\"ton (histoire|conte)\";}',0),(565,47,'ny tantarako','a:1:{i:0;s:20:\"mon (conte|histoire)\";}',0),(566,47,'ny anarako','a:1:{i:0;s:3:\"où\";}',0),(567,47,'ny anaranao','a:1:{i:0;s:3:\"où\";}',0),(568,47,'io ankizivavy io','a:1:{i:0;s:26:\"cette (jeune|petite) fille\";}',0),(569,47,'io tantara io','a:1:{i:0;s:3:\"où\";}',0),(574,NULL,'','a:1:{i:0;s:0:\"\";}',1),(575,NULL,'','a:1:{i:0;s:0:\"\";}',1),(576,NULL,'auie','a:1:{i:0;s:4:\"auie\";}',1),(590,48,'Bidule 1','a:1:{i:0;s:3:\"cul\";}',1),(591,NULL,'','N;',1),(592,NULL,'','N;',1),(593,NULL,'','N;',1),(594,NULL,'','N;',1),(595,NULL,'','N;',1),(596,NULL,'','N;',1),(597,NULL,'','N;',1),(598,NULL,'','N;',1),(599,NULL,'','N;',1),(600,NULL,'','N;',1),(601,NULL,'','N;',2),(602,NULL,'','N;',1),(603,NULL,'','N;',1),(604,NULL,'','N;',1),(606,48,'','a:0:{}',4),(613,48,'Bidule 2','a:1:{i:0;s:3:\"cul\";}',1),(616,48,'1 - 2','a:1:{i:0;s:3:\"cul\";}',2),(617,75,'2 - 2','a:1:{i:0;s:3:\"cul\";}',2),(618,2,'3 - 2','a:1:{i:0;s:3:\"cul\";}',2),(619,3,'4 - 2','a:1:{i:0;s:3:\"cul\";}',2),(620,4,'leçon 2','a:1:{i:0;s:3:\"cul\";}',1);
/*!40000 ALTER TABLE `translation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unlocked_lesson`
--

DROP TABLE IF EXISTS `unlocked_lesson`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unlocked_lesson` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `lesson_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_558E04AFA76ED395` (`user_id`),
  KEY `IDX_558E04AFCDF80196` (`lesson_id`),
  CONSTRAINT `FK_558E04AFA76ED395` FOREIGN KEY (`user_id`) REFERENCES `fos_user` (`id`),
  CONSTRAINT `FK_558E04AFCDF80196` FOREIGN KEY (`lesson_id`) REFERENCES `lesson` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unlocked_lesson`
--

LOCK TABLES `unlocked_lesson` WRITE;
/*!40000 ALTER TABLE `unlocked_lesson` DISABLE KEYS */;
INSERT INTO `unlocked_lesson` VALUES (1,35,2),(2,35,3),(3,35,4),(4,35,5),(5,35,6);
/*!40000 ALTER TABLE `unlocked_lesson` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-02-08 23:00:11
