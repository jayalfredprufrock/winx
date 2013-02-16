<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**

/**
 * Security Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Security
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/security.html
 */


class MY_Security extends CI_Security
{    
	
	public $csrf_error = FALSE;
	
	public function csrf_show_error()
	{
		$this->csrf_error = TRUE;
	}

}