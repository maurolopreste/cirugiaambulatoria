<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter 3 no soporta SameSite en la cookie de CSRF: se sobreescribe el método para agregarlo (PHP >= 7.3).
 * En PHP < 7.3 el atributo viaja en cookie_path (ver config.php).
 */
class MY_Security extends CI_Security {

	public function csrf_set_cookie()
	{
		if (PHP_VERSION_ID < 70300) {
			return parent::csrf_set_cookie();
		}

		$secure_cookie = (bool) config_item('cookie_secure');
		if ($secure_cookie && ! is_https()) {
			return FALSE;
		}

		setcookie($this->_csrf_cookie_name, $this->_csrf_hash, array(
			'expires'  => time() + $this->_csrf_expire,
			'path'     => config_item('cookie_path'),
			'domain'   => config_item('cookie_domain'),
			'secure'   => $secure_cookie,
			'httponly' => (bool) config_item('cookie_httponly'),
			'samesite' => 'Lax'
		));
		log_message('info', 'CSRF cookie sent');

		return $this;
	}

}
