<?php if (!defined("BASEPATH")) exit("No direct script access allowed");

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
            $str .= $key . '=' . '"' . htmlentities($value) . '" ';
        }
    }
		
	return rtrim($str);
}

function tag($tag, $content = '', $attr = '')
{
    
	if ($attr)
	{	
		$attr = ' ' . attr($attr);
	}
		
	return "<${tag}${attr}>${content}</${tag}>";
}

