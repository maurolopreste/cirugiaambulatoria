<?php

class Evolutivo_model extends Super_model {
	
	function __construct()
	{
		parent::__construct();
		$this->tbl = 'evolutivo';
		// $this->soft_delete = TRUE;
	}

	public function get_by_paciente($paciente_id)
	{
		$this->db->where("$this->tbl.paciente_id", $paciente_id);
		$this->db->order_by("$this->tbl.id DESC");
		$result = $this->db->get($this->tbl);
		return $result->num_rows() ? $result->result() : FALSE;
	}

}