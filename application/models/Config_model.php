<?php

class Config_model extends Super_model {
	
	function __construct()
	{
		parent::__construct();
		$this->tbl = 'config';
	}

	public function get_userdata()
	{
		$result = $this->db->get($this->tbl)->result();
		$data = [];
		foreach($result as $row) {
			$data[$row->meta_key] = $row->meta_value;
		}
		return (object) $data;
	}

	public function get_medicamentos()
	{
		$this->db->order_by('nombre ASC');
		return $this->db->get('medicamento')->result();
	}

	public function get_tipo_coberturas()
	{
		$this->db->order_by('nombre ASC');
		return $this->db->get('tipo_cobertura')->result();
	}

	public function get_valores($id=false)
	{
		if($id)
		{
			$this->db->where('id', $id);
			return $this->db->get('valor_consulta')->row();
		}
		else {
			$this->db->order_by('nombre ASC');
			return $this->db->get('valor_consulta')->result();
		}
	}

}