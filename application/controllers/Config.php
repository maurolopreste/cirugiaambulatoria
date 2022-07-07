<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('Front_Controller.php');

class Config extends Front_Controller {

	function __construct()
	{
		parent::__construct();
		$this->need_login();
		$this->is_admin();

		$this->load->model('config_model');
	}

	public function index()
	{
		$post = $this->input->post(NULL, TRUE);	
		if ($post)
		{
			// print_r($post);die;
			foreach($post as $k=>$v)
			{
				$data[] = array(
					'meta_key' => $k,
					'meta_value' => $v
				);
			}

			//save logo
			$this->load->library('upload');
			if($this->upload->do_upload('userfile'))
			{
				$uploadData = $this->upload->data();
				$data[] = array(
					'meta_key' => 'logo',
					'meta_value' => $uploadData['file_name']
				);
			}

			$this->db->update_batch('config', $data, 'meta_key');

			//unset session
			$this->session->unset_userdata('config');

			$this->session->set_flashdata('alert', array(
				'msg' => lang('Los cambios se guardaron correctamente'),
				'error' => FALSE
			));
			redirect('/config');
		}

		$data['row'] = $this->config_model->get_userdata();
		$this->load->view('config/index', $data);
	}

	public function medicamento($id=false)
	{
		$post = $this->input->post(NULL, TRUE);	
		if ($post)
		{
			if($id)
			{
				$data = array(
					'nombre' => $post['nombre']
				);
				$this->db->where('id', $id);
				$this->db->update('medicamento', $data);
			}
			else {
				$data = array(
					'nombre' => $post['nombre']
				);
				$this->db->insert('medicamento', $data);

				$this->session->set_flashdata('alert', array(
					'msg' => lang('Los cambios se guardaron correctamente'),
					'error' => FALSE
				));
				redirect('/config/medicamento');
			}
		}

		$data['rows'] = $this->config_model->get_medicamentos();
		$this->load->view('config/medicamento', $data);
	}

	public function add_medicamento()
	{
		$this->load->view('config/medicamento_add');
	}

	public function delete_medicamento($id)
	{
		$this->is_ajax();
		$this->db->delete('medicamento', array('id' => $id));
		echo 'ok';
	}

	public function tipo_cobertura($id=false)
	{
		$post = $this->input->post(NULL, TRUE);	
		if ($post)
		{
			if($id)
			{
				$data = array(
					'nombre' => $post['nombre']
				);
				$this->db->where('id', $id);
				$this->db->update('tipo_cobertura', $data);
			}
			else {
				$data = array(
					'nombre' => $post['nombre']
				);
				$this->db->insert('tipo_cobertura', $data);

				$this->session->set_flashdata('alert', array(
					'msg' => lang('Los cambios se guardaron correctamente'),
					'error' => FALSE
				));
				redirect('/config/tipo_cobertura');
			}
		}

		$data['rows'] = $this->config_model->get_tipo_coberturas();
		$this->load->view('config/tipo_cobertura', $data);
	}

	public function add_tipo_cobertura()
	{
		$this->load->view('config/tipo_cobertura_add');
	}

	public function delete_tipo_cobertura($id)
	{
		$this->is_ajax();
		$this->db->delete('tipo_cobertura', array('id' => $id));
		echo 'ok';
	}

	public function valor_consulta($id=false)
	{
		$post = $this->input->post(NULL, TRUE);	
		if ($post)
		{
			if($id)
			{
				$data = array(
					'nombre' => $post['nombre'],
					'valor' => $post['valor']
				);
				$this->db->where('id', $id);
				$this->db->update('valor_consulta', $data);
			}
			else {
				$data = array(
					'nombre' => $post['nombre'],
					'valor' => $post['valor']
				);
				$this->db->insert('valor_consulta', $data);

				$this->session->set_flashdata('alert', array(
					'msg' => lang('Los cambios se guardaron correctamente'),
					'error' => FALSE
				));
				redirect('/config/valor_consulta');
			}
		}

		$data['rows'] = $this->config_model->get_valores();
		$this->load->view('config/valor_consulta', $data);
	}

	public function add_valor_consulta()
	{
		$this->load->view('config/valor_consulta_add');
	}

	public function delete_valor_consulta($id)
	{
		$this->is_ajax();
		$this->db->delete('valor_consulta', array('id' => $id));
		echo 'ok';
	}

}