<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('Front_Controller.php');

/**
 * Entrega de archivos subidos (historias clínicas, evolutivos, pagos, prescripciones) y del logo.
 * La carpeta uploads/ no es accesible directamente desde la web (ver uploads/.htaccess):
 * todo pasa por acá, con control de acceso por paciente.
 */
class Archivo extends Front_Controller {

	//tablas/columnas donde se guarda el nombre de un archivo subido (todas tienen paciente_id)
	private $fuentes = array(
		array('historiaclinica', 'file'),
		array('evolutivo', 'file'),
		array('agenda', 'file'),
		array('prescripcion', 'file'),
		array('prescripcion', 'file2')
	);

	private $tipos = array(
		'pdf' => 'application/pdf', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png',
		'gif' => 'image/gif', 'svg' => 'image/svg+xml',
		'doc' => 'application/msword',
		'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
	);

	/**
	 * Archivo adjunto de un registro: sólo el staff o el paciente dueño.
	 */
	public function ver($nombre = '')
	{
		$this->need_login();

		$nombre = rawurldecode((string) $nombre);
		if(!$this->nombre_valido($nombre)) show_404();

		$paciente_id = NULL;
		foreach($this->fuentes as $f)
		{
			$row = $this->db->select('paciente_id')->where($f[1], $nombre)->limit(1)->get($f[0])->row();
			if($row) { $paciente_id = $row->paciente_id; break; }
		}
		if($paciente_id === NULL) show_404();

		$this->require_paciente_access($paciente_id);
		$this->enviar($nombre, TRUE);
	}

	/**
	 * Logo del sitio (público: se muestra también en el login).
	 */
	public function logo()
	{
		$this->load->model('config_model');
		$cfg = $this->config_model->get_userdata();
		$nombre = isset($cfg->logo) ? $cfg->logo : '';
		if(!$this->nombre_valido($nombre)) show_404();
		$this->enviar($nombre, FALSE);
	}

	private function nombre_valido($nombre)
	{
		//sólo un nombre de archivo (sin rutas ni caracteres de control); además debe existir en la base
		return is_string($nombre) && $nombre !== '' && $nombre[0] !== '.'
			&& basename($nombre) === $nombre && strpos($nombre, '\\') === FALSE
			&& !preg_match('/[\x00-\x1f\x7f]/', $nombre) && strpos($nombre, '..') === FALSE;
	}

	private function enviar($nombre, $privado)
	{
		$path = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . $nombre;
		$ext = strtolower(pathinfo($nombre, PATHINFO_EXTENSION));
		if(!is_file($path) || !isset($this->tipos[$ext])) show_404();

		$inline = in_array($ext, array('pdf', 'jpg', 'jpeg', 'png', 'gif', 'svg'), TRUE);
		header('Content-Type: ' . $this->tipos[$ext]);
		header('Content-Length: ' . filesize($path));
		header('Content-Disposition: ' . ($inline ? 'inline' : 'attachment') . '; filename="archivo.' . $ext . '"; filename*=UTF-8\'\'' . rawurlencode($nombre));
		header('X-Content-Type-Options: nosniff');
		header("Content-Security-Policy: default-src 'none'; style-src 'unsafe-inline'; sandbox");
		header('Cache-Control: ' . ($privado ? 'private, no-store' : 'public, max-age=3600'));
		readfile($path);
		exit;
	}

}
