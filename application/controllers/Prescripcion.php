<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('Front_Controller.php');

class Prescripcion extends Front_Controller {

	function __construct()
	{
		parent::__construct();
		$this->need_login();

		$this->load->model('prescripcion_model');
		$this->load->model('config_model');
		$this->load->library('upload');

		$this->medicamentos_list = $this->config_model->get_medicamentos();
		// $this->include_js = 'prescripcion.js';
	}

	public function index()
	{
		$data['rows'] = $this->prescripcion_model->get_prescripciones($this->login->id);
		$this->load->view('prescripcion/index', $data);
	}

	public function add($paciente_id)
	{
		$this->require_staff();//sólo el staff emite prescripciones
		$this->require_paciente_access($paciente_id);//y a pacientes propios
		$post = $this->input->post(null, true);
		if($post)
		{
			$data = [
				'paciente_id' => $paciente_id,
				'profesional_id' => $this->login->id,
				'fecha' => $post['fecha'],
				'comentarios' => $post['comentarios'],
				'created_by' => $this->login->id,
				'created_at' => date('Y-m-d H:i:s')
			];
			
			if($this->is_admin) {
				$data['profesional_id'] = $post['profesional_id'];
			}

			//procesar archivo receta
			if($this->upload->do_upload('userfile'))
			{
				$uploadData = $this->upload->data();
				$data['file'] = $uploadData['file_name'];
			}

			//procesar archivo indicaciones
			if($this->upload->do_upload('userfile2'))
			{
				$uploadData = $this->upload->data();
				$data['file2'] = $uploadData['file_name'];
			}

			$this->prescripcion_model->insert($data);
			
			//redirect to view
			$data['rows'] = $this->prescripcion_model->get_prescripciones($paciente_id);
			$data['paciente_id'] = $paciente_id;
			echo $this->load->view('paciente/index_prescripciones', $data, true);
			return;
		}

		$this->load->view('prescripcion/add');
	}

	public function edit($id)
	{
		$this->require_staff();
		$row = $this->prescripcion_model->get($id);
		if(!$row) show_404();
		//sólo el admin o el profesional que la emitió
		if(!$this->is_admin && (int)$row->profesional_id !== (int)$this->login->id) $this->deny();

		$post = $this->input->post(null, true);
		if($post)
		{
			$data = [
				'fecha' => $post['fecha'],
				'comentarios' => $post['comentarios']
			];
			
			if($this->is_admin) {
				$data['profesional_id'] = $post['profesional_id'];
			}

			//procesar archivo receta
			if($this->upload->do_upload('userfile'))
			{
				$uploadData = $this->upload->data();
				$data['file'] = $uploadData['file_name'];
			}

			//procesar archivo indicaciones
			if($this->upload->do_upload('userfile2'))
			{
				$uploadData = $this->upload->data();
				$data['file2'] = $uploadData['file_name'];
			}

			$this->prescripcion_model->update($id, $data);
			
			//redirect to view
			$data['rows'] = $this->prescripcion_model->get_prescripciones($row->paciente_id);
			$data['paciente_id'] = $row->paciente_id;
			echo $this->load->view('paciente/index_prescripciones', $data, true);
			return;
		}

		$data['row'] = $row;
		$this->load->view('prescripcion/edit', $data);
	}

	public function view($id)
	{
		$prescripcion = $this->prescripcion_model->get($id);
		if(!$prescripcion) show_404();
		//el paciente sólo ve las suyas
		$this->require_paciente_access($prescripcion->paciente_id);

		$data['row'] = $this->prescripcion_model->get_prescripciones(null, $id);
		$this->load->view('prescripcion/view', $data);
	}

	public function delete($id)
	{
		$this->is_admin();
		$this->is_ajax();
		$this->prescripcion_model->delete($id);
		echo 'ok';
	}

}