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

	/**
	 * Un paciente es "de" un profesional si tiene (o tuvo) con él un turno, una historia clínica,
	 * un informe evolutivo o una prescripción.
	 */
	public function es_paciente_de($profesional_id, $paciente_id)
	{
		$prof = (int)$profesional_id;
		$pac = (int)$paciente_id;
		if(!$prof || !$pac) return FALSE;

		$sql = "SELECT 1 FROM agenda WHERE paciente_id = ? AND profesional_id = ?
			UNION SELECT 1 FROM historiaclinica WHERE paciente_id = ? AND profesional_id = ?
			UNION SELECT 1 FROM evolutivo WHERE paciente_id = ? AND profesional_id = ?
			UNION SELECT 1 FROM prescripcion WHERE paciente_id = ? AND profesional_id = ?
			LIMIT 1";
		return $this->db->query($sql, array($pac, $prof, $pac, $prof, $pac, $prof, $pac, $prof))->num_rows() > 0;
	}

	/**
	 * Pacientes de un profesional (ver es_paciente_de), con búsqueda opcional.
	 */
	public function get_by_profesional($profesional_id=null, $search=null)
	{
		$prof = (int)$profesional_id;

		$this->db->select("
			$this->tbl.*,
			group.name as perfil
		");
		$this->db->join('group', "$this->tbl.group_id = group.id", 'left');

		if($search)
		{
			$this->db->group_start();
			$this->db->like("$this->tbl.first_name", $search);
			$this->db->or_like("$this->tbl.last_name", $search);
			$this->db->or_like("$this->tbl.dni", $search);
			$this->db->or_like("$this->tbl.email", $search);
			$this->db->group_end();
		}

		$this->db->where("$this->tbl.deleted_at IS NULL");
		$this->db->where("$this->tbl.group_id", 3);
		//$prof es un entero (cast), no hay inyección posible
		$this->db->where("$this->tbl.id IN (
			SELECT paciente_id FROM agenda WHERE profesional_id = $prof
			UNION SELECT paciente_id FROM historiaclinica WHERE profesional_id = $prof
			UNION SELECT paciente_id FROM evolutivo WHERE profesional_id = $prof
			UNION SELECT paciente_id FROM prescripcion WHERE profesional_id = $prof
		)", NULL, FALSE);
		$this->db->order_by($this->order_by);

		return $this->db->get($this->tbl)->result();
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
		$this->db->select("
			$this->tbl.*,
			group.name as perfil
		");
		$this->db->join('group', "$this->tbl.group_id = group.id", 'left');

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

	/**
	 * Genera un token de un solo uso (40 hex): 8 hex con el vencimiento + 32 hex aleatorios (random_bytes).
	 * En la base se guarda sólo el hash SHA-256 (si se filtra la tabla, los tokens no sirven).
	 * @param int $ttl segundos de validez (1 hora para recuperar clave, 48 hs para confirmar email)
	 */
	public function generate_token($email, $ttl = 3600)
	{
		$token = str_pad(dechex(time() + (int)$ttl), 8, '0', STR_PAD_LEFT) . bin2hex(random_bytes(16));
		$this->db->where('email', $email);
		$this->db->where('deleted_at IS NULL');
		$this->db->update($this->tbl, array('rp_token' => hash('sha256', $token)));
		return $token;
	}
	
	/**
	 * Devuelve el usuario dueño del token si es válido y no venció; si no, FALSE.
	 */
	public function verify_token($token)
	{
		if (!is_string($token) || !preg_match('/^[0-9a-f]{40}$/', $token)) return FALSE;
		if (hexdec(substr($token, 0, 8)) < time()) return FALSE;

		$this->db->where('rp_token', hash('sha256', $token));
		$this->db->where('deleted_at IS NULL');
		$result = $this->db->get($this->tbl);
		return $result->num_rows() ? $result->row() : FALSE;
	}

	/**
	 * Usuario para guardar en la sesión: sin hash de contraseña ni token.
	 */
	public function for_session($user)
	{
		if (!$user) return FALSE;
		$u = clone $user;
		unset($u->password, $u->rp_token);
		return $u;
	}

	/**
	 * Usuario activo y no eliminado, sin datos sensibles (se consulta en cada request para que
	 * los cambios de rol/baja se apliquen enseguida). FALSE si ya no puede operar.
	 */
	public function get_session_user($id)
	{
		$this->db->where('id', (int)$id);
		$this->db->where('deleted_at IS NULL');
		$this->db->where('active', 1);
		$row = $this->db->get($this->tbl)->row();
		return $this->for_session($row);
	}

}
