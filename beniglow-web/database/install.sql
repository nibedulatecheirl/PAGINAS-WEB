CREATE DATABASE IF NOT EXISTS `beniglow_store`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE `beniglow_store`;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `pedido_web_detalles`;
DROP TABLE IF EXISTS `pedidos_web`;
DROP TABLE IF EXISTS `actividad_log`;
DROP TABLE IF EXISTS `backups`;
DROP TABLE IF EXISTS `puntos_fidelidad`;
DROP TABLE IF EXISTS `promociones`;
DROP TABLE IF EXISTS `movimientos_inventario`;
DROP TABLE IF EXISTS `compra_detalles`;
DROP TABLE IF EXISTS `compras`;
DROP TABLE IF EXISTS `venta_detalles`;
DROP TABLE IF EXISTS `ventas`;
DROP TABLE IF EXISTS `movimientos_caja`;
DROP TABLE IF EXISTS `turnos_caja`;
DROP TABLE IF EXISTS `cajas`;
DROP TABLE IF EXISTS `clientes`;
DROP TABLE IF EXISTS `productos`;
DROP TABLE IF EXISTS `proveedores`;
DROP TABLE IF EXISTS `categorias`;
DROP TABLE IF EXISTS `configuraciones`;
DROP TABLE IF EXISTS `empresas`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `roles`;
DROP TABLE IF EXISTS `migrations`;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE `migrations` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` VARCHAR(255) NOT NULL,
  `batch` INT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `roles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(80) NOT NULL,
  `descripcion` VARCHAR(255) NULL,
  `permisos` JSON NULL,
  `activo` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_nombre_unique` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `username` VARCHAR(50) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `email_verified_at` TIMESTAMP NULL,
  `password` VARCHAR(255) NOT NULL,
  `role_id` BIGINT UNSIGNED NULL,
  `telefono` VARCHAR(30) NULL,
  `avatar` VARCHAR(255) NULL,
  `activo` TINYINT(1) NOT NULL DEFAULT 1,
  `ultimo_login` TIMESTAMP NULL,
  `remember_token` VARCHAR(100) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_id_foreign` (`role_id`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `password_reset_tokens` (
  `email` VARCHAR(255) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sessions` (
  `id` VARCHAR(255) NOT NULL,
  `user_id` BIGINT UNSIGNED NULL,
  `ip_address` VARCHAR(45) NULL,
  `user_agent` TEXT NULL,
  `payload` LONGTEXT NOT NULL,
  `last_activity` INT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `empresas` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `razon_social` VARCHAR(255) NOT NULL,
  `nombre_comercial` VARCHAR(255) NULL,
  `ruc_nit` VARCHAR(30) NULL,
  `direccion` VARCHAR(255) NULL,
  `ciudad` VARCHAR(100) NULL,
  `telefono` VARCHAR(30) NULL,
  `email` VARCHAR(255) NULL,
  `sitio_web` VARCHAR(255) NULL,
  `logo` VARCHAR(255) NULL,
  `moneda` VARCHAR(10) NOT NULL DEFAULT 'S/',
  `codigo_moneda` VARCHAR(5) NOT NULL DEFAULT 'PEN',
  `impuesto` DECIMAL(5,2) NOT NULL DEFAULT 18.00,
  `impuesto_incluido` TINYINT(1) NOT NULL DEFAULT 1,
  `mensaje_ticket` VARCHAR(255) NULL,
  `terminos_condiciones` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `configuraciones` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `clave` VARCHAR(100) NOT NULL,
  `valor` TEXT NULL,
  `tipo` VARCHAR(30) NOT NULL DEFAULT 'string',
  `grupo` VARCHAR(50) NOT NULL DEFAULT 'general',
  `descripcion` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `configuraciones_clave_unique` (`clave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `categorias` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `descripcion` VARCHAR(255) NULL,
  `color` VARCHAR(20) NOT NULL DEFAULT '#3B82F6',
  `icono` VARCHAR(50) NOT NULL DEFAULT 'spa',
  `activo` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `proveedores` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `codigo` VARCHAR(30) NOT NULL,
  `razon_social` VARCHAR(255) NOT NULL,
  `nombre_comercial` VARCHAR(255) NULL,
  `ruc_nit` VARCHAR(30) NULL,
  `contacto` VARCHAR(255) NULL,
  `telefono` VARCHAR(30) NULL,
  `email` VARCHAR(255) NULL,
  `direccion` VARCHAR(255) NULL,
  `ciudad` VARCHAR(100) NULL,
  `observaciones` TEXT NULL,
  `activo` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `proveedores_codigo_unique` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `productos` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `codigo` VARCHAR(50) NOT NULL,
  `codigo_barras` VARCHAR(50) NULL,
  `nombre` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NULL,
  `descripcion` TEXT NULL,
  `categoria_id` BIGINT UNSIGNED NULL,
  `proveedor_id` BIGINT UNSIGNED NULL,
  `marca` VARCHAR(120) NULL,
  `linea` VARCHAR(120) NULL,
  `tono` VARCHAR(80) NULL,
  `presentacion` VARCHAR(120) NULL,
  `tipo_piel` VARCHAR(120) NULL,
  `acabado` VARCHAR(120) NULL,
  `volumen` VARCHAR(80) NULL,
  `ingredientes_clave` TEXT NULL,
  `unidad_medida` VARCHAR(20) NOT NULL DEFAULT 'UND',
  `precio_compra` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `precio_venta` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `precio_oferta` DECIMAL(12,2) NULL,
  `oferta_inicio` DATE NULL,
  `oferta_fin` DATE NULL,
  `precio_mayoreo` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `cantidad_mayoreo` INT NOT NULL DEFAULT 0,
  `stock` DECIMAL(12,3) NOT NULL DEFAULT 0.000,
  `stock_minimo` DECIMAL(12,3) NOT NULL DEFAULT 0.000,
  `stock_maximo` DECIMAL(12,3) NOT NULL DEFAULT 0.000,
  `controla_stock` TINYINT(1) NOT NULL DEFAULT 1,
  `aplica_impuesto` TINYINT(1) NOT NULL DEFAULT 1,
  `imagen` VARCHAR(255) NULL,
  `imagen_alt` VARCHAR(255) NULL,
  `meta_title` VARCHAR(255) NULL,
  `meta_description` VARCHAR(320) NULL,
  `fecha_vencimiento` DATE NULL,
  `lote` VARCHAR(50) NULL,
  `ubicacion` VARCHAR(100) NULL,
  `activo` TINYINT(1) NOT NULL DEFAULT 1,
  `destacado` TINYINT(1) NOT NULL DEFAULT 0,
  `visible_web` TINYINT(1) NOT NULL DEFAULT 1,
  `destacado_web` TINYINT(1) NOT NULL DEFAULT 0,
  `orden_web` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `productos_codigo_unique` (`codigo`),
  UNIQUE KEY `productos_slug_unique` (`slug`),
  KEY `productos_codigo_barras_index` (`codigo_barras`),
  KEY `productos_categoria_id_foreign` (`categoria_id`),
  KEY `productos_proveedor_id_foreign` (`proveedor_id`),
  CONSTRAINT `productos_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL,
  CONSTRAINT `productos_proveedor_id_foreign` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `clientes` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `codigo` VARCHAR(30) NOT NULL,
  `tipo_documento` VARCHAR(20) NOT NULL DEFAULT 'DNI',
  `documento` VARCHAR(30) NULL,
  `nombres` VARCHAR(255) NOT NULL,
  `apellidos` VARCHAR(255) NULL,
  `razon_social` VARCHAR(255) NULL,
  `telefono` VARCHAR(30) NULL,
  `email` VARCHAR(255) NULL,
  `direccion` VARCHAR(255) NULL,
  `ciudad` VARCHAR(100) NULL,
  `fecha_nacimiento` DATE NULL,
  `genero` VARCHAR(20) NULL,
  `puntos_fidelidad` INT NOT NULL DEFAULT 0,
  `credito_limite` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `credito_usado` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `observaciones` TEXT NULL,
  `activo` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clientes_codigo_unique` (`codigo`),
  KEY `clientes_documento_index` (`documento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cajas` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `descripcion` VARCHAR(255) NULL,
  `activo` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `turnos_caja` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `caja_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `fecha_apertura` DATETIME NOT NULL,
  `fecha_cierre` DATETIME NULL,
  `monto_apertura` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `monto_cierre` DECIMAL(12,2) NULL,
  `monto_calculado` DECIMAL(12,2) NULL,
  `diferencia` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `total_ventas` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `total_efectivo` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `total_tarjeta` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `total_otros` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `cantidad_ventas` INT NOT NULL DEFAULT 0,
  `observaciones` TEXT NULL,
  `estado` ENUM('abierto','cerrado') NOT NULL DEFAULT 'abierto',
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `turnos_caja_caja_id_foreign` (`caja_id`),
  KEY `turnos_caja_user_id_foreign` (`user_id`),
  CONSTRAINT `turnos_caja_caja_id_foreign` FOREIGN KEY (`caja_id`) REFERENCES `cajas` (`id`),
  CONSTRAINT `turnos_caja_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `movimientos_caja` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `turno_caja_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `tipo` ENUM('ingreso','egreso') NOT NULL,
  `concepto` VARCHAR(255) NOT NULL,
  `monto` DECIMAL(12,2) NOT NULL,
  `observaciones` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `movimientos_caja_turno_caja_id_foreign` (`turno_caja_id`),
  KEY `movimientos_caja_user_id_foreign` (`user_id`),
  CONSTRAINT `movimientos_caja_turno_caja_id_foreign` FOREIGN KEY (`turno_caja_id`) REFERENCES `turnos_caja` (`id`),
  CONSTRAINT `movimientos_caja_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `ventas` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `numero_ticket` VARCHAR(30) NOT NULL,
  `tipo_comprobante` VARCHAR(30) NOT NULL DEFAULT 'TICKET',
  `serie` VARCHAR(10) NOT NULL DEFAULT 'T001',
  `canal` VARCHAR(30) NOT NULL DEFAULT 'pos',
  `referencia_externa` VARCHAR(255) NULL,
  `fecha_venta` DATETIME NOT NULL,
  `cliente_id` BIGINT UNSIGNED NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `turno_caja_id` BIGINT UNSIGNED NULL,
  `subtotal` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `descuento` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `impuesto` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `total` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `monto_recibido` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `cambio` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `forma_pago` VARCHAR(30) NOT NULL DEFAULT 'efectivo',
  `detalle_pago` JSON NULL,
  `estado_pago` VARCHAR(30) NOT NULL DEFAULT 'pagado',
  `estado_envio` VARCHAR(30) NULL,
  `direccion_envio` JSON NULL,
  `estado` ENUM('completada','anulada','pendiente') NOT NULL DEFAULT 'completada',
  `observaciones` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ventas_numero_ticket_unique` (`numero_ticket`),
  KEY `ventas_cliente_id_foreign` (`cliente_id`),
  KEY `ventas_user_id_foreign` (`user_id`),
  KEY `ventas_turno_caja_id_foreign` (`turno_caja_id`),
  CONSTRAINT `ventas_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE SET NULL,
  CONSTRAINT `ventas_turno_caja_id_foreign` FOREIGN KEY (`turno_caja_id`) REFERENCES `turnos_caja` (`id`),
  CONSTRAINT `ventas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `venta_detalles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `venta_id` BIGINT UNSIGNED NOT NULL,
  `producto_id` BIGINT UNSIGNED NOT NULL,
  `codigo` VARCHAR(50) NOT NULL,
  `descripcion` VARCHAR(255) NOT NULL,
  `cantidad` DECIMAL(12,3) NOT NULL,
  `precio_unitario` DECIMAL(12,2) NOT NULL,
  `descuento` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `impuesto` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `subtotal` DECIMAL(12,2) NOT NULL,
  `total` DECIMAL(12,2) NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `venta_detalles_venta_id_foreign` (`venta_id`),
  KEY `venta_detalles_producto_id_foreign` (`producto_id`),
  CONSTRAINT `venta_detalles_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  CONSTRAINT `venta_detalles_venta_id_foreign` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `compras` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `numero` VARCHAR(30) NOT NULL,
  `numero_factura` VARCHAR(50) NULL,
  `fecha_compra` DATETIME NOT NULL,
  `fecha_vencimiento` DATE NULL,
  `proveedor_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `subtotal` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `descuento` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `impuesto` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `total` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `forma_pago` VARCHAR(30) NOT NULL DEFAULT 'efectivo',
  `estado` ENUM('recibida','pendiente','anulada') NOT NULL DEFAULT 'recibida',
  `observaciones` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `compras_numero_unique` (`numero`),
  KEY `compras_proveedor_id_foreign` (`proveedor_id`),
  KEY `compras_user_id_foreign` (`user_id`),
  CONSTRAINT `compras_proveedor_id_foreign` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`),
  CONSTRAINT `compras_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `compra_detalles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `compra_id` BIGINT UNSIGNED NOT NULL,
  `producto_id` BIGINT UNSIGNED NOT NULL,
  `codigo` VARCHAR(50) NOT NULL,
  `descripcion` VARCHAR(255) NOT NULL,
  `cantidad` DECIMAL(12,3) NOT NULL,
  `precio_unitario` DECIMAL(12,2) NOT NULL,
  `descuento` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `impuesto` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `subtotal` DECIMAL(12,2) NOT NULL,
  `total` DECIMAL(12,2) NOT NULL,
  `fecha_vencimiento` DATE NULL,
  `lote` VARCHAR(50) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `compra_detalles_compra_id_foreign` (`compra_id`),
  KEY `compra_detalles_producto_id_foreign` (`producto_id`),
  CONSTRAINT `compra_detalles_compra_id_foreign` FOREIGN KEY (`compra_id`) REFERENCES `compras` (`id`) ON DELETE CASCADE,
  CONSTRAINT `compra_detalles_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `movimientos_inventario` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `producto_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `tipo` ENUM('entrada','salida','ajuste','merma','transferencia') NOT NULL,
  `motivo` VARCHAR(100) NOT NULL,
  `cantidad` DECIMAL(12,3) NOT NULL,
  `stock_anterior` DECIMAL(12,3) NOT NULL,
  `stock_nuevo` DECIMAL(12,3) NOT NULL,
  `referencia_tipo` VARCHAR(50) NULL,
  `referencia_id` BIGINT UNSIGNED NULL,
  `observaciones` TEXT NULL,
  `fecha` DATETIME NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `movimientos_inventario_producto_id_foreign` (`producto_id`),
  KEY `movimientos_inventario_user_id_foreign` (`user_id`),
  CONSTRAINT `movimientos_inventario_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  CONSTRAINT `movimientos_inventario_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `promociones` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NOT NULL,
  `descripcion` TEXT NULL,
  `tipo` ENUM('descuento_porcentaje','descuento_fijo','2x1','3x2','precio_especial') NOT NULL,
  `valor` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `producto_id` BIGINT UNSIGNED NULL,
  `categoria_id` BIGINT UNSIGNED NULL,
  `fecha_inicio` DATE NOT NULL,
  `fecha_fin` DATE NOT NULL,
  `cantidad_minima` INT NOT NULL DEFAULT 1,
  `activo` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `promociones_producto_id_foreign` (`producto_id`),
  KEY `promociones_categoria_id_foreign` (`categoria_id`),
  CONSTRAINT `promociones_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL,
  CONSTRAINT `promociones_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `puntos_fidelidad` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `cliente_id` BIGINT UNSIGNED NOT NULL,
  `venta_id` BIGINT UNSIGNED NULL,
  `tipo` ENUM('ganado','canjeado','expirado','ajuste') NOT NULL,
  `puntos` INT NOT NULL,
  `saldo_anterior` INT NOT NULL,
  `saldo_nuevo` INT NOT NULL,
  `descripcion` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `puntos_fidelidad_cliente_id_foreign` (`cliente_id`),
  KEY `puntos_fidelidad_venta_id_foreign` (`venta_id`),
  CONSTRAINT `puntos_fidelidad_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  CONSTRAINT `puntos_fidelidad_venta_id_foreign` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `backups` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NOT NULL,
  `archivo` VARCHAR(255) NOT NULL,
  `tamano` BIGINT NOT NULL DEFAULT 0,
  `tipo` ENUM('manual','automatico') NOT NULL DEFAULT 'manual',
  `user_id` BIGINT UNSIGNED NULL,
  `observaciones` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `backups_user_id_foreign` (`user_id`),
  CONSTRAINT `backups_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `actividad_log` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NULL,
  `accion` VARCHAR(50) NOT NULL,
  `modulo` VARCHAR(50) NOT NULL,
  `descripcion` VARCHAR(255) NOT NULL,
  `ip` VARCHAR(45) NULL,
  `datos` JSON NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `actividad_log_user_id_foreign` (`user_id`),
  CONSTRAINT `actividad_log_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `pedidos_web` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `codigo` VARCHAR(40) NOT NULL,
  `cliente_id` BIGINT UNSIGNED NULL,
  `venta_id` BIGINT UNSIGNED NULL,
  `canal` VARCHAR(30) NOT NULL DEFAULT 'web',
  `origen` VARCHAR(255) NULL,
  `estado` ENUM('pendiente_pago','pagado','preparando','enviado','entregado','cancelado','fallido') NOT NULL DEFAULT 'pendiente_pago',
  `estado_pago` ENUM('pendiente','pagado','rechazado','reembolsado') NOT NULL DEFAULT 'pendiente',
  `estado_stock` ENUM('sin_descontar','descontado','restaurado') NOT NULL DEFAULT 'sin_descontar',
  `subtotal` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `descuento` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `impuesto` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `envio` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `total` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `moneda` VARCHAR(10) NOT NULL DEFAULT 'PEN',
  `metodo_pago` VARCHAR(50) NULL,
  `referencia_pago` VARCHAR(255) NULL,
  `payment_payload` JSON NULL,
  `cliente_nombre` VARCHAR(255) NOT NULL,
  `cliente_email` VARCHAR(255) NULL,
  `cliente_telefono` VARCHAR(30) NULL,
  `cliente_documento` VARCHAR(30) NULL,
  `direccion_envio` JSON NULL,
  `notas` TEXT NULL,
  `confirmed_at` TIMESTAMP NULL,
  `cancelled_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pedidos_web_codigo_unique` (`codigo`),
  KEY `pedidos_web_cliente_id_foreign` (`cliente_id`),
  KEY `pedidos_web_venta_id_foreign` (`venta_id`),
  KEY `pedidos_web_estado_estado_pago_index` (`estado`, `estado_pago`),
  KEY `pedidos_web_cliente_email_index` (`cliente_email`),
  KEY `pedidos_web_created_at_index` (`created_at`),
  CONSTRAINT `pedidos_web_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pedidos_web_venta_id_foreign` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `pedido_web_detalles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `pedido_web_id` BIGINT UNSIGNED NOT NULL,
  `producto_id` BIGINT UNSIGNED NOT NULL,
  `codigo` VARCHAR(50) NOT NULL,
  `nombre` VARCHAR(255) NOT NULL,
  `cantidad` DECIMAL(12,3) NOT NULL,
  `precio_unitario` DECIMAL(12,2) NOT NULL,
  `descuento` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `impuesto` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `subtotal` DECIMAL(12,2) NOT NULL,
  `total` DECIMAL(12,2) NOT NULL,
  `meta` JSON NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `pedido_web_detalles_pedido_web_id_foreign` (`pedido_web_id`),
  KEY `pedido_web_detalles_producto_id_foreign` (`producto_id`),
  CONSTRAINT `pedido_web_detalles_pedido_web_id_foreign` FOREIGN KEY (`pedido_web_id`) REFERENCES `pedidos_web` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pedido_web_detalles_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2024_01_01_000000_create_roles_table', 1),
('2024_01_01_000001_create_users_table', 1),
('2024_01_01_000002_create_empresas_table', 1),
('2024_01_01_000003_create_configuraciones_table', 1),
('2024_01_01_000004_create_categorias_table', 1),
('2024_01_01_000005_create_proveedores_table', 1),
('2024_01_01_000006_create_productos_table', 1),
('2024_01_01_000007_create_clientes_table', 1),
('2024_01_01_000008_create_cajas_table', 1),
('2024_01_01_000009_create_ventas_table', 1),
('2024_01_01_000010_create_compras_table', 1),
('2024_01_01_000011_create_movimientos_inventario_table', 1),
('2024_01_01_000012_create_promociones_table', 1),
('2024_01_01_000013_create_backups_table', 1),
('2026_05_27_000001_add_boutique_web_fields_to_productos_table', 2),
('2026_05_27_000002_create_pedidos_web_tables', 2),
('2026_05_27_000003_add_ecommerce_fields_to_ventas_table', 2);

INSERT INTO `roles` (`id`, `nombre`, `descripcion`, `permisos`, `activo`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', 'Acceso completo al sistema', '["*"]', 1, NOW(), NOW()),
(2, 'Gerente', 'Gestion operativa de ventas, catalogo, compras, clientes y reportes', '["productos","ventas","compras","clientes","proveedores","caja","reportes","promociones","pedidos-web"]', 1, NOW(), NOW()),
(3, 'Cajero', 'Venta mostrador y caja opcional', '["ventas","caja","clientes"]', 1, NOW(), NOW()),
(4, 'Almacenero', 'Inventario, compras y proveedores', '["productos","compras","proveedores","reportes"]', 1, NOW(), NOW());

INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `role_id`, `telefono`, `activo`, `created_at`, `updated_at`) VALUES
(1, 'Administrador BeniGlow', 'admin', 'admin@beniglow.com', '$2y$12$JIj525q7QiIIUFETLkM3KO5jDBrPWqdWVffs18L7yf.Zeqpgz7MKy', 1, '999-000-000', 1, NOW(), NOW());

INSERT INTO `empresas` (`id`, `razon_social`, `nombre_comercial`, `ruc_nit`, `direccion`, `ciudad`, `telefono`, `email`, `sitio_web`, `logo`, `moneda`, `codigo_moneda`, `impuesto`, `impuesto_incluido`, `mensaje_ticket`, `terminos_condiciones`, `created_at`, `updated_at`) VALUES
(1, 'Beniglow E.I.R.L.', 'BeniGlow Store', '20600000001', 'Ciudad de Tacna, Perú', 'Tacna', '993 902 669', 'binitostore15@gmail.com', 'https://beniglow.com', 'beniglow-logo.png', 'S/', 'PEN', 18.00, 1, 'Gracias por comprar en BeniGlow.', 'Cambios sujetos a verificación del producto y comprobante de compra.', NOW(), NOW());

INSERT INTO `configuraciones` (`clave`, `valor`, `tipo`, `grupo`, `created_at`, `updated_at`) VALUES
('puntos_por_moneda', '0.1', 'string', 'fidelidad', NOW(), NOW()),
('dias_aviso_vencimiento', '30', 'integer', 'inventario', NOW(), NOW()),
('stock_minimo_default', '5', 'integer', 'inventario', NOW(), NOW()),
('serie_ticket', 'T001', 'string', 'facturacion', NOW(), NOW()),
('serie_boleta', 'B001', 'string', 'facturacion', NOW(), NOW()),
('serie_factura', 'F001', 'string', 'facturacion', NOW(), NOW()),
('ancho_ticket', '80', 'integer', 'ticket', NOW(), NOW()),
('imprimir_auto', '1', 'boolean', 'ticket', NOW(), NOW()),
('mostrar_logo_ticket', '1', 'boolean', 'ticket', NOW(), NOW());

INSERT INTO `cajas` (`id`, `nombre`, `descripcion`, `activo`, `created_at`, `updated_at`) VALUES
(1, 'Caja Mostrador', 'Caja opcional para ventas presenciales de BeniGlow', 1, NOW(), NOW());

INSERT INTO `categorias` (`id`, `nombre`, `descripcion`, `color`, `icono`, `activo`, `created_at`, `updated_at`) VALUES
(1, 'Limpieza facial', 'Aceites, espumas y productos para retirar maquillaje, protector solar e impurezas.', '#B98263', 'droplet', 1, NOW(), NOW()),
(2, 'Protección solar', 'Protectores solares ligeros para rutina diaria y acabado natural.', '#D8A155', 'sun', 1, NOW(), NOW()),
(3, 'Tratamientos faciales', 'Serums y boosters enfocados en textura, luminosidad y firmeza.', '#8A5A44', 'sparkles', 1, NOW(), NOW()),
(4, 'Contorno de ojos', 'Cremas y tratamientos para mirada cansada, ojeras y tono desigual.', '#C87F67', 'eye', 1, NOW(), NOW()),
(5, 'Sets y rutinas', 'Combinaciones listas para armar una rutina completa de cuidado facial.', '#7A6A5A', 'package', 1, NOW(), NOW());

INSERT INTO `proveedores` (`id`, `codigo`, `razon_social`, `nombre_comercial`, `ruc_nit`, `contacto`, `telefono`, `email`, `direccion`, `ciudad`, `observaciones`, `activo`, `created_at`, `updated_at`) VALUES
(1, 'YESSTYLE', 'YesStyle.com', 'YesStyle', NULL, 'Canal de compras online YesStyle Beauty', NULL, NULL, 'https://www.yesstyle.com/es/beauty-skin-care/list.html', 'Hong Kong', 'Proveedor internacional de productos de belleza, skincare y K-beauty. Enlace base del catalogo: https://www.yesstyle.com/es/beauty-skin-care/list.html. Contacto formal pendiente de confirmar en el formulario oficial de YesStyle.', 1, NOW(), NOW());

INSERT INTO `productos` (`id`, `codigo`, `codigo_barras`, `nombre`, `slug`, `descripcion`, `categoria_id`, `proveedor_id`, `marca`, `linea`, `tono`, `presentacion`, `tipo_piel`, `acabado`, `volumen`, `ingredientes_clave`, `unidad_medida`, `precio_compra`, `precio_venta`, `precio_oferta`, `oferta_inicio`, `oferta_fin`, `precio_mayoreo`, `cantidad_mayoreo`, `stock`, `stock_minimo`, `stock_maximo`, `controla_stock`, `aplica_impuesto`, `imagen`, `imagen_alt`, `meta_title`, `meta_description`, `fecha_vencimiento`, `lote`, `ubicacion`, `activo`, `destacado`, `visible_web`, `destacado_web`, `orden_web`, `created_at`, `updated_at`) VALUES
(1, 'BG-CEL-RET-001', NULL, 'Celimax The Vita-A Retinal Shot Tightening Booster', 'celimax-the-vita-a-retinal-shot-tightening-booster', 'Booster facial de noche enfocado en mejorar textura, apariencia de poros y firmeza. Ideal para rutinas que buscan un acabado de piel mas liso y luminoso.', 3, 1, 'Celimax', 'The Vita-A', NULL, 'Booster facial', 'Piel con textura, poros visibles o primeros signos de edad', 'Ligero', '15 ml', 'Retinal, complejo Vita-A y activos de soporte para firmeza y textura.', 'UND', 62.00, 99.90, 92.90, DATE_SUB(CURDATE(), INTERVAL 1 DAY), DATE_ADD(CURDATE(), INTERVAL 3 MONTH), 0.00, 0, 12.000, 3.000, 100.000, 1, 1, 'celimax-the-vita-a-retinal-shot-tightening-booster.webp', 'Celimax The Vita-A Retinal Shot Tightening Booster en BeniGlow Store', 'Celimax The Vita-A Retinal Shot Tightening Booster | BeniGlow Store', 'Booster facial de noche enfocado en mejorar textura, apariencia de poros y firmeza. Ideal para rutinas que buscan un acabado de piel mas liso y luminoso.', NULL, 'IMP-2026-01', 'Almacen principal', 1, 1, 1, 1, 1, NOW(), NOW()),
(2, 'BG-MAM-EYE-001', NULL, 'MARY & MAY Tranexamic Acid + Glutathione Eye Cream', 'mary-may-tranexamic-acid-glutathione-eye-cream', 'Crema para el contorno de ojos orientada a luminosidad, tono desigual y apariencia de cansancio. Textura suave para integrar en rutina de dia o noche.', 4, 1, 'MARY & MAY', 'Brightening Eye Care', NULL, 'Crema de contorno de ojos', 'Todo tipo de piel; mirada cansada o tono desigual', 'Cremoso ligero', '30 g', 'Acido tranexamico, glutation y niacinamida.', 'UND', 28.00, 49.90, 42.90, DATE_SUB(CURDATE(), INTERVAL 1 DAY), DATE_ADD(CURDATE(), INTERVAL 3 MONTH), 0.00, 0, 20.000, 4.000, 100.000, 1, 1, 'mary-may-tranexamic-acid-glutathione-eye-cream.webp', 'MARY & MAY Tranexamic Acid + Glutathione Eye Cream en BeniGlow Store', 'MARY & MAY Tranexamic Acid + Glutathione Eye Cream | BeniGlow Store', 'Crema para el contorno de ojos orientada a luminosidad, tono desigual y apariencia de cansancio. Textura suave para integrar en rutina de dia o noche.', NULL, 'IMP-2026-01', 'Almacen principal', 1, 0, 1, 0, 2, NOW(), NOW()),
(3, 'BG-MIX-SUN-001', NULL, 'Mixsoon Bean Sun Serum SPF 50+ PA++++', 'mixsoon-bean-sun-serum-spf-50-pa', 'Protector solar en formato serum con SPF 50+ PA++++. Pensado para uso diario, textura ligera y sensacion hidratante sin acabado pesado.', 2, 1, 'Mixsoon', 'Bean', NULL, 'Serum protector solar', 'Todo tipo de piel; rutina diaria', 'Ligero y natural', '50 ml', 'Extracto de frijol fermentado y filtros solares de amplio espectro.', 'UND', 34.00, 59.90, 52.90, DATE_SUB(CURDATE(), INTERVAL 1 DAY), DATE_ADD(CURDATE(), INTERVAL 3 MONTH), 0.00, 0, 15.000, 4.000, 100.000, 1, 1, 'mixsoon-bean-sun-serum-spf-50-pa.webp', 'Mixsoon Bean Sun Serum SPF 50+ PA++++ en BeniGlow Store', 'Mixsoon Bean Sun Serum SPF 50+ PA++++ | BeniGlow Store', 'Protector solar en formato serum con SPF 50+ PA++++. Pensado para uso diario, textura ligera y sensacion hidratante sin acabado pesado.', NULL, 'IMP-2026-01', 'Almacen principal', 1, 1, 1, 1, 3, NOW(), NOW()),
(4, 'BG-SK4-DUO-001', NULL, 'SKIN1004 Madagascar Centella Double Cleansing Duo Set', 'skin1004-madagascar-centella-double-cleansing-duo-set', 'Set para doble limpieza facial con aceite limpiador y espuma. Ayuda a retirar maquillaje, protector solar e impurezas sin dejar sensacion tirante.', 5, 1, 'SKIN1004', 'Madagascar Centella', NULL, 'Set de doble limpieza', 'Todo tipo de piel; piel sensible', 'Rutina de limpieza suave', 'Aceite 200 ml + espuma 125 ml', 'Centella asiatica de Madagascar y agentes limpiadores suaves.', 'UND', 65.00, 109.90, 99.90, DATE_SUB(CURDATE(), INTERVAL 1 DAY), DATE_ADD(CURDATE(), INTERVAL 3 MONTH), 0.00, 0, 8.000, 2.000, 100.000, 1, 1, 'skin1004-madagascar-centella-double-cleansing-duo-set.webp', 'SKIN1004 Madagascar Centella Double Cleansing Duo Set en BeniGlow Store', 'SKIN1004 Madagascar Centella Double Cleansing Duo Set | BeniGlow Store', 'Set para doble limpieza facial con aceite limpiador y espuma. Ayuda a retirar maquillaje, protector solar e impurezas sin dejar sensacion tirante.', NULL, 'IMP-2026-01', 'Almacen principal', 1, 1, 1, 1, 4, NOW(), NOW()),
(5, 'BG-SK4-OIL-001', NULL, 'SKIN1004 Madagascar Centella Light Cleansing Oil', 'skin1004-madagascar-centella-light-cleansing-oil', 'Aceite limpiador ligero para retirar protector solar, maquillaje e impurezas. Formula pensada para una primera limpieza comoda y suave.', 1, 1, 'SKIN1004', 'Madagascar Centella', NULL, 'Aceite limpiador', 'Todo tipo de piel; piel sensible', 'Ligero', '200 ml', 'Centella asiatica de Madagascar y aceites limpiadores ligeros.', 'UND', 40.00, 69.90, 62.90, DATE_SUB(CURDATE(), INTERVAL 1 DAY), DATE_ADD(CURDATE(), INTERVAL 3 MONTH), 0.00, 0, 18.000, 4.000, 100.000, 1, 1, 'skin1004-madagascar-centella-light-cleansing-oil.webp', 'SKIN1004 Madagascar Centella Light Cleansing Oil en BeniGlow Store', 'SKIN1004 Madagascar Centella Light Cleansing Oil | BeniGlow Store', 'Aceite limpiador ligero para retirar protector solar, maquillaje e impurezas. Formula pensada para una primera limpieza comoda y suave.', NULL, 'IMP-2026-01', 'Almacen principal', 1, 0, 1, 0, 5, NOW(), NOW());

INSERT INTO `promociones` (`id`, `nombre`, `descripcion`, `tipo`, `valor`, `producto_id`, `categoria_id`, `fecha_inicio`, `fecha_fin`, `cantidad_minima`, `activo`, `created_at`, `updated_at`) VALUES
(1, 'Lanzamiento K-Beauty BeniGlow', 'Descuento de bienvenida para el catalogo inicial de cuidado facial coreano.', 'descuento_porcentaje', 10.00, NULL, NULL, DATE_SUB(CURDATE(), INTERVAL 1 DAY), DATE_ADD(CURDATE(), INTERVAL 3 MONTH), 1, 1, NOW(), NOW());
