<?php
// STUB DE TESTS: el modelo real no está en el repositorio. Sólo se copia si falta (ver run.sh).
class Prescripcion_model extends Super_model {
	function __construct() { parent::__construct(); $this->tbl = 'prescripcion'; }
	public function get_prescripciones($paciente_id = null, $id = null)
	{
		if ($paciente_id) $this->db->where('paciente_id', $paciente_id);
		if ($id) $this->db->where('id', $id);
		$rs = $this->db->get($this->tbl)->result();
		return $id ? ($rs ? $rs[0] : null) : $rs;
	}
}
