<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('Front_Controller.php');

class Pagos extends Front_Controller {

	function __construct()
	{
		parent::__construct();
		$this->need_login();
		$this->is_admin();

		$this->load->model('agenda_model');
		$this->load->model('config_model');
		$this->load->library('upload');

		$this->valor_consulta_list = $this->config_model->get_valor_consulta();
		$this->metodopago_list = $this->config_model->get_metodos_pago();
		// $this->include_js = 'pagos.js';
	}

	public function index()
	{
		$search = $this->input->get('q', true);
		$data['rows'] = $this->agenda_model->get_all($search);
		$this->load->view('pagos/index', $data);
	}

	public function edit($id)
	{
		$row_pago = $this->agenda_model->get_turno($id);

		$post = $this->input->post(NULL, TRUE);	
		if ($post)
		{
			$data = array(
				'valor_consulta' => $post['valor_consulta'],
				'payment_method' => $post['payment_method'],
				'payment_status' => $post['payment_status'],
				'comentarios' => $post['comentarios']
			);

			//adjuntar archivo
			if($this->upload->do_upload('userfile'))
			{
				$uploadData = $this->upload->data();
				$data['file'] = $uploadData['file_name'];
			}

			$this->agenda_model->update($id, $data);

			$this->session->set_flashdata('alert', array(
				'msg' => lang('El pago se actualizó correctamente'),
				'error' => FALSE
			));
			redirect('/pagos');
		}

		$data['row'] = $row_pago;
		$this->load->view('pagos/edit', $data);
	}

}