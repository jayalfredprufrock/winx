<?php if (!defined("BASEPATH")) exit("No direct script access allowed");

class Modal extends Widget {
	
	
	public function display($modal = array()) {
			
		
		//default type
		if (empty($modal['type'])){
			
			$modal['type'] = 'alert';
		}
		
		
		//alert type defaults
		$defaults = array(
			
			'id'                  => FALSE,
			'title'               => '',
			'body'                => '',
			
			'form_action' 		  => '',
			'form_hidden'      	  => array(),
			
			'close_btn_text'      => 'Close',
			'close_btn_url'       => FALSE,
			'close_btn_attr'      => array('class'=>'btn','data-dismiss'=>'modal'),
			
			'secondary_btn_text'  => FALSE,
			'secondary_btn_url'   => FALSE,
			'secondary_btn_attr'  => array('class'=>'btn btn-info'),
						
			'primary_btn_text'    => FALSE,
			'primary_btn_url'     => FALSE,
			'primary_btn_attr'    => array('class'=>'btn btn-primary'),
			
			
			//allows for the complete overridding of the buttons output			
			'buttons'             => ''
	
		);
		
		
		//overrides for other types
		switch ($modal['type']){
			
			case 'form' :
				
				$defaults['close_btn_text'] = 'Cancel';
				$defaults['primary_btn_text'] = 'Save';
				$defaults['primary_btn_attr']['data-loading-text'] = 'Saving...';
				
				break;
				
			case 'confirm' :
				
				$defaults['primary_btn_attr']['class'] = 'btn btn-danger';
				
				$defaults['close_btn_text'] = 'Cancel';
				$defaults['primary_btn_text'] = 'OK';
				
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
				}
			}

		}
		
		$this->load->view('layout/_modal', $modal);
	}
	
	
}
