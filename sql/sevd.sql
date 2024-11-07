-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-11-2024 a las 00:25:30
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
-- Base de datos: `sevd`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carreras`
--

CREATE TABLE `carreras` (
  `idcarrera` int(11) NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `descripcion` text NOT NULL,
  `planestudiocarrera` varchar(50) DEFAULT NULL,
  `resolucionministerial` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carreras`
--

INSERT INTO `carreras` (`idcarrera`, `nombre`, `descripcion`, `planestudiocarrera`, `resolucionministerial`) VALUES
(1, 'Tecnicatura Superior en Análisis de Sistemas', 'Técnico superior en análisis de sistemas', 'Plan_Estudio_Analistas.pdf', ''),
(2, 'Tecnicatura Superior en Administración y Gestión de las empr', 'Técnico Superior en Administración y Gestión de las empresas', '', ''),
(3, 'Tecnicatura Superior en Bioseguridad, Higiene y Seguridad', 'Técnico Superior en Bioseguridad, Higiene y Seguridad', '', ''),
(4, 'Pendiente', 'Carrera  no asignada ', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `correlatividades`
--

CREATE TABLE `correlatividades` (
  `idcorrelatividad` int(11) NOT NULL,
  `Idmateriapadre` int(11) NOT NULL,
  `idmateriahijo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `correlatividades`
--

INSERT INTO `correlatividades` (`idcorrelatividad`, `Idmateriapadre`, `idmateriahijo`) VALUES
(1, 1, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `idestudiante` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `apellido` varchar(30) NOT NULL,
  `fechanacimiento` date NOT NULL,
  `direccion` varchar(50) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `idcarrera` int(11) NOT NULL,
  `dni` int(8) UNSIGNED DEFAULT NULL,
  `idusuario` int(11) DEFAULT NULL,
  `eliminado` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`idestudiante`, `nombre`, `apellido`, `fechanacimiento`, `direccion`, `telefono`, `correo`, `idcarrera`, `dni`, `idusuario`, `eliminado`) VALUES
(2, 'Franco Emanuel', 'Anker Nielsen', '1999-02-09', 'Nueva Dirección 123', '3751498789', 'franconielsen97@hotmail.com.ar', 1, 41285952, 26, 0),
(8, 'Mariano Lorenzo', 'Villalba', '2003-12-16', 'Itaembe Miní, Calle 180, Casa 7022', '3764222212', 'm.villalba@gmail.com', 1, 45391192, NULL, 0),
(9, 'Danzel', 'Burki', '2003-07-14', 'Av. Kolping y Av. Blas Parera', '3757512877', 'burki.dannzel@gmail.com', 3, 45026226, NULL, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes_mesas`
--

CREATE TABLE `estudiantes_mesas` (
  `notaexamen` decimal(4,2) NOT NULL,
  `fechapreinscripcion` date NOT NULL,
  `fechainscripcion` date NOT NULL,
  `idestudiante` int(11) NOT NULL,
  `idmesa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripciones`
--

CREATE TABLE `inscripciones` (
  `idinscripcion` int(11) NOT NULL,
  `estado` varchar(20) NOT NULL,
  `fechainscripcion` date NOT NULL,
  `idestudiante` int(11) NOT NULL,
  `idmateria` int(11) NOT NULL,
  `condicion` enum('Regular','Libre') NOT NULL DEFAULT 'Regular'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inscripciones`
--

INSERT INTO `inscripciones` (`idinscripcion`, `estado`, `fechainscripcion`, `idestudiante`, `idmateria`, `condicion`) VALUES
(2, 'Activo', '2024-11-22', 8, 3, 'Libre'),
(3, 'Activo', '2024-11-20', 8, 6, 'Regular'),
(4, 'Activo', '2024-11-03', 2, 3, 'Libre');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materias`
--

CREATE TABLE `materias` (
  `idmateria` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `aniocursado` int(11) NOT NULL,
  `planestudiocarrera` varchar(100) NOT NULL,
  `idcarrera` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `materias`
--

INSERT INTO `materias` (`idmateria`, `nombre`, `aniocursado`, `planestudiocarrera`, `idcarrera`) VALUES
(1, 'Programacion I', 1, '', 1),
(2, 'Base de Datos', 2, '', 1),
(3, 'Química 1', 1, '', 3),
(4, 'Bioseguridad 3', 3, '', 3),
(5, 'Economía', 1, '', 2),
(6, 'Marketing', 2, '', 2),
(7, 'Programacion II', 2, '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `idmesa` int(11) NOT NULL,
  `fechahora` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `inicioinscripcion` date NOT NULL,
  `fininscripcion` date NOT NULL,
  `idmateria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`idmesa`, `fechahora`, `inicioinscripcion`, `fininscripcion`, `idmateria`) VALUES
(1, '2024-10-31 20:54:37', '2024-10-02', '2024-10-16', 1),
(2, '2024-10-11 14:18:06', '2024-10-01', '2024-10-08', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas`
--

CREATE TABLE `notas` (
  `idnota` int(11) NOT NULL,
  `valor` decimal(4,2) NOT NULL,
  `idtiponota` int(11) NOT NULL,
  `idmateria` int(11) NOT NULL,
  `idestudiante` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `notas`
--

INSERT INTO `notas` (`idnota`, `valor`, `idtiponota`, `idmateria`, `idestudiante`) VALUES
(6, 8.50, 1, 1, 2),
(7, 7.75, 3, 2, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `idpermiso` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `modulo` varchar(30) NOT NULL,
  `icono` varchar(50) NOT NULL,
  `eliminado` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`idpermiso`, `nombre`, `descripcion`, `modulo`, `icono`, `eliminado`) VALUES
(1, 'Inscripción a mesas', 'Accede al portal para inscribirte en las materias del próximo semestre.', 'inscripcion_mesas', 'fas fa-clipboard-list', 0),
(2, 'Estado Académico', 'Accede a tus notas de cada materia cursada y condición a materias.', 'estado_academico', 'fas fa-book-open', 0),
(4, 'Tus Cursos', 'Accede al portal para gestionar tus cursos y cargar las notas de tus alumnos.', 'tus_cursos', 'fas fa-chalkboard-teacher', 0),
(5, 'Gestión de las Carreras', 'Este módulo permite ver la lista de alumnos de las diferentes carreras y administrar los planes de e', 'gestion_carreras', 'fas fa-folder-open', 1),
(8, 'ABM Permisos', 'Podras gestionar la lista de permisos', 'amb_permisos', 'fas fa-lock', 0),
(9, 'Prueba', 'Prueba', 'prueba', 'prueba', 1),
(10, 'danzel', 'blabla', 'ola_kase', 'prueba', 1),
(11, 'Permisos Usuarios', 'Se utiliza para gestionar los permisos especiales por ususarios', 'permisos_usuarios', 'fas fa-clipboard-list', 0),
(12, 'Permisos Roles', 'Se utiliza para gestionar los permisos por roles', 'permisos_roles', 'fas fa-clipboard-list', 0),
(13, 'ABM Roles', 'Se utiliza para gestionar los roles', 'amb_roles', 'fas fa-users', 0),
(14, 'ABM Estudiantes', 'Se utiliza para gestionar los estudiantes', 'amb_estudiantes', 'fas fa-user-graduate', 0),
(15, 'Acta volante de exámenes ', 'Permite administrar las notas de los exámenes finales', 'registro_acta', 'fas fa-file-alt', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `idrol` int(11) NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `descripcion` varchar(20) NOT NULL,
  `eliminado` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`idrol`, `tipo`, `descripcion`, `eliminado`) VALUES
(1, 'Estudiante', 'Estudiante', 0),
(2, 'Docente', 'Docente', 0),
(3, 'Administrador', 'Administración', 0),
(4, 'Super Usuario', 'Administrador BD', 0),
(5, 'test', 'test2', 1),
(6, 'test', 'wertyuiop', 1),
(7, 'test', 'amb de itemsrtghu6j7', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles_permisos`
--

CREATE TABLE `roles_permisos` (
  `idrol` int(11) NOT NULL,
  `idpermiso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles_permisos`
--

INSERT INTO `roles_permisos` (`idrol`, `idpermiso`) VALUES
(1, 1),
(1, 2),
(2, 4),
(2, 1),
(4, 13),
(4, 14),
(5, 9),
(4, 8),
(3, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiponotas`
--

CREATE TABLE `tiponotas` (
  `idtiponota` int(11) NOT NULL,
  `descripcion` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tiponotas`
--

INSERT INTO `tiponotas` (`idtiponota`, `descripcion`) VALUES
(1, 'Parcial'),
(2, 'Trabajo Practico'),
(3, 'Final');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `idusuario` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `clave` varchar(20) NOT NULL,
  `idrol` int(11) NOT NULL,
  `dni` int(8) UNSIGNED DEFAULT NULL,
  `apellido` varchar(20) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `nombreusuario` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idusuario`, `nombre`, `clave`, `idrol`, `dni`, `apellido`, `correo`, `nombreusuario`) VALUES
(4, 'Danzel', '123456789', 2, 12345678, 'Burki', 'danzelburki@gmail.com', 'DanzelB'),
(7, 'Ivan', '123456789', 3, 87654321, 'Ivan', 'sdfasdf@gmail.com', 'ivan01'),
(10, 'Agus', '123456789', 4, 87655432, 'Encina', 'asdafgh@gmail.com', 'agus01'),
(23, 'marianiano', '123456789', 3, 45391192, 'villslba', 'viollalala@gmail.com', 'marianexo'),
(25, 'Laura', '123', 1, 33445566, 'Gomez', 'laura.gomez@example.com', 'laurag'),
(26, 'Franco Emanuel', '123', 1, 41285952, 'Anker Nielsen', 'franconielsen97@hotmail.com.ar', 'franco'),
(27, 'Mónica Patricia', '123', 3, 23468020, 'Rojas', 'correo_monica@gmail.com', 'monica'),
(28, 'Gabriela Itatí', '123', 3, 40897356, 'Romero', 'correo_gabriela@gmail.com', 'gabriela'),
(29, 'Alexis Santiago', '123', 3, 28403664, 'Valenzuela', 'correo_alexis@gmail.com', 'alexis');

--
-- Disparadores `usuarios`
--
DELIMITER $$
CREATE TRIGGER `after_insert_usuario` AFTER INSERT ON `usuarios` FOR EACH ROW BEGIN
    /* Verificar si el nuevo usuario tiene el rol de Estudiante (asumiendo que el idrol para estudiante es 1) */
    IF NEW.idrol = 1 THEN
        /* Insertar el nuevo estudiante en la tabla 'estudiantes'*/
        INSERT INTO estudiantes (nombre, apellido, dni, correo, idusuario, idcarrera)
        VALUES (NEW.nombre, NEW.apellido, NEW.dni, NEW.correo, NEW.idusuario, 4); 
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_permisos`
--

CREATE TABLE `usuarios_permisos` (
  `idusuario` int(11) NOT NULL,
  `idpermiso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios_permisos`
--

INSERT INTO `usuarios_permisos` (`idusuario`, `idpermiso`) VALUES
(10, 11),
(10, 12),
(28, 15);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_correlatividades`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_correlatividades` (
`materia_padre` varchar(30)
,`materia_hijo` varchar(30)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_estudiantes_carreras`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_estudiantes_carreras` (
`nombre` varchar(30)
,`apellido` varchar(30)
,`carrera` varchar(60)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_inscripciones_activas`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_inscripciones_activas` (
`nombre_estudiante` varchar(30)
,`apellido_estudiante` varchar(30)
,`materia` varchar(30)
,`estado_inscripcion` varchar(20)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_materias_carrera`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_materias_carrera` (
`carrera` varchar(60)
,`materia` varchar(30)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_mesas_examen`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_mesas_examen` (
`materia` varchar(30)
,`fecha_hora_mesa` varchar(21)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_notas_estudiantes`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_notas_estudiantes` (
`nombre_estudiante` varchar(30)
,`apellido_estudiante` varchar(30)
,`materia` varchar(30)
,`nota` decimal(4,2)
,`tipo_nota` varchar(20)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_permisos_usuarios`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_permisos_usuarios` (
`nombre_usuario` varchar(20)
,`apellido_usuario` varchar(20)
,`permiso` varchar(50)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_correlatividades`
--
DROP TABLE IF EXISTS `vista_correlatividades`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_correlatividades`  AS SELECT `mp`.`nombre` AS `materia_padre`, `mh`.`nombre` AS `materia_hijo` FROM ((`correlatividades` `c` join `materias` `mp` on(`c`.`Idmateriapadre` = `mp`.`idmateria`)) join `materias` `mh` on(`c`.`idmateriahijo` = `mh`.`idmateria`)) ORDER BY `mh`.`nombre` ASC, `mp`.`nombre` ASC ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_estudiantes_carreras`
--
DROP TABLE IF EXISTS `vista_estudiantes_carreras`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_estudiantes_carreras`  AS SELECT `e`.`nombre` AS `nombre`, `e`.`apellido` AS `apellido`, `c`.`nombre` AS `carrera` FROM (`estudiantes` `e` join `carreras` `c` on(`e`.`idcarrera` = `c`.`idcarrera`)) ORDER BY `e`.`nombre` ASC, `e`.`apellido` ASC, `c`.`nombre` ASC ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_inscripciones_activas`
--
DROP TABLE IF EXISTS `vista_inscripciones_activas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_inscripciones_activas`  AS SELECT `e`.`nombre` AS `nombre_estudiante`, `e`.`apellido` AS `apellido_estudiante`, `m`.`nombre` AS `materia`, `i`.`estado` AS `estado_inscripcion` FROM ((`inscripciones` `i` join `estudiantes` `e` on(`e`.`idestudiante` = `i`.`idestudiante`)) join `materias` `m` on(`m`.`idmateria` = `i`.`idmateria`)) WHERE `i`.`estado` = 'Activo' ORDER BY `e`.`nombre` ASC, `e`.`apellido` ASC, `m`.`nombre` ASC ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_materias_carrera`
--
DROP TABLE IF EXISTS `vista_materias_carrera`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_materias_carrera`  AS SELECT `c`.`nombre` AS `carrera`, `m`.`nombre` AS `materia` FROM (`carreras` `c` join `materias` `m` on(`c`.`idcarrera` = `m`.`idcarrera`)) ORDER BY `c`.`nombre` ASC, `m`.`nombre` ASC ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_mesas_examen`
--
DROP TABLE IF EXISTS `vista_mesas_examen`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_mesas_examen`  AS SELECT `m`.`nombre` AS `materia`, date_format(`me`.`fechahora`,'%d/%m/%Y %H:%i') AS `fecha_hora_mesa` FROM (`mesas` `me` join `materias` `m` on(`m`.`idmateria` = `me`.`idmateria`)) ORDER BY `m`.`nombre` ASC, `me`.`fechahora` ASC ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_notas_estudiantes`
--
DROP TABLE IF EXISTS `vista_notas_estudiantes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_notas_estudiantes`  AS SELECT `e`.`nombre` AS `nombre_estudiante`, `e`.`apellido` AS `apellido_estudiante`, `m`.`nombre` AS `materia`, `n`.`valor` AS `nota`, `t`.`descripcion` AS `tipo_nota` FROM (((`estudiantes` `e` join `notas` `n` on(`e`.`idestudiante` = `n`.`idestudiante`)) join `materias` `m` on(`n`.`idmateria` = `m`.`idmateria`)) join `tiponotas` `t` on(`n`.`idtiponota` = `t`.`idtiponota`)) WHERE `t`.`descripcion` = 'Final' ORDER BY `e`.`nombre` ASC, `e`.`apellido` ASC, `m`.`nombre` ASC ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_permisos_usuarios`
--
DROP TABLE IF EXISTS `vista_permisos_usuarios`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_permisos_usuarios`  AS SELECT `u`.`nombre` AS `nombre_usuario`, `u`.`apellido` AS `apellido_usuario`, `p`.`nombre` AS `permiso` FROM ((`usuarios` `u` join `roles_permisos` `rp` on(`rp`.`idrol` = `u`.`idrol`)) join `permisos` `p` on(`p`.`idpermiso` = `rp`.`idpermiso`)) ORDER BY `u`.`nombre` ASC, `u`.`apellido` ASC, `p`.`nombre` ASC ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carreras`
--
ALTER TABLE `carreras`
  ADD PRIMARY KEY (`idcarrera`);

--
-- Indices de la tabla `correlatividades`
--
ALTER TABLE `correlatividades`
  ADD PRIMARY KEY (`idcorrelatividad`),
  ADD KEY `materias_idmateriapadre_correlatividades` (`Idmateriapadre`),
  ADD KEY `materias_idmateriahijo_correlatividades` (`idmateriahijo`);

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`idestudiante`),
  ADD KEY `carreras_idcarrera_estudiantes` (`idcarrera`),
  ADD KEY `usuarios_idusuario_estudiantes` (`idusuario`);

--
-- Indices de la tabla `estudiantes_mesas`
--
ALTER TABLE `estudiantes_mesas`
  ADD KEY `estudiantes_idestudiante_estudiantes_mesas` (`idestudiante`),
  ADD KEY `mesas_idmesa_estudiantes_mesas` (`idmesa`);

--
-- Indices de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD PRIMARY KEY (`idinscripcion`),
  ADD KEY `estudiantes_idestudiante_inscripciones` (`idestudiante`),
  ADD KEY `materias_idmateria_inscripciones` (`idmateria`);

--
-- Indices de la tabla `materias`
--
ALTER TABLE `materias`
  ADD PRIMARY KEY (`idmateria`),
  ADD KEY `carreras_idcarrera_materias` (`idcarrera`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`idmesa`),
  ADD KEY `materias_idmateria_mesas` (`idmateria`);

--
-- Indices de la tabla `notas`
--
ALTER TABLE `notas`
  ADD PRIMARY KEY (`idnota`),
  ADD KEY `tiponotas_idtiponota_notas` (`idtiponota`),
  ADD KEY `materias_idmateria_notas` (`idmateria`),
  ADD KEY `estudiantes_idestudiante_notas` (`idestudiante`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`idpermiso`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`idrol`);

--
-- Indices de la tabla `roles_permisos`
--
ALTER TABLE `roles_permisos`
  ADD KEY `roles_idrol_roles_permisos` (`idrol`),
  ADD KEY `permisos_idpermiso_roles_permisos` (`idpermiso`);

--
-- Indices de la tabla `tiponotas`
--
ALTER TABLE `tiponotas`
  ADD PRIMARY KEY (`idtiponota`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idusuario`),
  ADD KEY `roles_idrol_usuarios` (`idrol`);

--
-- Indices de la tabla `usuarios_permisos`
--
ALTER TABLE `usuarios_permisos`
  ADD KEY `usuarios_idusuario_usuarios_permisos` (`idusuario`),
  ADD KEY `permisos_idpermiso_usuarios_permisos` (`idpermiso`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carreras`
--
ALTER TABLE `carreras`
  MODIFY `idcarrera` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `correlatividades`
--
ALTER TABLE `correlatividades`
  MODIFY `idcorrelatividad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `idestudiante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  MODIFY `idinscripcion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `materias`
--
ALTER TABLE `materias`
  MODIFY `idmateria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `idmesa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `notas`
--
ALTER TABLE `notas`
  MODIFY `idnota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `idpermiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `idrol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tiponotas`
--
ALTER TABLE `tiponotas`
  MODIFY `idtiponota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `correlatividades`
--
ALTER TABLE `correlatividades`
  ADD CONSTRAINT `materias_idmateriahijo_correlatividades` FOREIGN KEY (`idmateriahijo`) REFERENCES `materias` (`idmateria`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `materias_idmateriapadre_correlatividades` FOREIGN KEY (`Idmateriapadre`) REFERENCES `materias` (`idmateria`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD CONSTRAINT `carreras_idcarrera_estudiantes` FOREIGN KEY (`idcarrera`) REFERENCES `carreras` (`idcarrera`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `estudiantes_mesas`
--
ALTER TABLE `estudiantes_mesas`
  ADD CONSTRAINT `estudiantes_idestudiante_estudiantes_mesas` FOREIGN KEY (`idestudiante`) REFERENCES `estudiantes` (`idestudiante`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `mesas_idmesa_estudiantes_mesas` FOREIGN KEY (`idmesa`) REFERENCES `mesas` (`idmesa`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD CONSTRAINT `estudiantes_idestudiante_inscripciones` FOREIGN KEY (`idestudiante`) REFERENCES `estudiantes` (`idestudiante`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `materias_idmateria_inscripciones` FOREIGN KEY (`idmateria`) REFERENCES `materias` (`idmateria`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `materias`
--
ALTER TABLE `materias`
  ADD CONSTRAINT `carreras_idcarrera_materias` FOREIGN KEY (`idcarrera`) REFERENCES `carreras` (`idcarrera`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD CONSTRAINT `materias_idmateria_mesas` FOREIGN KEY (`idmateria`) REFERENCES `materias` (`idmateria`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `notas`
--
ALTER TABLE `notas`
  ADD CONSTRAINT `estudiantes_idestudiante_notas` FOREIGN KEY (`idestudiante`) REFERENCES `estudiantes` (`idestudiante`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `materias_idmateria_notas` FOREIGN KEY (`idmateria`) REFERENCES `materias` (`idmateria`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `tiponotas_idtiponota_notas` FOREIGN KEY (`idtiponota`) REFERENCES `tiponotas` (`idtiponota`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `roles_permisos`
--
ALTER TABLE `roles_permisos`
  ADD CONSTRAINT `permisos_idpermiso_roles_permisos` FOREIGN KEY (`idpermiso`) REFERENCES `permisos` (`idpermiso`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `roles_idrol_roles_permisos` FOREIGN KEY (`idrol`) REFERENCES `roles` (`idrol`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `roles_idrol_usuarios` FOREIGN KEY (`idrol`) REFERENCES `roles` (`idrol`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usuarios_permisos`
--
ALTER TABLE `usuarios_permisos`
  ADD CONSTRAINT `permisos_idpermiso_usuarios_permisos` FOREIGN KEY (`idpermiso`) REFERENCES `permisos` (`idpermiso`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `usuarios_idusuario_usuarios_permisos` FOREIGN KEY (`idusuario`) REFERENCES `usuarios` (`idusuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
