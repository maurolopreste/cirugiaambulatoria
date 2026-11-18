<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('Front_Controller.php');

class Paciente extends Front_Controller {

	function __construct()
	{
		parent::__construct();
		$this->need_login();
		$this->require_staff();//los pacientes no acceden a este módulo

		$this->load->model('user_model');
		$this->load->model('historiaclinica_model');
		$this->load->model('evolutivo_model');
		$this->load->model('agenda_model');
		$this->load->model('config_model');
		$this->load->library('upload');

		$this->art_list = $this->user_model->get_art_list();
		$this->obra_social_list = $this->config_model->get_obras_sociales();
		$this->medicamentos_list = $this->config_model->get_medicamentos();

		$this->include_js = 'paciente.js';
	}

	public function index()
	{
		$search = $this->input->get('q', true);

		if($this->is_admin) {
			$data['rows'] = $this->user_model->get_by_group_id(3, $search);
			$this->load->view('paciente/index', $data);
		}
		else {
			// $data['rows'] = $this->user_model->get_by_profesional($this->login->id, $search);
			$data['rows'] = $this->user_model->get_by_group_id(3, $search);
			$this->load->view('profesional/pacientes', $data);
		}
	}

	public function index_historiaclinica($paciente_id)
	{
		$this->is_ajax();

		$historiaclinica = $this->historiaclinica_model->get_by_paciente($paciente_id);
		$informes = $this->evolutivo_model->get_by_paciente($paciente_id);

		$data['rows'] = $informes ? array_merge($historiaclinica, $informes) : $historiaclinica;
		$data['paciente_id'] = $paciente_id;

		$this->load->view('paciente/index_historiaclinica', $data);
	}

	public function add_historiaclinica($paciente_id)
	{
		$post = $this->input->post(NULL, TRUE);	
		if ($post)
		{
			$data = array(
				'paciente_id' => $paciente_id,
				'fecha_accidente' => $post['fecha_accidente'],
				'mecanismo' => $post['mecanismo'],
				'antecedentes' => $post['antecedentes'],
				'examen_fisico' => $post['examen_fisico'],
				'antecedentes_patologicos' => $post['antecedentes_patologicos'],
				'estudios_solicitados' => $post['estudios_solicitados'],
				'tratamiento' => $post['tratamiento'],
				'created_at' => date('Y-m-d H:i:s')
			);

			if($this->is_admin && !empty($post['profesional_id'])) {
				$data['profesional_id'] = $post['profesional_id'];
			}

			//adjuntar archivo
			if($this->upload->do_upload('userfile'))
			{
				$uploadData = $this->upload->data();
				$data['file'] = $uploadData['file_name'];
			}

			$this->historiaclinica_model->insert($data);

			$this->session->set_flashdata('alert', array(
				'msg' => lang('La historia clínica se agregó correctamente'),
				'error' => FALSE
			));
			redirect('/paciente/edit/'.$paciente_id);
		}

		$this->load->view('paciente/add_historiaclinica');
	}

	public function edit_historiaclinica($historiaclinica_id, $paciente_id)
	{
		$historiaclinica = $this->historiaclinica_model->get($historiaclinica_id);
		if($historiaclinica->paciente_id != $paciente_id) die('No es posible editar esta historia');

		$post = $this->input->post(NULL, TRUE);	
		if ($post)
		{
			$data = array(
				'paciente_id' => $historiaclinica->paciente_id,
				'fecha_accidente' => $post['fecha_accidente'],
				'mecanismo' => $post['mecanismo'],
				'antecedentes' => $post['antecedentes'],
				'examen_fisico' => $post['examen_fisico'],
				'antecedentes_patologicos' => $post['antecedentes_patologicos'],
				'estudios_solicitados' => $post['estudios_solicitados'],
				'tratamiento' => $post['tratamiento']
			);

			if($this->is_admin && !empty($post['profesional_id'])) {
				$data['profesional_id'] = $post['profesional_id'];
			}

			//adjuntar archivo
			if($this->upload->do_upload('userfile'))
			{
				$uploadData = $this->upload->data();
				$data['file'] = $uploadData['file_name'];
			}

			$this->historiaclinica_model->update($historiaclinica_id, $data);

			$this->session->set_flashdata('alert', array(
				'msg' => lang('La historia clínica se agregó correctamente'),
				'error' => FALSE
			));
			redirect('/paciente/edit/'.$historiaclinica->paciente_id);
		}

		$data['row'] = $historiaclinica;
		$this->load->view('paciente/edit_historiaclinica', $data);
	}

	public function send_historiaclinica($historiaclinica_id)
	{
		$historiaclinica = $this->historiaclinica_model->get($historiaclinica_id);
		if(!$historiaclinica) show_404();
		$paciente = $this->user_model->get($historiaclinica->paciente_id);

		$post = $this->input->post(NULL, TRUE);	
		if ($post)
		{
			//enviar historia clínica por email
			$email = $post['email'];
			$data['nombre'] = $post['nombre'];
			$data['historiaclinica'] = $historiaclinica;
			$data['paciente'] = $paciente;
			$message = $this->load->view('email/historia_clinica', $data, true);
			$this->email->from(get_site_config('admin_email'), get_site_config('site_title'));
			$this->email->to($email);
			$this->email->subject('Historia Clínica - '.$paciente->last_name.', '.$paciente->first_name);
			$this->email->message($message);
			$this->email->send();

			$this->session->set_flashdata('alert', array(
				'msg' => lang('El email se envió correctamente'),
				'error' => FALSE
			));
			redirect('/paciente/edit/'.$historiaclinica->paciente_id);
		}

		$this->load->view('paciente/send_historiaclinica');
	}

	public function delete_historiaclinica($historiaclinica_id, $paciente_id)
	{
		$this->is_admin();
		$this->is_ajax();

		$historiaclinica = $this->historiaclinica_model->get($historiaclinica_id);
		if($historiaclinica->paciente_id != $paciente_id) die('No es posible eliminar esta historia');

		$this->historiaclinica_model->delete($historiaclinica_id);
		echo 'ok';
	}

	public function add_evolutivo($paciente_id)
	{
		$post = $this->input->post(NULL, TRUE);	
		if ($post)
		{
			$data = array(
				'paciente_id' => $paciente_id,
				'profesional_id' => $this->login->id,
				'fecha' => $post['fecha'],
				'informe' => $post['informe'],
				'proxima_visita' => $post['proxima_visita'],
				'alta' => $post['alta'],
				// 'facturado' => $post['facturado'],
				'created_at' => date('Y-m-d H:i:s')
			);

			if($this->is_admin && !empty($post['profesional_id'])) {
				$data['profesional_id'] = $post['profesional_id'];
			}

			//adjuntar archivo
			if($this->upload->do_upload('userfile'))
			{
				$uploadData = $this->upload->data();
				$data['file'] = $uploadData['file_name'];
			}

			$this->evolutivo_model->insert($data);

			$this->session->set_flashdata('alert', array(
				'msg' => lang('La historia clínica se agregó correctamente'),
				'error' => FALSE
			));
			redirect('/paciente/edit/'.$paciente_id);
		}

		$data['row'] = $this->user_model->get($paciente_id);
		$this->load->view('paciente/add_evolutivo', $data);
	}

	public function edit_evolutivo($evolutivo_id, $paciente_id)
	{
		$evolutivo = $this->evolutivo_model->get($evolutivo_id);
		if($evolutivo->paciente_id != $paciente_id) die('No es posible editar este informe');

		$post = $this->input->post(NULL, TRUE);	
		if ($post)
		{
			$data = array(
				'fecha' => $post['fecha'],
				'informe' => $post['informe'],
				'proxima_visita' => $post['proxima_visita'],
				'alta' => $post['alta'],
				'facturado' => $post['facturado']
			);

			if($this->is_admin && !empty($post['profesional_id'])) {
				$data['profesional_id'] = $post['profesional_id'];
			}

			//adjuntar archivo
			if($this->upload->do_upload('userfile'))
			{
				$uploadData = $this->upload->data();
				$data['file'] = $uploadData['file_name'];
			}

			$this->evolutivo_model->update($evolutivo_id, $data);

			$this->session->set_flashdata('alert', array(
				'msg' => lang('El informe se modificó correctamente'),
				'error' => FALSE
			));
			redirect('/paciente/edit/'.$paciente_id);
		}

		$data['row'] = $this->evolutivo_model->get($evolutivo_id);
		$this->load->view('paciente/edit_evolutivo', $data);
	}

	public function delete_evolutivo($evolutivo_id, $paciente_id)
	{
		$this->is_ajax();

		$evolutivo = $this->evolutivo_model->get($evolutivo_id);
		if(!$evolutivo || $evolutivo->paciente_id != $paciente_id) die('No es posible eliminar este informe');

		//sólo el admin o el profesional autor del informe
		if(!$this->is_admin && (int)$evolutivo->profesional_id !== (int)$this->login->id) $this->deny();

		$this->evolutivo_model->delete($evolutivo_id);
		echo 'ok';
	}

	public function index_citas($paciente_id)
	{
		$this->is_ajax();

		$data['rows'] = $this->agenda_model->get_by_paciente($paciente_id);
		$data['paciente_id'] = $paciente_id;

		$this->load->view('paciente/index_citas', $data);
	}

	public function add()
	{
		$this->is_admin();

		$post = $this->input->post(NULL, TRUE);	
		if ($post)
		{
			//print_r($post);die;
			$data = array(
				'group_id' => 3,//pacientes
				'first_name' => $post['first_name'],
				'last_name' => $post['last_name'],
				'dni' => $post['dni'],
				// 'direccion' => $post['direccion'],
				'telefono' => $post['telefono'],
				'email' => $post['email'],
				'created_at' => date('Y-m-d H:i:s')
			);

			if($this->is_admin) {
				//$data['profesional_id'] = $post['profesional_id'];
			}

			$paciente_id = $this->user_model->insert($data);

			//guardar datos formingreso
			if(!empty($post['art_id']) && !empty($post['nro_siniestro']))
			{
				$formingreso = $this->user_model->formingreso($paciente_id);
				if(!$formingreso)
				{
					$this->db->insert('formingreso', ['user_id'=>$paciente_id, 'created_at'=>date('Y-m-d H:i:s')]);
				}
				$data = array(
					'art_id' => $post['art_id'],
					'nro_siniestro' => $post['nro_siniestro']
				);
				$this->user_model->formingreso($paciente_id, $data);
			}

			//enviar email para confirmar cuenta
			// $email = $post['email'];
			// $rp_token = $this->user_model->generate_token($email);
			// $data['url'] = site_url('/login/confirmar_email/'. $rp_token);
			// $data['nombre'] = $post['first_name'];
			// $message = $this->load->view('email/confirmar_email', $data, true);
			// $this->email->from(get_site_config('admin_email'), get_site_config('site_title'));
			// $this->email->to($email);
			// $this->email->subject(lang('Confirmar email'));
			// $this->email->message($message);
			// $this->email->send();

			$this->session->set_flashdata('alert', array(
				'msg' => lang('El paciente se agregó correctamente'),
				'error' => FALSE
			));
			redirect('/paciente/edit/'.$paciente_id);
		}

		$this->load->view('paciente/add');
	}

	public function edit($id)
	{
		$paciente = $this->user_model->get($id);

		// if(!$this->is_admin && $paciente->profesional_id != $this->login->id)
		// {
		// 	$this->session->set_flashdata('alert', array(
		// 		'msg' => lang('No tienes permisos para editar este paciente'),
		// 		'error' => TRUE
		// 	));
		// 	redirect('/paciente');
		// }

		$formingreso = $this->user_model->formingreso($id);
		if(!$formingreso)
		{
			$this->db->insert('formingreso', ['user_id'=>$id, 'created_at'=>date('Y-m-d H:i:s')]);
			$formingreso = $this->user_model->formingreso($id);
		}
		$paciente->art_id = $formingreso->art_id;
		$paciente->nro_siniestro = $formingreso->nro_siniestro;
		
		$post = $this->input->post(NULL, TRUE);
		if ($post)
		{
			$data = array(
				'first_name' => $post['first_name'],
				'last_name' => $post['last_name'],
				'dni' => $post['dni'],
				// 'direccion' => $post['direccion'],
				'telefono' => $post['telefono'],
				'email' => $post['email'],
				'obra_social' => $post['obra_social'],
				'nro_afiliado' => $post['nro_afiliado']
			);

			if(!empty($post['password']))
			{
				$data['password'] = password_hash($post['password'], PASSWORD_DEFAULT);
			}

			if($this->is_admin)
			{
				// $data['profesional_id'] = $post['profesional_id'];
			}

			$this->user_model->update($id, $data);

			//guardar datos formingreso
			$data = array(
				'art_id' => $post['art_id'],
				'nro_siniestro' => $post['nro_siniestro']
			);
			$this->user_model->formingreso($id, $data);

			$this->session->set_flashdata('alert', array(
				'msg' => lang('Los cambios se guardaron correctamente'),
				'error' => FALSE
			));
			redirect('/paciente/edit/'.$id);
		}

		$data['row'] = $paciente;
		$this->load->view('paciente/edit', $data);
	}

	public function formingreso($id)
	{
		$formingreso = $this->user_model->formingreso($id);
		if(!$formingreso)
		{
			$this->db->insert('formingreso', ['user_id'=>$id, 'created_at'=>date('Y-m-d H:i:s')]);
			$formingreso = $this->user_model->formingreso($id);
		}

		$post = $this->input->post(NULL, TRUE);	
		if ($post)
		{
			// print_r($post);die;
			$data = array(
				'first_name' => $post['first_name'],
				'last_name' => $post['last_name'],
				'dni' => $post['dni'],
				'telefono' => $post['tel_movil'],
				'email' => $post['email'],
				'fecha_nac' => $post['fecha_nac'],
				'sexo' => $post['sexo'],
				'obra_social' => $post['obra_social'],
				'nro_afiliado' => $post['nro_afiliado']
			);
			$this->user_model->update($id, $data);

			//guardar datos form
			$data = array(
				'art_id' => $post['art_id'],
				'nro_siniestro' => $post['nro_siniestro'],
				'contingencia' => $post['contingencia'],
				'calle' => $post['calle'],
				'calle_nro' => $post['calle_nro'],
				'piso' => $post['piso'],
				'depto' => $post['depto'],
				'localidad' => $post['localidad'],
				'provincia' => $post['provincia'],
				'cp' => $post['cp'],
				'tel_fijo' => $post['tel_fijo'],
				'provincia' => $post['provincia'],
				'tel_movil' => $post['tel_movil'],
				'tel_alt' => $post['tel_alt'],
				'empleador_nombre' => $post['empleador_nombre'],
				'empleador_cuit' => $post['empleador_cuit'],
				'empleador_domicilio' => $post['empleador_domicilio'],
				'empleador_tel' => $post['empleador_tel'],
				'empleador_contacto' => $post['empleador_contacto'],
				'empleador_celular' => $post['empleador_celular'],
				'empleador_email' => $post['empleador_email'],
				'prestador_nombre' => $post['prestador_nombre'],
				'prestador_cuit' => $post['prestador_cuit'],
				'prestador_calle' => $post['prestador_calle'],
				'prestador_calle_nro' => $post['prestador_calle_nro'],
				'prestador_piso' => $post['prestador_piso'],
				'prestador_depto' => $post['prestador_depto'],
				'prestador_localidad' => $post['prestador_localidad'],
				'prestador_provincia' => $post['prestador_provincia'],
				'prestador_cp' => $post['prestador_cp'],
				'prestador_tel' => $post['prestador_tel'],
				'prestador_fax' => $post['prestador_fax'],
				'prestador_email' => $post['prestador_email'],
				'motivo_consulta' => $post['motivo_consulta'],
				'fecha_accidente' => $post['fecha_accidente'] .' '. $post['hora_accidente'],
				'fecha_inasistencia' => $post['fecha_inasistencia'] .' '. $post['hora_inasistencia'],
				'fecha_primera_atencion' => $post['fecha_primera_atencion'] .' '. $post['hora_primera_atencion'],
				'diagnostico' => $post['diagnostico'],
				'indicaciones' => $post['indicaciones'],
				'baja_laboral' => $post['baja_laboral'],
				'fecha_probable_alta' => $post['fecha_probable_alta'],
				'fecha_proxima_revision' => $post['fecha_proxima_revision'] .' '. $post['hora_proxima_revision'],
				'fecha_retorno' => $post['fecha_retorno']
			);
			$this->user_model->formingreso($id, $data);

			$this->session->set_flashdata('alert', array(
				'msg' => lang('Los cambios se guardaron correctamente'),
				'error' => FALSE
			));
			redirect('/paciente/edit/'.$id);
		}

		$row = $this->user_model->get($id);
		
		$data['row'] = $row;
		$data['form'] = $this->user_model->formingreso($id);
		$data['page_title'] = "Formulario de Ingreso - ".$row->first_name." ".$row->last_name;

		$this->load->view('paciente/edit_form_ingreso', $data);
	}

	public function formalta($id)
	{
		$formalta = $this->user_model->formalta($id);
		if(!$formalta)
		{
			$this->db->insert('formalta', ['user_id'=>$id, 'created_at'=>date('Y-m-d H:i:s')]);
			$formalta = $this->user_model->formalta($id);
		}

		$post = $this->input->post(NULL, TRUE);	
		if ($post)
		{
			// print_r($post);die;
			$data = array(
				'first_name' => $post['first_name'],
				'last_name' => $post['last_name'],
				'dni' => $post['dni'],
				'telefono' => $post['tel_movil'],
				'email' => $post['email'],
				'fecha_nac' => $post['fecha_nac'],
				'sexo' => $post['sexo']
			);
			$this->user_model->update($id, $data);

			//guardar datos form
			$data = array(
				'art_id' => $post['art_id'],
				'nro_siniestro' => $post['nro_siniestro'],
				'calle' => $post['calle'],
				'calle_nro' => $post['calle_nro'],
				'piso' => $post['piso'],
				'depto' => $post['depto'],
				'localidad' => $post['localidad'],
				'provincia' => $post['provincia'],
				'cp' => $post['cp'],
				'tel_fijo' => $post['tel_fijo'],
				'tel_movil' => $post['tel_movil'],
				'empleador_nombre' => $post['empleador_nombre'],
				'empleador_cuit' => $post['empleador_cuit'],
				'prestador_nombre' => $post['prestador_nombre'],
				'prestador_cuit' => $post['prestador_cuit'],
				'prestador_calle' => $post['prestador_calle'],
				'prestador_calle_nro' => $post['prestador_calle_nro'],
				'prestador_piso' => $post['prestador_piso'],
				'prestador_depto' => $post['prestador_depto'],
				'prestador_localidad' => $post['prestador_localidad'],
				'prestador_provincia' => $post['prestador_provincia'],
				'prestador_cp' => $post['prestador_cp'],
				'prestador_tel' => $post['prestador_tel'],
				'prestador_fax' => $post['prestador_fax'],
				'prestador_email' => $post['prestador_email'],
				'fecha_accidente' => $post['fecha_accidente'] .' '. $post['hora_accidente'],
				'fecha_inasistencia' => $post['fecha_inasistencia'] .' '. $post['hora_inasistencia'],
				'fecha_primera_atencion' => $post['fecha_primera_atencion'] .' '. $post['hora_primera_atencion'],
				'descripcion_motivo_consulta' => $post['descripcion_motivo_consulta'],
				'diagnostico' => $post['diagnostico'],
				'indicaciones' => $post['indicaciones'],
				'tratamiento_pendiente' => !empty($post['tratamiento_pendiente']) ? $post['tratamiento_pendiente'] : '',
				'tratamiento_extra' => !empty($post['tratamiento_extra']) ? $post['tratamiento_extra'] : '',
				'fecha_proxima_revision' => $post['fecha_proxima_revision'] .' '. $post['hora_proxima_revision'],
				'fecha_retorno_trabajo' => $post['fecha_retorno_trabajo'] .' '. $post['hora_retorno_trabajo'],
				'fecha_fin_tratamiento' => $post['fecha_fin_tratamiento'] .' '. $post['hora_fin_tratamiento'],
				'motivo_cese' => is_array($post['motivo_cese']) ? serialize($post['motivo_cese']) : "",
				'recalificacion_profesional' => $post['recalificacion_profesional'],
				'afeccion_inculpable' => $post['afeccion_inculpable'],
				'secuelas_incapacitantes' => $post['secuelas_incapacitantes'],
				'prestaciones_mantenimiento' => $post['prestaciones_mantenimiento']
			);
			$this->user_model->formalta($id, $data);

			$this->session->set_flashdata('alert', array(
				'msg' => lang('Los cambios se guardaron correctamente'),
				'error' => FALSE
			));
			redirect('/paciente/edit/'.$id);
		}

		$row = $this->user_model->get($id);

		$data['row'] = $row;
		$data['form'] = $formalta;
		$data['page_title'] = "Formulario de Alta - ".$row->first_name." ".$row->last_name;

		$this->load->view('paciente/edit_form_alta', $data);
	}

	public function formreingreso($id)
	{
		$formreingreso = $this->user_model->formreingreso($id);
		if(!$formreingreso)
		{
			$this->db->insert('formreingreso', ['user_id'=>$id, 'created_at'=>date('Y-m-d H:i:s')]);
			$formreingreso = $this->user_model->formreingreso($id);
		}

		$post = $this->input->post(NULL, TRUE);	
		if ($post)
		{
			// print_r($post);die;
			$data = array(
				'first_name' => $post['first_name'],
				'last_name' => $post['last_name'],
				'dni' => $post['dni'],
				'telefono' => $post['tel_movil'],
				'email' => $post['email'],
				'fecha_nac' => $post['fecha_nac'],
				'sexo' => $post['sexo']
			);
			$this->user_model->update($id, $data);

			//guardar datos form
			$data = array(
				'art_id' => $post['art_id'],
				'nro_siniestro' => $post['nro_siniestro'],
				'calle' => $post['calle'],
				'calle_nro' => $post['calle_nro'],
				'piso' => $post['piso'],
				'depto' => $post['depto'],
				'localidad' => $post['localidad'],
				'provincia' => $post['provincia'],
				'cp' => $post['cp'],
				'tel_fijo' => $post['tel_fijo'],
				'tel_movil' => $post['tel_movil'],
				'empleador_nombre' => $post['empleador_nombre'],
				'empleador_cuit' => $post['empleador_cuit'],
				'prestador_nombre' => $post['prestador_nombre'],
				'prestador_cuit' => $post['prestador_cuit'],
				'prestador_calle' => $post['prestador_calle'],
				'prestador_calle_nro' => $post['prestador_calle_nro'],
				'prestador_piso' => $post['prestador_piso'],
				'prestador_depto' => $post['prestador_depto'],
				'prestador_localidad' => $post['prestador_localidad'],
				'prestador_provincia' => $post['prestador_provincia'],
				'prestador_cp' => $post['prestador_cp'],
				'prestador_tel' => $post['prestador_tel'],
				'prestador_fax' => $post['prestador_fax'],
				'prestador_email' => $post['prestador_email'],
				'motivo_consulta' => $post['motivo_consulta'],
				'fecha_ocurrencia' => $post['fecha_ocurrencia'] .' '. $post['hora_ocurrencia'],
				'fecha_alta_medica' => $post['fecha_alta_medica'] .' '. $post['hora_alta_medica'],
				'fecha_solicitud_reingreso' => $post['fecha_solicitud_reingreso'] .' '. $post['hora_solicitud_reingreso'],
				'descripcion_motivo_consulta' => $post['descripcion_motivo_consulta'],
				'diagnostico' => $post['diagnostico'],
				'indicaciones' => $post['indicaciones'],
				'aceptacion_reingreso' => !empty($post['aceptacion_reingreso']) ? $post['aceptacion_reingreso'] : '',
				'aceptacion_reingreso_otro' => !empty($post['aceptacion_reingreso_otro']) ? $post['aceptacion_reingreso_otro'] : ''
			);
			$this->user_model->formreingreso($id, $data);

			$this->session->set_flashdata('alert', array(
				'msg' => lang('Los cambios se guardaron correctamente'),
				'error' => FALSE
			));
			redirect('/paciente/edit/'.$id);
		}

		$row = $this->user_model->get($id);

		$data['row'] = $row;
		$data['form'] = $formreingreso;
		$data['page_title'] = "Formulario de Reingreso - ".$row->first_name." ".$row->last_name;

		$this->load->view('paciente/edit_form_reingreso', $data);
	}

	public function delete($id)
	{
		$this->is_admin();
		$this->is_ajax();
		$this->user_model->delete($id);
		echo 'ok';
	}

}