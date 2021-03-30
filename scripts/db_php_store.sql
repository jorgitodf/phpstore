-- MySQL dump 10.13  Distrib 5.7.26, for Win64 (x86_64)
--
-- Host: localhost    Database: php_store
-- ------------------------------------------------------
-- Server version	5.7.25-log

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
-- Table structure for table `addres_user`
--

DROP TABLE IF EXISTS `addres_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `addres_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tipo_endereco` varchar(40) NOT NULL,
  `address_id` int(10) unsigned NOT NULL,
  `users_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_addres_user_address1_idx` (`address_id`) USING BTREE,
  KEY `fk_addres_user_users1_idx` (`users_id`) USING BTREE,
  CONSTRAINT `fk_addres_user_address1` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_addres_user_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `addres_user`
--

LOCK TABLES `addres_user` WRITE;
/*!40000 ALTER TABLE `addres_user` DISABLE KEYS */;
INSERT INTO `addres_user` VALUES (1,'Residencial',1,1);
/*!40000 ALTER TABLE `addres_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `address`
--

DROP TABLE IF EXISTS `address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `complemento` varchar(80) NOT NULL,
  `numero` varchar(10) NOT NULL,
  `bairro` varchar(80) NOT NULL,
  `cep` varchar(8) NOT NULL,
  `uf` char(2) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `public_place_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_address_public_place1_idx` (`public_place_id`) USING BTREE,
  CONSTRAINT `fk_address_public_place1` FOREIGN KEY (`public_place_id`) REFERENCES `public_place` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `address`
--

LOCK TABLES `address` WRITE;
/*!40000 ALTER TABLE `address` DISABLE KEYS */;
INSERT INTO `address` VALUES (1,'16 Conjunto A Casa','19','Sobradinho','73050161','DF','2021-03-28 11:03:36','2021-03-28 11:03:36',29);
/*!40000 ALTER TABLE `address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome_categoria` varchar(130) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (1,'Masculino'),(2,'Feminino');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_status`
--

DROP TABLE IF EXISTS `payment_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_status` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status_id` int(10) unsigned NOT NULL,
  `purchasing_id` int(10) unsigned NOT NULL,
  `data_status` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_payment_status_status1_idx` (`status_id`) USING BTREE,
  KEY `fk_payment_status_purchasing1_idx` (`purchasing_id`) USING BTREE,
  CONSTRAINT `fk_payment_status_purchasing1` FOREIGN KEY (`purchasing_id`) REFERENCES `purchasing` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_payment_status_status1` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_status`
--

LOCK TABLES `payment_status` WRITE;
/*!40000 ALTER TABLE `payment_status` DISABLE KEYS */;
INSERT INTO `payment_status` VALUES (1,2,1,'2021-03-30 08:06:07'),(2,7,1,'2021-03-30 08:06:07');
/*!40000 ALTER TABLE `payment_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome_produto` varchar(100) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `preco` decimal(8,2) NOT NULL,
  `qtd_estoque` int(11) NOT NULL,
  `visibilidade` char(1) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `category_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_products_category_idx1` (`category_id`) USING BTREE,
  CONSTRAINT `category_id` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Tshirt Vermelha','Ab laborum, commodi aspernatur, quas distinctio cum quae omnis autem ea, odit sint quisquam similique! Labore aliquam amet veniam ad fugiat optio.','tshirt_vermelha.png',45.70,0,'1','2021-02-06 19:45:18','2021-02-06 19:45:25',1),(2,'Tshirt Azul','Possimus iusto esse atque autem rem, porro officiis sapiente quos velit laboriosam id expedita odio obcaecati voluptate repudiandae dignissimos eveniet repellat blanditiis.','tshirt_azul.png',55.25,100,'1','2021-02-06 19:45:19','2021-02-06 19:45:25',1),(3,'Tshirt Verde','Nostrum quisquam dolorum dolor autem accusamus fugit nesciunt, atque et? Quis eum nemo quidem officia cum dolorem voluptates! Autem, earum. Similique, fugit.','tshirt_verde.png',35.15,100,'1','2021-02-06 19:45:20','2021-02-06 19:45:26',1),(4,'Tshirt Amarela','Molestiae quaerat distinctio, facere perferendis necessitatibus optio repellat alias commodi voluptatem velit corrupti natus exercitationem quos amet facilis sint nulla delectus.','tshirt_amarela.png',32.00,100,'1','2021-02-06 19:45:20','2021-02-06 19:45:27',1),(5,'Vestido Vermelho','Labore voluptatem sed in distinctio iste tempora quo assumenda impedit illo soluta repudiandae animi earum suscipit, sequi excepturi inventore magnam velit voluptatibus.','dress_vermelho.png',75.20,100,'1','2021-02-06 19:45:21','2021-02-06 19:45:27',2),(6,'Vertido Azul','Provident ipsum earum magnam odit in, illum nostrum est illo pariatur molestias esse delectus aliquam ullam maxime mollitia tempore, sunt officia suscipit.','dress_azul.png',86.00,100,'1','2021-02-06 19:45:21','2021-02-06 19:45:28',2),(7,'Vestido Verde','Qui aliquid sed quisquam autem quas recusandae labore neque laudantium iusto modi repudiandae doloremque ipsam ad omnis inventore, cum ducimus praesentium. Consectetur!','dress_verde.png',48.85,100,'1','2021-02-06 19:45:22','2021-02-06 19:45:28',2),(8,'Vestido Amarelo','Aspernatur labore corporis modi quis temporibus eos hic? Sed fugiat, repudiandae distinctio, labore temporibus, non magni consectetur dolorum earum amet impedit nesciunt.','dress_amarelo.png',46.45,100,'1','2021-02-06 19:45:22','2021-02-06 19:45:29',2);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `public_place`
--

DROP TABLE IF EXISTS `public_place`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `public_place` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `logradouro` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `public_place`
--

LOCK TABLES `public_place` WRITE;
/*!40000 ALTER TABLE `public_place` DISABLE KEYS */;
INSERT INTO `public_place` VALUES (1,'Aeroporto'),(2,'Alameda'),(3,'Área'),(4,'Avenida'),(5,'Campo'),(6,'Chácara'),(7,'Colônia'),(8,'Condomínio'),(9,'Conjunto'),(10,'Distrito'),(11,'Esplanada'),(12,'Estação'),(13,'Estrada'),(14,'Favela'),(15,'Fazenda'),(16,'Feira'),(17,'Jardim'),(18,'Ladeira'),(19,'Lago'),(20,'Lagoa'),(21,'Largo'),(22,'Loteamento'),(23,'Morro'),(24,'Núcleo'),(25,'Parque'),(26,'Passarela'),(27,'Pátio'),(28,'Praça'),(29,'Quadra'),(30,'Recanto'),(31,'Residencial'),(32,'Rodovia'),(33,'Rua'),(34,'Setor'),(35,'Sítio'),(36,'Travessa'),(37,'Trecho'),(38,'Trevo'),(39,'Vale'),(40,'Vereda'),(41,'Via'),(42,'Viaduto'),(43,'Viela'),(44,'Vila');
/*!40000 ALTER TABLE `public_place` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_product`
--

DROP TABLE IF EXISTS `purchase_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_product` (
  `preco_unidade` decimal(8,2) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `products_id` int(10) unsigned NOT NULL,
  `purchasing_id` int(10) unsigned NOT NULL,
  KEY `fk_purchase_product_products1_idx` (`products_id`) USING BTREE,
  KEY `fk_purchase_product_purchasing1_idx` (`purchasing_id`) USING BTREE,
  CONSTRAINT `fk_purchase_product_products1` FOREIGN KEY (`products_id`) REFERENCES `products` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_purchase_product_purchasing1` FOREIGN KEY (`purchasing_id`) REFERENCES `purchasing` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_product`
--

LOCK TABLES `purchase_product` WRITE;
/*!40000 ALTER TABLE `purchase_product` DISABLE KEYS */;
INSERT INTO `purchase_product` VALUES (55.25,1,'2021-03-30 11:06:07',2,1),(35.15,1,'2021-03-30 11:06:07',3,1),(32.00,1,'2021-03-30 11:06:07',4,1);
/*!40000 ALTER TABLE `purchase_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchasing`
--

DROP TABLE IF EXISTS `purchasing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchasing` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data_compra` datetime NOT NULL,
  `codigo_compra` varchar(70) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `status` char(9) DEFAULT NULL,
  `users_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo_compra_UNIQUE` (`codigo_compra`),
  KEY `fk_purchasing_users1_idx` (`users_id`) USING BTREE,
  CONSTRAINT `fk_purchasing_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchasing`
--

LOCK TABLES `purchasing` WRITE;
/*!40000 ALTER TABLE `purchasing` DISABLE KEYS */;
INSERT INTO `purchasing` VALUES (1,'2021-03-30 08:06:07','JKD513187','2021-03-30 08:06:07','2021-03-30 08:06:07','canceled',1);
/*!40000 ALTER TABLE `purchasing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchasing_status`
--

DROP TABLE IF EXISTS `purchasing_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchasing_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_status` datetime NOT NULL,
  `purchasing_id` int(10) unsigned NOT NULL,
  `status_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_purchasing_status_purchasing1_idx` (`purchasing_id`) USING BTREE,
  KEY `fk_purchasing_status_status1_idx` (`status_id`) USING BTREE,
  CONSTRAINT `fk_purchasing_status_purchasing1` FOREIGN KEY (`purchasing_id`) REFERENCES `purchasing` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_purchasing_status_status1` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchasing_status`
--

LOCK TABLES `purchasing_status` WRITE;
/*!40000 ALTER TABLE `purchasing_status` DISABLE KEYS */;
INSERT INTO `purchasing_status` VALUES (1,'2021-03-30 08:06:07',1,1),(2,'2021-03-30 08:06:07',1,7);
/*!40000 ALTER TABLE `purchasing_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `status`
--

DROP TABLE IF EXISTS `status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `status` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome_status` varchar(60) NOT NULL,
  `mensagem_status` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `status`
--

LOCK TABLES `status` WRITE;
/*!40000 ALTER TABLE `status` DISABLE KEYS */;
INSERT INTO `status` VALUES (1,'Pedido confirmado','O seu pedido foi confirmado e está aguardando a confirmação do pagamento'),(2,'Pagamento pendente','O seu pagamento ainda não foi confirmado'),(3,'Pagamento aprovado','O seu pagamento foi aprovado, em seguida será preparado para o envio'),(4,'Preparando pedido','O seu pedido está sendo preparado para ser enviado'),(5,'Enviar pedido','O seu pedido foi enviado'),(6,'Pedido entregue','O pedido foi entregue'),(7,'Pagamento Negado','O seu pagamento não foi aprovado');
/*!40000 ALTER TABLE `status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(155) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `ativo` char(1) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `last_login_ip` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Jorgito Paiva','jspaiva.1977@gmail.com','$2y$10$Qi5OTPNLv8kNi14Np6RivOy00DuB0iUDKyGgUS/HmMjp58qUHdTCq',NULL,'1','2021-03-28 10:51:04','2021-03-28 10:51:42','127.0.0.1');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-03-30  8:13:51
