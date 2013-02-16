<?php

class MY_Controller extends CI_Controller {
	
	protected $_theme = 'default';

	function __construct()
    {
        parent::__construct();
		
		$this->language->set();
    }
	

	function _remap($method, $params = array())
	{
		
		//normal function	
		$f = method_exists($this, $method) ? array($this, $method) : FALSE;
		
		//post function
		$p = method_exists($this, '_'.$method) ? array($this, '_'.$method) : FALSE;
		
		
		//is this a form submission?
		if ($_SERVER['REQUEST_METHOD'] === 'POST'){
			
			//only works if you include MY_Security.php in the core overrides
			$this->_breaking_error(!empty($this->security->csrf_error), 'Your request token has expired. Please refresh your browser and try again.');	
			
			$this->load->library('form_validation');
			
			//validation fails	
			if (!$this->form_validation->run($this->uri->slash_rsegment(1).$this->uri->rsegment(2))){
				
				if ($this->input->is_ajax_request()){
					
					$this->_ajax_form_error();			
				}
						
			}
			else if($p){
				
				return call_user_func_array($p, $params);
			}
		}
		
		//validation fails, follow original route
		if ($f){
			
			return call_user_func_array($f, $params);
		}
		
		$this->_show_404();
	}

	//Stub for adding ajax-specific behaviors
	function _ajax_form_error(){
		

	}
	
	
	function _page($view, $data = array(), $template = FALSE){
		
		$this->template->title = lang('page_title');
		$this->template->description = lang('page_description');
		
		
		$this->template->css = $this->combine->css_folder('',1,'style.css')
											 ->css_folder('themes/'.$this->_theme,1,array('style.css','responsive.css'))
											 ->build('css');
		
		$this->template->js_bottom = $this->combine->js_folder('libs', 2, array('jquery-latest.js','bootstrap/bootstrap-tooltip.js'))
												   ->build('js');

		if ($this->messages->has_messages()){
			
			$this->template->messages->view('layout/_messages', array('messages'=>$this->messages->get()));
		}
		
		$this->template->breadcrumb->view('layout/_breadcrumb', array('breadcrumb'=>array(base_url()=>'Home','/signin'=>'Signin')));
		
		if ($view){
			
			$this->template->content->view($view, $data);	
		}		
		
		$this->template->publish($template);	
	}
	
	function _show_404(){
			
		//override this method and use
		//show_404() to replicate native CI functionality		
		
		$this->_breaking_error(TRUE, 'The page you are trying to access does not exist.', 'Page not found.!');
	}
	
	
	function _breaking_error($error, $message, $title = FALSE){
		
		if ($error){

			$this->messages->error($message);
		
			$this->_page(FALSE);
			
			//make sure function that called this can't continue
			$this->output->_display();
	    	exit();			
		}	
		
	}
	
	
}
//End of MY_Controller.php