<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('Front_Controller.php');

class Payment extends Front_Controller {

	function __construct()
	{
		parent::__construct();
		$this->need_login();

		$this->load->model('agenda_model');
	}

	public function success()
	{
		$payment_data = $this->input->get(null, true);
		$agenda = $this->agenda_model->get($payment_data['external_reference']);
		if(!$agenda) show_404();
		$this->require_turno_access($agenda, TRUE);
		$data['agenda'] = $agenda;
		$this->load->view('payment/success', $data);
	}

	public function failure()
	{
		$this->load->view('payment/failure');
	}

}