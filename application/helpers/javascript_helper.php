<?php

function script($js){
	
	return tag('script', $js, array('type'=>'text/javascript'));
}

function json($obj){
	
	$CI =& get_instance();
	
	if ($CI->input->is_ajax_request() && $CI->input->server('REQUEST_METHOD') === 'POST'){
		
		$obj = (array)$obj + array('js'=>js_var('csrf', csrf()));
	}
	
	$CI->output->enable_profiler(FALSE);
	
	$CI->output->set_content_type('application/json');
	$CI->output->set_header('Cache-Control: no-cache, must-revalidate');
	$CI->output->set_header('Expires: '.date('r', time()+(86400*365)));
	
	$CI->output->set_output(json_encode($obj));
	$CI->output->_display();		
	exit();
}


function js_var($name, $value, $global = TRUE){
	
	return ($global ? 'window.' : 'var ') . $name . ' = ' . json_encode($value) . ';';
}

function js_vars($vars, $global = TRUE){
	
	$vars = '';
	foreach($vars as $name=>$value){
		
		$vars .= js_var($name, $value, $global);
	}
	
	return $vars;
}


function js_notification($type, $message){
		
	$fadeout = js_bool($type != 'error');	
	
	ob_start();
?>
	
	$('.notifications').notify({
		
	    message: { html: true, text: '${message}' },
	    closable: true,
	    type: '${type}',
	    fadeOut: {enabled: ${fadeout}, delay: 5000}
	    
	}).show();
	
<?php return js_replace_vars(ob_get_clean(), get_defined_vars());
}



function js_post($url, $data = '', $callback = FALSE){
	
	if (!is_string($data)){
	
		$data = json_encode($data);		
	}

	$callback = $callback === FALSE ? 'processResponse(resp);' : $callback;
	
	ob_start();
?>
	
	$.post('${url}', ${data}, function(resp, textStatus, jqXHR){
		
		${callback}
		
	}, 'json');

<?php return js_replace_vars(ob_get_clean(), get_defined_vars());
}



function js_replace_vars($js, $vars){
	
	foreach($vars as $var=>$value){
		
		if (is_string($value)){
			
			$js = str_replace('${'.$var.'}', $value, $js);	
		}
	}
	
	
	return $js;
}


function js_bool($bool){
	
	return $bool ? 'true' : 'false';
}


function json_escape_function($json){
	    	
	 return preg_replace_callback('/(?<=:)"function\((?:(?!}").)*}"/','json_unescape',$json);
}
	 
function json_unescape($string){
 	    	
 	 return str_replace('\\"','\"',substr($string[0],1,-1));
}
