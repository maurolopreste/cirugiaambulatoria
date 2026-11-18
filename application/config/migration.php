<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| Migraciones de base de datos (CodeIgniter 3). Se ejecutan SÓLO por línea de comandos:
|     CI_ENV=production php index.php migrate          (estado: qué hay pendiente)
|     CI_ENV=production php index.php migrate run      (aplicar las pendientes)
| Ver application/migrations/README.md.
*/
$config['migration_enabled'] = TRUE;      // el controlador Migrate sólo responde por CLI
$config['migration_type'] = 'sequential'; // 001_, 002_, ... (evita saltear migraciones por orden de fechas)
$config['migration_table'] = 'migrations';
$config['migration_auto_latest'] = FALSE; // NUNCA migrar solo al recibir un request web
$config['migration_version'] = 1;         // última versión conocida; se actualiza al agregar una migración
$config['migration_path'] = APPPATH.'migrations/';
