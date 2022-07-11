<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('Front_Controller.php');

class User extends Front_Controller {

	function __construct()
	{
		parent::__construct();
		$this->need_login();

		$this->load->model('user_model');
	}

	public function index()
	{
		$this->is_admin();

		$data['rows'] = $this->user_model->get_by_group_id(1);
		$this->load->view('user/index', $data);
	}
	
	public function add()
	{
		$this->is_admin();

		$post = $this->input->post(NULL, TRUE);
		if ($post)
		{
			$data = array(
				'group_id' => 1,//admin
				'first_name' => $post['first_name'],
				'last_name' => $post['last_name'],
				'email' => $post['email'],
				'password' => password_hash($post['password'], PASSWORD_DEFAULT),
				'created_at' => date('Y-m-d H:i:s')
			);

			$this->user_model->insert($data);

			$this->session->set_flashdata('alert', array(
				'msg' => lang('El usuario se agregó correctamente'),
				'error' => FALSE
			));
			redirect('/user');
		}

		$this->load->view('user/add');
	}

	public function edit($id)
	{
		$this->is_admin();

		$user = $this->user_model->get($id);

		$post = $this->input->post(NULL, TRUE);	
		if ($post)
		{
			$data = array(
				'first_name' => $post['first_name'],
				'last_name' => $post['last_name'],
				'email' => $post['email']
			);

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

}