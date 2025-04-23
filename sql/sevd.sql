-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-04-2025 a las 21:39:41
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
  `nombre` varchar(80) NOT NULL,
  `descripcion` text NOT NULL,
  `planestudiocarrera` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carreras`
--

INSERT INTO `carreras` (`idcarrera`, `nombre`, `descripcion`, `planestudiocarrera`) VALUES
(1, 'Tecnicatura Superior en Análisis de Sistemas', 'Técnico superior en análisis de sistemas', 'Plan_Estudio_Analistas.pdf'),
(2, 'Tecnicatura Superior en Administración y Gestión de las empresas', 'Técnico Superior en Administración y Gestión de las empresas', 'plan_de_estudio_Administraci__n.pdf'),
(3, 'Tecnicatura Superior en Bioseguridad, Higiene y Seguridad', 'Técnico Superior en Bioseguridad, Higiene y Seguridad', 'Analistas.pdf'),
(4, 'Pendiente', 'Carrera  no asignada ', '');

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
(8, 'Mariano Lorenzo', 'Villalba', '2003-12-16', 'Itaembe Miní, Calle 180, Casa 7022', '3764222212', 'm.villalba@gmail.com', 2, 45391192, NULL, 0),
(9, 'Danzel', 'Burki', '2003-07-14', 'Av. Kolping y Av. Blas Parera', '3757512877', 'burki.danzel@gmail.com', 3, 45026226, NULL, 0),
(14, 'ivan', 'dzs', '2024-12-12', 'sdfgh', '3751496788', 'dsfsdf@gmail.com', 1, 12345678, 32, 0);

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
  `estado` enum('Pendiente','Activo') NOT NULL,
  `fechainscripcion` date NOT NULL,
  `idestudiante` int(11) NOT NULL,
  `idmateria` int(11) NOT NULL,
  `condicion` enum('Regular','Libre') NOT NULL DEFAULT 'Regular',
  `idmesa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(15, '2024-12-16 19:32:43', '2024-12-08', '2024-12-14', 1);

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
(11, 8.00, 1, 1, 2),
(12, 4.00, 1, 2, 2),
(13, 8.00, 1, 1, 2);

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
(1, 'Pre-Inscripción a mesas', 'Accede al portal para pre-inscribirte en las materias del próximo semestre.', 'inscripcion_mesas', 'fas fa-clipboard-list', 0),
(2, 'Estado Académico', 'Accede a tus notas de cada materia cursada y condición a materias.', 'estado_academico', 'fas fa-book-open', 0),
(4, 'Tus Cursos', 'Accede al portal para gestionar tus cursos y cargar las notas de tus alumnos.', 'tus_cursos', 'fas fa-chalkboard-teacher', 0),
(5, 'Administración y gestión de las empresas', 'Gestionar el plan de estudio de Administración y Gestión de Empresas.', 'administracion_empresas', 'fas fa-briefcase', 1),
(6, 'Bioseguridad, higiene y seguridad', 'Gestionar el plan de estudio de Bioseguridad, Higiene y Seguridad.', 'higiene_seguridad', 'fas fa-first-aid', 1),
(7, 'Análisis de sistemas', 'Gestionar el plan de estudio de Análisis de Sistemas.', 'analista_sistemas', 'fas fa-laptop', 1),
(8, 'ABM Permisos', 'Podras gestionar la lista de permisos', 'amb_permisos', 'fas fa-lock', 0),
(11, 'Permisos Usuarios', 'Se utiliza para gestionar los permisos especiales por ususarios', 'permisos_usuarios', 'fas fa-clipboard-list', 0),
(12, 'Permisos Roles', 'Se utiliza para gestionar los permisos por roles', 'permisos_roles', 'fas fa-clipboard-list', 0),
(13, 'ABM Roles', 'Se utiliza para gestionar los roles', 'amb_roles', 'fas fa-users', 0),
(14, 'ABM Estudiantes', 'Se utiliza para gestionar los estudiantes', 'amb_estudiantes', 'fas fa-user-graduate', 0),
(15, 'Reportes', 'Accede a tu historial académico y al plan de estudio ', 'reportes', 'fas fa-clipboard-list', 0),
(16, 'Acta Volante', 'Permite administrar las notas de los exámenes finales', 'registro_acta', 'fas fa-file-alt', 0),
(17, 'Gestión de Carreras', 'Permite ver lista de alumnos de las diferentes carreras', 'gestion_carreras', 'fas fa-folder-open', 0),
(18, 'Consulta plan de estudio', 'Permite consulta el plan de estudio de la carrera.', 'consulta_plan_estudio', 'fa-solid fa-clipboard-check', 0),
(19, 'Consulta historial académico', 'Permite realizar la consulta acerca del historial académico.', 'consulta_historial_academico', 'fas fa-graduation-cap', 0),
(20, 'Administrar mesas.', 'Gestionar las inscripciones finales de las mesas de examen.', 'administracion_mesas', 'fas fa-file-alt', 0),
(21, 'Carga de notas', 'En este módulo se permitirá la carga de notas de los estudiantes', 'carga_notas', 'fas fa-file-signature', 0),
(22, 'Validación de Usuarios', 'Permite verificar los nuevos usuarios para que accedan al sistema', 'abm_verificar_usuario', 'fa-solid fa-user-check', 0);

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
(8, 'visitante', 'visita', 1);

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
(4, 8),
(1, 15),
(3, 17),
(3, 18),
(3, 19),
(4, 22);

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
(3, 'Final'),
(4, 'Recuperatorio');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `idusuario` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `clave` varchar(80) NOT NULL,
  `idrol` int(11) NOT NULL,
  `dni` int(8) UNSIGNED DEFAULT NULL,
  `apellido` varchar(20) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `nombreusuario` varchar(20) NOT NULL,
  `verificacion` enum('pendiente','verificado') NOT NULL DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idusuario`, `nombre`, `clave`, `idrol`, `dni`, `apellido`, `correo`, `nombreusuario`, `verificacion`) VALUES
(4, 'Danzel', '$2y$10$Rfe.6BQEZQS61KPJtU0p9Oyu90D63zbGBwbYy.0DtTBc7PpYXNFIi', 1, 12345678, 'Burki', 'danzelburki@gmail.com', 'danzel', 'verificado'),
(10, 'Agus', '$2y$10$nMafU9rGdoDlv1nb.sHjNO7W0UAQe0VULe53lb8Kx.ww7E/MAuY0W', 4, 87655432, 'Encina', 'asdafgh@gmail.com', 'agus01', 'verificado'),
(23, 'Mariano', '$2y$10$0/9kzZ5kZVJyvMavo5lrxu8uqV6B..NC4lWg6tpw6nWn.Eai4O1A.', 1, 45391192, 'Villalba', 'viollalala@gmail.com', 'mariano', 'verificado'),
(26, 'Franco Emanuel', '$2y$10$0.k5sbeN8qoPxQekav3W.OoebB0LVlhmbhstNINjIugnx30084v4G', 1, 41285952, 'Anker Nielsen', 'franconielsen97@hotmail.com.ar', 'franco', 'verificado'),
(27, 'Mónica Patricia', '$2y$10$mJ8CZwcjrkQ..Rs8ugXS1.jphkb5AFbgH26KGSWW27HtWMn29TxNa', 3, 23468020, 'Rojas', 'correo_monica@gmail.com', 'monica', 'verificado'),
(28, 'Gabriela Itatí', '$2y$10$FEUct7pWRJXDnvwi08xTLOkFoj3j6IJNM4OAB/FO25S6Id/.bErP2', 3, 40897356, 'Romero', 'correo_gabriela@gmail.com', 'gabriela', 'verificado'),
(29, 'Alexis Santiago', '$2y$10$wjqj0y/UfZS6oe8MSCPz1.FdmS05zEg5/5xcUCh4c8DBJOHtPcAFK', 3, 28403664, 'Valenzuela', 'correo_alexis@gmail.com', 'alexis', 'verificado'),
(32, 'ivan', '$2y$10$lh7jyP99ZNghSUSciQm3m.I1VRsipsbvO2jQBFQ/PJVZkznMxafMu', 1, 12345678, 'dzs', 'dsfsdf@gmail.com', 'ivan', 'verificado'),
(43, 'Franco', '$2y$10$nKkQXz/0a9kaxG/8nOwn8OYXpbPnzDKO38jSzFYwkAb6Xg22pHNrK', 1, 41285951, 'Nielsen', 'franconielsen99@hotmail.com.ar', 'franco1', 'verificado');

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
(28, 16),
(27, 20),
(28, 21);

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
  MODIFY `idcarrera` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `correlatividades`
--
ALTER TABLE `correlatividades`
  MODIFY `idcorrelatividad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `idestudiante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  MODIFY `idinscripcion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `materias`
--
ALTER TABLE `materias`
  MODIFY `idmateria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `idmesa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `notas`
--
ALTER TABLE `notas`
  MODIFY `idnota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `idpermiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `idrol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tiponotas`
--
ALTER TABLE `tiponotas`
  MODIFY `idtiponota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

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
