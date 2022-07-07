<?php

class Super_model extends CI_Model
{
	protected $pk = "id";
	protected $tbl;
	protected $per_page = 20;
	protected $soft_delete = FALSE;
	public $filter_results = FALSE;
	public $order_by = FALSE;

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Actualiza un objeto en la DB
	 */
	function update($id, $data)
	{
		$this->db->where($this->pk, $id);
		$this->db->update($this->tbl, $data);
		return $id;
	}

	/**
	 * Actualiza un objeto en la tabla PIVOT
	 */
	function update_pivot($pivot_table, $data)
	{
		$this->db->where($this->pk, $data['id']);
		return $this->db->update($this->tbl.'_'.$pivot_table, $data);
	}

	/**
	 * Inserta un objeto en la DB
	 */
	function insert($data)
	{
		$this->db->insert($this->tbl, $data);
		return $this->db->insert_id();
	}

	/**
	 * Inserta un objeto en la tabla PIVOT
	 */
	function insert_pivot($pivot_table, $data)
	{
		$this->db->insert($this->tbl.'_'.$pivot_table, $data);
		return $this->db->insert_id();
	}

	/**
	 * Obtiene uno o más elementos de la DB
	 */
	function get($id=null)
	{
		if ($this->soft_delete) $this->db->where('deleted_at IS NULL');

		if($id) {
			$this->db->where($this->pk, $id);
			$rs = $this->db->get($this->tbl);
			if($rs->num_rows()) return $rs->row();
		}
		else {
			if($this->per_page) $this->db->limit($this->per_page);
			if($this->orderby) $this->db->order_by($this->orderby);
			$rs = $this->db->get($this->tbl);
			return $rs->result();
		}
	}

	/**
	 * Obtiene uno o más elementos de la DB
	 */
	function get_paginated($page_num=1, $per_page=10)
	{
		if ($this->soft_delete) $this->db->where('deleted_at IS NULL');
		if ($this->filter_results) $this->db->where($this->filter_results);
		$this->db->limit($per_page, $page_num);
		if($this->orderby) $this->db->order_by($this->orderby);
		$rs = $this->db->get($this->tbl);
		return $rs->result();
	}

	/**
	 * Obtiene el numero total de registros
	 */
	function get_total()
	{
		if ($this->soft_delete) $this->db->where('deleted_at IS NULL');
		if ($this->filter_results) $this->db->where($this->filter_results);
		return $this->db->count_all_results($this->tbl);
	}

	/**
	 * Elimina un elemento de la DB
	 */
	function delete($id=null, $force=FALSE)
	{
		if($id)
		{
			if($this->soft_delete && !$force){
				$this->db->where($this->pk, $id);
				return $this->db->update($this->tbl, array('deleted_at' => date('Y-m-d H:i:s')));
			}
			else {
				return $this->db->delete($this->tbl, array($this->pk => $id));
			}
		}
	}

	/**
	 * Obtiene uno o más elementos de la DB según ciertos atributos
	 */
	function find_by_attributes($attr)
	{
		foreach($attr as $k=>$v) {
			$this->db->where($k, $v);
		}
		if($this->orderby) $this->db->order_by($this->orderby);
		return $this->db->get($this->tbl)->result();
	}

	/**
	 * Obtiene el numero total de registros según ciertos atributos
	 */
	function count_by_attributes($attr)
	{
		foreach($attr as $k=>$v) {
			$this->db->where($k, $v);
		}
		if ($this->soft_delete) $this->db->where('deleted_at IS NULL');
		return $this->db->count_all_results($this->tbl);
	}

	/**
	 * Restaura un elemento de la DB
	 */
	function restore($id=null)
	{
		$this->db->set('deleted_at', null);
		$this->db->where($this->pk, $id);
		return $this->db->update($this->tbl);
	}

}