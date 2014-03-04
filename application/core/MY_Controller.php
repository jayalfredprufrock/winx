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
		if ($this->input->server('REQUEST_METHOD') === 'POST'){
			
			//only works if you include MY_Security.php in the core overrides
			$this->_breaking_error(!empty($this->security->csrf_error), 'Your request token has expired. Please refresh your browser and try again.');	
			
			$this->load->library('form_validation');
			
			//validation fails	
			if (!$this->form_validation->run($this->uri->slash_rsegment(1).$this->uri->rsegment(2)) && $this->form_validation->error_array()){
					
				$this->_form_error();	
					
				if ($this->input->is_ajax_request()){
					
					$this->_ajax_form_error();			
				}
						
			}
			else {
				
				if (!$this->form_validation->error_array()){
					
					//$this->log->log_message('error','POST request without validation rules.', 'URI:'.$this->uri->uri_string());
				}
				
				if($p){
				
					$this->_after_post($this->uri->uri_string(), call_user_func_array($p, $params));
				}
			}
		}
		
		//validation fails, follow original route
		if ($f){
			
			return call_user_func_array($f, $params);
		}
		
		$this->_show_404();
	}
	
	
	protected function _form_error(){
		
		
	}
	
	//Stub for adding ajax-specific behaviors
	protected function _ajax_form_error(){
		

	}
	
	protected function _after_post($uri, $returned){
		
		
	}
	
	
	protected function _page($view, $data = array(), $template = FALSE){
			
		$this->_load_page_lang($view);	
		
			
		$this->template->title->default((lang('page_title') ? lang('page_title') . ' | ' : '') . lang('site_title'));	
		$this->template->description->default(lang('page_description') ?: lang('site_description'));
		
		$this->template->css = $this->combine->css_folder('',1,'style.css')
											 ->css_folder('themes/'.$this->_theme,1,array('style.css','responsive.css'))
											 ->build('css');
		
		$this->template->js_bottom = $this->combine->js_folder('libs', 2, array('jquery-latest.js','bootstrap/bootstrap-tooltip.js'))
												   ->build('js');

		if ($this->messages->has_messages()){
			
			$this->template->messages->view('layout/_messages', array('messages'=>$this->messages->get()));
		}
		
		if ($view){
			
			$this->template->content->view($view, $data);	
		}		
		
		$this->template->publish($template);
	}
	
	protected function _load_page_lang($view){
		
		if (file_exists(APPPATH . 'language/' . $this->language->get(TRUE) . '/views/' . $view . '_lang.php')){
			
			$this->lang->load('views/'.$view);
		}
	}
	
	protected function _show_404(){
			
		//override this method and use
		//show_404() to replicate native CI functionality		
		
		$this->_breaking_error(TRUE, 'The page you are trying to access does not exist.', 'Page not found.!');
	}
	
	
	protected function _breaking_error($error, $message, $title = FALSE){
		
		if ($error){

			$this->messages->danger($message);
		
			$this->_page(FALSE);
			
			//make sure function that called this can't continue
			$this->output->_display();
	    	exit();			
		}	
		
	}
	
	
}
//End of MY_Controller.php