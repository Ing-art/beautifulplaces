-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-04-2024 a las 12:28:03
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `beautifulplaces`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `text` text DEFAULT NULL,
  `iduser` int(11) DEFAULT NULL,
  `idphoto` int(11) DEFAULT NULL,
  `idplace` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comments`
--

INSERT INTO `comments` (`id`, `text`, `iduser`, `idphoto`, `idplace`, `created_at`) VALUES
(6, 'Beautiful place indeed!', NULL, NULL, 5, '2024-04-23 09:28:36'),
(8, 'I want to delete this!', NULL, NULL, 5, '2024-04-23 09:43:52'),
(12, 'So white!', NULL, NULL, 6, '2024-04-23 10:19:54'),
(16, 'What places did you visit?', NULL, NULL, 1, '2024-04-24 07:02:30'),
(19, 'Wow! amazing place!! What is the best time to visit?\r\n\r\nIs food ok?', 3, NULL, 1, '2024-04-24 09:53:53'),
(20, 'Oh! I was there last year', NULL, NULL, 3, '2024-04-24 10:04:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `errors`
--

CREATE TABLE `errors` (
  `id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `level` varchar(32) NOT NULL DEFAULT 'ERROR',
  `url` varchar(256) NOT NULL,
  `message` varchar(256) NOT NULL,
  `user` varchar(128) DEFAULT NULL,
  `ip` char(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `photos`
--

CREATE TABLE `photos` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `file` varchar(256) NOT NULL,
  `description` text DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `iduser` int(11) DEFAULT NULL,
  `idplace` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `photos`
--

INSERT INTO `photos` (`id`, `name`, `file`, `description`, `date`, `time`, `iduser`, `idplace`, `created_at`, `updated_at`) VALUES
(4, 'Playa bonita', 'photo_662641c51cdff8.25891958.jpg', 'Hello', NULL, NULL, 1, 1, '2024-04-22 10:53:57', '2024-04-22 10:53:57'),
(5, 'Playa loca', 'photo_66264204abe501.23204903.jpg', 'Ha', NULL, NULL, 1, 1, '2024-04-22 10:55:00', '2024-04-22 10:55:00'),
(7, 'Tokyo park', 'photo_662765b3e1e483.88759627.jpg', NULL, NULL, NULL, 3, 4, '2024-04-23 07:39:31', '2024-04-23 07:39:31'),
(8, 'Another beach', 'photo_66276ba54f59d7.85869240.jpeg', 'August 2022', NULL, NULL, 3, 1, '2024-04-23 08:04:53', '2024-04-23 08:07:21'),
(11, 'Into the night', 'photo_66278e40de8fb0.92862719.jpg', 'Incredible view', NULL, NULL, NULL, 7, '2024-04-23 10:32:32', '2024-04-23 10:32:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `places`
--

CREATE TABLE `places` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `type` varchar(128) NOT NULL,
  `location` varchar(128) NOT NULL,
  `description` text DEFAULT NULL,
  `cover` varchar(128) NOT NULL,
  `iduser` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `places`
--

INSERT INTO `places` (`id`, `name`, `type`, `location`, `description`, `cover`, `iduser`, `created_at`, `updated_at`) VALUES
(1, 'Cuba', 'Waterscape', 'Cuba', 'The 5 most beautiful beaches in Cuba · Varadero · Cayo Coco · Playa Paraiso, on the island of Cayo Largo · Playa Ancón · Playa Pilar. Amazing place!!', 'place_66260e37542f31.19829680.jpg', NULL, '2024-04-22 07:13:59', '2024-04-24 07:02:01'),
(3, 'Pyrinees', 'Landscape', 'Benasque', 'The Pyrenees stretch more than 400 kilometres between Navarre, Aragon and Catalonia.', 'place_6627635e4e1502.34303833.jpg', 3, '2024-04-23 07:29:34', '2024-04-23 07:29:34'),
(4, 'Tokyo', 'Cities', 'Japan', 'The capital and largest city of Japan; the economic and cultural center of Japan. synonyms: Edo, Japanese capital, Tokio, Yeddo, Yedo, capital of Japan.', 'place_6627643ad2d197.31900145.jpg', 3, '2024-04-23 07:33:14', '2024-04-23 07:33:14'),
(5, 'Koh Tao', 'Waterscape', 'Thailand', 'The best things to do in Koh Tao, Thailand, from scuba diving and snorkeling to exploring local markets, hiking, and more!', 'place_662764af953500.60184425.jpg', 3, '2024-04-23 07:35:11', '2024-04-23 07:35:11'),
(6, 'Alps', 'Landscape', 'France', 'Characterised by their breathtaking snowcapped peaks and extensive ski runs, the French Alps offer a diverse range of winter activities ', 'place_66276537893926.65951186.jpg', 3, '2024-04-23 07:37:27', '2024-04-23 07:37:27'),
(7, 'Norway', 'Landscape', 'Norway', 'Perfect for families · Beautiful fjords · Stunning nature · Beautiful train journey\\r\\n\\r\\nDestinations: Flåm, Nærøyfjorden, Geirangerfjorden, Fjærlandsfjorden, Lysefjorden', 'place_66278dddcb9262.19956265.jpg', NULL, '2024-04-23 10:30:53', '2024-04-23 10:30:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `displayname` varchar(32) NOT NULL,
  `email` varchar(128) NOT NULL,
  `phone` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`roles`)),
  `picture` varchar(256) DEFAULT NULL,
  `blocked_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `displayname`, `email`, `phone`, `password`, `roles`, `picture`, `blocked_at`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@fastlight.com', '666666677', '81dc9bdb52d04dc20036dbd8313ed055', '[\"ROLE_USER\",\"ROLE_ADMIN\"]', 'user_66260b06a8d893.26642347.jpg', NULL, '2024-04-22 06:58:59', '2024-04-22 07:00:22'),
(2, 'editor', 'editor@fastlight.com', '666666665', '81dc9bdb52d04dc20036dbd8313ed055', '[\"ROLE_USER\", \"ROLE_EDITOR\"]', NULL, NULL, '2024-04-22 06:58:59', NULL),
(3, 'user', 'user@fastlight.com', '666666764', '81dc9bdb52d04dc20036dbd8313ed055', '[\"ROLE_USER\"]', 'user_6628d669db9510.38595306.jpg', NULL, '2024-04-22 06:58:59', '2024-04-24 09:52:41'),
(4, 'test', 'test@fastlight.com', '666666663', '81dc9bdb52d04dc20036dbd8313ed055', '[\"ROLE_USER\", \"ROLE_MODERATOR\"]', NULL, NULL, '2024-04-22 06:58:59', '2024-04-24 06:07:37'),
(5, 'api', 'api@fastlight.com', '666666662', '81dc9bdb52d04dc20036dbd8313ed055', '[\"ROLE_API\"]', NULL, NULL, '2024-04-22 06:58:59', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `iduser` (`iduser`),
  ADD KEY `idplace` (`idplace`),
  ADD KEY `idphoto` (`idphoto`);

--
-- Indices de la tabla `errors`
--
ALTER TABLE `errors`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `photos`
--
ALTER TABLE `photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `iduser` (`iduser`),
  ADD KEY `idplace` (`idplace`);

--
-- Indices de la tabla `places`
--
ALTER TABLE `places`
  ADD PRIMARY KEY (`id`),
  ADD KEY `iduser` (`iduser`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `errors`
--
ALTER TABLE `errors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT de la tabla `photos`
--
ALTER TABLE `photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `places`
--
ALTER TABLE `places`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`iduser`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`idplace`) REFERENCES `places` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comments_ibfk_3` FOREIGN KEY (`idphoto`) REFERENCES `photos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `photos`
--
ALTER TABLE `photos`
  ADD CONSTRAINT `photos_ibfk_1` FOREIGN KEY (`iduser`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `photos_ibfk_2` FOREIGN KEY (`idplace`) REFERENCES `places` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `places`
--
ALTER TABLE `places`
  ADD CONSTRAINT `places_ibfk_1` FOREIGN KEY (`iduser`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
