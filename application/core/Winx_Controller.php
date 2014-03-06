<?php

class Winx_Controller extends MY_Controller {
	
	protected $_theme = 'default';
	protected $_ajax_disabled = FALSE;
	
	protected $_layout = 'layout/default';

	function __construct()
    {
        parent::__construct();
    }
	
	function _page($view = FALSE, $data = array(), $layout = FALSE){
								
		$this->_pre_page($view, $data, $layout);	
		
		$this->_load_js();	
		$this->_load_css();		
		
		$this->_load_messages();	
		
		if ($view){
			
			$this->template->layout->view($layout ?: $this->_layout, $data);
			
			$this->_load_page_lang($view);	
			
			$this->_load_content($view, $data, $layout);	
			
			$this->_load_navigation($data);
		}
		//edge case where user refreshes a url
		//that is meant to serve a partial...
		elseif (!$this->input->is_ajax_request()){
			
			redirect('');
		}				

		$this->_render(!$view);
	}


	protected function _redirect($location=''){
		
		if ($this->input->is_ajax_request()){

			json(array('redirect'=>$location));
		}
		
		redirect($location);
	}
	
	protected function _after_post($uri, $returned){
		
		if ($this->input->is_ajax_request()){
			
			$this->_partial();
		}
		else {
			
			$this->_redirect($returned !== NULL ? $returned : $uri);
		}
	}
	
	protected function _form_error(){
		
		if (!$this->input->is_ajax_request()){
			
			$this->messages->form_danger(validation_errors('<li>','</li>',3));
		}
	}
	
	protected function _ajax_form_error(){
		
		
		json(array('errors'=>$this->form_validation->error_array()));
	}

	protected function _load_css(){
		
		$this->template->css->prepend($this->combine->css_folder('themes/'.$this->_theme,1,array('style.css','responsive.css'))
										            ->build('css'));		
	}
	
	protected function _load_js(){
		
		$this->template->js_page->prepend(script(js_var('ajax_disabled', $this->_ajax_disabled)), FALSE, TRUE);
		
		if (!$this->_ajax_disabled && $this->config->item('csrf_protection')){

			$this->template->js_page->prepend(script(js_var('csrf', csrf())), FALSE, TRUE);			
		}
				
			
		$this->template->js->prepend($this->combine->js_folder('libs', 2, array('jquery-latest.js','bootstrap/bootstrap-tooltip.js'))
												   ->js_folder('history', 1, array('history.js'), array('history.html4.js'))
												   ->js_folder('winx')
												   ->js_folder('ui')
												   ->js_folder('social')
												   ->js_folder('', 1)
												   ->build('js'));
														  
	}
	
	protected function _load_messages(){
			
		if ($this->messages->has_messages()){
			
			$messages = $this->messages->get();
			
			$note_messages = array();
			$form_messages = array();
			
			foreach($messages as $i=>$message){
				
				$group = substr($message->type,0,5); 
				
				if ($group == 'note_' || $group == 'form_'){
					
					$group = $group . 'messages';
	
					$message->type = substr($message->type, 5);
					
					array_unshift($$group, $message);
					
					unset($messages[$i]);
				}
			}
			
			if ($messages){
				
				$this->template->messages->view('layout/_messages', array('messages'=>$messages));
			}
			
			if ($note_messages){
				
				$this->template->notifications->view('layout/_messages', array('messages'=>$note_messages));
			}
	
			if ($form_messages){
				
				$this->template->form_messages->view('layout/_messages', array('messages'=>$form_messages));
			}

		
			
		}	
	}
	
	protected function _load_content($view, $data){
		
		$this->template->content->view($view, $data);
	}

	function _db_breaking_error($object, $error = TRUE, $record = FALSE, $action = FALSE){
		
		$this->_breaking_error($error, db_message($object, !$error, $record, $action));
	}
	
	
	function _db_message($object, $success = TRUE, $record = FALSE, $action = FALSE){
		
		$type = $success ? 'note_success' : 'danger';
		
		$this->messages->$type(db_message($object, $success, $record, $action));
	}
	
	function _modal($view, $view_data = array(), $modal = array()){
			
		if (!empty($modal['js'])){
			
			$this->template->js_page->append(script($modal['js']));
		}
		
		$modal['body'] = $this->load->view($view, $view_data, TRUE);
		$this->template->load_assets($view);
				
		$this->template->modal->widget('modal', $modal, 'append');
		
		$this->_page(FALSE);
	}
	
	
	protected function _pre_page(&$view, &$data, &$template){
		
	}
	
	protected function _render($merge_previous_state = FALSE){

		$this->template->reorder_partials(array('css','js_head','css_page'), array('js','js_page'))
					   ->render($merge_previous_state);
	}

	protected function _load_navigation($data){

		//array of section objects
		$navigation['nav'] = array(
		
		
			(object) array('section_name' => 'Home',  'section_uri' => '/'),
			
			(object) array('section_name' => 'Carousel', 'section_uri' => 'example/carousel'),	
			
			(object) array('section_name' => 'Hero', 'section_uri' => 'example/hero'),
						
			(object) array('section_name' => 'Extra', 'pages' => array(
			
					(object) array('page_name' => 'Login', 'page_uri' =>'example/signin'),
					
					(object) array('page_name' => 'Modal', 'page_uri' => 'example/modal')
					
				)
			)
	
		);
		
		$current_uri = $this->uri->uri_string();
		
		foreach($navigation['nav'] as $i=>$section){
			
			if (isset($section->section_uri) && $section->section_uri == $current_uri){
				
				$navigation['nav'][$i]->active = TRUE;
				
				break;
			}
		}
		
		$this->template->nav->set_default_view('layout/_nav', $data+array('nav'=>$navigation['nav']));
			
	}
	
	
	function live_update(){
			
		
		//handle refreshing of csrf tokens
		//timeout, auto-logout, etc.
		
		$this->_page(FALSE);

	}
	
	
	
}
//End of Winx_Controller.php