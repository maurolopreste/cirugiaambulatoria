<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('Front_Controller.php');

class User extends Front_Controller {

	function __construct()
	{
		parent::__construct();
		$this->need_login();

		$this->load->model('user_model');

		$this->group_list = $this->user_model->get_groups();
		$this->profesionales_list = $this->profesional_model->get();

		$this->include_js = 'user.js';
	}

	public function index()
	{
		$this->is_admin();

		if($this->is_profesional) {
			$this->user_model->filter_results = array('profesional_id' => $this->login->id);
		}

		$search = $this->input->get('q', true);
		if(!empty($search))
		{
			$data['search'] = $search;
			$data['rows'] = $this->user_model->get_search($search);
			$data['pagination'] = FALSE;
		}
		else {
			$data['rows'] = $this->user_model->get();

			$config_pagination = [
				'base_url' => '/poliza/page',
				'total_rows' => $this->user_model->get_total()
			];
			$data['pagination'] = $this->set_pagination($config_pagination);
		}

		$this->load->view('user/index', $data);
	}

	public function index_seguros($user_id)
	{
		$this->is_ajax();

		$data['rows'] = $this->user_model->get_seguros($user_id);
		$data['user_id'] = $user_id;

		$this->load->view('user/index_seguros', $data);
	}

	public function index_mensajes($user_id)
	{
		$this->is_ajax();

		$data['rows'] = $this->user_model->get_mensajes($user_id);
		$data['user_id'] = $user_id;

		$this->load->view('user/index_mensajes', $data);
	}

	public function add()
	{
		$this->is_admin();

		$post = $this->input->post(NULL, TRUE);
		if ($post)
		{
			$data = array(
				'group_id' => 3,//paciente
				'first_name' => $post['first_name'],
				'last_name' => $post['last_name'],
				'email' => $post['email'],
				'password' => password_hash($post['password'], PASSWORD_DEFAULT),
				'created_at' => date('Y-m-d H:i:s')
			);

			if($this->is_admin && isset($post['group_id'])) {
				$data['group_id'] = $post['group_id'];
			}
			
			if($data['group_id']==3) {
				$data['dni'] = $post['dni'];
				$data['direccion'] = $post['direccion'];
				$data['telefono'] = $post['telefono'];
				$data['profesional_id'] = $post['profesional_id'];
			}

			if($this->is_profesional) {
				$data['profesional_id'] = $this->login->id;
			}

			$this->user_model->insert($data);

			$this->session->set_flashdata('alert', array(
				'msg' => lang('El usuario se agregó correctamente'),
				'error' => FALSE
			));
			redirect('/user');
		}

		$this->load->view('user/add');
	}

	public function add_seguro($user_id)
	{
		$post = $this->input->post(null, true);
		if($post)
		{
			// print_r($post);die;
			$data = [
				'user_id' => $user_id,
				'poliza_tipo_id' => $post['poliza_tipo_id'],
				'profesional_id' => $post['profesional_id'],
				'nro_poliza' => $post['nro_poliza'],
				'vencimiento' => $post['vencimiento'],
				'code' => md5($post['nro_poliza'])
			];
			$this->poliza_model->insert($data);
			
			//redirect to view
			$data['rows'] = $this->user_model->get_seguros($user_id);
			$data['user_id'] = $user_id;
			echo $this->load->view('user/index_seguros', $data, true);
			return;
		}

		$this->load->view('user/add_seguro');
	}

	public function add_mensaje($user_id)
	{
		$post = $this->input->post(null, true);
		if($post)
		{
			// print_r($post);die;
			$data = [
				'user_id' => $user_id,
				'mensaje' => $post['mensaje'],
				'fecha' => date('Y-m-d H:i:s')
			];
			$this->user_model->insert_pivot('mensajes', $data);
			
			//redirect to view
			$data['rows'] = $this->user_model->get_mensajes($user_id);
			$data['user_id'] = $user_id;
			echo $this->load->view('user/index_mensajes', $data, true);
			return;
		}

		$this->load->view('user/add_mensaje');
	}

	public function edit($id)
	{
		$this->is_admin();

		$user = $this->user_model->get($id);
		if($this->is_profesional && $user->profesional_id!=$this->login->id)
		{
			$this->is_admin();
		}

		$post = $this->input->post(NULL, TRUE);	
		if ($post)
		{
			$data = array(
				'first_name' => $post['first_name'],
				'last_name' => $post['last_name'],
				'dni' => $post['dni'],
				'direccion' => $post['direccion'],
				'telefono' => $post['telefono'],
				'email' => $post['email']
			);

			//admin only changes
			if($this->is_admin) {
				$data['group_id'] = $post['group_id'];
				$data['profesional_id'] = $post['profesional_id'];
				$data['matricula'] = $post['matricula'];
			}

			//password change
			if(!empty($post['password'])) {
				$data['password'] = password_hash($post['password'], PASSWORD_DEFAULT);
			}

			$this->user_model->update($id, $data);

			$this->session->set_flashdata('alert', array(
				'msg' => lang('Los cambios se guardaron correctamente'),
				'error' => FALSE
			));
			redirect('/user');
		}

		$data['user'] = $user;
		$this->load->view('user/edit', $data);
	}

	public function delete($id)
	{
		$this->is_admin();
		$this->is_ajax();
		$this->user_model->delete($id);
		echo 'ok';
	}

	public function delete_mensaje($id)
	{
		// $this->is_admin();
		$this->is_ajax();
		$this->db->delete('user_mensajes', array('id' => $id));
		echo 'ok';
	}

}