<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		$this->load->model('user_model');
		$this->load->helper('lang');
		// $this->load->helper('get_site_config');
		$this->lang->load('es', 'spanish');
		
		$this->login = $this->session->userdata('login');
		$this->is_admin = ($this->login && $this->login->group_id==1);
		
		$this->redirect_after_login = '/dashboard';
	}

	public function index()
	{
		$post = $this->input->post(NULL, TRUE);
		if ($post)
		{
			$user = $this->user_model->login($post['email']);

			if(!$user->active)
			{
				$this->session->set_flashdata('alert', array(
					'msg' => lang('Por favor, confirmá el registro desde tu casilla de correo'),
					'error' => TRUE
				));
				redirect('/login');
			}
			else if ($user && password_verify($post['password'], $user->password))
			{
				$this->session->set_userdata('login', $user);
				$this->user_model->save_login($user->id);
				
				$referrer = $this->input->get('referrer', true);
				if(!empty($referrer)) {
					redirect($referrer, 'refresh');
				}
				else {
					redirect($this->redirect_after_login);
				}
			}
			else {
				$this->session->set_flashdata('alert', array(
					'msg' => lang('Los datos ingresados son incorrectos.'),
					'error' => TRUE
				));
				redirect('/login');
			}
		}

		$this->load->view("login/index");
	}

	public function register()
	{
		$this->load->library('email');

		$post = $this->input->post(NULL, TRUE);
		if ($post)
		{
			$check_user = $this->user_model->login($post['email']);
			if($check_user)
			{
				$this->session->set_flashdata('alert', array(
					'msg' => lang('Ya existe un usuario con ese email'),
					'error' => TRUE
				));
				redirect('/register');
			}

			$data = array(
				'group_id' => '3',//pacientes
				'first_name' => $post['first_name'],
				'last_name' => $post['last_name'],
				'email' => $post['email'],
				'password' => password_hash($post['password'], PASSWORD_DEFAULT),
				'created_at' => date('Y-m-d H:i:s')
			);
			$user = $this->user_model->insert($data);

			//enviar email para confirmar cuenta
			$email = $post['email'];
			$rp_token = $this->user_model->generate_token($email);
			$data['url'] = site_url('/login/confirmar_email/'. $rp_token);
			$data['nombre'] = $post['first_name'];
			$message = $this->load->view('email/confirmar_email', $data, true);
			$this->email->from(get_site_config('admin_email'), get_site_config('site_title'));
			$this->email->to($email);
			$this->email->subject(lang('Confirmar email'));
			$this->email->message($message);
			$this->email->send();
			
			$this->session->set_flashdata('alert', array(
				'msg' => lang('Te enviamos un email para completar tu registro'),
				'error' => FALSE
			));
			redirect('/login');
		}

		$this->load->view("login/register");
	}

	public function forgotpassword()
	{
		$email = $this->input->post('email', TRUE);
		
		if ($email)
		{
			//check if user exists
			$user = $this->user_model->login($email);
			if ($user)
			{
				$this->load->library('email');

				//send mail to user
				$rp_token = $this->user_model->generate_token($email);
				$data['url'] = site_url('/login/changepassword/'. $rp_token);
				$message = $this->load->view('email/forgot_password', $data, true);
				$this->email->from(get_site_config('admin_email'), get_site_config('site_title'));
				$this->email->to($email);
				$this->email->subject(lang('Restablecer contraseña'));
				$this->email->message($message);
				$this->email->send();
				
				$this->session->set_flashdata('alert', array(
					'msg' => lang('Te enviamos un email con instrucciones para restablecer tu contraseña'),
					'error' => FALSE
				));
				redirect('/login');
			}
			else {
				$this->session->set_flashdata('alert', array(
					'msg' => lang('El usuario no existe'),
					'error' => TRUE
				));
				redirect('/login/forgotpassword');
			}

		}

		$this->load->view("login/forgotpassword");
	}

	public function changepassword($token=null)
	{
		$verify_token = $this->user_model->verify_token($token);

		if(!$verify_token)
		{
			$this->session->set_flashdata('alert', array(
				'msg' => lang('No se pudo restablecer la contraseña'),
				'error' => TRUE
			));
			redirect('/login');
		}
		
		$post = $this->input->post(NULL, TRUE);
		
		if (!empty($post['email']))
		{
			if($post['password']==$post['password_confirmation'])
			{
				$update_data = array(
					'password' => password_hash($post['password'], PASSWORD_DEFAULT),
					'rp_token' => NULL
				);
				$this->db->where('id', $verify_token->id);
				$this->db->update('user', $update_data);

				$this->session->set_flashdata('alert', array(
					'msg' => lang('Usa tu nueva clave para ingresar.'),
					'error' => FALSE
				));
				redirect('/login');
			}
			else {
				$this->session->set_flashdata('alert', array(
					'msg' => lang('Las contraseñas no coinciden'),
					'error' => TRUE
				));
				redirect('/login/changepassword/'.$token);
			}
		}

		$data['user'] = $verify_token;
		$this->load->view("login/changepassword", $data);
	}

	public function confirmar_email($token=null)
	{
		$verify_token = $this->user_model->verify_token($token);

		if(!$verify_token)
		{
			$this->session->set_flashdata('alert', array(
				'msg' => lang('No se pudo verificar tu dirección de correo'),
				'error' => TRUE
			));
			redirect('/login');
		}
		
		$update_data = array(
			'active' => 1,
			'rp_token' => NULL
		);
		$this->db->where('id', $verify_token->id);
		$this->db->update('user', $update_data);

		//update session variables
		$userdata = $this->user_model->login($verify_token->email);
		$this->session->set_userdata('login', $userdata);

		redirect('/perfil');
	}

	public function logout()
	{
		session_destroy();
		redirect('/login', 'refresh');
	}

}