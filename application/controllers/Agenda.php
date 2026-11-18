<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('Front_Controller.php');

class Agenda extends Front_Controller {

	function __construct()
	{
		parent::__construct();
		$this->need_login();
		$this->require_staff();//los pacientes no acceden a la agenda interna

		$this->load->model('agenda_model');
		$this->load->model('config_model');
		$this->load->model('user_model');
		$this->load->library('upload');

		$this->pacientes_list = $this->user_model->get_by_group_id(3);
		$this->metodopago_list = $this->config_model->get_metodos_pago();
		$this->obrasocial_list = $this->config_model->get_obras_sociales();
		
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
		$post = $this->input->post(NULL, TRUE);	
		if ($post)
		{
			if(!empty($post['nuevopaciente']['first_name']))
			{
				$nuevopaciente = $post['nuevopaciente'];
				$data_paciente = array(
					'group_id' => 3,//paciente
					'first_name' => $nuevopaciente['first_name'],
					'last_name' => $nuevopaciente['last_name'],
					'email' => $nuevopaciente['email'],
					'telefono' => $nuevopaciente['telefono'],
					'obra_social' => $nuevopaciente['obra_social'],
					'created_at' => date('Y-m-d H:i:s')
				);
				$paciente_id = $this->user_model->insert($data_paciente);
			}

			if(!$paciente_id) $paciente_id = $post['paciente_id'];
			
			if(!$paciente_id)
			{
				$this->session->set_flashdata('alert', array(
					'msg' => lang('No se ha seleccionado ningún paciente'),
					'error' => TRUE
				));
				redirect('/agenda');
			}

			$data = array(
				'paciente_id' => $paciente_id,
				'profesional_id' => $post['profesional_id'],
				'fecha' => $post['fecha'].' '.$post['hora'].':'.$post['hora_min'],
				'comentarios' => $post['comentarios'],
				'estado_cita' => 1,
				'created_at' => date('Y-m-d H:i:s'),
				'created_by' => $this->login->id
			);

			$agenda_id = $this->agenda_model->insert($data);

			$this->session->set_flashdata('alert', array(
				'msg' => lang('La agenda se actualizó correctamente'),
				'error' => FALSE
			));

			//notificar al paciente
			$turno = $this->agenda_model->get_turno($agenda_id);
			if($turno->email_paciente)
			{
				$data['paciente'] = $turno->nombre_paciente;
				$data['profesional'] = $turno->nombre_profesional;
				$data['fecha'] = strftime('%A %e/%m a las %H:%M hs.', strtotime($turno->fecha));
				$message = $this->load->view('email/turno_nuevo', $data, true);
				$this->email->from(get_site_config('admin_email'), get_site_config('site_title'));
				$this->email->to($turno->email_paciente);
				$this->email->subject('Asignación de Turno Cirugía Ambulatoria: '. $post['fecha'] .' a las '. $post['hora'] .':'. $post['hora_min'] . ' con '. $turno->nombre_profesional);
				$this->email->message($message);
				$this->email->send();
			}

			redirect('/agenda');
		}

		$data['paciente_id'] = $paciente_id;
		$this->load->view('agenda/add', $data);
	}

	public function edit($id)
	{
		$row_agenda = $this->agenda_model->get($id);
		if(!$row_agenda) show_404();
		$this->require_turno_access($row_agenda);//admin o profesional del turno

		$post = $this->input->post(NULL, TRUE);	
		if ($post && $id)
		{
			$data = array(
				'fecha' => $post['fecha'].' '.$post['hora'].':'.$post['hora_min']
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
		// $this->is_ajax();
		$this->is_admin();
		$this->load->library('email');

		//cambiar estado agenda
		$turno = $this->agenda_model->get_turno($id);
		if(!$turno) die('no existe el turno');
		$this->agenda_model->update($id, array('estado_cita'=>3));//cancelado

		//notificar al paciente
		$data['paciente'] = $turno->nombre_paciente;
		$data['fecha'] = date('d/m', strtotime($turno->fecha));
		$message = $this->load->view('email/turno_cancelado', $data, true);
		$this->email->from(get_site_config('admin_email'), get_site_config('site_title'));
		$this->email->to($turno->email_paciente);
		$this->email->subject(lang('Cancelación turno'));
		$this->email->message($message);
		$this->email->send();

		$this->session->set_flashdata('alert', array(
			'msg' => lang('La agenda se actualizó correctamente'),
			'error' => FALSE
		));	
		redirect('/agenda', 'refresh');
	}

	public function anunciar($id)
	{
		$this->is_admin();
		
		//cambiar estado agenda
		$turno = $this->agenda_model->get_turno($id);
		if(!$turno) die('no existe el turno');
		$this->agenda_model->update($id, array('estado_cita'=>5));//en espera

		//refrescar la página
		redirect('/dashboard', 'refresh');
	}

	public function completar($id)
	{
		//cambiar estado agenda
		$turno = $this->agenda_model->get_turno($id);
		if(!$turno) die('no existe el turno');
		$this->require_turno_access($turno);//admin o profesional del turno
		$this->agenda_model->update($id, array('estado_cita'=>2));//completado

		//refrescar la página
		redirect('/dashboard', 'refresh');
	}

}