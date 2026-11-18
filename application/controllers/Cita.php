<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('Front_Controller.php');

class Cita extends Front_Controller {

	function __construct()
	{
		parent::__construct();
		$this->need_login();

		$this->load->model('agenda_model');
		// $this->include_js = 'cita.js';
	}

	public function index()
	{
		$data['rows'] = $this->agenda_model->get_by_paciente($this->login->id);
		$this->load->view('cita/index', $data);
	}

	public function edit($id)
	{
		//el paciente no puede modificar estado ni observaciones de un turno
		$this->require_staff();
		$this->require_turno_access($this->agenda_model->get($id));

		$post = $this->input->post(NULL, TRUE);	
		if ($post && $id)
		{
			$data = array(
				'estado' => $post['estado'],
				'observacion_profesional' => $post['observacion_profesional']
			);

			$this->agenda_model->update($id, $data);

			$this->session->set_flashdata('alert', array(
				'msg' => lang('La agenda se actualizó correctamente'),
				'error' => FALSE
			));
			redirect('/agenda');
		}

		$data['row'] = $this->agenda_model->get_turno($id);
		$this->load->view('cita/edit', $data);
	}

	public function generar_link_pago($cita_id)
	{
		$this->load->model('config_model');
		$this->load->library('mercadopago');

		$cita = $this->agenda_model->get($cita_id);
		//admin, profesional del turno o el propio paciente
		$this->require_turno_access($cita, TRUE);

		//items
		$tipo_consulta = $this->config_model->get_valor_consulta($cita->valor_consulta);
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
			"external_reference" => $cita_id,
			"back_urls" => array (
				"success"=> site_url('/payment/success'),
				"failure"=> site_url('/payment/failure')
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

	public function delete($id)
	{
		$this->is_admin();
		$this->is_ajax();
		$this->agenda_model->delete($id);
		echo 'ok';
	}

}