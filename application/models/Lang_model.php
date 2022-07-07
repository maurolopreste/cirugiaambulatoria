<?php

class Lang_model extends CI_model {

	function __construct()
	{
		parent::__construct();
		// $this->tbl = 'lang';
	}

	public function add_line($line)
	{
		$this->load->helper('file');
		
		$data =  '$lang[\''. $line .'\'] = \''. $line .'\';' . "\n";
		
		write_file(APPPATH . "language/english/en_lang.php", $data, 'a');
		write_file(APPPATH . "language/spanish/es_lang.php", $data, 'a');
	}

}