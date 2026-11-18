# Migraciones de base de datos (CodeIgniter 3)

Las migraciones son clases PHP numeradas (`001_`, `002_`, …) con `up()` (aplicar) y `down()` (revertir).
Se ejecutan **solo por línea de comandos** y **nunca solas**: ni el deploy ni un request web las corren.
La base guarda la versión aplicada en la tabla `migrations`.

## Aplicar migraciones (en el servidor)

```bash
cd ~/public_html/gestion
# 1) BACKUP (MySQL no revierte cambios de estructura)
mysqldump NOMBRE_BASE > ~/backups_privados/antes_de_migrar_$(date +%F).sql     # ver nota sobre ~/.my.cnf abajo
# 2) ver qué hay pendiente (no modifica nada)
CI_ENV=production php index.php migrate
# 3) aplicar
CI_ENV=production php index.php migrate run
```

`CI_ENV` es obligatorio por CLI (`production` o `development`): sin él el comando se rechaza para no operar contra una base equivocada.
En tu máquina: `CI_ENV=development php index.php migrate run`.

Para no escribir la contraseña en la línea de comandos, creá `~/.my.cnf` (permiso 600) con
`[client]`, `user=…`, `password=…`; así `mysqldump NOMBRE_BASE` no la pide ni queda en el historial.

## Crear una migración nueva

1. Copiar el siguiente número libre: `application/migrations/002_agregar_algo.php`.
2. El nombre de la clase es `Migration_` + el nombre sin número, con la primera letra en mayúscula:

```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Agregar_algo extends CI_Migration {

	public function up()
	{
		$this->db->query("ALTER TABLE user ADD COLUMN algo VARCHAR(50) NULL");
	}

	public function down()
	{
		$this->db->query("ALTER TABLE user DROP COLUMN algo");
	}
}
```

3. Subir `config['migration_version']` en `application/config/migration.php` al número nuevo.
4. Probarla: `./tests/run.sh` arma la base de pruebas **usando las migraciones** (así se prueban todas).
5. Commit y push. Después, en el servidor: backup → `migrate` → `migrate run`.

## Reglas

- **Nunca editar una migración ya aplicada en producción**: se agrega una nueva.
- **Numeración secuencial** y sin saltos. Si dos personas crean la misma, se renumera una antes del merge.
- **Orden respecto del deploy:** los cambios *aditivos* (columna o tabla nueva) se aplican **antes** de publicar el código que los usa;
  los destructivos (borrar o renombrar) se hacen en dos pasos: primero un código que funcione con ambos esquemas, después la migración.
- `down()` es una ayuda, no una garantía (se ejecuta con `migrate version N`): el backup es la vuelta atrás real.
- Las migraciones de **datos** (corregir registros) también van acá, con `WHERE` explícito y verificación de la cantidad afectada.
- `001_baseline`: en una base que ya tiene el esquema (producción) **no hace nada**, solo registra la versión 1.
  En una base vacía crea las 20 tablas (estructura en `sql/001_baseline.sql`; los datos iniciales —grupos, configuración— no se incluyen).

## Primera vez en producción

Después del primer deploy con migraciones, correr una vez `CI_ENV=production php index.php migrate run`.
Crea la tabla `migrations`, registra la versión 1 y no toca nada más.
