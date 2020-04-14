-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 14 Nis 2020, 23:45:32
-- Sunucu sürümü: 10.1.38-MariaDB
-- PHP Sürümü: 7.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `kandilli`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `earthquakes`
--

CREATE TABLE `earthquakes` (
  `eq_id` bigint(20) NOT NULL,
  `eq_date` date NOT NULL,
  `eq_time` time NOT NULL,
  `eq_latitude` varchar(100) NOT NULL,
  `eq_longitude` varchar(100) NOT NULL,
  `eq_depth` double NOT NULL,
  `eq_md` double NOT NULL,
  `eq_ml` double NOT NULL,
  `eq_mw` double NOT NULL,
  `eq_location` varchar(250) NOT NULL,
  `eq_revize` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `earthquakes`
--
ALTER TABLE `earthquakes`
  ADD PRIMARY KEY (`eq_id`),
  ADD KEY `eq_time` (`eq_time`),
  ADD KEY `eq_latitude` (`eq_latitude`),
  ADD KEY `eq_longitude` (`eq_longitude`),
  ADD KEY `eq_depth` (`eq_depth`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `earthquakes`
--
ALTER TABLE `earthquakes`
  MODIFY `eq_id` bigint(20) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
