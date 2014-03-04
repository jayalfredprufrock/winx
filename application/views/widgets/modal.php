<?php if (!defined("BASEPATH")) exit("No direct script access allowed");

class Modal extends Widget {
	
	private $_ci;
	
	function __construct()
    {
        
		$this->_ci = & get_instance();	
    }
	
	public function content($modal = array()) {
			
		
		//default type
		if (empty($modal['type'])){
			
			$modal['type'] = 'alert';
		}
		
		
		//alert type defaults
		$defaults = array(
			
			'id'                  => FALSE,
			'title'               => '',
			'body'                => '',
			
			'form_action' 		  => FALSE,
			'form_hidden'      	  => array(),
			
			'close_btn_text'      => 'Close',
			'close_btn_url'       => '',
			'close_btn_attr'      => array('class'=>'btn','data-dismiss'=>'modal','data-previous-state'=>TRUE),
			
			'secondary_btn_text'  => FALSE,
			'secondary_btn_url'   => FALSE,
			'secondary_btn_attr'  => array('class'=>'btn btn-info','autocomplete'=>FALSE),
						
			'primary_btn_text'    => FALSE,
			'primary_btn_url'     => FALSE,
			'primary_btn_attr'    => array('class'=>'btn btn-primary','autocomplete'=>FALSE),
			
			
			//allows for the complete overridding of the buttons output			
			'buttons'             => ''
	
		);
		
		//overrides for other types
		switch ($modal['type']){
			
			case 'form' :
				
				$defaults['form_action'] = '';
				$defaults['close_btn_text'] = 'Cancel';
				$defaults['primary_btn_text'] = 'Save';
				$defaults['primary_btn_attr']['data-loading-text'] = 'Saving...';
				$defaults['primary_btn_attr']['type'] = 'submit';
				
				break;
				
			case 'confirm' :
				
				$defaults['form_action'] = '';
				$defaults['primary_btn_attr']['class'] = 'btn btn-danger';		
				$defaults['close_btn_text'] = 'Cancel';
				$defaults['primary_btn_text'] = 'OK';
				$defaults['primary_btn_attr']['data-loading-text'] = 'Processing...';
				$defaults['primary_btn_attr']['type'] = 'submit';
				
				break;	
		}
		
		
		//merge with user specified overrides
		$modal = array_merge($defaults, $modal);
		
		if (!$modal['buttons']){

			foreach(array('close_btn_','secondary_btn_','primary_btn_') as $btn){
					
				$text = $modal[$btn.'text'];
				
				if ($text !== FALSE){	
					
					$url = 	$modal[$btn.'url'];
					$attr = $modal[$btn.'attr'];
						
					$modal['buttons'] .= $modal[$btn.'url'] === FALSE ? tag('button', $text, $attr)
																	  : anchor($url, $text, $attr);
					if ($btn == 'close_btn_'){
						
						$attr['class'] .= ' close';
						$modal['close_button'] = $modal['close_btn_url'] === FALSE ? tag('button', '&times;', $attr)
																	               : anchor($url, '&times;', $attr);
					}
					
				}
			}

		}
		
		return $this->_ci->load->view('widgets/modal', $modal, TRUE);
	}
	
	
}
