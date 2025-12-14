/*
SQLyog Ultimate
MySQL - 10.4.17-MariaDB : Database - peimamonitoringdb
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `users` */

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` mediumtext NOT NULL,
  `username` mediumtext NOT NULL,
  `email` mediumtext NOT NULL,
  `password` mediumtext NOT NULL,
  `phone` mediumtext NOT NULL,
  `address` longtext NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `role` int(11) NOT NULL,
  `reset_token` mediumtext NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `img_type` varchar(3000) NOT NULL DEFAULT 'png',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '2020-01-01 01:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`username`,`email`,`password`,`phone`,`address`,`last_login`,`role`,`reset_token`,`status`,`img_type`,`created_at`,`updated_at`) values 
(1,'Administrator','admin','admin@gmail.com','240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9','123456','dsf','2025-12-14 17:12:11',1,'',1,'png','2018-06-27 13:00:16','2020-01-01 01:00:00'),
(2,'Rafiq IT Officer','rafiq_it_officer','oit.peima@punjab.gov.pk','8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92','03334292580','PEIMA Lahore','2025-12-14 17:22:25',2,'',1,'png','2025-12-14 17:22:25','2020-01-01 01:00:00'),
(3,'GPS Talab Wala Satelite Town','36110105','gpstalabwala@pen.org.pk','86ed07513c6ba37f3edda0100c98d204edeee4ac8af61df8739519c0a0199302','0322-4842563','GPS Talab Wala Satelite Town','2025-12-14 17:30:04',3,'',1,'png','2025-12-14 17:30:04','2020-01-01 01:00:00'),
(4,'Haris','focal_lahore','focal_lahore@gmail.com','8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92','03331245678','PEIMA Lahore','2025-12-14 17:32:01',4,'',1,'png','2025-12-14 17:32:01','2020-01-01 01:00:00'),
(5,'AEO Lahore','aeo_lahore','aeo_lahore@gmail.com','8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92','123456','AEO Lahore','2025-12-14 17:33:06',5,'',1,'png','2025-12-14 17:33:06','2020-01-01 01:00:00'),
(6,'CEO Lahore','ceo_lahore','ceo_lahore@gmail.com','8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92','12345678','CEO Lahore','2025-12-14 17:33:39',5,'',1,'png','2025-12-14 17:33:39','2020-01-01 01:00:00'),
(7,'Ali Special Monitor','ali_special_monitor','ali_special@gmail.com','8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92','123456','Lahore','2025-12-14 17:34:36',6,'',1,'png','2025-12-14 17:34:36','2020-01-01 01:00:00');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
