-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 15-03-2024 a las 20:15:33
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
-- Base de datos: `sisobras_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `apis`
--

CREATE TABLE `apis` (
  `id` bigint UNSIGNED NOT NULL,
  `google_maps` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `map_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `apis`
--

INSERT INTO `apis` (`id`, `google_maps`, `map_id`, `created_at`, `updated_at`) VALUES
(1, 'AIzaSyDhJquXCekb8guwEiX1aLHvPePi3SMkKis', '1fb896f332f7b53c', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `avance_obras`
--

CREATE TABLE `avance_obras` (
  `id` bigint UNSIGNED NOT NULL,
  `obra_id` bigint UNSIGNED NOT NULL,
  `nro_progreso` int NOT NULL,
  `marcados` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `observacion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nro_avances` int NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `nro_avances`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 'CATEGORIA #1', 5, '2024-03-11', '2024-03-11 16:42:23', '2024-03-11 16:42:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_accions`
--

CREATE TABLE `historial_accions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `accion` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `datos_original` text COLLATE utf8mb4_unicode_ci,
  `datos_nuevo` text COLLATE utf8mb4_unicode_ci,
  `modulo` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `historial_accions`
--

INSERT INTO `historial_accions` (`id`, `user_id`, `accion`, `descripcion`, `datos_original`, `datos_nuevo`, `modulo`, `fecha`, `hora`, `created_at`, `updated_at`) VALUES
(1, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN USUARIO', 'id: 2<br/>usuario: JPERES<br/>password: $2y$12$hWJdYj5pW7P.2o3/isHoNOeB/O1avn47utKVXE7.PJbNKHckZqrrO<br/>nombre: JUAN<br/>paterno: PERES<br/>materno: MAMANI<br/>ci: 1111<br/>ci_exp: LP<br/>dir: LOS OLIVOS<br/>email: JUAN@GMAIL.COM<br/>fono: 777777<br/>tipo: GERENTE REGIONAL<br/>foto: 1710002777_JPERES.png<br/>acceso: 1<br/>fecha_registro: 2024-03-09 00:00:00<br/>created_at: 2024-03-09 12:46:16<br/>updated_at: 2024-03-09 12:46:17<br/>', NULL, 'USUARIOS', '2024-03-09', '12:46:17', '2024-03-09 16:46:17', '2024-03-09 16:46:17'),
(2, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN USUARIO', 'id: 2<br/>usuario: JPERES<br/>password: $2y$12$hWJdYj5pW7P.2o3/isHoNOeB/O1avn47utKVXE7.PJbNKHckZqrrO<br/>nombre: JUAN<br/>paterno: PERES<br/>materno: MAMANI<br/>ci: 1111<br/>ci_exp: LP<br/>dir: LOS OLIVOS<br/>email: JUAN@GMAIL.COM<br/>fono: 777777<br/>tipo: GERENTE REGIONAL<br/>foto: 1710002777_JPERES.png<br/>acceso: 1<br/>fecha_registro: 2024-03-09 00:00:00<br/>created_at: 2024-03-09 12:46:16<br/>updated_at: 2024-03-09 12:46:17<br/>', 'id: 2<br/>usuario: JPERES<br/>password: $2y$12$hWJdYj5pW7P.2o3/isHoNOeB/O1avn47utKVXE7.PJbNKHckZqrrO<br/>nombre: JUAN<br/>paterno: PERES<br/>materno: MAMANI<br/>ci: 1111<br/>ci_exp: LP<br/>dir: LOS OLIVOS<br/>email: JUAN@GMAIL.COM<br/>fono: 777777<br/>tipo: GERENTE REGIONAL<br/>foto: 1710002777_JPERES.png<br/>acceso: 0<br/>fecha_registro: 2024-03-09 00:00:00<br/>created_at: 2024-03-09 12:46:16<br/>updated_at: 2024-03-09 12:46:52<br/>', 'USUARIOS', '2024-03-09', '12:46:52', '2024-03-09 16:46:52', '2024-03-09 16:46:52'),
(3, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN USUARIO', 'id: 2<br/>usuario: JPERES<br/>password: $2y$12$hWJdYj5pW7P.2o3/isHoNOeB/O1avn47utKVXE7.PJbNKHckZqrrO<br/>nombre: JUAN<br/>paterno: PERES<br/>materno: MAMANI<br/>ci: 1111<br/>ci_exp: LP<br/>dir: LOS OLIVOS<br/>email: JUAN@GMAIL.COM<br/>fono: 777777<br/>tipo: GERENTE REGIONAL<br/>foto: 1710002777_JPERES.png<br/>acceso: 0<br/>fecha_registro: 2024-03-09 00:00:00<br/>created_at: 2024-03-09 12:46:16<br/>updated_at: 2024-03-09 12:46:52<br/>', 'id: 2<br/>usuario: JPERES<br/>password: $2y$12$hWJdYj5pW7P.2o3/isHoNOeB/O1avn47utKVXE7.PJbNKHckZqrrO<br/>nombre: JUAN<br/>paterno: PERES<br/>materno: MAMANI<br/>ci: 1111<br/>ci_exp: LP<br/>dir: LOS OLIVOS<br/>email: JUAN@GMAIL.COM<br/>fono: 777777<br/>tipo: GERENTE REGIONAL<br/>foto: 1710002777_JPERES.png<br/>acceso: 1<br/>fecha_registro: 2024-03-09 00:00:00<br/>created_at: 2024-03-09 12:46:16<br/>updated_at: 2024-03-09 12:47:04<br/>', 'USUARIOS', '2024-03-09', '12:47:04', '2024-03-09 16:47:04', '2024-03-09 16:47:04'),
(4, 1, 'CREACIÓN', 'EL USUARIO  REGISTRO UN MATERIAL', 'id: 1<br/>nombre: MATERIAL #1<br/>descripcion: DESC. MATERIAL 1<br/>fecha_registro: 2024-03-11<br/>created_at: 2024-03-11 11:48:22<br/>updated_at: 2024-03-11 11:48:22<br/>', NULL, 'MATERIALES', '2024-03-11', '11:48:22', '2024-03-11 15:48:22', '2024-03-11 15:48:22'),
(5, 1, 'MODIFICACIÓN', 'EL USUARIO  MODIFICÓ UN MATERIAL', 'id: 1<br/>nombre: MATERIAL #1<br/>descripcion: DESC. MATERIAL 1<br/>fecha_registro: 2024-03-11<br/>created_at: 2024-03-11 11:48:22<br/>updated_at: 2024-03-11 11:48:22<br/>', 'id: 1<br/>nombre: MATERIAL #1 XS<br/>descripcion: DESC. MATERIAL 1 DF<br/>fecha_registro: 2024-03-11<br/>created_at: 2024-03-11 11:48:22<br/>updated_at: 2024-03-11 11:48:35<br/>', 'MATERIALES', '2024-03-11', '11:48:35', '2024-03-11 15:48:35', '2024-03-11 15:48:35'),
(6, 1, 'MODIFICACIÓN', 'EL USUARIO  MODIFICÓ UN MATERIAL', 'id: 1<br/>nombre: MATERIAL #1 XS<br/>descripcion: DESC. MATERIAL 1 DF<br/>fecha_registro: 2024-03-11<br/>created_at: 2024-03-11 11:48:22<br/>updated_at: 2024-03-11 11:48:35<br/>', 'id: 1<br/>nombre: MATERIAL #1<br/>descripcion: DESC. MATERIAL 1<br/>fecha_registro: 2024-03-11<br/>created_at: 2024-03-11 11:48:22<br/>updated_at: 2024-03-11 11:48:44<br/>', 'MATERIALES', '2024-03-11', '11:48:44', '2024-03-11 15:48:44', '2024-03-11 15:48:44'),
(7, 1, 'ELIMINACIÓN', 'EL USUARIO  ELIMINÓ UN MATERIAL', 'id: 1<br/>nombre: MATERIAL #1<br/>descripcion: DESC. MATERIAL 1<br/>fecha_registro: 2024-03-11<br/>created_at: 2024-03-11 11:48:22<br/>updated_at: 2024-03-11 11:48:44<br/>', NULL, 'MATERIALES', '2024-03-11', '11:48:55', '2024-03-11 15:48:55', '2024-03-11 15:48:55'),
(8, 1, 'CREACIÓN', 'EL USUARIO  REGISTRO UN MATERIAL', 'id: 2<br/>nombre: MATERIAL #1<br/>descripcion: DESC. MATERIAL #1<br/>fecha_registro: 2024-03-11<br/>created_at: 2024-03-11 11:49:06<br/>updated_at: 2024-03-11 11:49:06<br/>', NULL, 'MATERIALES', '2024-03-11', '11:49:06', '2024-03-11 15:49:06', '2024-03-11 15:49:06'),
(9, 1, 'ELIMINACIÓN', 'EL USUARIO  ELIMINÓ UN MATERIAL', 'id: 2<br/>nombre: MATERIAL #1<br/>descripcion: DESC. MATERIAL #1<br/>fecha_registro: 2024-03-11<br/>created_at: 2024-03-11 11:49:06<br/>updated_at: 2024-03-11 11:49:06<br/>', NULL, 'MATERIALES', '2024-03-11', '11:49:10', '2024-03-11 15:49:10', '2024-03-11 15:49:10'),
(10, 1, 'CREACIÓN', 'EL USUARIO  REGISTRO UN MATERIAL', 'id: 1<br/>nombre: MATERIAL #1<br/>descripcion: DESC. MATERIAL #1<br/>fecha_registro: 2024-03-11<br/>created_at: 2024-03-11 11:49:45<br/>updated_at: 2024-03-11 11:49:45<br/>', NULL, 'MATERIALES', '2024-03-11', '11:49:45', '2024-03-11 15:49:45', '2024-03-11 15:49:45'),
(11, 1, 'CREACIÓN', 'EL USUARIO  REGISTRO UN MATERIAL', 'id: 2<br/>nombre: MATERIAL #2<br/>descripcion: <br/>fecha_registro: 2024-03-11<br/>created_at: 2024-03-11 11:49:50<br/>updated_at: 2024-03-11 11:49:50<br/>', NULL, 'MATERIALES', '2024-03-11', '11:49:50', '2024-03-11 15:49:50', '2024-03-11 15:49:50'),
(12, 1, 'CREACIÓN', 'EL USUARIO  REGISTRO UN MATERIAL', 'id: 1<br/>nombre: JORGE<br/>paterno: MAMANI<br/>materno: MAMANI<br/>ci: 6666<br/>ci_exp: LP<br/>fono: 77777<br/>tipo: OPERADOR<br/>fecha_registro: 2024-03-11<br/>created_at: 2024-03-11 12:12:43<br/>updated_at: 2024-03-11 12:12:43<br/>', NULL, 'MATERIALES', '2024-03-11', '12:12:43', '2024-03-11 16:12:43', '2024-03-11 16:12:43'),
(13, 1, 'MODIFICACIÓN', 'EL USUARIO  MODIFICÓ UN MATERIAL', 'id: 1<br/>nombre: JORGE<br/>paterno: MAMANI<br/>materno: MAMANI<br/>ci: 6666<br/>ci_exp: LP<br/>fono: 77777<br/>tipo: OPERADOR<br/>fecha_registro: 2024-03-11<br/>created_at: 2024-03-11 12:12:43<br/>updated_at: 2024-03-11 12:12:43<br/>', 'id: 1<br/>nombre: JORGES<br/>paterno: MAMANIS<br/>materno: MAMANIE<br/>ci: 66667<br/>ci_exp: CB<br/>fono: 777778<br/>tipo: PERSONAL<br/>fecha_registro: 2024-03-11<br/>created_at: 2024-03-11 12:12:43<br/>updated_at: 2024-03-11 12:12:56<br/>', 'MATERIALES', '2024-03-11', '12:12:56', '2024-03-11 16:12:56', '2024-03-11 16:12:56'),
(14, 1, 'MODIFICACIÓN', 'EL USUARIO  MODIFICÓ UN MATERIAL', 'id: 1<br/>nombre: JORGES<br/>paterno: MAMANIS<br/>materno: MAMANIE<br/>ci: 66667<br/>ci_exp: CB<br/>fono: 777778<br/>tipo: PERSONAL<br/>fecha_registro: 2024-03-11<br/>created_at: 2024-03-11 12:12:43<br/>updated_at: 2024-03-11 12:12:56<br/>', 'id: 1<br/>nombre: JORGE<br/>paterno: MAMANI<br/>materno: MAMANI<br/>ci: 66666666<br/>ci_exp: LP<br/>fono: 777777<br/>tipo: OPERADOR<br/>fecha_registro: 2024-03-11<br/>created_at: 2024-03-11 12:12:43<br/>updated_at: 2024-03-11 12:13:10<br/>', 'MATERIALES', '2024-03-11', '12:13:10', '2024-03-11 16:13:10', '2024-03-11 16:13:10'),
(15, 1, 'MODIFICACIÓN', 'EL USUARIO  MODIFICÓ UN MATERIAL', 'id: 1<br/>nombre: JORGE<br/>paterno: MAMANI<br/>materno: MAMANI<br/>ci: 66666666<br/>ci_exp: LP<br/>fono: 777777<br/>tipo: OPERADOR<br/>fecha_registro: 2024-03-11<br/>created_at: 2024-03-11 12:12:43<br/>updated_at: 2024-03-11 12:13:10<br/>', 'id: 1<br/>nombre: JORGE<br/>paterno: MAMANI<br/>materno: CHOQUE<br/>ci: 66666666<br/>ci_exp: LP<br/>fono: 777777<br/>tipo: OPERADOR<br/>fecha_registro: 2024-03-11<br/>created_at: 2024-03-11 12:12:43<br/>updated_at: 2024-03-11 12:13:27<br/>', 'MATERIALES', '2024-03-11', '12:13:27', '2024-03-11 16:13:27', '2024-03-11 16:13:27'),
(16, 1, 'ELIMINACIÓN', 'EL USUARIO  ELIMINÓ UN MATERIAL', 'id: 1<br/>nombre: JORGE<br/>paterno: MAMANI<br/>materno: CHOQUE<br/>ci: 66666666<br/>ci_exp: LP<br/>fono: 777777<br/>tipo: OPERADOR<br/>fecha_registro: 2024-03-11<br/>created_at: 2024-03-11 12:12:43<br/>updated_at: 2024-03-11 12:13:27<br/>', NULL, 'MATERIALES', '2024-03-11', '12:15:22', '2024-03-11 16:15:22', '2024-03-11 16:15:22'),
(17, 1, 'CREACIÓN', 'EL USUARIO  REGISTRO UNA MAQUINARIA', 'id: 1<br/>nombre: MAQUINARIA #1<br/>descripcion: DESC. MAQUINARIA 1<br/>fecha_registro: 2024-03-11<br/>created_at: 2024-03-11 12:23:29<br/>updated_at: 2024-03-11 12:23:29<br/>', NULL, 'MAQUINARIAS', '2024-03-11', '12:23:29', '2024-03-11 16:23:29', '2024-03-11 16:23:29'),
(18, 1, 'MODIFICACIÓN', 'EL USUARIO  MODIFICÓ UNA MAQUINARIA', 'id: 1<br/>nombre: MAQUINARIA #1<br/>descripcion: DESC. MAQUINARIA 1<br/>fecha_registro: 2024-03-11<br/>created_at: 2024-03-11 12:23:29<br/>updated_at: 2024-03-11 12:23:29<br/>', 'id: 1<br/>nombre: MAQUINARIA #1S<br/>descripcion: DESC. MAQUINARIA 1E<br/>fecha_registro: 2024-03-11<br/>created_at: 2024-03-11 12:23:29<br/>updated_at: 2024-03-11 12:23:34<br/>', 'MAQUINARIAS', '2024-03-11', '12:23:34', '2024-03-11 16:23:34', '2024-03-11 16:23:34'),
(19, 1, 'MODIFICACIÓN', 'EL USUARIO  MODIFICÓ UNA MAQUINARIA', 'id: 1<br/>nombre: MAQUINARIA #1S<br/>descripcion: DESC. MAQUINARIA 1E<br/>fecha_registro: 2024-03-11<br/>created_at: 2024-03-11 12:23:29<br/>updated_at: 2024-03-11 12:23:34<br/>', 'id: 1<br/>nombre: MAQUINARIA #1S<br/>descripcion: DESC. MAQUINARIA 1E<br/>fecha_registro: 2024-03-11<br/>created_at: 2024-03-11 12:23:29<br/>updated_at: 2024-03-11 12:23:34<br/>', 'MAQUINARIAS', '2024-03-11', '12:23:40', '2024-03-11 16:23:40', '2024-03-11 16:23:40'),
(20, 1, 'ELIMINACIÓN', 'EL USUARIO  ELIMINÓ UNA MAQUINARIA', 'id: 1<br/>nombre: MAQUINARIA #1S<br/>descripcion: DESC. MAQUINARIA 1E<br/>fecha_registro: 2024-03-11<br/>created_at: 2024-03-11 12:23:29<br/>updated_at: 2024-03-11 12:23:34<br/>', NULL, 'MAQUINARIAS', '2024-03-11', '12:23:43', '2024-03-11 16:23:43', '2024-03-11 16:23:43'),
(21, 1, 'CREACIÓN', 'EL USUARIO  REGISTRO UNA MAQUINARIA', 'id: 1<br/>nombre: MAQUINARIA #1<br/>descripcion: DESC. MAQUINARIA 1<br/>fecha_registro: 2024-03-11<br/>created_at: 2024-03-11 12:24:01<br/>updated_at: 2024-03-11 12:24:01<br/>', NULL, 'MAQUINARIAS', '2024-03-11', '12:24:01', '2024-03-11 16:24:01', '2024-03-11 16:24:01'),
(22, 1, 'MODIFICACIÓN', 'EL USUARIO  MODIFICÓ UNA MAQUINARIA', 'id: 1<br/>nombre: MAQUINARIA #1<br/>descripcion: DESC. MAQUINARIA 1<br/>fecha_registro: 2024-03-11<br/>created_at: 2024-03-11 12:24:01<br/>updated_at: 2024-03-11 12:24:01<br/>', 'id: 1<br/>nombre: MAQUINARIA #1<br/>descripcion: DESC. MAQUINARIA 1<br/>fecha_registro: 2024-03-11<br/>created_at: 2024-03-11 12:24:01<br/>updated_at: 2024-03-11 12:24:01<br/>', 'MAQUINARIAS', '2024-03-11', '12:24:04', '2024-03-11 16:24:04', '2024-03-11 16:24:04'),
(23, 1, 'CREACIÓN', 'EL USUARIO  REGISTRO UNA CATEGORIA', 'id: 1<br/>nombre: CATEGORIA #1<br/>nro_avances: 5<br/>fecha_registro: 2024-03-11<br/>created_at: 2024-03-11 12:41:56<br/>updated_at: 2024-03-11 12:41:56<br/>', NULL, 'CATEGORIAS', '2024-03-11', '12:41:56', '2024-03-11 16:41:56', '2024-03-11 16:41:56'),
(24, 1, 'MODIFICACIÓN', 'EL USUARIO  MODIFICÓ UNA CATEGORIA', 'id: 1<br/>nombre: CATEGORIA #1<br/>nro_avances: 5<br/>fecha_registro: 2024-03-11<br/>created_at: 2024-03-11 12:41:56<br/>updated_at: 2024-03-11 12:41:56<br/>', 'id: 1<br/>nombre: CATEGORIA #1S<br/>nro_avances: 55<br/>fecha_registro: 2024-03-11<br/>created_at: 2024-03-11 12:41:56<br/>updated_at: 2024-03-11 12:42:05<br/>', 'CATEGORIAS', '2024-03-11', '12:42:05', '2024-03-11 16:42:05', '2024-03-11 16:42:05'),
(25, 1, 'ELIMINACIÓN', 'EL USUARIO  ELIMINÓ UNA CATEGORIA', 'id: 1<br/>nombre: CATEGORIA #1S<br/>nro_avances: 55<br/>fecha_registro: 2024-03-11<br/>created_at: 2024-03-11 12:41:56<br/>updated_at: 2024-03-11 12:42:05<br/>', NULL, 'CATEGORIAS', '2024-03-11', '12:42:11', '2024-03-11 16:42:11', '2024-03-11 16:42:11'),
(26, 1, 'CREACIÓN', 'EL USUARIO  REGISTRO UNA CATEGORIA', 'id: 1<br/>nombre: CATEGORIA #1<br/>nro_avances: 5<br/>fecha_registro: 2024-03-11<br/>created_at: 2024-03-11 12:42:23<br/>updated_at: 2024-03-11 12:42:23<br/>', NULL, 'CATEGORIAS', '2024-03-11', '12:42:23', '2024-03-11 16:42:23', '2024-03-11 16:42:23'),
(27, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN USUARIO', 'id: 3<br/>usuario: ECORTEZ<br/>password: $2y$12$hUzZXPEaKdH0/Hkaukv6BOY9cSrkR6gPpl7rwClgTjJQw0We4yY9.<br/>nombre: EDUARDO<br/>paterno: CORTEZ<br/>materno: CORTEZ<br/>ci: 2222<br/>ci_exp: LP<br/>dir: LOS OLIVOS<br/>email: <br/>fono: 777777<br/>tipo: ENCARGADO DE OBRA<br/>foto: <br/>acceso: 1<br/>fecha_registro: 2024-03-12 00:00:00<br/>created_at: 2024-03-12 14:16:20<br/>updated_at: 2024-03-12 14:16:20<br/>', NULL, 'USUARIOS', '2024-03-12', '14:16:20', '2024-03-12 18:16:20', '2024-03-12 18:16:20'),
(28, 1, 'CREACIÓN', 'EL USUARIO  REGISTRO UNA CATEGORIA', 'id: 2<br/>nombre: OBRA #1<br/>gerente_regional_id: 2<br/>encargado_obra_id: 3<br/>fecha_pent: 2024-04-05<br/>fecha_peje: 2024-03-13<br/>descripcion: DESC. OBRA #1<br/>lat: -16.496691663593598<br/>lon: <br/>categoria_id: 1<br/>fecha_registro: 2024-03-12<br/>created_at: 2024-03-12 16:12:10<br/>updated_at: 2024-03-12 16:12:10<br/>', NULL, 'CATEGORIAS', '2024-03-12', '16:12:10', '2024-03-12 20:12:10', '2024-03-12 20:12:10'),
(29, 1, 'MODIFICACIÓN', 'EL USUARIO  MODIFICÓ UNA CATEGORIA', 'id: 2<br/>nombre: OBRA #1<br/>gerente_regional_id: 2<br/>encargado_obra_id: 3<br/>fecha_pent: 2024-04-05<br/>fecha_peje: 2024-03-13<br/>descripcion: DESC. OBRA #1<br/>lat: -16.496691663593598<br/>lng: -68.133345<br/>categoria_id: 1<br/>fecha_registro: 2024-03-12<br/>created_at: 2024-03-12 16:12:10<br/>updated_at: 2024-03-12 16:12:10<br/>', 'id: 2<br/>nombre: OBRA #1<br/>gerente_regional_id: 2<br/>encargado_obra_id: 3<br/>fecha_pent: 2024-04-05<br/>fecha_peje: 2024-03-13<br/>descripcion: DESC. OBRA #1<br/>lat: -16.496665945601666<br/>lng: -68.1326637189585<br/>categoria_id: 1<br/>fecha_registro: 2024-03-12<br/>created_at: 2024-03-12 16:12:10<br/>updated_at: 2024-03-12 16:14:35<br/>', 'CATEGORIAS', '2024-03-12', '16:14:35', '2024-03-12 20:14:35', '2024-03-12 20:14:35'),
(30, 1, 'ELIMINACIÓN', 'EL USUARIO  ELIMINÓ UNA CATEGORIA', 'id: 2<br/>nombre: OBRA #1<br/>gerente_regional_id: 2<br/>encargado_obra_id: 3<br/>fecha_pent: 2024-04-05<br/>fecha_peje: 2024-03-13<br/>descripcion: DESC. OBRA #1<br/>lat: -16.496665945601666<br/>lng: -68.1326637189585<br/>categoria_id: 1<br/>fecha_registro: 2024-03-12<br/>created_at: 2024-03-12 16:12:10<br/>updated_at: 2024-03-12 16:14:35<br/>', NULL, 'CATEGORIAS', '2024-03-12', '16:16:26', '2024-03-12 20:16:26', '2024-03-12 20:16:26'),
(31, 1, 'CREACIÓN', 'EL USUARIO  REGISTRO UNA CATEGORIA', 'id: 1<br/>nombre: OBRA #1<br/>gerente_regional_id: 2<br/>encargado_obra_id: 3<br/>fecha_pent: 2024-04-30<br/>fecha_peje: 2024-03-12<br/>descripcion: DESC. OBRA #1<br/>lat: -16.496059<br/>lng: -68.133345<br/>categoria_id: 1<br/>fecha_registro: 2024-03-12<br/>created_at: 2024-03-12 16:17:02<br/>updated_at: 2024-03-12 16:17:02<br/>', NULL, 'CATEGORIAS', '2024-03-12', '16:17:02', '2024-03-12 20:17:02', '2024-03-12 20:17:02'),
(32, 1, 'MODIFICACIÓN', 'EL USUARIO  MODIFICÓ UNA CATEGORIA', 'id: 1<br/>nombre: OBRA #1<br/>gerente_regional_id: 2<br/>encargado_obra_id: 3<br/>fecha_pent: 2024-04-30<br/>fecha_peje: 2024-03-12<br/>descripcion: DESC. OBRA #1<br/>lat: -16.496059<br/>lng: -68.133345<br/>categoria_id: 1<br/>fecha_registro: 2024-03-12<br/>created_at: 2024-03-12 16:17:02<br/>updated_at: 2024-03-12 16:17:02<br/>', 'id: 1<br/>nombre: OBRA #1S<br/>gerente_regional_id: 2<br/>encargado_obra_id: 3<br/>fecha_pent: 2024-04-27<br/>fecha_peje: 2024-03-14<br/>descripcion: DESC. OBRA #1S<br/>lat: -16.496017851076694<br/>lng: -68.13218092137002<br/>categoria_id: 1<br/>fecha_registro: 2024-03-12<br/>created_at: 2024-03-12 16:17:02<br/>updated_at: 2024-03-12 16:17:16<br/>', 'CATEGORIAS', '2024-03-12', '16:17:16', '2024-03-12 20:17:16', '2024-03-12 20:17:16'),
(33, 1, 'MODIFICACIÓN', 'EL USUARIO  MODIFICÓ UNA CATEGORIA', 'id: 1<br/>nombre: OBRA #1S<br/>gerente_regional_id: 2<br/>encargado_obra_id: 3<br/>fecha_pent: 2024-04-27<br/>fecha_peje: 2024-03-14<br/>descripcion: DESC. OBRA #1S<br/>lat: -16.496017851076694<br/>lng: -68.13218092137002<br/>categoria_id: 1<br/>fecha_registro: 2024-03-12<br/>created_at: 2024-03-12 16:17:02<br/>updated_at: 2024-03-12 16:17:16<br/>', 'id: 1<br/>nombre: OBRA #1<br/>gerente_regional_id: 2<br/>encargado_obra_id: 3<br/>fecha_pent: 2024-04-29<br/>fecha_peje: 2024-03-14<br/>descripcion: DESC. OBRA #1<br/>lat: -16.496691663593637<br/>lng: -68.13262616803492<br/>categoria_id: 1<br/>fecha_registro: 2024-03-12<br/>created_at: 2024-03-12 16:17:02<br/>updated_at: 2024-03-12 16:17:31<br/>', 'CATEGORIAS', '2024-03-12', '16:17:31', '2024-03-12 20:17:31', '2024-03-12 20:17:31'),
(34, 1, 'CREACIÓN', 'EL USUARIO  REGISTRO UN MATERIAL', 'id: 1<br/>nombre: JOSE<br/>paterno: CHOQUE<br/>materno: ORTIZ<br/>ci: 343434<br/>ci_exp: <br/>fono: <br/>tipo: OPERADOR<br/>fecha_registro: 2024-03-15<br/>created_at: 2024-03-15 14:54:31<br/>updated_at: 2024-03-15 14:54:31<br/>', NULL, 'MATERIALES', '2024-03-15', '14:54:31', '2024-03-15 18:54:31', '2024-03-15 18:54:31'),
(35, 1, 'CREACIÓN', 'EL USUARIO  REGISTRO UN MATERIAL', 'id: 2<br/>nombre: JUAN CARLOS<br/>paterno: SOLIZ<br/>materno: ORTIZ<br/>ci: <br/>ci_exp: <br/>fono: <br/>tipo: PERSONAL<br/>fecha_registro: 2024-03-15<br/>created_at: 2024-03-15 14:54:50<br/>updated_at: 2024-03-15 14:54:50<br/>', NULL, 'MATERIALES', '2024-03-15', '14:54:50', '2024-03-15 18:54:50', '2024-03-15 18:54:50'),
(36, 1, 'CREACIÓN', 'EL USUARIO  REGISTRO UNA CATEGORIA', 'id: 2<br/>obra_id: 1<br/>presupuesto: 3000<br/>total_precio: 0<br/>total_cantidad: 0<br/>total: 2450<br/>fecha_registro: 2024-03-15<br/>created_at: 2024-03-15 15:00:28<br/>updated_at: 2024-03-15 15:00:28<br/>', NULL, 'CATEGORIAS', '2024-03-15', '15:00:28', '2024-03-15 19:00:28', '2024-03-15 19:00:28'),
(37, 1, 'MODIFICACIÓN', 'EL USUARIO  MODIFICÓ UNA CATEGORIA', 'id: 2<br/>obra_id: 1<br/>presupuesto: 3000.00<br/>total_precio: 0.00<br/>total_cantidad: 0.00<br/>total: 2450.00<br/>fecha_registro: 2024-03-15<br/>created_at: 2024-03-15 15:00:28<br/>updated_at: 2024-03-15 15:00:28<br/>', 'id: 2<br/>obra_id: 1<br/>presupuesto: 3000.00<br/>total_precio: 410<br/>total_cantidad: 27<br/>total: 2050<br/>fecha_registro: 2024-03-15<br/>created_at: 2024-03-15 15:00:28<br/>updated_at: 2024-03-15 15:23:49<br/>', 'CATEGORIAS', '2024-03-15', '15:23:49', '2024-03-15 19:23:49', '2024-03-15 19:23:49'),
(38, 1, 'MODIFICACIÓN', 'EL USUARIO  MODIFICÓ UNA CATEGORIA', 'id: 2<br/>obra_id: 1<br/>presupuesto: 3000.00<br/>total_precio: 410.00<br/>total_cantidad: 27.00<br/>total: 2050.00<br/>fecha_registro: 2024-03-15<br/>created_at: 2024-03-15 15:00:28<br/>updated_at: 2024-03-15 15:23:49<br/>', 'id: 2<br/>obra_id: 1<br/>presupuesto: 3000.00<br/>total_precio: 410<br/>total_cantidad: 27<br/>total: 2050<br/>fecha_registro: 2024-03-15<br/>created_at: 2024-03-15 15:00:28<br/>updated_at: 2024-03-15 15:25:03<br/>', 'CATEGORIAS', '2024-03-15', '15:25:03', '2024-03-15 19:25:03', '2024-03-15 19:25:03'),
(39, 1, 'MODIFICACIÓN', 'EL USUARIO  MODIFICÓ UNA CATEGORIA', 'id: 2<br/>obra_id: 1<br/>presupuesto: 3000.00<br/>total_precio: 410.00<br/>total_cantidad: 27.00<br/>total: 2050.00<br/>fecha_registro: 2024-03-15<br/>created_at: 2024-03-15 15:00:28<br/>updated_at: 2024-03-15 15:25:03<br/>', 'id: 2<br/>obra_id: 1<br/>presupuesto: 3000.00<br/>total_precio: 410<br/>total_cantidad: 27<br/>total: 2050<br/>fecha_registro: 2024-03-15<br/>created_at: 2024-03-15 15:00:28<br/>updated_at: 2024-03-15 15:28:10<br/>', 'CATEGORIAS', '2024-03-15', '15:28:10', '2024-03-15 19:28:10', '2024-03-15 19:28:10'),
(40, 1, 'MODIFICACIÓN', 'EL USUARIO  MODIFICÓ UNA CATEGORIA', 'id: 2<br/>obra_id: 1<br/>presupuesto: 3000.00<br/>total_precio: 410.00<br/>total_cantidad: 27.00<br/>total: 2050.00<br/>fecha_registro: 2024-03-15<br/>created_at: 2024-03-15 15:00:28<br/>updated_at: 2024-03-15 15:28:10<br/>', 'id: 2<br/>obra_id: 1<br/>presupuesto: 3000.00<br/>total_precio: 410<br/>total_cantidad: 27<br/>total: 2050<br/>fecha_registro: 2024-03-15<br/>created_at: 2024-03-15 15:00:28<br/>updated_at: 2024-03-15 15:31:00<br/>', 'CATEGORIAS', '2024-03-15', '15:31:00', '2024-03-15 19:31:00', '2024-03-15 19:31:00'),
(41, 1, 'MODIFICACIÓN', 'EL USUARIO  MODIFICÓ UNA CATEGORIA', 'id: 2<br/>obra_id: 1<br/>presupuesto: 3000.00<br/>total_precio: 410.00<br/>total_cantidad: 27.00<br/>total: 2050.00<br/>fecha_registro: 2024-03-15<br/>created_at: 2024-03-15 15:00:28<br/>updated_at: 2024-03-15 15:31:00<br/>', 'id: 2<br/>obra_id: 1<br/>presupuesto: 3000.00<br/>total_precio: 410<br/>total_cantidad: 27<br/>total: 2050<br/>fecha_registro: 2024-03-15<br/>created_at: 2024-03-15 15:00:28<br/>updated_at: 2024-03-15 15:38:44<br/>', 'CATEGORIAS', '2024-03-15', '15:38:44', '2024-03-15 19:38:44', '2024-03-15 19:38:44'),
(42, 1, 'MODIFICACIÓN', 'EL USUARIO  MODIFICÓ UNA CATEGORIA', 'id: 2<br/>obra_id: 1<br/>presupuesto: 3000.00<br/>total_precio: 410.00<br/>total_cantidad: 27.00<br/>total: 2050.00<br/>fecha_registro: 2024-03-15<br/>created_at: 2024-03-15 15:00:28<br/>updated_at: 2024-03-15 15:38:44<br/>', 'id: 2<br/>obra_id: 1<br/>presupuesto: 3000.00<br/>total_precio: 410<br/>total_cantidad: 27<br/>total: 2050<br/>fecha_registro: 2024-03-15<br/>created_at: 2024-03-15 15:00:28<br/>updated_at: 2024-03-15 15:51:01<br/>', 'CATEGORIAS', '2024-03-15', '15:51:01', '2024-03-15 19:51:01', '2024-03-15 19:51:01'),
(43, 1, 'MODIFICACIÓN', 'EL USUARIO  MODIFICÓ UNA CATEGORIA', 'id: 2<br/>obra_id: 1<br/>presupuesto: 3000.00<br/>total_precio: 410.00<br/>total_cantidad: 27.00<br/>total: 2050.00<br/>fecha_registro: 2024-03-15<br/>created_at: 2024-03-15 15:00:28<br/>updated_at: 2024-03-15 15:51:01<br/>', 'id: 2<br/>obra_id: 1<br/>presupuesto: 3000.00<br/>total_precio: 380<br/>total_cantidad: 20<br/>total: 1840<br/>fecha_registro: 2024-03-15<br/>created_at: 2024-03-15 15:00:28<br/>updated_at: 2024-03-15 15:51:26<br/>', 'CATEGORIAS', '2024-03-15', '15:51:26', '2024-03-15 19:51:26', '2024-03-15 19:51:26'),
(44, 1, 'MODIFICACIÓN', 'EL USUARIO  MODIFICÓ UNA CATEGORIA', 'id: 2<br/>obra_id: 1<br/>presupuesto: 3000.00<br/>total_precio: 380.00<br/>total_cantidad: 20.00<br/>total: 1840.00<br/>fecha_registro: 2024-03-15<br/>created_at: 2024-03-15 15:00:28<br/>updated_at: 2024-03-15 15:51:26<br/>', 'id: 2<br/>obra_id: 1<br/>presupuesto: 3000.00<br/>total_precio: 420<br/>total_cantidad: 28<br/>total: 2160<br/>fecha_registro: 2024-03-15<br/>created_at: 2024-03-15 15:00:28<br/>updated_at: 2024-03-15 15:55:00<br/>', 'CATEGORIAS', '2024-03-15', '15:55:00', '2024-03-15 19:55:00', '2024-03-15 19:55:00'),
(45, 1, 'ELIMINACIÓN', 'EL USUARIO  ELIMINÓ UN PRESUPUESTO', 'id: 2<br/>obra_id: 1<br/>presupuesto: 3000.00<br/>total_precio: 420.00<br/>total_cantidad: 28.00<br/>total: 2160.00<br/>fecha_registro: 2024-03-15<br/>created_at: 2024-03-15 15:00:28<br/>updated_at: 2024-03-15 15:55:00<br/>', NULL, 'PRESUPUESTOS', '2024-03-15', '15:59:51', '2024-03-15 19:59:51', '2024-03-15 19:59:51'),
(46, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN USUARIO', 'id: 2<br/>usuario: JPERES<br/>password: $2y$12$hWJdYj5pW7P.2o3/isHoNOeB/O1avn47utKVXE7.PJbNKHckZqrrO<br/>nombre: JUAN<br/>paterno: PERES<br/>materno: MAMANI<br/>ci: 1111<br/>ci_exp: LP<br/>dir: LOS OLIVOS<br/>email: JUAN@GMAIL.COM<br/>fono: 777777<br/>tipo: GERENTE REGIONAL<br/>foto: 1710002777_JPERES.png<br/>acceso: 1<br/>fecha_registro: 2024-03-09 00:00:00<br/>created_at: 2024-03-09 12:46:16<br/>updated_at: 2024-03-09 12:47:04<br/>', 'id: 2<br/>usuario: JPERES<br/>password: $2y$12$hWJdYj5pW7P.2o3/isHoNOeB/O1avn47utKVXE7.PJbNKHckZqrrO<br/>nombre: JUAN<br/>paterno: PERES<br/>materno: MAMANI<br/>ci: 1111<br/>ci_exp: LP<br/>dir: LOS OLIVOS<br/>email: JUAN@GMAIL.COM<br/>fono: 777777<br/>tipo: GERENTE REGIONAL<br/>foto: 1710533238_JPERES.jpg<br/>acceso: 1<br/>fecha_registro: 2024-03-09 00:00:00<br/>created_at: 2024-03-09 12:46:16<br/>updated_at: 2024-03-15 16:07:18<br/>', 'USUARIOS', '2024-03-15', '16:07:18', '2024-03-15 20:07:18', '2024-03-15 20:07:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `institucions`
--

CREATE TABLE `institucions` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre_sistema` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `razon_social` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ciudad` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dir` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fono` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `web` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `actividad` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `institucions`
--

INSERT INTO `institucions` (`id`, `nombre_sistema`, `alias`, `razon_social`, `nit`, `ciudad`, `dir`, `fono`, `correo`, `web`, `actividad`, `logo`, `created_at`, `updated_at`) VALUES
(1, 'SISOBRAS', 'SO', 'SISOBRAS S.A.', '11111111', 'LA PAZ', 'LOS OLIVOS', '7777777', 'SISOBRAS@GMAIL.COM', 'SISOBRAS.COM', 'ACTIVIDAD', '1710002413_1.jpg', NULL, '2024-03-09 16:40:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `maquinarias`
--

CREATE TABLE `maquinarias` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `maquinarias`
--

INSERT INTO `maquinarias` (`id`, `nombre`, `descripcion`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 'MAQUINARIA #1', 'DESC. MAQUINARIA 1', '2024-03-11', '2024-03-11 16:24:01', '2024-03-11 16:24:01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materials`
--

CREATE TABLE `materials` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(600) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `materials`
--

INSERT INTO `materials` (`id`, `nombre`, `descripcion`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 'MATERIAL #1', 'DESC. MATERIAL #1', '2024-03-11', '2024-03-11 15:49:45', '2024-03-11 15:49:45'),
(2, 'MATERIAL #2', '', '2024-03-11', '2024-03-11 15:49:50', '2024-03-11 15:49:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(3, '2024_01_31_165641_create_institucions_table', 1),
(4, '2024_02_02_205431_create_historial_accions_table', 1),
(5, '2024_03_09_124741_create_materials_table', 2),
(6, '2024_03_09_125103_create_operarios_table', 2),
(7, '2024_03_09_125116_create_maquinarias_table', 2),
(8, '2024_03_09_125125_create_categorias_table', 2),
(9, '2024_03_09_125138_create_obras_table', 2),
(10, '2024_03_09_125159_create_presupuestos_table', 2),
(11, '2024_03_09_125209_create_presupuesto_materials_table', 2),
(12, '2024_03_09_125216_create_presupuesto_operarios_table', 2),
(13, '2024_03_09_125225_create_presupuesto_maquinarias_table', 2),
(14, '2024_03_09_125249_create_avance_obras_table', 2),
(15, '2024_03_09_125314_create_notificacions_table', 2),
(16, '2024_03_09_125316_create_notificacion_users_table', 2),
(17, '2024_03_12_142219_create_apis_table', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificacions`
--

CREATE TABLE `notificacions` (
  `id` bigint UNSIGNED NOT NULL,
  `registro_id` bigint UNSIGNED NOT NULL,
  `modelo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificacion_users`
--

CREATE TABLE `notificacion_users` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `notificacion_id` bigint UNSIGNED NOT NULL,
  `visto` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `obras`
--

CREATE TABLE `obras` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gerente_regional_id` bigint UNSIGNED NOT NULL,
  `encargado_obra_id` bigint UNSIGNED NOT NULL,
  `fecha_pent` date NOT NULL,
  `fecha_peje` date NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `lat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lng` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `categoria_id` bigint UNSIGNED NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `obras`
--

INSERT INTO `obras` (`id`, `nombre`, `gerente_regional_id`, `encargado_obra_id`, `fecha_pent`, `fecha_peje`, `descripcion`, `lat`, `lng`, `categoria_id`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 'OBRA #1', 2, 3, '2024-04-29', '2024-03-14', 'DESC. OBRA #1', '-16.496691663593637', '-68.13262616803492', 1, '2024-03-12', '2024-03-12 20:17:02', '2024-03-12 20:17:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `operarios`
--

CREATE TABLE `operarios` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paterno` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `materno` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ci` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ci_exp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fono` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `operarios`
--

INSERT INTO `operarios` (`id`, `nombre`, `paterno`, `materno`, `ci`, `ci_exp`, `fono`, `tipo`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 'JOSE', 'CHOQUE', 'ORTIZ', '343434', '', '', 'OPERADOR', '2024-03-15', '2024-03-15 18:54:31', '2024-03-15 18:54:31'),
(2, 'JUAN CARLOS', 'SOLIZ', 'ORTIZ', '', '', '', 'PERSONAL', '2024-03-15', '2024-03-15 18:54:50', '2024-03-15 18:54:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presupuestos`
--

CREATE TABLE `presupuestos` (
  `id` bigint UNSIGNED NOT NULL,
  `obra_id` bigint UNSIGNED NOT NULL,
  `presupuesto` decimal(24,2) NOT NULL,
  `total_precio` decimal(24,2) NOT NULL,
  `total_cantidad` decimal(24,2) NOT NULL,
  `total` decimal(24,2) NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presupuesto_maquinarias`
--

CREATE TABLE `presupuesto_maquinarias` (
  `id` bigint UNSIGNED NOT NULL,
  `presupuesto_id` bigint UNSIGNED NOT NULL,
  `maquinaria_id` bigint UNSIGNED NOT NULL,
  `precio` decimal(24,2) NOT NULL,
  `cantidad` double(8,2) NOT NULL,
  `subtotal` decimal(24,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presupuesto_materials`
--

CREATE TABLE `presupuesto_materials` (
  `id` bigint UNSIGNED NOT NULL,
  `presupuesto_id` bigint UNSIGNED NOT NULL,
  `material_id` bigint UNSIGNED NOT NULL,
  `precio` decimal(24,2) NOT NULL,
  `cantidad` double(8,2) NOT NULL,
  `subtotal` decimal(24,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presupuesto_operarios`
--

CREATE TABLE `presupuesto_operarios` (
  `id` bigint UNSIGNED NOT NULL,
  `presupuesto_id` bigint UNSIGNED NOT NULL,
  `operario_id` bigint UNSIGNED NOT NULL,
  `precio` decimal(24,2) NOT NULL,
  `cantidad` double(8,2) NOT NULL,
  `subtotal` decimal(24,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `usuario` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paterno` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `materno` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ci` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ci_exp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fono` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `acceso` int NOT NULL DEFAULT '1',
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `usuario`, `password`, `nombre`, `paterno`, `materno`, `ci`, `ci_exp`, `dir`, `email`, `fono`, `tipo`, `foto`, `acceso`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$12$65d4fgZsvBV5Lc/AxNKh4eoUdbGyaczQ4sSco20feSQANshNLuxSC', 'admin', NULL, NULL, '0', '', '', 'admin@gmail.com', '', 'GERENTE GENERAL', NULL, 1, '2024-01-31', NULL, '2024-02-02 18:13:58'),
(2, 'JPERES', '$2y$12$hWJdYj5pW7P.2o3/isHoNOeB/O1avn47utKVXE7.PJbNKHckZqrrO', 'JUAN', 'PERES', 'MAMANI', '1111', 'LP', 'LOS OLIVOS', 'JUAN@GMAIL.COM', '777777', 'GERENTE REGIONAL', '1710533238_JPERES.jpg', 1, '2024-03-09', '2024-03-09 16:46:16', '2024-03-15 20:07:18'),
(3, 'ECORTEZ', '$2y$12$hUzZXPEaKdH0/Hkaukv6BOY9cSrkR6gPpl7rwClgTjJQw0We4yY9.', 'EDUARDO', 'CORTEZ', 'CORTEZ', '2222', 'LP', 'LOS OLIVOS', '', '777777', 'ENCARGADO DE OBRA', NULL, 1, '2024-03-12', '2024-03-12 18:16:20', '2024-03-12 18:16:20');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `apis`
--
ALTER TABLE `apis`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `avance_obras`
--
ALTER TABLE `avance_obras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `avance_obras_obra_id_foreign` (`obra_id`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `historial_accions`
--
ALTER TABLE `historial_accions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `institucions`
--
ALTER TABLE `institucions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `maquinarias`
--
ALTER TABLE `maquinarias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `notificacions`
--
ALTER TABLE `notificacions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `notificacion_users`
--
ALTER TABLE `notificacion_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notificacion_users_user_id_foreign` (`user_id`),
  ADD KEY `notificacion_users_notificacion_id_foreign` (`notificacion_id`);

--
-- Indices de la tabla `obras`
--
ALTER TABLE `obras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `obras_gerente_regional_id_foreign` (`gerente_regional_id`),
  ADD KEY `obras_encargado_obra_id_foreign` (`encargado_obra_id`),
  ADD KEY `obras_categoria_id_foreign` (`categoria_id`);

--
-- Indices de la tabla `operarios`
--
ALTER TABLE `operarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indices de la tabla `presupuestos`
--
ALTER TABLE `presupuestos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `presupuestos_obra_id_foreign` (`obra_id`);

--
-- Indices de la tabla `presupuesto_maquinarias`
--
ALTER TABLE `presupuesto_maquinarias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `presupuesto_maquinarias_presupuesto_id_foreign` (`presupuesto_id`),
  ADD KEY `presupuesto_maquinarias_maquinaria_id_foreign` (`maquinaria_id`);

--
-- Indices de la tabla `presupuesto_materials`
--
ALTER TABLE `presupuesto_materials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `presupuesto_materials_presupuesto_id_foreign` (`presupuesto_id`),
  ADD KEY `presupuesto_materials_material_id_foreign` (`material_id`);

--
-- Indices de la tabla `presupuesto_operarios`
--
ALTER TABLE `presupuesto_operarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `presupuesto_operarios_presupuesto_id_foreign` (`presupuesto_id`),
  ADD KEY `presupuesto_operarios_operario_id_foreign` (`operario_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_usuario_unique` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `apis`
--
ALTER TABLE `apis`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `avance_obras`
--
ALTER TABLE `avance_obras`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `historial_accions`
--
ALTER TABLE `historial_accions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de la tabla `institucions`
--
ALTER TABLE `institucions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `maquinarias`
--
ALTER TABLE `maquinarias`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `materials`
--
ALTER TABLE `materials`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `notificacions`
--
ALTER TABLE `notificacions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notificacion_users`
--
ALTER TABLE `notificacion_users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `obras`
--
ALTER TABLE `obras`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `operarios`
--
ALTER TABLE `operarios`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `presupuestos`
--
ALTER TABLE `presupuestos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `presupuesto_maquinarias`
--
ALTER TABLE `presupuesto_maquinarias`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `presupuesto_materials`
--
ALTER TABLE `presupuesto_materials`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `presupuesto_operarios`
--
ALTER TABLE `presupuesto_operarios`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `avance_obras`
--
ALTER TABLE `avance_obras`
  ADD CONSTRAINT `avance_obras_obra_id_foreign` FOREIGN KEY (`obra_id`) REFERENCES `obras` (`id`);

--
-- Filtros para la tabla `notificacion_users`
--
ALTER TABLE `notificacion_users`
  ADD CONSTRAINT `notificacion_users_notificacion_id_foreign` FOREIGN KEY (`notificacion_id`) REFERENCES `notificacions` (`id`),
  ADD CONSTRAINT `notificacion_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `obras`
--
ALTER TABLE `obras`
  ADD CONSTRAINT `obras_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`),
  ADD CONSTRAINT `obras_encargado_obra_id_foreign` FOREIGN KEY (`encargado_obra_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `obras_gerente_regional_id_foreign` FOREIGN KEY (`gerente_regional_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `presupuestos`
--
ALTER TABLE `presupuestos`
  ADD CONSTRAINT `presupuestos_obra_id_foreign` FOREIGN KEY (`obra_id`) REFERENCES `obras` (`id`);

--
-- Filtros para la tabla `presupuesto_maquinarias`
--
ALTER TABLE `presupuesto_maquinarias`
  ADD CONSTRAINT `presupuesto_maquinarias_maquinaria_id_foreign` FOREIGN KEY (`maquinaria_id`) REFERENCES `maquinarias` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `presupuesto_maquinarias_presupuesto_id_foreign` FOREIGN KEY (`presupuesto_id`) REFERENCES `presupuestos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `presupuesto_materials`
--
ALTER TABLE `presupuesto_materials`
  ADD CONSTRAINT `presupuesto_materials_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `presupuesto_materials_presupuesto_id_foreign` FOREIGN KEY (`presupuesto_id`) REFERENCES `presupuestos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `presupuesto_operarios`
--
ALTER TABLE `presupuesto_operarios`
  ADD CONSTRAINT `presupuesto_operarios_operario_id_foreign` FOREIGN KEY (`operario_id`) REFERENCES `operarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `presupuesto_operarios_presupuesto_id_foreign` FOREIGN KEY (`presupuesto_id`) REFERENCES `presupuestos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
