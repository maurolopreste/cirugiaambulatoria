-- Línea base: estructura de la base al 2026-11-18 (idéntica en producción y desarrollo). Sólo estructura, sin datos.
-- La ejecuta 001_baseline.php únicamente si la base está vacía.

CREATE TABLE `agenda` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `paciente_id` int(11) DEFAULT NULL,
  `profesional_id` int(11) DEFAULT NULL,
  `cita_id` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `valor_consulta` int(11) DEFAULT NULL,
  `file` varchar(100) DEFAULT NULL,
  `comentarios` text,
  `observacion_profesional` text,
  `link_video` varchar(100) DEFAULT NULL,
  `estado_cita` varchar(100) DEFAULT '1',
  `payment_method` varchar(11) DEFAULT '',
  `payment_status` varchar(10) DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `art` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `active` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `dias_habilitados` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `profesional_id` int(11) DEFAULT NULL,
  `daysOfWeekDisabled` varchar(11) DEFAULT NULL,
  `hora_desde` varchar(10) DEFAULT NULL,
  `hora_hasta` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `estado_cita` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `evolutivo` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `paciente_id` int(11) DEFAULT NULL,
  `profesional_id` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `informe` text,
  `file` varchar(100) DEFAULT NULL,
  `proxima_visita` datetime DEFAULT NULL,
  `alta` int(11) DEFAULT NULL,
  `facturado` int(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `fecha_comprometida` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `profesional_id` int(11) DEFAULT NULL,
  `fecha_desde` date DEFAULT NULL,
  `fecha_hasta` date DEFAULT NULL,
  `motivo` varchar(100) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `formalta` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `art_id` int(11) DEFAULT NULL,
  `nro_siniestro` varchar(100) DEFAULT '',
  `calle` varchar(100) DEFAULT NULL,
  `calle_nro` varchar(10) DEFAULT NULL,
  `piso` varchar(10) DEFAULT NULL,
  `depto` varchar(10) DEFAULT NULL,
  `localidad` varchar(100) DEFAULT NULL,
  `provincia` int(11) DEFAULT NULL,
  `cp` varchar(10) DEFAULT NULL,
  `tel_fijo` varchar(10) DEFAULT NULL,
  `tel_movil` varchar(10) DEFAULT NULL,
  `empleador_nombre` varchar(100) DEFAULT NULL,
  `empleador_cuit` varchar(20) DEFAULT NULL,
  `prestador_nombre` varchar(100) DEFAULT NULL,
  `prestador_cuit` varchar(20) DEFAULT NULL,
  `prestador_calle` varchar(100) DEFAULT NULL,
  `prestador_calle_nro` varchar(10) DEFAULT NULL,
  `prestador_piso` varchar(10) DEFAULT NULL,
  `prestador_depto` varchar(10) DEFAULT NULL,
  `prestador_localidad` varchar(100) DEFAULT NULL,
  `prestador_provincia` int(11) DEFAULT NULL,
  `prestador_cp` varchar(10) DEFAULT NULL,
  `prestador_tel` varchar(10) DEFAULT NULL,
  `prestador_fax` varchar(20) DEFAULT NULL,
  `prestador_email` varchar(100) DEFAULT NULL,
  `motivo_consulta` text,
  `fecha_accidente` datetime DEFAULT NULL,
  `fecha_inasistencia` datetime DEFAULT NULL,
  `fecha_primera_atencion` datetime DEFAULT NULL,
  `descripcion_motivo_consulta` text,
  `diagnostico` text,
  `indicaciones` text,
  `tratamiento_pendiente` varchar(10) DEFAULT NULL,
  `tratamiento_extra` varchar(100) DEFAULT NULL,
  `recalificacion_profesional` varchar(10) DEFAULT NULL,
  `fecha_probable_alta` datetime DEFAULT NULL,
  `fecha_proxima_revision` datetime DEFAULT NULL,
  `fecha_retorno_trabajo` datetime DEFAULT NULL,
  `fecha_fin_tratamiento` datetime DEFAULT NULL,
  `motivo_cese` varchar(250) DEFAULT NULL,
  `afeccion_inculpable` varchar(10) DEFAULT NULL,
  `secuelas_incapacitantes` varchar(10) DEFAULT NULL,
  `prestaciones_mantenimiento` varchar(10) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `formingreso` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `art_id` int(11) DEFAULT NULL,
  `nro_siniestro` varchar(100) DEFAULT '',
  `contingencia` varchar(20) DEFAULT NULL,
  `calle` varchar(100) DEFAULT NULL,
  `calle_nro` varchar(10) DEFAULT NULL,
  `piso` varchar(10) DEFAULT NULL,
  `depto` varchar(10) DEFAULT NULL,
  `localidad` varchar(100) DEFAULT NULL,
  `provincia` int(11) DEFAULT NULL,
  `cp` varchar(10) DEFAULT NULL,
  `tel_fijo` varchar(10) DEFAULT NULL,
  `tel_movil` varchar(10) DEFAULT NULL,
  `tel_alt` varchar(10) DEFAULT NULL,
  `empleador_nombre` varchar(100) DEFAULT NULL,
  `empleador_cuit` varchar(20) DEFAULT NULL,
  `empleador_domicilio` varchar(100) DEFAULT NULL,
  `empleador_tel` varchar(10) DEFAULT NULL,
  `empleador_contacto` varchar(100) DEFAULT NULL,
  `empleador_celular` varchar(10) DEFAULT NULL,
  `empleador_email` varchar(100) DEFAULT NULL,
  `prestador_nombre` varchar(100) DEFAULT NULL,
  `prestador_cuit` varchar(20) DEFAULT NULL,
  `prestador_calle` varchar(100) DEFAULT NULL,
  `prestador_calle_nro` varchar(10) DEFAULT NULL,
  `prestador_piso` varchar(10) DEFAULT NULL,
  `prestador_depto` varchar(10) DEFAULT NULL,
  `prestador_localidad` varchar(100) DEFAULT NULL,
  `prestador_provincia` int(11) DEFAULT NULL,
  `prestador_cp` varchar(10) DEFAULT NULL,
  `prestador_tel` varchar(10) DEFAULT NULL,
  `prestador_fax` varchar(20) DEFAULT NULL,
  `prestador_email` varchar(100) DEFAULT NULL,
  `motivo_consulta` text,
  `fecha_accidente` datetime DEFAULT NULL,
  `fecha_inasistencia` datetime DEFAULT NULL,
  `fecha_primera_atencion` datetime DEFAULT NULL,
  `descripcion_motivo_consulta` text,
  `diagnostico` text,
  `indicaciones` text,
  `baja_laboral` varchar(10) DEFAULT NULL,
  `fecha_probable_alta` datetime DEFAULT NULL,
  `fecha_proxima_revision` datetime DEFAULT NULL,
  `fecha_retorno` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `formreingreso` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `art_id` int(11) DEFAULT NULL,
  `nro_siniestro` varchar(100) DEFAULT '',
  `contingencia` varchar(20) DEFAULT NULL,
  `calle` varchar(100) DEFAULT NULL,
  `calle_nro` varchar(10) DEFAULT NULL,
  `piso` varchar(10) DEFAULT NULL,
  `depto` varchar(10) DEFAULT NULL,
  `localidad` varchar(100) DEFAULT NULL,
  `provincia` int(11) DEFAULT NULL,
  `cp` varchar(10) DEFAULT NULL,
  `tel_fijo` varchar(10) DEFAULT NULL,
  `tel_movil` varchar(10) DEFAULT NULL,
  `tel_alt` varchar(10) DEFAULT NULL,
  `empleador_nombre` varchar(100) DEFAULT NULL,
  `empleador_cuit` varchar(20) DEFAULT NULL,
  `empleador_domicilio` varchar(100) DEFAULT NULL,
  `empleador_tel` varchar(10) DEFAULT NULL,
  `empleador_contacto` varchar(100) DEFAULT NULL,
  `empleador_celular` varchar(10) DEFAULT NULL,
  `empleador_email` varchar(100) DEFAULT NULL,
  `prestador_nombre` varchar(100) DEFAULT NULL,
  `prestador_cuit` varchar(20) DEFAULT NULL,
  `prestador_calle` varchar(100) DEFAULT NULL,
  `prestador_calle_nro` varchar(10) DEFAULT NULL,
  `prestador_piso` varchar(10) DEFAULT NULL,
  `prestador_depto` varchar(10) DEFAULT NULL,
  `prestador_localidad` varchar(100) DEFAULT NULL,
  `prestador_provincia` int(11) DEFAULT NULL,
  `prestador_cp` varchar(10) DEFAULT NULL,
  `prestador_tel` varchar(10) DEFAULT NULL,
  `prestador_fax` varchar(20) DEFAULT NULL,
  `prestador_email` varchar(100) DEFAULT NULL,
  `motivo_consulta` text,
  `fecha_ocurrencia` datetime DEFAULT NULL,
  `fecha_alta_medica` datetime DEFAULT NULL,
  `fecha_solicitud_reingreso` datetime DEFAULT NULL,
  `descripcion_motivo_consulta` text,
  `diagnostico` text,
  `indicaciones` text,
  `aceptacion_reingreso` varchar(10) DEFAULT NULL,
  `aceptacion_reingreso_otro` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `group` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `historiaclinica` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `paciente_id` int(11) DEFAULT NULL,
  `profesional_id` int(11) DEFAULT NULL,
  `fecha_accidente` datetime DEFAULT NULL,
  `mecanismo` varchar(100) DEFAULT NULL,
  `antecedentes` varchar(100) DEFAULT NULL,
  `examen_fisico` varchar(100) DEFAULT NULL,
  `antecedentes_patologicos` varchar(100) DEFAULT NULL,
  `estudios_solicitados` varchar(100) DEFAULT NULL,
  `tratamiento` varchar(100) DEFAULT NULL,
  `file` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `medicamento` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `metodo_pago` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `obra_social` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `active` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `prescripcion` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `agenda_id` int(11) DEFAULT NULL,
  `paciente_id` int(11) DEFAULT NULL,
  `medicamento` varchar(100) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `profesional_id` int(11) DEFAULT NULL,
  `dosis` varchar(100) DEFAULT NULL,
  `frecuencia` varchar(100) DEFAULT NULL,
  `duracion` varchar(100) DEFAULT NULL,
  `comentarios` text,
  `indicaciones` text,
  `file` varchar(250) DEFAULT NULL,
  `file2` varchar(250) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `profesional_valor` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `profesional_id` int(11) DEFAULT NULL,
  `valor_consulta` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `provincia` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL DEFAULT '',
  `active` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(11) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `obra_social` int(11) DEFAULT NULL,
  `nro_afiliado` varchar(20) DEFAULT NULL,
  `sexo` varchar(1) DEFAULT NULL,
  `fecha_nac` date DEFAULT NULL,
  `especialidad` varchar(100) DEFAULT NULL,
  `telefono` varchar(10) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL,
  `dni` varchar(20) DEFAULT NULL,
  `rp_token` varchar(100) DEFAULT NULL,
  `active` int(1) DEFAULT NULL,
  `tyc` int(1) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `valor_consulta` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
