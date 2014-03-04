<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH.'/core/Winx_Controller.php';

class Example extends Winx_Controller {

	
	public function index()
	{
		$this->fluid();
	}
	
	public function fluid()
	{
			
		$this->messages->info('Welcome to Winx!');
			
		$this->_page('example/fluid', array(), 'layout/two_column');
	}
	
	public function signin()
	{
		
		$this->messages->info('Welcome to CI boilerplate! Today is %s.', date('m/d/Y'), 'Welcome!');
		
		$this->_page('example/signin');
	}
	
	public function carousel()
	{
		
		$this->_page('example/carousel');
	}
	
	public function modal()
	{
		
		$this->template->modal->widget('modal',array('title'=>'Modal Fun!', 'body'=>'Here is a wonderful modal'));
		
		$this->_page(FALSE);
	}
	
	public function modal2()
	{
		
		$this->template->modal->widget('modal',array('title'=>'Modal2 Fun!', 'body'=>'Here is a wonderful modal'));
		
		$this->_page(FALSE);	
	}
	
	public function hero()
	{
		
		$this->_page('example/hero');
	}

	
}

/* End of file example.php */
/* Location: ./application/controllers/example.php */