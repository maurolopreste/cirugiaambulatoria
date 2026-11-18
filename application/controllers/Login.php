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
			$email = trim((string) (isset($post['email']) ? $post['email'] : ''));
			$password = (string) (isset($post['password']) ? $post['password'] : '');

			//límite de intentos fallidos (fuerza bruta)
			if($this->rate_limited('login:'.strtolower($email).'|'.$this->input->ip_address(), 5)
				|| $this->rate_limited('login-ip:'.$this->input->ip_address(), 30))
			{
				$this->session->set_flashdata('alert', array(
					'msg' => lang('Demasiados intentos fallidos. Probá de nuevo en 15 minutos.'),
					'error' => TRUE
				));
				redirect('/login');
			}

			$user = $this->user_model->login($email);
			//si el usuario no existe se verifica igual contra un hash falso (mismo tiempo de respuesta)
			$hash = $user ? $user->password : '$2y$10$usesomesillystringfore7hnbRJHxXVLeakoG8K30oukPsA.ztMG';
			$valid = password_verify($password, $hash) && $user;

			if(!$valid)
			{
				$this->rate_hit('login:'.strtolower($email).'|'.$this->input->ip_address());
				$this->rate_hit('login-ip:'.$this->input->ip_address());
				$this->session->set_flashdata('alert', array(
					'msg' => lang('Los datos ingresados son incorrectos.'),
					'error' => TRUE
				));
				redirect('/login');
			}

			//recién con la contraseña correcta se informa que falta confirmar el email
			if(!$user->active)
			{
				$this->session->set_flashdata('alert', array(
					'msg' => lang('Por favor, confirmá el registro desde tu casilla de correo'),
					'error' => TRUE
				));
				redirect('/login');
			}

			$this->rate_clear('login:'.strtolower($email).'|'.$this->input->ip_address());

			//nuevo ID de sesión (evita fijación de sesión) y sin hash ni token dentro de la sesión
			$this->session->sess_regenerate(TRUE);
			$this->session->set_userdata('login', $this->user_model->for_session($user));
			$this->user_model->save_login($user->id);
			
			$referrer = $this->input->get('referrer', true);
			if($this->is_safe_referrer($referrer)) {
				redirect($referrer, 'refresh');
			}
			else {
				redirect($this->redirect_after_login);
			}
		}

		$this->load->view("login/index");
	}

	/**
	 * Sólo rutas internas (evita open redirect: //sitio.com, http://..., javascript:, etc.)
	 */
	private function is_safe_referrer($url)
	{
		return is_string($url) && $url !== ''
			&& preg_match('#^/?[A-Za-z0-9_\-./]*$#', $url)
			&& strpos($url, '//') === FALSE
			&& strpos($url, '..') === FALSE;
	}

	/* ---- límite de intentos (archivos en el directorio temporal, sin tocar el esquema) ---- */

	private function rate_file($key)
	{
		return sys_get_temp_dir().DIRECTORY_SEPARATOR.'ci_rate_'.hash('sha256', $key);
	}

	private function rate_state($key)
	{
		$f = $this->rate_file($key);
		$d = is_file($f) ? json_decode((string) @file_get_contents($f), TRUE) : NULL;
		if(!is_array($d) || !isset($d['n'], $d['t']) || time() - $d['t'] > 900) return array('n' => 0, 't' => time());
		return $d;
	}

	private function rate_limited($key, $max)
	{
		$d = $this->rate_state($key);
		return $d['n'] >= $max;
	}

	private function rate_hit($key)
	{
		$d = $this->rate_state($key);
		@file_put_contents($this->rate_file($key), json_encode(array('n' => $d['n'] + 1, 't' => $d['n'] ? $d['t'] : time())), LOCK_EX);
	}

	private function rate_clear($key)
	{
		@unlink($this->rate_file($key));
	}

	public function register()
	{
		$this->load->library('email');

		$post = $this->input->post(NULL, TRUE);
		if ($post)
		{
			if(!filter_var($post['email'], FILTER_VALIDATE_EMAIL) || !password_is_valid($post['password']))
			{
				$this->session->set_flashdata('alert', array(
					'msg' => lang('Ingresá un email válido y una contraseña de al menos 8 caracteres'),
					'error' => TRUE
				));
				redirect('/register');
			}

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
			$rp_token = $this->user_model->generate_token($email, 172800);//48 hs
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
		$email = trim((string) $this->input->post('email', TRUE));
		
		if ($email)
		{
			$key = 'forgot-ip:'.$this->input->ip_address();
			if(!$this->rate_limited($key, 5))
			{
				$this->rate_hit($key);

				$user = $this->user_model->login($email);
				if ($user)
				{
					$this->load->library('email');

					//send mail to user
					$rp_token = $this->user_model->generate_token($email, 3600);//1 hora
					$data['url'] = site_url('/login/changepassword/'. $rp_token);
					$message = $this->load->view('email/forgot_password', $data, true);
					$this->email->from(get_site_config('admin_email'), get_site_config('site_title'));
					$this->email->to($email);
					$this->email->subject(lang('Restablecer contraseña'));
					$this->email->message($message);
					$this->email->send();
				}
			}

			//misma respuesta exista o no la cuenta (no revela qué emails están registrados)
			$this->session->set_flashdata('alert', array(
				'msg' => lang('Si el email está registrado, te enviamos instrucciones para restablecer tu contraseña'),
				'error' => FALSE
			));
			redirect('/login');
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
		
		if (isset($post['password']))
		{
			if(!password_is_valid($post['password']))
			{
				$this->session->set_flashdata('alert', array(
					'msg' => lang('La contraseña debe tener al menos 8 caracteres'),
					'error' => TRUE
				));
				redirect('/login/changepassword/'.$token);
			}
			else if($post['password']==$post['password_confirmation'])
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
		$this->session->sess_regenerate(TRUE);
		$this->session->set_userdata('login', $this->user_model->for_session($userdata));

		redirect('/perfil');
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('/login', 'refresh');
	}

}