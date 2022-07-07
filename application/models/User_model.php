<?php

class User_model extends Super_model {
	
	function __construct()
	{
		parent::__construct();
		$this->tbl = 'user';
		$this->soft_delete = TRUE;
		$this->order_by = "first_name ASC, last_name ASC";
	}

	public function get($id=null)
	{
		$this->db->select("
			$this->tbl.*,
			group.name as perfil,
			CONCAT($this->tbl.first_name, ' ',  $this->tbl.last_name) as nombre_completo,
		");
		$this->db->join('group', "$this->tbl.group_id = group.id", 'left');

		if($id) {
			$this->db->where("$this->tbl.id", $id);
			$rs = $this->db->get($this->tbl);
			if($rs->num_rows()) return $rs->row();
		}
		else {
			//hide deleted rows
			$this->db->where("$this->tbl.deleted_at IS NULL");

			$this->db->order_by($this->order_by);
			$rs = $this->db->get($this->tbl);
			return $rs->result();
		}
	}

	public function formingreso($user_id, $update=null)
	{
		if($update)
		{
			$this->db->where('user_id', $user_id);
			$this->db->update('formingreso', $update);
		}
		else {
			$this->db->where('user_id', $user_id);
			$rs = $this->db->get('formingreso');
			return $rs->num_rows() ? $rs->row() : FALSE;
		}
	}

	public function formalta($user_id, $update=null)
	{
		if($update)
		{
			$this->db->where('user_id', $user_id);
			$this->db->update('formalta', $update);
		}
		else {
			$this->db->where('user_id', $user_id);
			$rs = $this->db->get('formalta');
			return $rs->num_rows() ? $rs->row() : FALSE;
		}
	}

	public function formreingreso($user_id, $update=null)
	{
		if($update)
		{
			$this->db->where('user_id', $user_id);
			$this->db->update('formreingreso', $update);
		}
		else {
			$this->db->where('user_id', $user_id);
			$rs = $this->db->get('formreingreso');
			return $rs->num_rows() ? $rs->row() : FALSE;
		}
	}

	public function get_by_profesional($profesional_id=null, $search=null)
	{
		$this->db->select("
			$this->tbl.*,
			CONCAT(profesional.first_name, ' ',  profesional.last_name) as profesional,
			tipo_cobertura.nombre as tipo_cobertura
		");
		$this->db->join('user as profesional', "$this->tbl.profesional_id = profesional.id", 'left');
		$this->db->join('tipo_cobertura', "$this->tbl.cobertura = tipo_cobertura.id", 'left');

		//search by $search
		if($search)
		{
			$this->db->group_start();
			$this->db->like("$this->tbl.first_name", $search);
			$this->db->or_like("$this->tbl.last_name", $search);
			$this->db->or_like("$this->tbl.dni", $search);
			$this->db->or_like("$this->tbl.email", $search);
			$this->db->group_end();
		}

		//hide deleted rows
		$this->db->where("$this->tbl.deleted_at IS NULL");

		$this->db->where("$this->tbl.profesional_id", $profesional_id);
		$this->db->order_by($this->order_by);
		
		$rs = $this->db->get($this->tbl);
		return $rs->result();
	}

	public function get_fechas_comprometidas($profesional_id)
	{
		$this->db->where('profesional_id', $profesional_id);
		$rs = $this->db->get('fecha_comprometida');
		return $rs->num_rows() ? $rs->result() : FALSE;
	}

	public function get_dias_habilitados($profesional_id)
	{
		$this->db->where('profesional_id', $profesional_id);
		$rs = $this->db->get('dias_habilitados');
		return $rs->num_rows() ? $rs->result() : FALSE;
	}

	public function login($email)
	{
		$this->db->where('email', $email);
		$this->db->where('deleted_at IS NULL');
		$result = $this->db->get('user')->row();
		return $result ? $result : FALSE;
	}

	public function get_groups()
	{
		return $this->db->get('group')->result();
	}

	public function get_by_group_id($group_id, $search=null)
	{
		//search by $search
		$this->db->group_start();
		$this->db->like("$this->tbl.first_name", $search);
		$this->db->or_like("$this->tbl.last_name", $search);
		$this->db->or_like("$this->tbl.dni", $search);
		$this->db->or_like("$this->tbl.email", $search);
		$this->db->group_end();

		//hide deleted rows
		$this->db->where("$this->tbl.deleted_at IS NULL");

		$this->db->where("$this->tbl.group_id", $group_id);
		$this->db->order_by($this->order_by);

		return $this->db->get($this->tbl)->result();
	}

	public function get_valor_profesional($id)
	{
		$this->db->select("
			profesional_valor.*,
			valor_consulta.nombre,
			valor_consulta.valor
		");
		$this->db->where("profesional_valor.profesional_id", $id);
		$this->db->join('valor_consulta', "profesional_valor.valor_consulta = valor_consulta.id", 'left');

		$result = $this->db->get('profesional_valor')->result();
		$arr = [];
		foreach($result as $v) $arr[$v->valor_consulta] = $v;
		return $arr;
	}

	public function get_provincias()
	{
		$rs = $this->db->get('provincia');
		return $rs->result();
	}

	public function get_art_list()
	{
		$rs = $this->db->get('art');
		return $rs->result();
	}

	public function save_login($id)
	{
		$data['last_login'] = date('Y-m-d H:i:s');
		$this->db->where('id', $id);
		$this->db->update($this->tbl, $data);
	}

	public function generate_token($email)
	{
		$token = sha1(uniqid($email, true));
		$data['rp_token'] = $token;
		$this->db->where('email', $email);
		$this->db->update($this->tbl, $data);
		return $token;
	}
	
	public function verify_token($token)
	{
		if (preg_match('/^[0-9A-F]{40}$/i', $token))
		{
			$this->db->where('rp_token', $token);
			$result = $this->db->get($this->tbl);
			return $result->num_rows() ? $result->row() : FALSE;
		}
	}

}