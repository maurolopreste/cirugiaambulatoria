<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('Front_Controller.php');

class Perfil extends Front_Controller {

	function __construct()
	{
		parent::__construct();
		$this->need_login();

		$this->load->model('user_model');
		// $this->include_js = 'perfil.js';
	}

	public function index()
	{
		$post = $this->input->post(NULL, TRUE);	
		if ($post)
		{
			// print_r($post);die;
			$data = array(
				'first_name' => $post['first_name'],
				'last_name' => $post['last_name'],
				'dni' => $post['dni'],
				'direccion' => $post['direccion'],
				'telefono' => $post['telefono']
			);

			if(!empty($post['password'])) {
				$data['password'] = password_hash($post['password'], PASSWORD_DEFAULT);
			}

			$this->user_model->update($this->login->id, $data);

			$this->session->set_flashdata('alert', array(
				'msg' => lang('Los cambios se guardaron correctamente'),
				'error' => FALSE
			));
			redirect('/perfil');
		}

		$data['user'] = $this->user_model->get($this->login->id);
		$this->load->view('user/perfil', $data);
	}

}
