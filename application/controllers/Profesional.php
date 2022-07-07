<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('Front_Controller.php');

class Profesional extends Front_Controller {

	function __construct()
	{
		parent::__construct();
		$this->need_login();
		$this->is_admin();

		$this->load->model('user_model');
		$this->load->model('agenda_model');
		
		$this->include_js = 'profesional.js';
	}

	public function index()
	{
		$search = $this->input->get('q', true);
		$data['rows'] = $this->user_model->get_by_group_id(2, $search);//profesionales
		$this->load->view('profesional/index', $data);
	}

	public function add()
	{
		$post = $this->input->post(NULL, TRUE);	
		if ($post)
		{
			$data = array(
				'group_id' => 2,//profesionales
				'first_name' => $post['first_name'],
				'last_name' => $post['last_name'],
				'especialidad' => $post['especialidad'],
				'dni' => $post['dni'],
				'telefono' => $post['telefono'],
				'email' => $post['email'],
				'password' => password_hash($post['password'], PASSWORD_DEFAULT)
			);

			$user_id = $this->user_model->insert($data);

			//enviar email para confirmar cuenta
			// $email = $post['email'];
			// $rp_token = $this->user_model->generate_token($email);
			// $data['url'] = site_url('/login/confirmar_email_profesional/'. $rp_token);
			// $data['nombre'] = $post['first_name'];
			// $message = $this->load->view('email/confirmar_email', $data, true);
			// $this->email->from(get_site_config('admin_email'), get_site_config('site_title'));
			// $this->email->to($email);
			// $this->email->subject(lang('Confirmar email'));
			// $this->email->message($message);
			// $this->email->send();

			$this->session->set_flashdata('alert', array(
				'msg' => lang('El profesional se agregó correctamente'),
				'error' => FALSE
			));
			redirect('/profesional/edit/'.$user_id);
		}

		$this->load->view('profesional/add');
	}

	public function edit($id)
	{
		$post = $this->input->post(NULL, TRUE);	
		if ($post)
		{
			// print_r($post);die;
			$data = array(
				'first_name' => $post['first_name'],
				'last_name' => $post['last_name'],
				'especialidad' => $post['especialidad'],
				'dni' => $post['dni'],
				'telefono' => $post['telefono'],
				'email' => $post['email']
			);

			if(!empty($post['password'])) {
				$data['password'] = password_hash($post['password'], PASSWORD_DEFAULT);
			}

			$this->user_model->update($id, $data);

			$this->session->set_flashdata('alert', array(
				'msg' => lang('Los cambios se guardaron correctamente'),
				'error' => FALSE
			));
			redirect('/profesional/edit/'.$id);
		}

		//show calendar
		$date = $this->input->get('date', true);
		$year = $date ? substr($date, 1, 4) : date('Y');
		$month = $date ? substr($date, 6, 2) : date('m');

		$profesional = $this->user_model->get($id);
		$this->agenda_model->prefs['next_prev_url'] = base_url('profesional/edit/'. $profesional->id .'?date=');
		$data['calendar'] = $this->agenda_model->get_calendar($year, $month, $profesional->id);
		$data['row'] = $profesional;
		
		$this->load->view('profesional/edit', $data);
	}

	public function index_fechas_comprometidas($profesional_id)
	{
		$this->is_ajax();

		$data['rows'] = $this->user_model->get_fechas_comprometidas($profesional_id);
		$data['profesional_id'] = $profesional_id;

		$this->load->view('profesional/index_fechas_comprometidas', $data);
	}

	public function add_fecha_comprometida($profesional_id)
	{
		$post = $this->input->post(NULL, TRUE);	
		if ($post)
		{
			$data = array(
				'profesional_id' => $profesional_id,
				'fecha_desde' => $post['fecha_desde'],
				'fecha_hasta' => $post['fecha_hasta'],
				'motivo' => $post['motivo']
			);
			$this->db->insert('fecha_comprometida', $data);

			$this->session->set_flashdata('alert', array(
				'msg' => lang('La agenda se actualizó correctamente'),
				'error' => FALSE
			));
			redirect('/profesional/edit/'.$profesional_id);
		}

		$this->load->view('profesional/add_fecha_comprometida');
	}

	public function delete_fecha_comprometida($id)
	{
		$this->is_admin();
		$this->is_ajax();

		$this->db->where('id', $id);
		$this->db->delete('fecha_comprometida');

		echo 'ok';
	}

	public function index_dias_habilitados($profesional_id)
	{
		$this->is_ajax();

		$data['rows'] = $this->user_model->get_dias_habilitados($profesional_id);
		$data['profesional_id'] = $profesional_id;

		$this->load->view('profesional/index_dias_habilitados', $data);
	}

	public function add_dia_habilitado($profesional_id)
	{
		$this->is_ajax();

		$post = $this->input->post(NULL, TRUE);	
		if ($post)
		{
			$daysOfWeekDisabled = array_diff(["0","1","2","3","4","5","6"], $post['days']);
			
			$insert = array(
				'profesional_id' => $profesional_id,
				'daysOfWeekDisabled' => implode('', $daysOfWeekDisabled),
				'hora_desde' => $post['hora_desde'],
				'hora_hasta' => $post['hora_hasta']
			);

			if(isset($post['dia_id']))
			{
				$this->db->where('id', $post['dia_id']);
				$this->db->update('dias_habilitados', $insert);
			}
			else {
				$this->db->insert('dias_habilitados', $insert);
			}
		}

		$data['profesional_id'] = $profesional_id;
		$data['rows'] = $this->user_model->get_dias_habilitados($profesional_id);

		$this->load->view('profesional/index_dias_habilitados', $data);
	}

	public function deshabilitar($id)
	{
		$this->is_admin();

		$data = array('active' => NULL);
		$this->user_model->update($id, $data);

		$this->session->set_flashdata('alert', array(
			'msg' => lang('El profesional se deshabilitó correctamente'),
			'error' => FALSE
		));
		redirect('/profesional');
	}

	public function activar($id)
	{
		$this->is_admin();

		$data = array('active' => 1);
		$this->user_model->update($id, $data);

		$this->session->set_flashdata('alert', array(
			'msg' => lang('El profesional se habilitó nuevamente'),
			'error' => FALSE
		));
		redirect('/profesional');
	}

	public function delete($id)
	{
		$this->is_admin();
		$this->is_ajax();
		$this->user_model->delete($id);
		echo 'ok';
	}

}