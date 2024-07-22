-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 22-07-2024 a las 08:15:43
-- Versión del servidor: 8.0.30
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ferremas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carritos`
--

CREATE TABLE `carritos` (
  `id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` enum('activo','completado','cancelado') COLLATE utf8mb4_general_ci DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carritos`
--

INSERT INTO `carritos` (`id`, `usuario_id`, `fecha_creacion`, `estado`) VALUES
(3, 15, '2024-07-11 06:45:19', 'completado'),
(4, 16, '2024-07-11 09:30:44', 'completado'),
(5, 16, '2024-07-11 10:22:20', 'completado'),
(6, 16, '2024-07-11 10:24:54', 'completado'),
(7, 16, '2024-07-11 10:28:43', 'completado'),
(8, 16, '2024-07-11 10:29:22', 'completado'),
(9, 15, '2024-07-11 10:39:30', 'completado'),
(10, 15, '2024-07-11 10:43:18', 'completado'),
(11, 15, '2024-07-11 10:54:43', 'completado'),
(12, 15, '2024-07-11 11:04:41', 'completado'),
(13, 15, '2024-07-11 21:35:15', 'completado'),
(14, 17, '2024-07-17 03:17:50', 'completado'),
(15, 15, '2024-07-21 02:25:18', 'completado'),
(16, 18, '2024-07-21 03:21:48', 'completado'),
(17, 18, '2024-07-21 03:32:20', 'completado'),
(18, 18, '2024-07-21 03:37:02', 'completado'),
(19, 19, '2024-07-21 03:38:47', 'completado'),
(20, 19, '2024-07-21 03:45:38', 'completado'),
(21, 19, '2024-07-21 03:54:04', 'completado'),
(22, 19, '2024-07-21 04:00:15', 'completado'),
(23, 19, '2024-07-21 23:39:53', 'completado'),
(24, 19, '2024-07-21 23:44:48', 'completado'),
(25, 19, '2024-07-21 23:48:09', 'completado'),
(26, 19, '2024-07-21 23:50:37', 'completado'),
(27, 19, '2024-07-21 23:53:45', 'completado'),
(28, 19, '2024-07-21 23:59:13', 'completado'),
(29, 19, '2024-07-22 00:12:21', 'completado'),
(30, 19, '2024-07-22 00:15:19', 'completado'),
(31, 19, '2024-07-22 00:16:33', 'completado'),
(32, 19, '2024-07-22 00:17:57', 'completado'),
(33, 19, '2024-07-22 00:24:45', 'completado'),
(34, 19, '2024-07-22 00:33:43', 'completado'),
(35, 19, '2024-07-22 00:37:17', 'completado'),
(36, 19, '2024-07-22 01:18:14', 'completado'),
(37, 19, '2024-07-22 01:23:35', 'completado'),
(38, 19, '2024-07-22 01:25:02', 'completado'),
(39, 19, '2024-07-22 01:59:35', 'completado'),
(40, 19, '2024-07-22 02:00:14', 'completado'),
(41, 19, '2024-07-22 02:21:50', 'completado'),
(42, 19, '2024-07-22 02:23:23', 'completado'),
(43, 19, '2024-07-22 02:27:19', 'completado'),
(44, 19, '2024-07-22 02:31:44', 'completado'),
(45, 19, '2024-07-22 02:33:03', 'completado'),
(46, 19, '2024-07-22 02:33:45', 'completado'),
(47, 19, '2024-07-22 02:34:08', 'completado'),
(48, 19, '2024-07-22 02:37:09', 'completado'),
(49, 19, '2024-07-22 02:38:51', 'completado'),
(50, 19, '2024-07-22 02:40:29', 'completado'),
(51, 19, '2024-07-22 03:24:35', 'completado'),
(52, 19, '2024-07-22 03:41:50', 'completado'),
(53, 19, '2024-07-22 03:44:49', 'completado'),
(54, 19, '2024-07-22 03:47:49', 'completado'),
(55, 19, '2024-07-22 05:07:26', 'completado'),
(56, 19, '2024-07-22 05:24:32', 'completado'),
(57, 19, '2024-07-22 05:25:45', 'completado'),
(58, 19, '2024-07-22 05:27:10', 'completado'),
(59, 19, '2024-07-22 05:30:57', 'completado'),
(60, 19, '2024-07-22 05:36:35', 'completado'),
(61, 19, '2024-07-22 05:45:10', 'completado'),
(62, 19, '2024-07-22 05:46:42', 'completado'),
(63, 19, '2024-07-22 05:49:49', 'completado'),
(64, 19, '2024-07-22 05:58:08', 'completado'),
(65, 19, '2024-07-22 06:00:22', 'completado'),
(66, 19, '2024-07-22 06:00:54', 'completado'),
(67, 19, '2024-07-22 06:26:20', 'completado'),
(68, 19, '2024-07-22 06:31:01', 'completado'),
(69, 19, '2024-07-22 06:32:41', 'completado'),
(70, 19, '2024-07-22 06:39:44', 'completado'),
(71, 19, '2024-07-22 06:49:49', 'completado'),
(72, 9, '2024-07-22 07:16:42', 'completado'),
(73, 9, '2024-07-22 07:18:33', 'completado'),
(74, 9, '2024-07-22 07:23:19', 'completado'),
(75, 9, '2024-07-22 07:28:01', 'completado'),
(76, 9, '2024-07-22 07:28:46', 'completado'),
(77, 9, '2024-07-22 07:29:45', 'completado'),
(78, 15, '2024-07-22 07:37:37', 'completado'),
(79, 15, '2024-07-22 07:39:24', 'completado'),
(80, 15, '2024-07-22 07:39:58', 'completado'),
(81, 15, '2024-07-22 07:40:45', 'completado'),
(82, 19, '2024-07-22 07:41:18', 'completado'),
(83, 9, '2024-07-22 07:45:20', 'completado'),
(84, 9, '2024-07-22 07:48:58', 'completado'),
(85, 9, '2024-07-22 07:49:21', 'completado'),
(86, 9, '2024-07-22 07:52:29', 'completado'),
(87, 16, '2024-07-22 08:00:10', 'completado'),
(88, 19, '2024-07-22 08:14:17', 'completado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito_productos`
--

CREATE TABLE `carrito_productos` (
  `id` int NOT NULL,
  `carrito_id` int NOT NULL,
  `producto_id` int NOT NULL,
  `cantidad` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carrito_productos`
--

INSERT INTO `carrito_productos` (`id`, `carrito_id`, `producto_id`, `cantidad`) VALUES
(12, 21, 33, 1),
(13, 22, 33, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`) VALUES
(2, 'Herramienta'),
(10, 'Muebles'),
(11, 'Pisos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `egresos`
--

CREATE TABLE `egresos` (
  `id` int NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `finanzas`
--

CREATE TABLE `finanzas` (
  `id` int NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `finanzas`
--

INSERT INTO `finanzas` (`id`, `descripcion`, `monto`, `fecha`) VALUES
(1, 'Nosabriadecir', 13990.00, '2024-06-13 20:09:41'),
(2, 'compra de herramientas wey', 2990.00, '2024-06-15 03:50:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_compras`
--

CREATE TABLE `historial_compras` (
  `id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `carrito_id` int NOT NULL,
  `producto_nombre` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `cantidad` int NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `fecha_compra` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial_compras`
--

INSERT INTO `historial_compras` (`id`, `usuario_id`, `carrito_id`, `producto_nombre`, `cantidad`, `precio`, `total`, `fecha_compra`) VALUES
(1, 9, 74, 'Cerrucho', 1, 4990.00, 4990.00, '2024-07-22 07:27:29'),
(2, 15, 79, 'Cerrucho', 1, 4990.00, 4990.00, '2024-07-22 11:39:38'),
(3, 15, 80, 'Cerrucho', 1, 4990.00, 4990.00, '2024-07-22 11:40:10'),
(4, 9, 83, 'Cerrucho', 1, 4990.00, 4990.00, '2024-07-22 07:47:32'),
(5, 9, 86, 'Martillo', 1, 9990.00, 9990.00, '2024-07-22 07:52:45'),
(6, 16, 87, 'Cerrucho', 1, 4990.00, 4990.00, '2024-07-22 08:00:27'),
(7, 16, 87, 'Martillo', 1, 9990.00, 9990.00, '2024-07-22 08:00:27'),
(8, 19, 88, 'Cerrucho', 1, 4990.00, 4990.00, '2024-07-22 08:14:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_general_ci,
  `precio` decimal(10,2) NOT NULL,
  `stock` int NOT NULL,
  `categoria_id` int DEFAULT NULL,
  `imagen` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `stock`, `categoria_id`, `imagen`) VALUES
(33, 'Cerrucho', 'cerrucho marca hover', 4990.00, 98, 2, 'ceruchonike.jpg'),
(34, 'Martillo', 'Marillo marca naiki', 9990.00, 80, 2, 'martillo.webp'),
(36, 'Piso flotante madera', 'Piso flotante madera lol', 49990.00, 25, 11, 'kempasbrillante.webp');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transacciones`
--

CREATE TABLE `transacciones` (
  `id` int NOT NULL,
  `tipo` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `contrasena` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `correo` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `rol` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'cliente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `contrasena`, `correo`, `creado_en`, `rol`) VALUES
(9, 'vendedor', '$2y$10$CBJv6LyzM2EZm7LoijtOQe4.QzWCu.1KMLzWfL2dtnaYKVBmdLcUC', 'vendedor@ferremas.com', '2024-06-15 07:39:02', 'vendedor'),
(10, 'bodeguero', '$2y$10$BIsRT07tO4I3GtQXsuB82.bLjsufcbKPoZIcofrj26J7GrjUJpOAq', 'bodeguero@ferremas.com', '2024-06-15 07:39:21', 'bodeguero'),
(15, 'cliente', '$2y$10$I/IO1B9riYYWbwUAEjpVcOUXLFBNcIPCvbu3NS5AM46E9Y7VdD9pK', 'cliente@ferremas.com', '2024-07-11 06:45:00', 'cliente'),
(16, 'admin', '$2y$10$l3xIDJdjRlNeBudPbcKYlOXNRFsPlm36odnJJNN94d3Mx0hpUWAy.', 'admin@ferremas.com', '2024-07-11 06:46:26', 'admin'),
(17, 'alvaro', '$2y$10$zl5lcYSwOqXm9hQtsZJY9u820hi3homTPi0BsS2PZW/x3CzkT0PCO', 'alvaro@gmail.com', '2024-07-17 03:17:11', 'cliente'),
(18, 'benjamin lobos', '$2y$10$rQ9iwV3U5tHfc8vcfTSm.O1fI13LsKzs5DZmkmJeCNHkZAPsflC7q', 'bloboslorca@outlook.com', '2024-07-21 03:21:34', 'cliente'),
(19, 'benjamin', '$2y$10$SNHT4OsnGYF487eimvRqe.d/SI8uZFolkWqrIX/E.799ZYYuEmtWO', 'bloboslorca2@gmail.com', '2024-07-21 03:38:25', 'cliente'),
(21, 'contador', '$2y$10$dwm3qPIHdkXNQy4HpZ9uZORvH5jZxMmnZCXqVH7CJZ.jYIEhacuAq', 'contador@ferremas.com', '2024-07-22 08:13:35', 'contador');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carritos`
--
ALTER TABLE `carritos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `carrito_productos`
--
ALTER TABLE `carrito_productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carrito_id` (`carrito_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `egresos`
--
ALTER TABLE `egresos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `finanzas`
--
ALTER TABLE `finanzas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `historial_compras`
--
ALTER TABLE `historial_compras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carrito_id` (`carrito_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `transacciones`
--
ALTER TABLE `transacciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carritos`
--
ALTER TABLE `carritos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT de la tabla `carrito_productos`
--
ALTER TABLE `carrito_productos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `egresos`
--
ALTER TABLE `egresos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `finanzas`
--
ALTER TABLE `finanzas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `historial_compras`
--
ALTER TABLE `historial_compras`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `transacciones`
--
ALTER TABLE `transacciones`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carritos`
--
ALTER TABLE `carritos`
  ADD CONSTRAINT `carritos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `carrito_productos`
--
ALTER TABLE `carrito_productos`
  ADD CONSTRAINT `carrito_productos_ibfk_1` FOREIGN KEY (`carrito_id`) REFERENCES `carritos` (`id`),
  ADD CONSTRAINT `carrito_productos_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `historial_compras`
--
ALTER TABLE `historial_compras`
  ADD CONSTRAINT `historial_compras_ibfk_1` FOREIGN KEY (`carrito_id`) REFERENCES `carritos` (`id`),
  ADD CONSTRAINT `historial_compras_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
