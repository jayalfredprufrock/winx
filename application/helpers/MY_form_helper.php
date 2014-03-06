<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------

/**
 * CodeIgniter Form Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/form_helper.html
 */

function form_actions($object, $options = array()){
	
	$CI =& get_instance();
	
	return anchor($CI->app_state['previous_uri'], 'Cancel', array('class'=>'btn'))
		   . ' ' . tag('button', 'Save '.$object, array('type'=>'submit','value'=>'refresh','class'=>'btn btn-primary','autocomplete'=>FALSE,'data-loading-text'=>'processing...', 'name'=>'form_action'))
		   . ' ' . tag('button', 'Save & Close', array('type'=>'submit', 'value'=>'redirect', 'class'=>'btn btn-info','autocomplete'=>FALSE,'data-loading-text'=>'processing...', 'name'=>'form_action'));
}


function input_text($name, $value = '', $opt = array()){
		
	//allow the overriding of text input type for supporting
	//hidden and password inputs	
	$opt['type'] = isset($opt['type']) ? $opt['type'] : 'text';
		
	$input = input_merge_opt($name, $value, $opt);
	
	if ($input['prepend'] || $input['append'])
	{
		
		$class = '';
		
		$input['markup'] = p($input['markup'],'input','<div class="${class}">${input}</div>');
		
		if ($input['prepend'])
		{
			$class .= 'input-prepend';		
			$input['markup'] = p($input['markup'],'input','<span class="add-on">' . $input['prepend'] . '</span>${input}');
		}
		
		if ($input['append'])
		{
			$class .= ' input-append';	
			$input['markup'] = p($input['markup'],'input','${input}<span class="add-on">' . $input['append'] . '</span>');
		}
		
		$input['markup'] = p($input['markup'],'class', $class);
	}
	
	return input_markup($input);
}


function input_password($name, $value = '', $opt = array()){
	
	$opt['type'] = 'password';
	$opt['autocomplete'] = 'off';
	
	return input_text($name, $value, $opt);
}


function input_hidden($name, $value = '', $opt = array()){
	
	$opt['type'] = 'hidden';
	$opt['label'] = FALSE;
	
	return input_text($name, $value, $opt);
}


function input_textarea($name, $value = '', $opt = array()){
	
	$opt['type'] = 'textarea';
	
	$input = input_merge_opt($name, $value, $opt);
	
	return input_markup($input);
}


function input_dropdown($name, $options, $value, $opt = array())
{
	$opt['type'] = 'dropdown';
	
	$input = input_merge_opt($name, $value, $opt);
	
	$attr = attr(array_diff_key(clean_opt($input),array_fill_keys(array('name','value'),0)));
	
	
	foreach($options as $val=>$label){
		
		$label = lang($val);
		if ($label){
			$options[$val] = $label;	
		}
	}
	
	$input['form_input'] = form_dropdown($input['name'], $options, $input['value'], $attr);
	
	return input_markup($input);
}

function input_multiselect($name, $options, $value, $opt = array()){
	
	$opt['type'] = 'multiselect';
	
	$input = input_merge_opt($name, $value, $opt);
	
	$attr = attr(array_diff_key(clean_opt($input),array_fill_keys(array('name','value'),0)));
	
	
	foreach($options as $val=>$label){
		
		$label = lang($val);
		if ($label){
			$options[$val] = $label;	
		}
	}
	
	$input['form_input'] = form_multiselect($input['name'], $options, explode('|', $input['value']), $attr);
	
	return input_markup($input);
}


function input_checkbox($name, $value = 1, $opt = array()){
		
	$opt['type'] = 'checkbox';	
	
	$opt['id'] = isset($opt['id']) ? $opt['id'] : $name . '_' . $value;
	
	//for checkboxes, the default should place the input before the label
	$opt['markup'] = isset($opt['markup']) ? $opt['markup'] : '${input}${label}';
	
	$input = input_merge_opt($name, $value, $opt);
	
	$input['value'] = $value;
	
	$input['checked'] = set_checkbox($name, $value, $input['checked']);
	
	return  input_markup($input);
}

function input_radio($name, $value = 1, $opt = array()){
	
	$opt['type'] = 'radio';
	
	$opt['id'] = isset($opt['id']) ? $opt['id'] : $name . '_' . $value;
	
	//for radios, the default should place the input before the label
	$opt['markup'] = isset($opt['markup']) ? $opt['markup'] : '${input}${label}';
	
	$input = input_merge_opt($name, $value, $opt);
	
	$input['value'] = $value;
	
	$input['checked'] = set_radio($name, $value, $input['checked']);
	
	return input_markup($input);
}


function input_options($type, $name, $options, $checked, $opt = array()){
		
	$markup = isset($opt['options_markup']) ? $opt['options_markup'] : '<div class="options">${options}</div>';	
	$opt['markup'] = isset($opt['markup']) ? $opt['markup'] : '<div>${input}${label}</div>'; 
	
	$o = '';
	foreach($options as $value=>$label){
				
		$input_opt = $opt;
		
		$input_opt['label'] = $label;
		
		$input_opt['checked'] = in_array($value, is_object($checked) ? (array)$checked->$name : (array) $checked);
				
		$o .= $type == 'radio' ? input_radio($name, $value, $input_opt) : input_checkbox($name, $value, $input_opt);		
	}
	
	return p($markup, array('options'=>$o));
}

function input_phone($name, $value = '', $opt = array())
{
	
	$opt['prepend'] = isset($opt['prepend']) ? $opt['prepend'] : icon('phone');
	$opt['type'] = 'tel';
		
	return input_text($name, $value, $opt);
}


function input_email($name, $value = '', $opt = array())
{
	
	$opt['prepend'] = isset($opt['prepend']) ? $opt['prepend'] : icon('envelope');
	$opt['type'] = 'email';
	
	return input_text($name, $value, $opt);
}

function input_date($name, $value = '', $opt = array())
{
	
	$opt['append'] = isset($opt['append']) ? $opt['append'] : icon('calendar');
	$opt['class'] = 'date';
	$opt['data-date-format'] = 'mm/dd/yy';
	
	return input_text($name, $value, $opt);	
}

//overridden to output unordered list and support the limit parameter
function validation_errors($prefix = '', $suffix = '', $limit = 5)
{
	if (FALSE === ($OBJ =& _get_validation_object()) ||  $OBJ->error_string() == '')
	{
		return '';
	}

	return '<ul class="error">' . $OBJ->error_string($prefix, $suffix, $limit) . '</ul>';
} 


function input_merge_opt($name, $value, $opt){
	
	$options = array(
		
		'name'         => $name,
		'orig_value'   => $value,
		'column_name'  => str_replace(array('${i}','[]'),'', $name),
		'checked'      => FALSE,
		'label'        => TRUE,
		'prepend'      => FALSE,
		'append'       => FALSE,
		'markup'       => '${label}${input}',
		'multi'        => FALSE,
		'multi_index'  => FALSE,
		'multi_label'  => FALSE,
		'values'       => array($value)
	);
	
	$options['id'] = $options['column_name'];
	
	//override defaults
	$options = array_merge($options, $opt);
	
	if (!$options['id']){
		
		unset($options['id']);
	}
	
	$column_name = $options['column_name'];

	if (is_array($value)){
		
		$value = (object)$value;
	}

	//Multiple Inputs
	if ($options['multi'] || $options['multi_index'] !== FALSE){
		
		$CI =& get_instance();
		
		//multiple values stored delimited in this input
		if ($options['multi']){
				
			if ($options['multi_index'] === FALSE){
				
				$options['multi_index'] = 0;
			}	
	
			//object value could be an array or delimited string
			$obj_value = is_object($value) && property_exists((object)$value,$options['column_name']) ? explode('|',$value->{$options['column_name']}) : $options['values'];

			//if it is time to quit, return FALSE
			if (!isset($obj_value[$options['multi_index']]) && $options['multi_index'] >= count($options['values']) 
															&& $options['multi_index'] >= count($CI->input->post($options['column_name']))){
				
				return FALSE;		
			}
		}
		//value stored in the usual way
		else {

			$obj_value = array($options['multi_index']=>is_object($value) && property_exists($value,$options['column_name']) ? $value->{$options['column_name']} : $value);
		}
		
		$options['data-multi-index'] = $options['multi_index'];
		
		if (isset($options['id'])){
			
			$options['id'] = $options['column_name'] . '_' . $options['multi_index'];
		}	
		
		
		//Grab error message for this specific input
		$CI->load->library('form_validation');
		
		$errors = $CI->form_validation->error_array();
		
		$options['error'] = '';
		if (count($errors)){
			
			$error_input = p($name,'i', $options['multi_index']);
			
			if (isset($errors[$error_input])){
				
				$options['error'] = $errors[$error_input];
			}
			
		}
		
		//try to set value first to previous post value, then to object value
		$options['value'] = set_value($options['column_name'].'[]', isset($obj_value[$options['multi_index']]) ? $obj_value[$options['multi_index']] : FALSE ) ;		
		
	}
	//Normal Case
	else {
		
		$options['error'] = form_error($name);
		
		//set value first to previous post value, then to object value, or finally to default value
		$options['value'] = set_value($options['column_name'], is_object($value) && property_exists($value,$options['column_name']) ? $value->{$options['column_name']} : $value);	
	}
	

	//auto add label
	if ($options['label'] === TRUE)
	{
		//try and lookup label in lang file
		//fall back on a humanized version of column name
		$options['label'] = lang($options['column_name']) ? lang($options['column_name'], $options['id']) : 
															form_label(humanize($options['column_name']), $options['id']); 
	}
	//no label
	else if ($options['label'] === FALSE)
	{
		$options['label'] = '';
	}
	//custom label
	else 
	{	
		$options['label'] = form_label($options['label'], $options['id']);
	}
	
	return $options;
}

function input_markup($input){
	
	$type = $input['type'];
	if ($type == 'text' || $type == 'email' || $type == 'tel' || $type == 'hidden'){
			
		$type = 'input';
	}
	
	//call the appropriate CI form input function
	//but allow overrides (namely for form_dropdown)
	if (isset( $input['form_input'])){
		
		$form_input = $input['form_input'];
		unset($input['type']);
	}
	else {
		
		$form_input =  call_user_func('form_' . $type, clean_opt($input));
	}

	
	$markup = p($input['markup'],array('input','label','error','error_message'), 
				  	      		 array($form_input, $input['label'], $input['error'] ? 'error' : '', $input['error']));
					  
	
	//does this input support multiple values
	if ($input['multi']){
		
		$markup = p($markup,array('i'=>$input['multi_index'],'n'=>$input['multi_index']+1));
		
		$input['multi_index'] = $input['multi_index'] + 1;
		
		if (!$input['multi_label']){
			
			$input['label'] = FALSE;
		}
		
		$next_input = input_merge_opt($input['name'], $input['orig_value'], $input);

		if ($next_input !== FALSE){
			
			return $markup . input_markup($next_input);	
		}
	}

	return $markup;				  
}


function attr_list(){
	
	return array('maxlength','size','style','class','multiselect');
}

function form($action ='', $attr = array(), $hidden = array()){
	
	$attr['novalidate'] = 'novalidate';
	
	return form_open($action, $attr, $hidden);
}


function remove_non_attributes($opt){
	
}

function only_attributes($opt){
	
}


function clean_opt($opt)
{
	
	$clean = array('size','style','multiselect','label','prepend','append','markup','options_markup','error','column_name','orig_value','multi','multi_index','multi_label','values','form_input');
	
	return array_diff_key($opt, array_fill_keys($clean,0));
}
 



/* End of file MY_form_helper.php */
/* Location: ./application/helpers/MY_form_helper.php */
