-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 13, 2021 at 08:35 PM
-- Server version: 5.7.24
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `database`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_from_schedule` (IN `Mschedule_id` INT(11), IN `Mworker_id` INT(11), IN `Massigned_date` DATE, IN `Mtime_start` TIME, IN `Mtime_end` TIME, IN `Mclient_id` INT(11), IN `Mservice_id` INT(11), IN `Mprice` INT(11), IN `Mstatus_id` INT(11), OUT `result_delete` TINYINT)  BEGIN
	DECLARE time_start, time_end, time_end_service, last_time, duration, time_end_front TIME;
    DECLARE assigned_date DATE;
    DECLARE amount, amount_start, amount_end, back_id, front_id, schedule_id, worker_id, service_id, client_id, price, status_id INT;
    SET result_delete = 0;
    IF Mclient_id = 0 THEN
    	SET Mclient_id = NULL;
    END IF;
    IF Mservice_id = 0 THEN
    	SET Mservice_id = NULL;
    END IF;
    IF Mprice = 0 THEN
    	SET Mprice = NULL;
    END IF;
    SELECT worker_schedule.id, worker_schedule.worker_id, worker_schedule.time_start, worker_schedule.time_end, worker_schedule.assigned_date, worker_schedule.service_id, worker_schedule.client_id, worker_schedule.price, worker_schedule.status_id INTO schedule_id, worker_id, time_start, time_end, assigned_date, service_id, client_id, price, status_id FROM worker_schedule WHERE worker_schedule.id = Mschedule_id;
    IF (schedule_id = Mschedule_id AND worker_id = Mworker_id AND time_start = Mtime_start AND time_end = Mtime_end AND assigned_date = Massigned_date AND (service_id = Mservice_id OR Mservice_id IS NULL) AND (client_id = Mclient_id OR Mclient_id IS NULL) AND (price = Mprice OR Mprice IS NULL) AND status_id = Mstatus_id) THEN
    	SET result_delete = 1;
        SELECT services.duration INTO duration FROM services WHERE services.service_id = service_id;
		SELECT COUNT(*) INTO amount FROM worker_schedule WHERE worker_schedule.worker_id = worker_id AND worker_schedule.assigned_date = assigned_date;
    	SELECT COUNT(*), worker_schedule.id INTO amount_end, back_id FROM worker_schedule WHERE worker_schedule.client_id IS NULL AND  worker_schedule.worker_id = worker_id AND worker_schedule.time_end = time_start AND worker_schedule.assigned_date = assigned_date GROUP BY worker_schedule.id;
    	SELECT COUNT(*), worker_schedule.id, worker_schedule.time_end INTO amount_start, front_id, time_end_front FROM worker_schedule WHERE worker_schedule.client_id IS NULL AND worker_schedule.worker_id = worker_id AND worker_schedule.time_start = time_end AND worker_schedule.assigned_date = assigned_date GROUP BY worker_schedule.id;
  		CASE
  		WHEN amount_end IS NULL AND amount_start IS NULL THEN
    	IF client_id IS NULL THEN 
 			DELETE FROM worker_schedule WHERE worker_schedule.id = schedule_id;
        ELSE
        	UPDATE worker_schedule SET worker_schedule.client_id = NULL, worker_schedule.service_id = NULL, worker_schedule.price = NULL, worker_schedule.status_id = 0 WHERE worker_schedule.id = schedule_id;
        END IF;
    WHEN amount_end = 1 AND amount_start = 1 THEN
    	UPDATE worker_schedule SET worker_schedule.time_end = time_end_front WHERE worker_schedule.id = back_id;
 		DELETE FROM worker_schedule WHERE worker_schedule.id = schedule_id;
        DELETE FROM worker_schedule WHERE worker_schedule.id = front_id;
    WHEN amount_end IS NULL AND amount_start = 1 THEN
    	UPDATE worker_schedule SET worker_schedule.time_end = time_end_front, worker_schedule.client_id = NULL, worker_schedule.service_id = NULL, worker_schedule.price = NULL, worker_schedule.status_id = 0 WHERE worker_schedule.id = schedule_id;
        DELETE FROM worker_schedule WHERE worker_schedule.id = front_id;
    WHEN amount_end = 1 AND amount_start IS NULL THEN
    	UPDATE worker_schedule SET worker_schedule.time_end = time_end WHERE worker_schedule.id = back_id;
        DELETE FROM worker_schedule WHERE worker_schedule.id = schedule_id;
    ELSE
    	SELECT services.duration INTO duration FROM services WHERE services.service_id = service_id;
  	END CASE;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_from_schedule_test` (IN `schedule_id` INT(11))  BEGIN
	DECLARE time_start, time_end, time_end_service, last_time, duration, time_end_front TIME;
    DECLARE assigned_date DATE;
    DECLARE amount, amount_start, amount_end, back_id, front_id, worker_id, service_id, client_id INT;
    SELECT worker_schedule.worker_id, worker_schedule.time_start, worker_schedule.time_end, worker_schedule.assigned_date, worker_schedule.service_id, worker_schedule.client_id INTO worker_id, time_start, time_end, assigned_date, service_id, client_id FROM worker_schedule WHERE worker_schedule.id = schedule_id;
    SELECT services.duration INTO duration FROM services WHERE services.service_id = service_id;
	SELECT COUNT(*) INTO amount FROM worker_schedule WHERE worker_schedule.worker_id = worker_id AND worker_schedule.assigned_date = assigned_date;
    SELECT COUNT(*), worker_schedule.id INTO amount_end, back_id FROM worker_schedule WHERE worker_schedule.client_id IS NULL AND  worker_schedule.worker_id = worker_id AND worker_schedule.time_end = time_start AND worker_schedule.assigned_date = assigned_date GROUP BY worker_schedule.id;
    SELECT COUNT(*), worker_schedule.id, worker_schedule.time_end INTO amount_start, front_id, time_end_front FROM worker_schedule WHERE worker_schedule.client_id IS NULL AND worker_schedule.worker_id = worker_id AND worker_schedule.time_start = time_end AND worker_schedule.assigned_date = assigned_date GROUP BY worker_schedule.id;
  	CASE
  	WHEN amount_end IS NULL AND amount_start IS NULL THEN
    	IF client_id IS NULL THEN 
 			DELETE FROM worker_schedule WHERE worker_schedule.id = schedule_id;
        ELSE
        	UPDATE worker_schedule SET worker_schedule.client_id = NULL, worker_schedule.service_id = NULL, worker_schedule.price = NULL, worker_schedule.status_id = 0 WHERE worker_schedule.id = schedule_id;
        END IF;
    WHEN amount_end = 1 AND amount_start = 1 THEN
    	UPDATE worker_schedule SET worker_schedule.time_end = time_end_front WHERE worker_schedule.id = back_id;
 		DELETE FROM worker_schedule WHERE worker_schedule.id = schedule_id;
        DELETE FROM worker_schedule WHERE worker_schedule.id = front_id;
    WHEN amount_end IS NULL AND amount_start = 1 THEN
    	UPDATE worker_schedule SET worker_schedule.time_end = time_end_front, worker_schedule.client_id = NULL, worker_schedule.service_id = NULL, worker_schedule.price = NULL, worker_schedule.status_id = 0 WHERE worker_schedule.id = schedule_id;
        DELETE FROM worker_schedule WHERE worker_schedule.id = front_id;
    WHEN amount_end = 1 AND amount_start IS NULL THEN
    	UPDATE worker_schedule SET worker_schedule.time_end = time_end WHERE worker_schedule.id = back_id;
        DELETE FROM worker_schedule WHERE worker_schedule.id = schedule_id;
    ELSE
    	SELECT services.duration INTO duration FROM services WHERE services.service_id = service_id;
  	END CASE;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RA` (IN `schedule_id` INT, IN `duration` TIME, IN `time_start` TIME, IN `time_end` TIME)  BEGIN 
    DECLARE new_time_start TIME;
    SET new_time_start = time_end - duration;
    SELECT new_time_start; 
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `register_appointment` (IN `Mworker_id` INT(11), IN `Massigned_date` DATE, IN `Mtime_start` TIME, IN `selected_client_id` INT(11), IN `selected_service_id` INT(11), IN `selected_time` TIME, OUT `result_assign` TINYINT)  BEGIN
	DECLARE time_start, time_end, time_end_service, last_time, duration TIME;
	DECLARE assigned_date DATE;
	DECLARE schedule_id, price, worker_id, client_id, service_id, lock_result INT;

	DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
			ROLLBACK;
		END;

	SET result_assign = 0;

	SELECT services.duration,
		   services.price
	INTO duration,
		 price
	FROM services
	WHERE services.service_id = selected_service_id;

	SET lock_result = GET_LOCK('schedule',10);
	IF lock_result = 1 THEN

	START TRANSACTION;

    SET time_end_service = ADDTIME(selected_time, duration);

	SELECT worker_schedule.id,
           worker_schedule.worker_id,
           worker_schedule.time_start,
           worker_schedule.time_end,
           worker_schedule.assigned_date,
           worker_schedule.client_id,
           worker_schedule.service_id
    INTO schedule_id, worker_id, time_start, time_end, assigned_date, client_id, service_id
    FROM worker_schedule
    WHERE worker_schedule.worker_id = Mworker_id AND
          worker_schedule.assigned_date = Massigned_date AND
          worker_schedule.client_id IS NULL AND
          worker_schedule.time_start <= selected_time AND
          worker_schedule.time_end >= time_end_service;



		IF (Mworker_id = worker_id AND Massigned_date = assigned_date) THEN

   		SET last_time = TIMEDIFF(time_end, duration);

      CASE
    	WHEN selected_time = time_start AND time_end_service = time_end THEN

        UPDATE worker_schedule
		SET worker_schedule.client_id = selected_client_id,
			worker_schedule.service_id = selected_service_id,
			worker_schedule.price = price,
			worker_schedule.status_id = 1
		WHERE worker_schedule.id = schedule_id;

    	WHEN selected_time = time_start AND time_end_service < time_end THEN

       	UPDATE worker_schedule
		SET worker_schedule.time_end = time_end_service,
			worker_schedule.client_id = selected_client_id,
			worker_schedule.service_id = selected_service_id,
			worker_schedule.price = price,
			worker_schedule.status_id = 1
		WHERE worker_schedule.id = schedule_id;

        INSERT INTO worker_schedule(worker_id, assigned_date, time_start, time_end, client_id, service_id, price, status_id)
		VALUES (worker_id, assigned_date, time_end_service, time_end, NULL, NULL, NULL, 0);

    	WHEN selected_time = last_time AND selected_time > time_start THEN

  		UPDATE worker_schedule
		SET worker_schedule.time_end = selected_time
		WHERE worker_schedule.id = schedule_id;

  		INSERT INTO worker_schedule(worker_id, assigned_date, time_start, time_end, client_id, service_id, price, status_id)
		VALUES (worker_id, assigned_date, selected_time, time_end, selected_client_id, selected_service_id, price, 1);

		ELSE

  		UPDATE worker_schedule
		SET worker_schedule.time_end = selected_time
		WHERE worker_schedule.id = schedule_id;

  		INSERT INTO worker_schedule(worker_id, assigned_date, time_start, time_end, client_id, service_id, price, status_id)
		VALUES (worker_id, assigned_date, selected_time, time_end_service, selected_client_id, selected_service_id, price, 1);

  		INSERT INTO worker_schedule(worker_id, assigned_date, time_start, time_end, client_id, service_id, price, status_id)
		VALUES (worker_id, assigned_date, time_end_service, time_end, NULL, NULL, NULL, 0);

    	END CASE;

    SET result_assign = 1;
    END IF;

	COMMIT;
	SET lock_result = RELEASE_LOCK('schedule');

	END IF;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `register_appointment_test` (IN `schedule_id` INT(11), IN `client_id` INT(11) UNSIGNED, IN `service_id` INT(11) UNSIGNED, IN `selected_time` TIME)  BEGIN
	DECLARE time_start, time_end, time_end_service, last_time, duration TIME;
    DECLARE assigned_date DATE;
    DECLARE price, worker_id INT;
    SELECT worker_schedule.worker_id, worker_schedule.time_start, worker_schedule.time_end, worker_schedule.assigned_date INTO worker_id, time_start, time_end, assigned_date FROM worker_schedule WHERE worker_schedule.id = schedule_id;
    SELECT services.duration, services.price INTO duration, price FROM services WHERE services.service_id = service_id;
    SET time_end_service = ADDTIME(selected_time, duration);
    SET last_time = TIMEDIFF(time_end, duration);
    CASE
    WHEN selected_time = time_start AND time_end_service = time_end THEN
        UPDATE worker_schedule SET worker_schedule.client_id = client_id, worker_schedule.service_id = service_id, worker_schedule.price = price, worker_schedule.status_id = 1 WHERE worker_schedule.id = schedule_id;
    WHEN selected_time = time_start AND time_end_service < time_end THEN
        UPDATE worker_schedule SET worker_schedule.time_end = time_end_service, worker_schedule.client_id = client_id, worker_schedule.service_id = service_id, worker_schedule.price = price, worker_schedule.status_id = 1 WHERE worker_schedule.id = schedule_id;
        INSERT INTO worker_schedule(worker_id, assigned_date, time_start, time_end, client_id, service_id, price, status_id) VALUES (worker_id, assigned_date, time_end_service, time_end, NULL, NULL, NULL, 0);
    WHEN selected_time = last_time AND selected_time > time_start THEN
  		UPDATE worker_schedule SET worker_schedule.time_end = selected_time WHERE worker_schedule.id = schedule_id;
  		INSERT INTO worker_schedule(worker_id, assigned_date, time_start, time_end, client_id, service_id, price, status_id) VALUES (worker_id, assigned_date, selected_time, time_end, client_id, service_id, price, 1);
	ELSE
  		UPDATE worker_schedule SET worker_schedule.time_end = selected_time WHERE worker_schedule.id = schedule_id;
  		INSERT INTO worker_schedule(worker_id, assigned_date, time_start, time_end, client_id, service_id, price, status_id) VALUES (worker_id, assigned_date, selected_time, time_end_service, client_id, service_id, price, 1);
  		INSERT INTO worker_schedule(worker_id, assigned_date, time_start, time_end, client_id, service_id, price, status_id) VALUES (worker_id, assigned_date, time_end_service, time_end, NULL, NULL, NULL, 0);
    END CASE;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `client_id` int(11) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `registration_date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`client_id`, `client_name`, `phone_number`, `registration_date`) VALUES
(1, 'Дорошенко Максим Анатольевич', '79152281137', '2021-07-08'),
(2, 'Коков Василий Николаевич', '79166641844', '2021-06-30'),
(3, 'Маляров Иван Сергеевич', '79105249775', '2021-07-06'),
(4, 'Андрей', '79005007711', '2021-08-07'),
(5, 'Светлана', '79993610471', '2021-08-07'),
(6, 'Алексей', '79005453919', '2021-08-07'),
(7, 'Татьяна', '78881437510', '2021-08-07'),
(8, 'Марина', '78851014485', '2021-08-07'),
(11, 'Никита', '79163241839', '2021-08-07'),
(12, 'Дмитрий', '79113661931', '2021-08-07'),
(13, 'Василий', '79182231593', '2021-08-09');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` int(11) UNSIGNED NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `price` int(11) UNSIGNED NOT NULL,
  `duration` time NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `service_name`, `price`, `duration`, `description`) VALUES
(1, 'Стрижка мужская обычная', 300, '00:30:00', ''),
(2, 'Стрижка мужская модельная', 500, '00:40:00', ''),
(3, 'Стрижка женская обычная', 500, '00:40:00', ''),
(4, 'Стрижка женская модельная', 700, '00:50:00', ''),
(5, 'Бритье бороды', 250, '00:20:00', ''),
(7, 'Окрас волос', 1200, '00:50:00', ''),
(8, 'Укладка', 250, '00:20:00', ''),
(9, 'Моделирование бороды', 500, '00:30:00', ''),
(10, 'Окантовка машинкой', 300, '00:20:00', '');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `status_id` int(11) NOT NULL,
  `status_name` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`status_id`, `status_name`) VALUES
(1, 'Не подтверждено'),
(2, 'Подтверждено'),
(3, 'Исполнено'),
(0, '');

-- --------------------------------------------------------

--
-- Table structure for table `workers`
--

CREATE TABLE `workers` (
  `worker_id` int(11) UNSIGNED NOT NULL,
  `worker_name` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `workers`
--

INSERT INTO `workers` (`worker_id`, `worker_name`, `phone_number`) VALUES
(2, 'Каруля Валерия Михайловна', '79452349137'),
(3, 'Кузнецов Андрей Викторович', '79113541018'),
(4, 'Хадонова Светлана Николаевна', '79155183978'),
(5, 'Иванов Николай Сергеевич', '79165498736'),
(6, 'Щедрина Елена Анатольевна', '79156141044'),
(7, 'Смирнова Светлана Александровна', '79459893721'),
(8, 'Баранова Анастасия Ивановна', '79853995811');

-- --------------------------------------------------------

--
-- Table structure for table `workers_services`
--

CREATE TABLE `workers_services` (
  `worker_id` int(11) UNSIGNED NOT NULL,
  `service_id` int(11) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `workers_services`
--

INSERT INTO `workers_services` (`worker_id`, `service_id`) VALUES
(2, 3),
(2, 4),
(2, 9),
(2, 10),
(3, 1),
(3, 2),
(3, 5),
(4, 1),
(4, 2),
(4, 3),
(4, 4),
(4, 8),
(4, 9),
(4, 10),
(5, 1),
(5, 2),
(5, 5),
(5, 9),
(6, 1),
(6, 2),
(6, 3),
(6, 4),
(7, 1),
(7, 2),
(7, 3),
(7, 4),
(7, 7),
(7, 8),
(8, 1),
(8, 2),
(8, 3),
(8, 4),
(8, 7),
(8, 8);

-- --------------------------------------------------------

--
-- Table structure for table `worker_schedule`
--

CREATE TABLE `worker_schedule` (
  `id` int(11) NOT NULL,
  `worker_id` int(11) NOT NULL,
  `assigned_date` date NOT NULL,
  `time_start` time NOT NULL,
  `time_end` time NOT NULL,
  `client_id` int(11) UNSIGNED DEFAULT NULL,
  `service_id` int(11) UNSIGNED DEFAULT NULL,
  `price` int(11) UNSIGNED DEFAULT NULL,
  `status_id` int(11) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `worker_schedule`
--

INSERT INTO `worker_schedule` (`id`, `worker_id`, `assigned_date`, `time_start`, `time_end`, `client_id`, `service_id`, `price`, `status_id`) VALUES
(1, 2, '2021-07-06', '09:00:00', '18:00:00', NULL, NULL, NULL, 0),
(2, 3, '2021-07-07', '09:00:00', '18:00:00', 0, 0, 0, 0),
(5, 2, '2021-07-07', '09:00:00', '18:00:00', 0, 0, 0, 0),
(4, 3, '2021-07-06', '09:00:00', '18:00:00', 0, 0, 0, 0),
(6, 8, '2021-07-07', '09:00:00', '18:00:00', 0, 0, 0, 0),
(7, 8, '2021-07-08', '09:00:00', '18:00:00', 0, 0, 0, 0),
(8, 8, '2021-07-09', '09:00:00', '18:00:00', 0, 0, 0, 0),
(9, 8, '2021-07-10', '09:00:00', '18:00:00', 0, 0, 0, 0),
(10, 4, '2021-07-11', '09:00:00', '18:00:00', 0, 0, 0, 0),
(11, 4, '2021-07-12', '09:00:00', '18:00:00', 0, 0, 0, 0),
(12, 4, '2021-07-13', '09:00:00', '18:00:00', 0, 0, 0, 0),
(13, 4, '2021-07-14', '09:00:00', '18:00:00', 0, 0, 0, 0),
(14, 4, '2021-07-15', '09:00:00', '18:00:00', 0, 0, 0, 0),
(15, 4, '2021-07-16', '09:00:00', '18:00:00', 0, 0, 0, 0),
(16, 4, '2021-07-17', '09:00:00', '18:00:00', 0, 0, 0, 0),
(54, 5, '2021-07-20', '10:00:00', '10:40:00', 2, 2, 500, 3),
(25, 6, '2021-07-08', '09:00:00', '18:00:00', 0, 0, 0, 0),
(27, 5, '2021-07-15', '08:00:00', '17:00:00', 0, 0, 0, 0),
(20, 7, '2021-07-12', '10:00:00', '19:00:00', 0, 0, 0, 0),
(21, 7, '2021-07-13', '10:00:00', '19:00:00', 0, 0, 0, 0),
(22, 7, '2021-07-14', '10:00:00', '19:00:00', 0, 0, 0, 0),
(23, 7, '2021-07-15', '10:00:00', '19:00:00', 0, 0, 0, 0),
(33, 5, '2021-07-12', '10:00:00', '15:00:00', 0, 0, 0, 0),
(26, 6, '2021-07-09', '09:00:00', '18:00:00', 0, 0, 0, 0),
(29, 6, '2021-07-12', '13:00:00', '20:00:00', 0, 0, 0, 0),
(34, 5, '2021-07-10', '09:00:00', '18:00:00', NULL, NULL, NULL, 0),
(43, 2, '2021-07-17', '09:00:00', '09:30:00', 3, 1, 300, 0),
(56, 2, '2021-07-20', '09:00:00', '09:30:00', 3, 1, 300, 3),
(57, 2, '2021-07-20', '09:30:00', '17:00:00', NULL, NULL, NULL, 0),
(58, 3, '2021-07-20', '10:00:00', '10:30:00', 2, 1, 300, 3),
(59, 4, '2021-07-20', '09:00:00', '09:30:00', 1, 1, 300, 3),
(60, 4, '2021-07-20', '09:30:00', '18:00:00', NULL, NULL, NULL, 0),
(61, 3, '2021-07-20', '10:30:00', '18:00:00', NULL, NULL, NULL, 0),
(63, 8, '2021-07-20', '11:00:00', '11:30:00', 3, 1, 300, 3),
(64, 8, '2021-07-20', '11:30:00', '15:00:00', NULL, NULL, NULL, 0),
(65, 6, '2021-07-20', '09:00:00', '09:30:00', 2, 1, 300, 3),
(66, 6, '2021-07-20', '09:30:00', '10:00:00', 2, 1, 300, 3),
(67, 6, '2021-07-20', '10:00:00', '12:00:00', NULL, NULL, NULL, 0),
(68, 6, '2021-07-20', '16:30:00', '17:00:00', 2, 1, 300, 3),
(69, 6, '2021-07-20', '12:00:00', '12:40:00', 2, 2, 500, 3),
(70, 6, '2021-07-20', '12:40:00', '16:30:00', 2, 2, 500, 3),
(71, 2, '2021-07-21', '10:00:00', '11:30:00', NULL, NULL, NULL, 0),
(72, 2, '2021-07-22', '10:00:00', '15:00:00', NULL, NULL, NULL, 0),
(73, 2, '2021-07-21', '11:30:00', '12:00:00', 3, 1, 300, 0),
(74, 2, '2021-07-21', '12:00:00', '15:00:00', NULL, NULL, NULL, 0),
(75, 3, '2021-07-22', '10:10:00', '17:00:00', NULL, NULL, NULL, 0),
(76, 5, '2021-07-22', '10:20:00', '13:30:00', NULL, NULL, NULL, 0),
(122, 5, '2021-07-27', '10:00:00', '14:00:00', NULL, NULL, NULL, 0),
(124, 5, '2021-07-27', '14:00:00', '14:30:00', 3, 1, 300, 1),
(127, 5, '2021-07-27', '17:00:00', '18:00:00', NULL, NULL, NULL, 0),
(170, 3, '2021-08-01', '10:00:00', '15:00:00', NULL, NULL, NULL, 0),
(81, 2, '2021-07-23', '09:00:00', '18:00:00', NULL, NULL, NULL, 0),
(82, 2, '2021-07-24', '09:00:00', '17:00:00', NULL, NULL, NULL, 0),
(83, 2, '2021-07-25', '09:00:00', '17:00:00', NULL, NULL, NULL, 0),
(125, 5, '2021-07-27', '14:30:00', '16:30:00', NULL, NULL, NULL, 0),
(130, 2, '2021-07-28', '10:00:00', '17:00:00', NULL, NULL, NULL, 0),
(86, 2, '2021-07-28', '09:00:00', '09:30:00', 3, 1, 300, 1),
(136, 2, '2021-07-29', '10:00:00', '10:30:00', 3, 1, 300, 1),
(88, 2, '2021-07-30', '09:00:00', '17:00:00', NULL, NULL, NULL, 0),
(89, 3, '2021-07-23', '10:00:00', '16:00:00', NULL, NULL, NULL, 0),
(126, 5, '2021-07-27', '16:30:00', '17:00:00', 2, 1, 300, 1),
(91, 3, '2021-07-25', '10:00:00', '16:00:00', NULL, NULL, NULL, 0),
(100, 2, '2021-07-26', '09:00:00', '16:00:00', NULL, NULL, NULL, 0),
(128, 2, '2021-07-28', '09:30:00', '10:00:00', 3, 1, 300, 1),
(150, 2, '2021-07-29', '10:30:00', '18:00:00', NULL, NULL, NULL, 0),
(203, 2, '2021-08-03', '09:30:00', '10:00:00', 1, 1, 300, 3),
(202, 2, '2021-08-03', '09:00:00', '09:30:00', 3, 1, 300, 3),
(174, 5, '2021-08-01', '09:00:00', '17:00:00', NULL, NULL, NULL, 0),
(184, 2, '2021-08-02', '09:00:00', '09:40:00', 4, 2, 500, 1),
(210, 3, '2021-08-05', '09:40:00', '18:00:00', NULL, NULL, NULL, 0),
(206, 2, '2021-08-03', '10:00:00', '18:00:00', NULL, NULL, NULL, 0),
(209, 3, '2021-08-05', '09:00:00', '09:40:00', 2, 2, 500, 1),
(225, 3, '2021-08-06', '09:00:00', '09:20:00', 1, 5, 250, 1),
(224, 7, '2021-08-06', '15:30:00', '17:00:00', NULL, NULL, NULL, 0),
(213, 2, '2021-08-04', '09:00:00', '09:30:00', 3, 1, 300, 1),
(214, 2, '2021-08-04', '09:30:00', '18:00:00', NULL, NULL, NULL, 0),
(215, 2, '2021-08-07', '09:00:00', '09:40:00', 2, 2, 500, 3),
(216, 2, '2021-08-07', '09:40:00', '10:20:00', 5, 3, 500, 3),
(227, 2, '2021-08-07', '10:20:00', '11:10:00', 8, 4, 700, 3),
(218, 2, '2021-08-08', '09:00:00', '18:00:00', NULL, NULL, NULL, 0),
(290, 3, '2021-08-10', '14:30:00', '15:00:00', 6, 1, 300, 1),
(289, 3, '2021-08-10', '14:00:00', '14:30:00', 13, 1, 300, 3),
(221, 7, '2021-08-06', '08:00:00', '14:00:00', NULL, NULL, NULL, 0),
(222, 7, '2021-08-06', '14:00:00', '14:40:00', 3, 3, 500, 1),
(223, 7, '2021-08-06', '14:40:00', '15:30:00', 2, 4, 700, 1),
(226, 3, '2021-08-06', '09:20:00', '17:00:00', NULL, NULL, NULL, 0),
(228, 2, '2021-08-07', '11:10:00', '18:00:00', NULL, NULL, NULL, 0),
(229, 5, '2021-08-02', '09:00:00', '09:30:00', 12, 1, 300, 1),
(230, 5, '2021-08-03', '09:00:00', '18:00:00', NULL, NULL, NULL, 0),
(231, 5, '2021-08-04', '09:00:00', '18:00:00', NULL, NULL, NULL, 0),
(232, 5, '2021-08-05', '09:00:00', '18:00:00', NULL, NULL, NULL, 0),
(233, 5, '2021-08-06', '09:00:00', '09:30:00', 4, 9, 500, 1),
(234, 5, '2021-08-07', '09:00:00', '15:00:00', NULL, NULL, NULL, 0),
(235, 5, '2021-08-08', '09:00:00', '12:00:00', NULL, NULL, NULL, 0),
(292, 3, '2021-08-10', '15:00:00', '15:30:00', 6, 1, 300, 2),
(237, 5, '2021-08-08', '14:00:00', '14:20:00', 12, 5, 250, 1),
(238, 5, '2021-08-08', '14:20:00', '18:00:00', NULL, NULL, NULL, 0),
(239, 5, '2021-08-08', '13:00:00', '13:40:00', 11, 2, 500, 1),
(240, 5, '2021-08-08', '13:40:00', '14:00:00', NULL, NULL, NULL, 0),
(241, 5, '2021-08-07', '15:00:00', '15:30:00', 6, 1, 300, 2),
(242, 5, '2021-08-07', '15:30:00', '15:50:00', 13, 5, 250, 2),
(243, 5, '2021-08-06', '09:30:00', '18:00:00', NULL, NULL, NULL, 0),
(244, 8, '2021-08-02', '10:00:00', '13:00:00', NULL, NULL, NULL, 0),
(245, 8, '2021-08-03', '10:00:00', '10:50:00', 7, 7, 1200, 1),
(246, 8, '2021-08-04', '10:00:00', '14:30:00', NULL, NULL, NULL, 0),
(247, 8, '2021-08-05', '10:00:00', '10:40:00', 6, 2, 500, 1),
(248, 8, '2021-08-06', '10:00:00', '15:00:00', NULL, NULL, NULL, 0),
(249, 8, '2021-08-07', '10:00:00', '18:00:00', NULL, NULL, NULL, 0),
(250, 8, '2021-08-08', '10:00:00', '18:00:00', NULL, NULL, NULL, 0),
(251, 8, '2021-08-02', '13:00:00', '13:50:00', 8, 4, 700, 1),
(252, 8, '2021-08-02', '13:50:00', '14:10:00', 8, 8, 250, 1),
(253, 8, '2021-08-02', '14:10:00', '18:00:00', NULL, NULL, NULL, 0),
(254, 8, '2021-08-03', '10:50:00', '11:40:00', 5, 7, 1200, 3),
(255, 8, '2021-08-03', '11:40:00', '18:00:00', NULL, NULL, NULL, 0),
(256, 8, '2021-08-04', '14:30:00', '14:50:00', 5, 8, 250, 1),
(257, 8, '2021-08-04', '14:50:00', '18:00:00', NULL, NULL, NULL, 0),
(258, 7, '2021-08-04', '09:00:00', '11:30:00', NULL, NULL, NULL, 0),
(259, 7, '2021-08-04', '11:30:00', '12:00:00', 4, 1, 300, 1),
(260, 7, '2021-08-04', '12:00:00', '17:00:00', NULL, NULL, NULL, 0),
(261, 5, '2021-08-02', '09:30:00', '18:00:00', NULL, NULL, NULL, 0),
(262, 8, '2021-08-06', '15:00:00', '15:20:00', 6, 8, 250, 1),
(263, 8, '2021-08-06', '15:20:00', '18:00:00', NULL, NULL, NULL, 0),
(264, 3, '2021-08-02', '09:00:00', '13:30:00', NULL, NULL, NULL, 0),
(265, 3, '2021-08-02', '13:30:00', '13:50:00', 11, 5, 250, 1),
(266, 3, '2021-08-02', '13:50:00', '17:00:00', NULL, NULL, NULL, 0),
(267, 7, '2021-08-02', '09:00:00', '13:00:00', NULL, NULL, NULL, 0),
(268, 7, '2021-08-02', '13:00:00', '13:50:00', 11, 7, 1200, 1),
(269, 7, '2021-08-02', '13:50:00', '18:00:00', NULL, NULL, NULL, 0),
(270, 2, '2021-08-02', '09:40:00', '10:10:00', 13, 1, 300, 1),
(271, 5, '2021-08-08', '12:00:00', '12:40:00', 12, 2, 500, 1),
(272, 5, '2021-08-08', '12:40:00', '13:00:00', NULL, NULL, NULL, 0),
(273, 3, '2021-08-08', '08:00:00', '08:40:00', 4, 2, 500, 3),
(274, 3, '2021-08-08', '08:40:00', '16:00:00', NULL, NULL, NULL, 0),
(275, 7, '2021-08-07', '08:00:00', '12:30:00', NULL, NULL, NULL, 0),
(276, 7, '2021-08-07', '12:30:00', '13:20:00', 7, 4, 700, 1),
(277, 7, '2021-08-07', '13:20:00', '17:00:00', NULL, NULL, NULL, 0),
(278, 8, '2021-08-05', '10:40:00', '18:00:00', NULL, NULL, NULL, 0),
(279, 2, '2021-08-02', '10:10:00', '17:00:00', NULL, NULL, NULL, 0),
(280, 5, '2021-08-07', '15:50:00', '18:00:00', NULL, NULL, NULL, 0),
(288, 3, '2021-08-13', '09:00:00', '18:00:00', NULL, NULL, NULL, 0),
(287, 3, '2021-08-12', '09:00:00', '18:00:00', NULL, NULL, NULL, 0),
(286, 3, '2021-08-11', '09:00:00', '18:00:00', NULL, NULL, NULL, 0),
(285, 3, '2021-08-10', '09:00:00', '14:00:00', NULL, NULL, NULL, 0),
(293, 3, '2021-08-10', '15:30:00', '16:00:00', 11, 1, 300, 1),
(294, 3, '2021-08-10', '16:00:00', '18:00:00', NULL, NULL, NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `workers`
--
ALTER TABLE `workers`
  ADD PRIMARY KEY (`worker_id`);

--
-- Indexes for table `workers_services`
--
ALTER TABLE `workers_services`
  ADD PRIMARY KEY (`worker_id`,`service_id`);

--
-- Indexes for table `worker_schedule`
--
ALTER TABLE `worker_schedule`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `workers`
--
ALTER TABLE `workers`
  MODIFY `worker_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `worker_schedule`
--
ALTER TABLE `worker_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=301;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
