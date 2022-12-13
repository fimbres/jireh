-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-12-2022 a las 04:55:31
-- Versión del servidor: 10.4.25-MariaDB
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `jireh`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_admin`
--

CREATE TABLE `tb_admin` (
  `IdAdmin` int(11) NOT NULL,
  `Usuario` varchar(10) NOT NULL,
  `Contrasena` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tb_admin`
--

INSERT INTO `tb_admin` (`IdAdmin`, `Usuario`, `Contrasena`) VALUES
(1, 'root', 'root');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_cita`
--

CREATE TABLE `tb_cita` (
  `IdCita` int(11) NOT NULL,
  `IdPaciente` int(11) NOT NULL,
  `FechaInicio` datetime NOT NULL,
  `FechaFinal` datetime NOT NULL,
  `IdDoctor` int(11) NOT NULL,
  `Descripcion` varchar(100) NOT NULL,
  `IdTratamiento` int(11) DEFAULT NULL,
  `MotivoCancelacion` varchar(300) DEFAULT NULL,
  `Costo` float DEFAULT NULL,
  `IdStatus` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_datofactura`
--

CREATE TABLE `tb_datofactura` (
  `IdFactura` int(11) NOT NULL,
  `RFC` varchar(13) NOT NULL,
  `NUMTransaccion` varchar(15) DEFAULT NULL,
  `Regimen` varchar(6) NOT NULL,
  `Concepto` varchar(50) NOT NULL,
  `IdPago` int(11) NOT NULL,
  `IdCita` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_doctor`
--

CREATE TABLE `tb_doctor` (
  `IdDoctor` int(11) NOT NULL,
  `Nombre` varchar(20) NOT NULL,
  `APaterno` varchar(15) NOT NULL,
  `AMaterno` varchar(15) DEFAULT NULL,
  `Email` varchar(50) NOT NULL,
  `NumTelefono` varchar(13) NOT NULL,
  `Usuario` varchar(10) NOT NULL,
  `Contrasena` varchar(15) NOT NULL,
  `IdStatus` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_doctorespecialidad`
--

CREATE TABLE `tb_doctorespecialidad` (
  `IdDoctorEspecialidad` int(11) NOT NULL,
  `IdDoctor` int(11) NOT NULL,
  `IdEspecialidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_especialidad`
--

CREATE TABLE `tb_especialidad` (
  `IdEspecialidad` int(11) NOT NULL,
  `Descripcion` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tb_especialidad`
--

INSERT INTO `tb_especialidad` (`IdEspecialidad`, `Descripcion`) VALUES
(1, 'Endodoncia'),
(2, 'Poste/Resina'),
(3, 'Consulta'),
(4, 'Cita Exploratoria'),
(5, 'Periodoncia'),
(6, 'Curetage'),
(7, 'Limpieza Profunda'),
(8, 'Alargamiento'),
(9, 'Extracción Complicada'),
(10, 'Extraccion 3er Molar'),
(11, 'Regularizacion');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_estadocivil`
--

CREATE TABLE `tb_estadocivil` (
  `IdEstadoCivil` int(11) NOT NULL,
  `EstadoCivil` varchar(14) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tb_estadocivil`
--

INSERT INTO `tb_estadocivil` (`IdEstadoCivil`, `EstadoCivil`) VALUES
(1, 'Soltero(a)'),
(2, 'Casado(a)'),
(3, 'Divorsiado(a)'),
(4, 'Union Libre');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_metodopago`
--

CREATE TABLE `tb_metodopago` (
  `IdMetodo` int(11) NOT NULL,
  `MetodoPago` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tb_metodopago`
--

INSERT INTO `tb_metodopago` (`IdMetodo`, `MetodoPago`) VALUES
(1, 'Stripe'),
(2, 'PayPal'),
(3, 'Efectivo'),
(4, 'Tarjeta'),
(5, 'Transferencia');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_paciente`
--

CREATE TABLE `tb_paciente` (
  `IdPaciente` int(11) NOT NULL,
  `Nombre` varchar(20) NOT NULL,
  `APaterno` varchar(15) NOT NULL,
  `AMaterno` varchar(15) DEFAULT NULL,
  `IdSexo` int(11) NOT NULL,
  `Direccion` varchar(100) NOT NULL,
  `CodigoPostal` varchar(5) DEFAULT NULL,
  `Email` varchar(35) NOT NULL,
  `NumTelefono` varchar(13) NOT NULL,
  `FechaNacimiento` date NOT NULL,
  `Ocupacion` varchar(15) DEFAULT NULL,
  `IdEstadoCivil` int(11) NOT NULL,
  `IdPoliza` int(11) DEFAULT NULL,
  `MedicoEnvia` varchar(50) DEFAULT NULL,
  `Representante` varchar(50) DEFAULT NULL,
  `ArchivoAntecedentes` varchar(500) DEFAULT NULL,
  `ArchivoPresupuesto` varchar(500) DEFAULT NULL,
  `RFC` varchar(15) DEFAULT NULL,
  `IdStatus` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_pago`
--

CREATE TABLE `tb_pago` (
  `IdPago` int(11) NOT NULL,
  `IdCita` int(11) DEFAULT NULL,
  `IdMetodoPago` int(11) NOT NULL,
  `FechaPago` datetime NOT NULL,
  `NumeroOperacion` varchar(100) NOT NULL,
  `AuthToken` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_polizas`
--

CREATE TABLE `tb_polizas` (
  `IdPoliza` int(11) NOT NULL,
  `Archivo` varchar(500) NOT NULL,
  `Fecha` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_recepcionista`
--

CREATE TABLE `tb_recepcionista` (
  `IdRecepcionista` int(11) NOT NULL,
  `Nombre` varchar(20) NOT NULL,
  `APaterno` varchar(15) NOT NULL,
  `AMaterno` varchar(15) DEFAULT NULL,
  `Email` varchar(50) NOT NULL,
  `NumTelefono` varchar(13) NOT NULL,
  `Usuario` varchar(10) NOT NULL,
  `Contrasena` varchar(15) NOT NULL,
  `IdStatus` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_sexo`
--

CREATE TABLE `tb_sexo` (
  `IdSexo` int(11) NOT NULL,
  `Sexo` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tb_sexo`
--

INSERT INTO `tb_sexo` (`IdSexo`, `Sexo`) VALUES
(1, 'Masculino'),
(2, 'Femenino');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_status`
--

CREATE TABLE `tb_status` (
  `IdStatus` int(11) NOT NULL,
  `Descripcion` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tb_status`
--

INSERT INTO `tb_status` (`IdStatus`, `Descripcion`) VALUES
(1, 'Vigente'),
(2, 'Cancelada'),
(3, 'Activo'),
(4, 'Inactivo'),
(5, 'Pagado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_tratamiento`
--

CREATE TABLE `tb_tratamiento` (
  `IdTratamiento` int(11) NOT NULL,
  `Descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tb_tratamiento`
--

INSERT INTO `tb_tratamiento` (`IdTratamiento`, `Descripcion`) VALUES
(1, 'Pediatría'),
(2, 'Ortodoncia'),
(3, 'Maxilofacial'),
(4, 'General'),
(5, 'Endodoncia'),
(6, 'Periodoncia');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`IdAdmin`);

--
-- Indices de la tabla `tb_cita`
--
ALTER TABLE `tb_cita`
  ADD PRIMARY KEY (`IdCita`),
  ADD KEY `Tb_Cita_FK` (`IdPaciente`),
  ADD KEY `Tb_Cita_FK_1` (`IdDoctor`),
  ADD KEY `Tb_Cita_FK_2` (`IdStatus`),
  ADD KEY `Tb_Cita_FK_3` (`IdTratamiento`);

--
-- Indices de la tabla `tb_datofactura`
--
ALTER TABLE `tb_datofactura`
  ADD PRIMARY KEY (`IdFactura`),
  ADD KEY `Tb_DatoFactura_FK_1` (`IdPago`),
  ADD KEY `Tb_DatoFactura_IdCita_IDX` (`IdCita`) USING BTREE;

--
-- Indices de la tabla `tb_doctor`
--
ALTER TABLE `tb_doctor`
  ADD PRIMARY KEY (`IdDoctor`),
  ADD UNIQUE KEY `UNIQUE_User` (`Usuario`),
  ADD UNIQUE KEY `UNIQUEEmail` (`Email`),
  ADD KEY `Tb_Doctor_FK` (`IdStatus`);

--
-- Indices de la tabla `tb_doctorespecialidad`
--
ALTER TABLE `tb_doctorespecialidad`
  ADD PRIMARY KEY (`IdDoctorEspecialidad`),
  ADD KEY `Tb_DoctorEspecialidad_FK` (`IdDoctor`),
  ADD KEY `Tb_DoctorEspecialidad_FK_1` (`IdEspecialidad`);

--
-- Indices de la tabla `tb_especialidad`
--
ALTER TABLE `tb_especialidad`
  ADD PRIMARY KEY (`IdEspecialidad`);

--
-- Indices de la tabla `tb_estadocivil`
--
ALTER TABLE `tb_estadocivil`
  ADD PRIMARY KEY (`IdEstadoCivil`);

--
-- Indices de la tabla `tb_metodopago`
--
ALTER TABLE `tb_metodopago`
  ADD PRIMARY KEY (`IdMetodo`);

--
-- Indices de la tabla `tb_paciente`
--
ALTER TABLE `tb_paciente`
  ADD PRIMARY KEY (`IdPaciente`),
  ADD KEY `Tb_Paciente_FK` (`IdEstadoCivil`),
  ADD KEY `Tb_Paciente_FK_1` (`IdSexo`),
  ADD KEY `Tb_Paciente_FK_2` (`IdStatus`),
  ADD KEY `Tb_Paciente_FK_3` (`IdPoliza`);

--
-- Indices de la tabla `tb_pago`
--
ALTER TABLE `tb_pago`
  ADD PRIMARY KEY (`IdPago`),
  ADD KEY `Tb_Pago_FK` (`IdMetodoPago`),
  ADD KEY `Tb_Pago_FK_1` (`IdCita`);

--
-- Indices de la tabla `tb_polizas`
--
ALTER TABLE `tb_polizas`
  ADD PRIMARY KEY (`IdPoliza`);

--
-- Indices de la tabla `tb_recepcionista`
--
ALTER TABLE `tb_recepcionista`
  ADD PRIMARY KEY (`IdRecepcionista`),
  ADD UNIQUE KEY `Tb_Recepcionista_UN` (`Usuario`),
  ADD UNIQUE KEY `UNIQUEEmail` (`Email`),
  ADD KEY `Tb_Recepcionista_FK` (`IdStatus`);

--
-- Indices de la tabla `tb_sexo`
--
ALTER TABLE `tb_sexo`
  ADD PRIMARY KEY (`IdSexo`);

--
-- Indices de la tabla `tb_status`
--
ALTER TABLE `tb_status`
  ADD PRIMARY KEY (`IdStatus`);

--
-- Indices de la tabla `tb_tratamiento`
--
ALTER TABLE `tb_tratamiento`
  ADD PRIMARY KEY (`IdTratamiento`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tb_admin`
--
ALTER TABLE `tb_admin`
  MODIFY `IdAdmin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tb_cita`
--
ALTER TABLE `tb_cita`
  MODIFY `IdCita` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tb_datofactura`
--
ALTER TABLE `tb_datofactura`
  MODIFY `IdFactura` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tb_doctor`
--
ALTER TABLE `tb_doctor`
  MODIFY `IdDoctor` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tb_doctorespecialidad`
--
ALTER TABLE `tb_doctorespecialidad`
  MODIFY `IdDoctorEspecialidad` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tb_especialidad`
--
ALTER TABLE `tb_especialidad`
  MODIFY `IdEspecialidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `tb_estadocivil`
--
ALTER TABLE `tb_estadocivil`
  MODIFY `IdEstadoCivil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tb_metodopago`
--
ALTER TABLE `tb_metodopago`
  MODIFY `IdMetodo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tb_paciente`
--
ALTER TABLE `tb_paciente`
  MODIFY `IdPaciente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tb_pago`
--
ALTER TABLE `tb_pago`
  MODIFY `IdPago` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tb_polizas`
--
ALTER TABLE `tb_polizas`
  MODIFY `IdPoliza` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tb_recepcionista`
--
ALTER TABLE `tb_recepcionista`
  MODIFY `IdRecepcionista` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tb_sexo`
--
ALTER TABLE `tb_sexo`
  MODIFY `IdSexo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tb_status`
--
ALTER TABLE `tb_status`
  MODIFY `IdStatus` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tb_tratamiento`
--
ALTER TABLE `tb_tratamiento`
  MODIFY `IdTratamiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tb_cita`
--
ALTER TABLE `tb_cita`
  ADD CONSTRAINT `Tb_Cita_FK` FOREIGN KEY (`IdPaciente`) REFERENCES `tb_paciente` (`IdPaciente`),
  ADD CONSTRAINT `Tb_Cita_FK_1` FOREIGN KEY (`IdDoctor`) REFERENCES `tb_doctor` (`IdDoctor`),
  ADD CONSTRAINT `Tb_Cita_FK_2` FOREIGN KEY (`IdStatus`) REFERENCES `tb_status` (`IdStatus`),
  ADD CONSTRAINT `Tb_Cita_FK_3` FOREIGN KEY (`IdTratamiento`) REFERENCES `tb_tratamiento` (`IdTratamiento`);

--
-- Filtros para la tabla `tb_datofactura`
--
ALTER TABLE `tb_datofactura`
  ADD CONSTRAINT `Tb_DatoFactura_FK_1` FOREIGN KEY (`IdPago`) REFERENCES `tb_pago` (`IdPago`);

--
-- Filtros para la tabla `tb_doctor`
--
ALTER TABLE `tb_doctor`
  ADD CONSTRAINT `Tb_Doctor_FK` FOREIGN KEY (`IdStatus`) REFERENCES `tb_status` (`IdStatus`);

--
-- Filtros para la tabla `tb_doctorespecialidad`
--
ALTER TABLE `tb_doctorespecialidad`
  ADD CONSTRAINT `Tb_DoctorEspecialidad_FK` FOREIGN KEY (`IdDoctor`) REFERENCES `tb_doctor` (`IdDoctor`),
  ADD CONSTRAINT `Tb_DoctorEspecialidad_FK_1` FOREIGN KEY (`IdEspecialidad`) REFERENCES `tb_especialidad` (`IdEspecialidad`);

--
-- Filtros para la tabla `tb_paciente`
--
ALTER TABLE `tb_paciente`
  ADD CONSTRAINT `Tb_Paciente_FK` FOREIGN KEY (`IdEstadoCivil`) REFERENCES `tb_estadocivil` (`IdEstadoCivil`),
  ADD CONSTRAINT `Tb_Paciente_FK_1` FOREIGN KEY (`IdSexo`) REFERENCES `tb_sexo` (`IdSexo`),
  ADD CONSTRAINT `Tb_Paciente_FK_2` FOREIGN KEY (`IdStatus`) REFERENCES `tb_status` (`IdStatus`),
  ADD CONSTRAINT `Tb_Paciente_FK_3` FOREIGN KEY (`IdPoliza`) REFERENCES `tb_polizas` (`IdPoliza`);

--
-- Filtros para la tabla `tb_pago`
--
ALTER TABLE `tb_pago`
  ADD CONSTRAINT `Tb_Pago_FK` FOREIGN KEY (`IdMetodoPago`) REFERENCES `tb_metodopago` (`IdMetodo`),
  ADD CONSTRAINT `Tb_Pago_FK_1` FOREIGN KEY (`IdCita`) REFERENCES `tb_cita` (`IdCita`);

--
-- Filtros para la tabla `tb_recepcionista`
--
ALTER TABLE `tb_recepcionista`
  ADD CONSTRAINT `Tb_Recepcionista_FK` FOREIGN KEY (`IdStatus`) REFERENCES `tb_status` (`IdStatus`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
