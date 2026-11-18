<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Línea base. En una base que YA tiene el esquema (producción, desarrollo) no hace nada: sólo deja
 * registrada la versión 1. En una base vacía (instalación nueva, tests) crea todas las tablas.
 */
class Migration_Baseline extends CI_Migration {

	public function up()
	{
		if ($this->db->table_exists('user'))
		{
			echo "  (001) La base ya tiene el esquema: no se modifica nada.\n";
			return;
		}

		$sql = file_get_contents(APPPATH.'migrations/sql/001_baseline.sql');
		$sql = preg_replace('/^--.*$/m', '', $sql);
		foreach (preg_split('/;\s*\n/', $sql) as $statement)
		{
			if (trim($statement) !== '') $this->db->query($statement);
		}
		echo "  (001) Esquema base creado en una base vacía.\n";
	}

	public function down()
	{
		// Nunca se borra el esquema completo.
		echo "  (001) down(): sin acción (no se elimina el esquema base).\n";
	}
}
