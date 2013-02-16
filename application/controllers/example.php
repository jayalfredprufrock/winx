<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Example extends MY_Controller {

	
	public function index()
	{
		$this->fluid();
	}
	
	public function fluid()
	{
		$this->_page('example/fluid', array(), 'layout/two_column');
	}
	
	public function signin()
	{
		
		$this->messages->info('Welcome to CI boilerplate! Today is %s.', date('m/d/Y'), 'Welcome!');
		
		$this->_page('example/signin');
	}
	
	public function carousel()
	{
		
		$this->_page('example/carousel', array(),'layout/one_column');
	}
	
	public function modal()
	{
		
		$this->template->content->widget('modal',array('title'=>'Modal Fun!', 'body'=>'Here is a wonderful modal'));
		
		$this->_page(FALSE);
	}
	
	public function hero()
	{
		
		$this->_page('example/hero', array(), 'layout/one_column');
	}
	
	public function sticky_footer_navbar()
	{
		
		$this->_page('example/sticky-footer-navbar');
	}
	
	
}

/* End of file example.php */
/* Location: ./application/controllers/example.php */