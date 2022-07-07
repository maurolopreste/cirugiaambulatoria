<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('Front_Controller.php');

class Dashboard extends Front_Controller {

	function __construct()
	{
		parent::__construct();
		$this->need_login();

		$this->load->model('user_model');
		$this->load->model('agenda_model');
		$this->load->model('config_model');
		
		$this->tipo_cobertura_list = $this->config_model->get_tipo_coberturas();
		$this->profesionales_list = $this->user_model->get_by_group_id(2);//profesionales

		$this->include_js = 'dashboard.js';
	}

	public function index()
	{
		$view = 'dashboard';
		$data = array();

		if($this->is_paciente) {
			$view = 'paciente/dashboard';
			$data['user'] = $this->user_model->get($this->login->id);
			$data['citas'] = $this->agenda_model->get_by_paciente($this->login->id);
		}

		if($this->is_profesional) {
			$view = 'profesional/dashboard';
			$data['citas'] = $this->agenda_model->get_by_profesional($this->login->id);
		}

		if($this->is_admin) {
			$data['count_pacientes'] = $this->user_model->count_by_attributes(array('group_id'=>3));
			$data['count_profesionales'] = $this->user_model->count_by_attributes(array('group_id'=>2));
			$data['turnos'] = $this->agenda_model->get_turnos_hoy();
		}
		
		$this->load->view($view, $data);
	}

	public function get_valores_consulta($profesional_id)
	{
		$this->is_ajax();

		$result = array();
		$valor_consultas = $this->user_model->get_valor_profesional($profesional_id);
		
		foreach($valor_consultas as $v)
		{
			$result[] = '<option value="'. $v->valor_consulta .'">'. $v->nombre .' ($ '. $v->valor .')</option>';
		}

		echo implode('', $result);
	}

	public function get_fechas_disponibles($profesional_id)
	{
		$this->is_ajax();

		$result = array();
		$fechas_comprometidas = $this->user_model->get_fechas_comprometidas($profesional_id);

		if($fechas_comprometidas)
		{
			foreach ($fechas_comprometidas as $fecha)
			{
				//obtener diferencia de fechas en días
				$diff_dias = abs(round((strtotime($fecha->fecha_desde) - strtotime($fecha->fecha_hasta)) / 60 / 60 / 24));

				//agregar fechas al resultado
				for ($i=0; $i <= $diff_dias; $i++) {
					$nueva_fecha = date('Y-m-d', strtotime($fecha->fecha_desde.' +'.$i.' day'));
					if(!in_array($nueva_fecha, $result)) {
						$result[] = $nueva_fecha;
					}
				}
			}
		}

		if($result) echo "[\"".implode('","', $result)."\"]";
	}

	public function get_dias_habilitados($profesional_id)
	{
		$this->is_ajax();

		$dias_habilitados = $this->user_model->get_dias_habilitados($profesional_id);
		$daysOfWeekDisabled = "";

		if($dias_habilitados)
		{
			foreach ($dias_habilitados as $row)
			{
				$daysOfWeekDisabled = $daysOfWeekDisabled . $row->daysOfWeekDisabled;
			}
			$daysOfWeekDisabled = str_split($daysOfWeekDisabled);
			$daysOfWeekDisabled = array_unique($daysOfWeekDisabled, SORT_NUMERIC);
			$result = implode(',', $daysOfWeekDisabled);
		}

		if($result) echo "[".$result."]";
	}

	public function get_horarios_disponibles()
	{
		$this->is_ajax();

		$result = array();
		$post = $this->input->post(null, true);
		$search_turnos = $this->agenda_model->search_turnos($post['fecha'], $post['profesional_id']);
		$dias_habilitados = $this->user_model->get_dias_habilitados($post['profesional_id']);
		$horario_desde = $dias_habilitados ? str_replace(':00', '', $dias_habilitados[0]->hora_desde) : 9;
		$horario_hasta = $dias_habilitados ? str_replace(':00', '', $dias_habilitados[0]->hora_hasta) : 20;

		for ($i=$horario_desde; $i <= $horario_hasta; $i++)
		{
			if($search_turnos && in_array($i, $search_turnos)) {
				$result[] = '<option value="" disabled>'. $i .':00</option>';
			}
			else {
				$result[] = '<option value="'. $i .':00">'. $i .':00</option>';
			}
		}

		echo implode('', $result);
	}

	public function agendar_cita()
	{
		$this->load->model('agenda_model');
		$this->load->library('mercadopago');
		$this->load->library('email');

		$post = $this->input->post(NULL, TRUE);	
		if ($post)
		{
			$insert = array(
				'paciente_id' => $this->login->id,
				'profesional_id' => $post['profesional_id'],
				'valor_consulta' => $post['valor_consulta'],
				'fecha' => $post['fecha'] .' '. $post['hora'],
				'payment_method' => 1,
				// 'payment_status' => 0,
				'created_at' => date('Y-m-d H:i:s'),
				'created_by' => $this->login->id
			);
			$agenda_id = $this->agenda_model->insert($insert);

			//notificar al admin
			$data['agenda'] = $this->agenda_model->get($agenda_id);
			$data['paciente'] = $this->user_model->get($this->login->id);
			$message = $this->load->view('email/agenda_cita', $data, true);
			$this->email->from(get_site_config('admin_email'), get_site_config('site_title'));
			$this->email->to(get_site_config('admin_email'), get_site_config('site_title'));
			$this->email->subject(lang('Nueva cita online'));
			$this->email->message($message);
			$this->email->send();

			//items
			$tipo_consulta = $this->config_model->get_valores($post['valor_consulta']);
			$products_mp = array(
				array(
					"id" => 1,
					"title" => "Cirugía Ambulatoria - ". $tipo_consulta->nombre,
					"quantity" => 1,
					"currency_id" => "ARS",
					"unit_price" => $tipo_consulta->valor
				)
			);

			//options
			$preference_data = array(
				"payer_email" => $this->login->email,
				"external_reference" => $agenda_id,
				"back_urls" => array (
					"success"=> base_url('/payment/success'),
					"failure"=> base_url('/payment/failure')
				),
				"items" => $products_mp,
				"auto_return"=>"approved"
			);
			$preference = $this->mercadopago->create_preference($preference_data);

			if(isset($preference->init_point))
			{
				//link gateway MP
				$data['MP_link'] = $preference->init_point;
				$this->load->view('payment/mercadopago', $data);
			}
			else {
				redirect('/payment/failure');
			}
		}
	}

	public function tyc()
	{
		$post = $this->input->post(NULL, TRUE);	
		if ($post)
		{
			$data = array(
				'tyc' => 1
			);
			$this->user_model->update($this->login->id, $data);
			
			$this->login->tyc = 1;
			$this->session->set_userdata('login', $this->login);
			
			redirect('/dashboard', 'refresh');
		}

		$this->load->view('paciente/tyc');
	}

}
