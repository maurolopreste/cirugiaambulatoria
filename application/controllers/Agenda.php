<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('Front_Controller.php');

class Agenda extends Front_Controller {

	function __construct()
	{
		parent::__construct();
		$this->need_login();

		$this->load->model('agenda_model');
		$this->load->model('user_model');
		$this->load->library('upload');

		$this->pacientes_list = $this->user_model->get_by_group_id(3);
		$this->metodopago_list = $this->agenda_model->get_metodos_pago();
		
		$this->include_js = 'agenda.js';
	}

	public function index()
	{
		$date = $this->input->get('date', true);
		$year = $date ? substr($date, 1, 4) : date('Y');
		$month = $date ? substr($date, 6, 2) : date('m');

		if($this->is_admin)
		{
			$profesional_id = $this->input->get('p_id', true);
		}
		else {
			$profesional_id = $this->login->id;
		}
		
		$data['calendar'] = $this->agenda_model->get_calendar($year, $month, $profesional_id);
		$data['year'] = $year;
		$data['month'] = $month;

		$this->load->view('agenda/index', $data);
	}

	public function add($paciente_id=null)
	{
		//$this->is_admin();

		$post = $this->input->post(NULL, TRUE);	
		if ($post)
		{
			$data = array(
				'paciente_id' => $paciente_id,
				'profesional_id' => $this->login->id,
				'fecha' => $post['fecha'].' '.$post['hora'],
				'comentarios' => $post['comentarios'],
				'estado_cita' => 1,
				'created_at' => date('Y-m-d H:i:s'),
				'created_by' => $this->login->id
			);

			if($this->is_admin)
			{
				$data['paciente_id'] = $post['paciente_id'];
				$data['profesional_id'] = $post['profesional_id'];
			}

			$this->agenda_model->insert($data);

			$this->session->set_flashdata('alert', array(
				'msg' => lang('La agenda se actualizó correctamente'),
				'error' => FALSE
			));

			if($paciente_id)
			{
				redirect('/paciente/edit/'.$paciente_id);
			}
			else {
				redirect('/agenda');
			}
		}

		$data['paciente_id'] = $paciente_id;
		$this->load->view('agenda/add', $data);
	}

	public function edit($id)
	{
		// $this->is_admin();
		$row_agenda = $this->agenda_model->get($id);

		$post = $this->input->post(NULL, TRUE);	
		if ($post && $id)
		{
			$data = array(
				'fecha' => $post['fecha'].' '.$post['hora']
			);

			if($this->is_admin)
			{
				$data['paciente_id'] = $post['paciente_id'];
				$data['profesional_id'] = $post['profesional_id'];
				$data['payment_method'] = $post['payment_method'];
				$data['payment_status'] = $post['payment_status'];
				$data['comentarios'] = $post['comentarios'];
			}
			else {
				$data['observacion_profesional'] = $post['observacion_profesional'];	
			}

			if(!$row_agenda->link_video)
			{
				$data['link_video'] = 'bs'.time();
			}

			//adjuntar archivo
			if($this->upload->do_upload('userfile'))
			{
				$uploadData = $this->upload->data();
				$data['file'] = $uploadData['file_name'];
			}

			$this->agenda_model->update($id, $data);

			$this->session->set_flashdata('alert', array(
				'msg' => lang('La agenda se actualizó correctamente'),
				'error' => FALSE
			));
			redirect('/agenda');
		}

		$data['row'] = $row_agenda;
		$this->load->view('agenda/edit', $data);
	}

	public function cancelar_turno($id)
	{
		$this->is_ajax();
		$this->load->library('email');

		//cambiar estado agenda
		$this->agenda_model->update($id, array('estado_cita'=>3));//cancelado

		$turno = $this->agenda_model->get_turno($id);

		//notificar al paciente
		$data['paciente'] = $turno->nombre_paciente;
		$data['fecha'] = date('d/m', strtotime($turno->fecha));
		$message = $this->load->view('email/turno_cancelado', $data, true);
		$this->email->from(get_site_config('admin_email'), get_site_config('site_title'));
		$this->email->to($turno->email_paciente);
		$this->email->subject(lang('Cancelación turno'));
		$this->email->message($message);
		$this->email->send();

		echo 'ok';
	}

}