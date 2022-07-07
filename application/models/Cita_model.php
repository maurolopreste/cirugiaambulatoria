<?php

class Cita_model extends Super_model {
	
	function __construct()
	{
		parent::__construct();
		$this->tbl = 'cita';
		// $this->soft_delete = TRUE;
	}

	public function get_citas($paciente_id)
	{
		$this->db->select("
			$this->tbl.*,
			CONCAT(profesional.first_name, ' ',  profesional.last_name) as profesional
		");
		$this->db->join('user as profesional', "$this->tbl.profesional_id = profesional.id", 'left');
		$this->db->where("$this->tbl.paciente_id", $paciente_id);

		$result = $this->db->get($this->tbl);
		return $result->result();
	}

}