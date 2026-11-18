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
				'telefono' => $post['telefono']
			);

			if(!empty($post['password']))
			{
				//para cambiar la contraseña hay que conocer la actual y cumplir la política mínima
				$actual = $this->db->select('password')->where('id', $this->login->id)->get('user')->row();
				$ok_actual = $actual && !empty($post['current_password']) && password_verify($post['current_password'], $actual->password);

				if(!$ok_actual || !password_is_valid($post['password']))
				{
					$this->session->set_flashdata('alert', array(
						'msg' => lang($ok_actual ? 'La contraseña debe tener al menos 8 caracteres' : 'La contraseña actual no es correcta'),
						'error' => TRUE
					));
					redirect('/perfil');
				}
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
