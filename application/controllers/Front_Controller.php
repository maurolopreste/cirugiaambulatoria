<?php

class Front_Controller extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('user_model');
		$this->load->library('pagination');
		$this->load->library('email');
		
		//cargar variables de lenguaje
		$this->lang->load('es', 'spanish');

		$this->login = $this->session->userdata('login');
		$this->pacientes_list = $this->user_model->get_by_group_id(3);
		$this->profesionales_list = $this->user_model->get_by_group_id(2);
		$this->provincias_list = $this->user_model->get_provincias();
		$this->per_page = 20;

		//permisos
		$this->is_admin = ($this->login && $this->login->group_id==1);
		$this->is_profesional = ($this->login && $this->login->group_id==2);
		$this->is_paciente = ($this->login && $this->login->group_id==3);

		$this->is_local = strpos($_SERVER['HTTP_HOST'], "local")!==false;

		//si el paciente ha firmado los tyc
		$this->check_tyc();
	}

	private function check_tyc()
	{
		if($this->uri->uri_string()=='dashboard/tyc') {
			return;
		}

		if($this->is_paciente && !$this->login->tyc)
		{
			redirect('dashboard/tyc');
		}
	}

	public function need_login()
	{
		if($this->session->userdata('login') === NULL)
		{
			$this->session->set_flashdata('alert', array(
				'msg' => lang('No has iniciado sesión'),
				'error' => TRUE
			));
			redirect('/login?referrer='.$this->uri->uri_string());
		}
	}

	public function is_admin()
	{
		if(!$this->is_admin) {
			$this->session->set_flashdata('alert', array(
				'msg' => lang('No tienes los permisos necesarios'),
				'error' => TRUE
			));
			redirect('/login');
		}
	}

	public function is_ajax()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
	}

	public function set_pagination($config)
	{
		$config['per_page'] = $this->per_page;
		$config['uri_segment'] = 3;

		// custom paging configuration
		$config['num_links'] = 4;
		$config['use_page_numbers'] = TRUE;
		$config['attributes'] = array('class' => 'page-link');

		$config['full_tag_open'] = '<ul class="pagination justify-content-end font-weight-semi-bold mb-0">';
		$config['full_tag_close'] = '</ul>';

		$config['first_link'] = 'Primera';
		$config['first_tag_open'] = '<li class="page-item">';
		$config['first_tag_close'] = '</li>';

		$config['last_link'] = 'Última';
		$config['last_tag_open'] = '<li class="page-item">';
		$config['last_tag_close'] = '</li>';

		$config['next_link'] = '<i class="gd-angle-right icon-text icon-text-xs d-inline-block"></i>';
		$config['next_tag_open'] = '<li class="page-item">';
		$config['next_tag_close'] = '</li>';

		$config['prev_link'] = '<i class="gd-angle-left icon-text icon-text-xs d-inline-block"></i>';
		$config['prev_tag_open'] = '<li class="page-item">';
		$config['prev_tag_close'] = '</li>';

		$config['cur_tag_open'] = '<li class="page-item"><a href="#" class="page-link active">';
		$config['cur_tag_close'] = '</a></li>';

		$config['num_tag_open'] = '<li class="page-item">';
		$config['num_tag_close'] = '</li>';

		$this->pagination->initialize($config);

		// build paging links
		return $this->pagination->create_links();
	}

}