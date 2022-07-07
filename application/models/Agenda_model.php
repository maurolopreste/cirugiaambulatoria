<?php

class Agenda_model extends Super_model {
	
	function __construct()
	{
		parent::__construct();
		$this->tbl = 'agenda';
		// $this->soft_delete = TRUE;

		$this->prefs = array(
			'start_day'	=> 'monday',
			'day_type'	=> 'short',
			'show_other_days' => TRUE,
			'show_next_prev' => TRUE,
			'next_prev_url'   => base_url('agenda?date=')
		);

		$this->prefs['template'] = array(
			'table_open'				=> '<table border="1" cellpadding="0" cellspacing="0" class="calendar">',
			'heading_previous_cell'		=> '<th><a href="{previous_url}"><i class="fa fa-arrow-left fa-fw"></i></a></th>',
			'heading_title_cell'		=> '<th colspan="{colspan}" class="text-center"><h3>{heading}</h3></th>',
			'heading_next_cell'			=> '<th class="text-right"><a href="{next_url}"><i class="fa fa-arrow-right fa-fw"></i></a></th>',
			'week_day_cell'				=> '<td class="week_day">{week_day}</td>',
			'cal_row_start'				=> '<tr class="days">',
			'cal_cell_start_today'		=> '<td class="today">',
			'cal_cell_start_other'		=> '<td class="text-muted">',
			'cal_cell_content'			=> '<div class="day_num">{day}</div>{content}',
			'cal_cell_content_today'	=> '<div class="day_num today">{day}</div>{content}',
			'cal_cell_no_content'		=> '<div class="day_num">{day}</div>',
			'cal_cell_no_content_today'	=> '<div class="day_num today">{day}</div>',
			'cal_cell_other'			=> '<div class="day_num">{day}</div>'
		);
	}

	public function get_calendar($year, $month, $profesional_id=null)
	{
		$this->load->library('calendar', $this->prefs);
		$data = $this->get_calendar_data($year, $month, $profesional_id);
		return $this->calendar->generate($year, $month, $data);
	}

	public function get_calendar_data($year, $month, $profesional_id=null)
	{
		$this->db->select("
			$this->tbl.id,
			$this->tbl.fecha,
			$this->tbl.estado_cita,
			CONCAT(user.first_name, ' ',  user.last_name) as paciente
		");
		$this->db->join('user', "$this->tbl.paciente_id = user.id", 'left');
		$this->db->like("$this->tbl.fecha", "$year-$month", 'after');

		if($profesional_id) {
			$this->db->where("$this->tbl.profesional_id", $profesional_id);
		}

		$this->db->order_by("$this->tbl.fecha ASC");

		$query = $this->db->get($this->tbl)->result();
		$cal_data = array();
		
		foreach ($query as $row)
		{
			$calendar_date = date("Y-m-j", strtotime($row->fecha)); // to remove leading zero from day format
			$index = substr($calendar_date, 8, 2);
			if(!isset($cal_data[$index])) $cal_data[$index] = "";
			
			if(time() > strtotime($row->fecha)) {
				//si el turno es anterior a hoy
				$cal_data[$index] .= '<div class="badge badge-light" data-id="'. $row->id .'">'. date('H:i', strtotime($row->fecha)) .' '. $row->paciente .'</div>';
			}
			else {
				$badge_class = 'badge-primary';
				if($row->estado_cita==3) $badge_class = 'badge-danger';
				$cal_data[$index] .= '<div class="badge '. $badge_class .'" data-id="'. $row->id .'">'. date('H:i', strtotime($row->fecha)) .' '. $row->paciente .'</div>';
			}
		}
		
		return $cal_data;
	}

	public function get_all($search=null)
	{
		$this->db->select("
			$this->tbl.*,
			CONCAT(user.first_name, ' ',  user.last_name) as paciente,
			metodo_pago.nombre as metodo_pago,
			valor_consulta.nombre as tipo_consulta,
			valor_consulta.valor as valor
		");
		$this->db->join('user', "$this->tbl.paciente_id = user.id", 'left');
		$this->db->join('metodo_pago', "$this->tbl.payment_method = metodo_pago.id", 'left');
		$this->db->join('valor_consulta', "$this->tbl.valor_consulta = valor_consulta.id", 'left');

		if($search)
		{
			$this->db->group_start();
			$this->db->like("CONCAT(user.first_name, ' ', user.last_name)", $search);
			$this->db->or_like("metodo_pago.nombre", $search);
			$this->db->group_end();
		}

		$this->db->order_by("$this->tbl.fecha DESC");

		return $this->db->get($this->tbl)->result();
	}

	public function get_turno($id)
	{
		$this->db->select("
			$this->tbl.*,
			CONCAT(user.first_name, ' ', user.last_name) as nombre_paciente,
			CONCAT(profesional.first_name, ' ', profesional.last_name) as nombre_profesional,
			user.email as email_paciente
		");
		$this->db->join('user', "$this->tbl.paciente_id = user.id", 'left');
		$this->db->join('user as profesional', "$this->tbl.profesional_id = profesional.id", 'left');
		$this->db->where("$this->tbl.id", $id);
		
		return $this->db->get($this->tbl)->row();
	}

	public function search_turnos($fecha, $profesional_id)
	{
		$this->db->select('fecha');
		$this->db->where('estado_cita <>', 3);
		$this->db->where('profesional_id', $profesional_id);
		$this->db->like('fecha', $fecha, 'after');
		
		$result = $this->db->get($this->tbl)->result();
		$fechas = array();

		if($result)
		{
			foreach ($result as $row) {
				$fechas[] = intval(substr($row->fecha, 11, 2));//obtener hora del turno, sin ceros al principio
			}
		}

		return $fechas;
	}

	public function get_turnos_hoy()
	{
		$this->db->select("
			$this->tbl.*,
			paciente.telefono as telefono_paciente,
			CONCAT(paciente.first_name, ' ', paciente.last_name) as nombre_paciente,
			CONCAT(profesional.first_name, ' ', profesional.last_name) as nombre_profesional
		");
		$this->db->join('user as paciente', "$this->tbl.paciente_id = paciente.id", 'left');
		$this->db->join('user as profesional', "$this->tbl.profesional_id = profesional.id", 'left');
		
		// $this->db->where("$this->tbl.paciente_id", $paciente_id);
		// $this->db->where("$this->tbl.estado_cita", 1);//sólo citas pendientes
		$this->db->where("DATE($this->tbl.fecha)", "CURDATE()", false);
		$this->db->order_by("$this->tbl.fecha DESC");

		return $this->db->get($this->tbl)->result();
	}

	public function get_by_paciente($paciente_id)
	{
		$this->db->select("
			$this->tbl.*,
			CONCAT(profesional.first_name, ' ', profesional.last_name) as profesional
		");
		$this->db->join('user as profesional', "$this->tbl.profesional_id = profesional.id", 'left');
		
		$this->db->where("$this->tbl.paciente_id", $paciente_id);
		$this->db->where("$this->tbl.estado_cita", 1);//sólo citas pendientes

		$this->db->order_by("$this->tbl.fecha DESC");

		return $this->db->get($this->tbl)->result();
	}

	public function get_by_profesional($profesional_id)
	{
		$this->db->select("
			$this->tbl.*,
			CONCAT(paciente.first_name, ' ', paciente.last_name) as paciente
		");
		$this->db->join('user as paciente', "$this->tbl.paciente_id = paciente.id", 'left');

		$this->db->where("$this->tbl.profesional_id", $profesional_id);
		$this->db->where("$this->tbl.estado_cita", 1);//sólo citas pendientes

		$this->db->order_by("$this->tbl.fecha DESC");//ordernar por fecha y hora

		return $this->db->get($this->tbl)->result();
	}

	public function get_metodos_pago()
	{
		return $this->db->get('metodo_pago')->result();
	}

	public function get_valores_consulta()
	{
		return $this->db->get('valor_consulta')->result();
	}

}