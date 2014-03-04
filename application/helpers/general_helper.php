<?php if (!defined("BASEPATH")) exit("No direct script access allowed");

function post($fields = array()){
    $CI =& get_instance();
    $post = array();
    foreach($_POST as $key=>$value){
        if (in_array($key,$fields)){
            $post[$key] = $CI->input->post($key) ? $CI->input->post($key) : NULL;
        }
		else if (in_array($key.'[]',$fields)){
			$post[$key] = implode('|', (array)$CI->input->post($key));
		}
    }
    return $post;
}

function attr($attr)
{
	
	if (!is_array($attr))
	{	
		return $attr;
	}	
	
    $str = "";
    foreach($attr as $key => $value)
    {    	
        if (strlen($value) > 0) 
        {    	
            $str .= htmlspecialchars(strip_tags($key)) . '=' . '"' . htmlspecialchars(strip_tags($value)) . '" ';
        }
    }
		
	return rtrim($str);
}

function tag($tag, $content = '', $attr = '')
{
    
	$tag = htmlspecialchars(strip_tags($tag));
	
	if ($attr)
	{	
		$attr = ' ' . attr($attr);
	}
		
	return $content === FALSE ? "<${tag}${attr} />" : "<${tag}${attr}>${content}</${tag}>";
}

function reindex_2d($obj, $index1, $index2 = FALSE, $key = FALSE){
		
	$data = array();
	
	foreach($obj as $o){
		$o = (object) $o;
		if (!isset($data[$o->$index1])){
			$data[$o->$index1] = array();
		}
		
		$val = $key ? $o->$key : $o;
		
		if ($index2){
			$data[$o->$index1][$o->$index2] = $val;
		}
		else {
			$data[$o->$index1][] = $val;
		}
	}
	
	return $data;
}

function reindex($obj, $index = FALSE, $key = FALSE) {
		
	if ($index === FALSE && $key === FALSE){
		
		return $obj;
	}	
		
	$data = array();
	
    foreach($obj as $o) {
    	
    	$o = (object) $o;
		
		if ($index === FALSE){
			
			$data[] = $key ? $o->$key : $o;
		}
		else {
			
			$data[$o->$index] = $key ? $o->$key : $o;
		}
        
	}

    return $data;
}

function array_swap_keys($array, $map, $preserve_missing = TRUE){
		
	foreach($array as $key=>$value){
		
		$key_found = array_key_exists($key, $map);
		
		if ($key_found){
			
			$array[$map[$key]] = $value;
		}
		
		if($key_found || !$preserve_missing){
			
			unset($array[$key]);
		}
	}
	
	return $array;					
}

function distance($lat1, $lon1, $lat2, $lon2, $unit = 'm') {
	 
	$theta = $lon1 - $lon2; 
	$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)); 
	$dist = acos($dist); 
	$dist = rad2deg($dist); 
	
	$miles = $dist * 60 * 1.1515;
	
	$unit = strtoupper(substr($unit,0,1));
 
	if($unit == "K")
	{
		return ($miles * 1.609344); 
	}
	elseif($unit == "N") {
		return ($miles * 0.8684);
	}
	else
	{
		return $miles;
	}
}


function is_assoc($array) {
	
  return (bool)count(array_filter(array_keys($array), 'is_string'));
  
}

//convenient parsing function, almost synonymous with str_replace
//accepts either a keyed array, or separate arrays of keys and values
//string should contain variable names in the form ${var_name}
function p($str, $vars, $values = FALSE){
	
	if ($values !== FALSE){
		
		$vars = array_combine((array)$vars, (array)$values);
	}

	foreach($vars as $var=>$value){
		
		//occasionally it's useful to silently overlook non-strings
		if (!is_array($value) && !is_object($value)){
			
			$str = str_replace('${'.$var.'}', $value, $str);	
		}
	}

	return $str;
}

function l($line, $vars, $values = FALSE){
	
	$line = lang($line);
	
	if ($line){
		
		$line = p($line, $vars, $values);
	}
	
	return $line;
}



function snippet($name){
		
	$snippets = array(
	
		'input'       => '<div class="w${w} ${error}">${label}${input}</div>',
		'multi_input' => '<div class="row"><div class="w${w} ${error}">${label}${input}</div></div>'
	);
	
	return $snippets[$name];
}

function csrf(){
	
	$CI =& get_instance();
	
	return array('name'=>$CI->security->get_csrf_token_name(),'value'=>$CI->security->get_csrf_hash());
}

function db_action(){
		
		$CI =& get_instance();
		
		$action = substr($CI->db->last_query(),0,6);
		
		switch ($action){
			
			case 'SELECT' :
				return 'retrieve';
			case 'INSERT' :
				return 'create';
			case 'UPDATE' :
				return 'edit';
			case 'DELETE' :
				return 'delete';
			default :
				return ''; 
		}
	}

function db_message($object, $success = TRUE, $record = FALSE, $action = FALSE){
			
	$type = $success ? 'add_notification_success' : 'add_error';	

	$message = $success ? 'Successfully ${actioned} the ${object}${record}.'
						: 'There was an error ${actioning} the ${object}${record}.';
	
	$record = $record === FALSE ? '' : ' <strong>' . $record . '</strong>';
	
	if ($action === FALSE){
		
		$action = db_action();
	}
	
	
	switch($action){
		
		case 'retrieve' :
			$actioned = 'retrieved';
			$actioning = 'retrieving';
			break;
		case 'create' :
			$actioned = 'created';
			$actioning = 'creating';
			break;
		case 'edit' :
			$actioned = 'edited';
			$actioning = 'editing';
			break;
		case 'delete' :
			$actioned = 'deleted';
			$actioning = 'deleting';
			break;	
		default :
			$actioned = $action;
			$actioning = $action;
			break;
	}

	return p($message, compact('object', 'record', 'action', 'actioned', 'actioning'));
}

function fullname($person, $reverse = FALSE, $email = FALSE,$prefix=''){
		
	$person = (array)$person;
	
	$name = $reverse ? $person[$prefix.'lname'] . ', ' . $person[$prefix.'fname'] : $person[$prefix.'fname'] . ' ' . $person[$prefix.'lname'];

	return $email ? safe_mailto($person[$prefix.'email'], $name) : $name;
}

function format_address($obj, $prefix = ''){
			
	//force object	
	$obj = (object)$obj;	
	
	$address1 = $prefix . 'address1';
	$address2 = $prefix . 'address2';
	$city = $prefix . 'city';
	$state = $prefix . 'province';
	$zip = $prefix . 'zip';
	
	$address = $obj->$address1 ? $obj->$address1 . '<br />' : '';
	$address .= $obj->$address2 ? $obj->$address2 . '<br />' : '';
	
	if ($obj->$city){
		$address .= $obj->$city;
	}
	if ($obj->$state || $obj->$zip){
		if ($obj->$city){
			$address .=  ', ';
		}	
		$address .= $obj->$state . ' ' . $obj->$zip . '<br />' ;
	}

	return $address;
}


function format_multiline($lines, $func=FALSE, $params=array(), $delim = '|'){
			
	if (!is_array($lines)){
		$lines = explode('|',$lines);
	}	

	if ($func){
		foreach($lines as $k=>$v){
			array_unshift($params,$v);
			$lines[$k] = call_user_func_array($func,$params);
			array_shift($params);
		}
	}
	return implode('<br />',$lines);
}


//not so intelligently formats phone numbers
//TODO: modify this to take a "Country" parameter
//taking out a lot of the guesswork...
function format_phone($phone){
		
	//remove everything except numbers
	$p = preg_replace('/\D/','', $phone);
	$l = strlen($p);
	
	//handle no area code case
	//either 7 digits for US 123-1234
	//or 8 digits for NI 1234-1234
	if ($l == 7 || $l == 8){
			
		return substr($p,0,$l-4) . '-' . substr($p,$l-4);
	}
	//handle area code case
	//either 10 digits for US (123) 123-1234
	//or 11 digits for NI (123) 1234-1234
	else if ($l == 10 || $l == 11){
		
		return '(' . substr($p,0,3) . ') ' . substr($p,3,$l-7) . '-' . substr($p,$l-4); 
	}
	//if greater than 11 digits, assume US number plus extension
	else if ($l > 11){
			
		return '(' . substr($p,0,3) . ') ' . substr($p,3,3) . '-' . substr($p,6,4) . ' x ' . substr($p,10);
	}
	
	//return original if no match found
	return $phone;
}

function money($number){
	  
	$number = (double)$number;
	
	if ($number < 0){
		
		return '<span class="red">-$'.number_format(-1*$number, 2).'</span>';
	}
	  
	return '$' . number_format($number, 2);
}

//TODO support any number of arguments..
function obj_merge($obj1, $obj2){
	
	return (object) array_merge((array)$obj1, (array)$obj2);
}

function timezones(){
	
	return array(
        'Pacific/Midway' => '(UTC-11:00) Midway Island, Samoa',
        'Pacific/Honolulu' => '(UTC-10:00) Hawaii-Aleutian',
        'Pacific/Marquesas' => '(UTC-09:30) Marquesas Islands',
        'Pacific/Gambier' => '(UTC-09:00) Gambier Islands',
        'America/Anchorage' => '(UTC-09:00) Alaska',
        'America/Ensenada' => '(UTC-08:00) Tijuana, Baja California',
        'Etc/GMT+8' => '(UTC-08:00) Pitcairn Islands',
        'America/Los_Angeles' => '(UTC-08:00) Pacific Time (US & Canada)',
        'America/Denver' => '(UTC-07:00) Mountain Time (US & Canada)',
        'America/Chihuahua' => '(UTC-07:00) Chihuahua, La Paz, Mazatlan',
        'America/Dawson_Creek' => '(UTC-07:00) Arizona',
        'America/Belize' => '(UTC-06:00) Saskatchewan, Central America',
        'America/Cancun' => '(UTC-06:00) Guadalajara, Mexico City, Monterrey',
        'Chile/EasterIsland' => '(UTC-06:00) Easter Island',
        'America/Chicago' => '(UTC-06:00) Central Time (US & Canada)',
        'America/New_York' => '(UTC-05:00) Eastern Time (US & Canada)',
        'America/Havana' => '(UTC-05:00) Cuba',
        'America/Bogota' => '(UTC-05:00) Bogota, Lima, Quito, Rio Branco',
        'America/Caracas' => '(UTC-04:30) Caracas',
        'America/Santiago' => '(UTC-04:00) Santiago',
        'America/La_Paz' => '(UTC-04:00) La Paz',
        'Atlantic/Stanley' => '(UTC-04:00) Falkland Islands',
        'America/Campo_Grande' => '(UTC-04:00) Brazil',
        'America/Goose_Bay' => '(UTC-04:00) Atlantic Time (Goose Bay)',
        'America/Glace_Bay' => '(UTC-04:00) Atlantic Time (Canada)',
        'America/St_Johns' => '(UTC-03:30) Newfoundland',
        'America/Araguaina' => '(UTC-03:00) UTC-3',
        'America/Montevideo' => '(UTC-03:00) Montevideo',
        'America/Miquelon' => '(UTC-03:00) Miquelon, St. Pierre',
        'America/Godthab' => '(UTC-03:00) Greenland',
        'America/Argentina/Buenos_Aires' => '(UTC-03:00) Buenos Aires',
        'America/Sao_Paulo' => '(UTC-03:00) Brasilia',
        'America/Noronha' => '(UTC-02:00) Mid-Atlantic',
        'Atlantic/Cape_Verde' => '(UTC-01:00) Cape Verde Is.',
        'Atlantic/Azores' => '(UTC-01:00) Azores',
        'Europe/Dublin' => '(UTC) Irish Standard Time : Dublin',
        'Europe/Lisbon' => '(UTC) Western European Time : Lisbon',
        'Europe/London' => '(GMT) Greenwich Mean Time : London, Belfast',
        'Africa/Abidjan' => '(GMT) Monrovia, Reykjavik',
        'Europe/Amsterdam' => '(UTC+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna',
        'Europe/Belgrade' => '(UTC+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague',
        'Europe/Brussels' => '(UTC+01:00) Brussels, Copenhagen, Madrid, Paris',
        'Africa/Algiers' => '(UTC+01:00) West Central Africa',
        'Africa/Windhoek' => '(UTC+01:00) Windhoek',
        'Asia/Beirut' => '(UTC+02:00) Beirut',
        'Africa/Cairo' => '(UTC+02:00) Cairo',
        'Asia/Gaza' => '(UTC+02:00) Gaza',
        'Africa/Johannesburg' => '(UTC+02:00) Johannesburg, Harare, Pretoria',
        'Asia/Jerusalem' => '(UTC+02:00) Jerusalem',
        'Europe/Athens' => '(UTC+02:00) Athens',
        'Europe/Minsk' => '(UTC+02:00) Minsk',
        'Asia/Damascus' => '(UTC+02:00) Syria',
        'Europe/Moscow' => '(UTC+03:00) Moscow, St. Petersburg, Volgograd',
        'Africa/Addis_Ababa' => '(UTC+03:00) Nairobi',
        'Asia/Tehran' => '(UTC+03:30) Tehran',
        'Asia/Dubai' => '(UTC+04:00) Abu Dhabi, Muscat',
        'Asia/Yerevan' => '(UTC+04:00) Yerevan',
        'Asia/Kabul' => '(UTC+04:30) Kabul',
        'Asia/Yekaterinburg' => '(UTC+05:00) Ekaterinburg',
        'Asia/Tashkent' => '(UTC+05:00) Tashkent',
        'Asia/Kolkata' => '(UTC+05:30) Chennai, Kolkata, Mumbai, New Delhi',
        'Asia/Katmandu' => '(UTC+05:45) Kathmandu',
        'Asia/Dhaka' => '(UTC+06:00) Astana, Dhaka',
        'Asia/Novosibirsk' => '(UTC+06:00) Novosibirsk',
        'Asia/Rangoon' => '(UTC+06:30) Yangon (Rangoon)',
        'Asia/Bangkok' => '(UTC+07:00) Bangkok, Hanoi, Jakarta',
        'Asia/Krasnoyarsk' => '(UTC+07:00) Krasnoyarsk',
        'Asia/Hong_Kong' => '(UTC+08:00) Beijing, Chongqing, Hong Kong, Urumqi',
        'Asia/Irkutsk' => '(UTC+08:00) Irkutsk, Ulaan Bataar',
        'Australia/Perth' => '(UTC+08:00) Perth',
        'Australia/Eucla' => '(UTC+08:45) Eucla',
        'Asia/Tokyo' => '(UTC+09:00) Osaka, Sapporo, Tokyo',
        'Asia/Seoul' => '(UTC+09:00) Seoul',
        'Asia/Yakutsk' => '(UTC+09:00) Yakutsk',
        'Australia/Adelaide' => '(UTC+09:30) Adelaide',
        'Australia/Darwin' => '(UTC+09:30) Darwin',
        'Australia/Sydney' => '(UTC+10:00) Sydney, Canberra, Brisbane',
        'Australia/Hobart' => '(UTC+10:00) Hobart',
        'Asia/Vladivostok' => '(UTC+10:00) Vladivostok',
        'Australia/Lord_Howe' => '(UTC+10:30) Lord Howe Island',
        'Etc/GMT-11' => '(UTC+11:00) Solomon Is., New Caledonia',
        'Asia/Magadan' => '(UTC+11:00) Magadan',
        'Pacific/Norfolk' => '(UTC+11:30) Norfolk Island',
        'Asia/Anadyr' => '(UTC+12:00) Anadyr, Kamchatka',
        'Pacific/Auckland' => '(UTC+12:00) Auckland, Wellington',
        'Etc/GMT-12' => '(UTC+12:00) Fiji, Kamchatka, Marshall Is.',
        'Pacific/Chatham' => '(UTC+12:45) Chatham Islands',
        'Pacific/Tongatapu' => '(UTC+13:00) Nuku Alofa',
        'Pacific/Kiritimati' => '(UTC+14:00) Kiritimati'
	);
	
}

function generate_key($length=32, $friendly = FALSE, $case_sensitive = TRUE, $randomness = 4){
		
	$chars = 'abcdefghijklmnopqrstuvwxyz0123456789';	
	
	if ($case_sensitive){
		
		$chars .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	}

	if ($friendly){
		
		$chars = str_replace(array('i','l','o','0','1'), '', $chars);
	}
		
	return substr(str_shuffle(str_repeat($chars,$randomness)),0,$length);
}

function generate_id($length=8){
		
	if ($length <= 0){
		
		return '';
	}	
	
	$min = '1'.str_repeat('0',$length-1);
	$max = str_repeat('9',$length);
	
	return rand($min,$max);
}

function strip_nondigits($string){
	
	return preg_replace('/[^0-9\.]/','', $string);
}

function parse_expression($exp, $vars = array()){
		
	$values = array_values($vars);
	$vars = array_map('strtolower',array_keys($vars));
	
	//TODO: Write special eval function for MATH expressiongs only!!
	
	return eval(str_replace($vars, $values, 'return ' . strtolower($exp) . ';'));
}