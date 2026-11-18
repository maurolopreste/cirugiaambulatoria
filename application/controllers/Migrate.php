<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migraciones de base de datos. SOLO línea de comandos (por web devuelve 404).
 *
 *   CI_ENV=production php index.php migrate              estado: versión actual y migraciones pendientes
 *   CI_ENV=production php index.php migrate run          aplica todas las pendientes
 *   CI_ENV=production php index.php migrate version N    lleva la base a la versión N (usa up()/down())
 *
 * CI_ENV es obligatorio: sin él no se sabe contra qué base se va a operar.
 * HACER BACKUP ANTES de `run` o `version` (MySQL no revierte cambios de estructura).
 */
class Migrate extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if ( ! is_cli()) show_404();

		$this->config->load('migration');//deja migration_table etc. disponibles en config_item()
		$this->load->library('migration');
		$this->load->helper('file');
	}

	/** @return array número => nombre de archivo */
	private function disponibles()
	{
		$out = array();
		foreach (glob(APPPATH.'migrations/[0-9][0-9][0-9]_*.php') as $f) {
			$out[(int) substr(basename($f), 0, 3)] = basename($f);
		}
		ksort($out);
		return $out;
	}

	private function version_actual()
	{
		$row = $this->db->get($this->config->item('migration_table'))->row();
		return $row ? (int) $row->version : 0;
	}

	public function index()
	{
		$this->status();
	}

	public function status()
	{
		$actual = $this->version_actual();
		echo "Entorno: ".ENVIRONMENT." | Base: ".$this->db->database."\n";
		echo "Versión actual de la base: $actual\n";
		$pend = 0;
		foreach ($this->disponibles() as $n => $archivo) {
			echo sprintf("  %s %s\n", $n <= $actual ? '[aplicada] ' : '[PENDIENTE]', $archivo);
			if ($n > $actual) $pend++;
		}
		echo $pend ? "\nHay $pend migración(es) pendiente(s). Hacé un backup y ejecutá:  php index.php migrate run\n" : "\nLa base está al día.\n";
	}

	public function run()
	{
		echo "Entorno: ".ENVIRONMENT." | Base: ".$this->db->database."\n";
		echo "Versión antes: ".$this->version_actual()."\n";

		$res = $this->migration->latest();
		if ($res === FALSE) {
			echo "ERROR: ".$this->migration->error_string()."\n";
			exit(1);
		}
		echo "Versión después: ".$this->version_actual().($res === TRUE ? " (sin cambios)" : "")."\n";
	}

	public function version($n = NULL)
	{
		if ($n === NULL || ! ctype_digit((string) $n)) {
			echo "Uso: php index.php migrate version N\n";
			exit(1);
		}
		echo "Entorno: ".ENVIRONMENT." | Base: ".$this->db->database."\n";
		$res = $this->migration->version((int) $n);
		if ($res === FALSE) {
			echo "ERROR: ".$this->migration->error_string()."\n";
			exit(1);
		}
		echo "Versión ahora: ".$this->version_actual()."\n";
	}
}
