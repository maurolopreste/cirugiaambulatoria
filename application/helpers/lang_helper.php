<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('lang'))
{
	function lang($line)
	{
		$CI =& get_instance();
		$orig_line = $line;
		$line = $CI->lang->line($orig_line);

		if($line)
		{
			return $line;
		}
		else {
			$CI->load->model('lang_model');
			$CI->lang_model->add_line($orig_line);
			return $orig_line;
		}
	}
}