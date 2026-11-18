<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('get_site_config'))
{
	function get_site_config($key)
	{
		$CI =& get_instance();
		
		$get_site_config = $CI->session->userdata('config');
		if(!$get_site_config)
		{
			$CI->load->model('config_model');
			$get_site_config = $CI->config_model->get_userdata();
			$CI->session->set_userdata('config', $get_site_config);
		}

		return $get_site_config->{$key};
	}
}

if (!function_exists('get_userdata'))
{
	function get_userdata($key)
	{
		$CI =& get_instance();
		$CI->load->model('user_model');
		
		$get_userdata = $CI->user_data;
		if(!isset($get_userdata[$key]))
		{
			$data = array(
				'user_id' => $get_userdata['user_id'],
				'meta_key' => $key,
				'meta_value' => ""
			);
			$CI->user_model->insert_userdata($data);
			return "";
		}
		return isset($get_userdata[$key]) ? $get_userdata[$key] : "";
	}
}

if (!function_exists('password_is_valid'))
{
	/**
	 * Política mínima de contraseñas: al menos 8 caracteres.
	 */
	function password_is_valid($password)
	{
		return is_string($password) && strlen($password) >= 8;
	}
}
