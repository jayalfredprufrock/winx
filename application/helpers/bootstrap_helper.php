<?php if (!defined("BASEPATH")) exit("No direct script access allowed");

	function icon($icon, $url = FALSE, $attr=array(), $state = '', $type='icon'){
		
		if (!isset($attr['class'])){
			$attr['class'] = '';
		}
		
		$attr['class'] .= ' ' . $type;
		
		if ($type == 'icon'){
			
			$attr['class'] .= '-' . $icon;			
			$content = '  ';
		}
		else {
			
			$content = 'text';
		}

		if ($state){
			
			$attr['class'] .= ' ' . $type . '-' . $state;
		}
		
		$content = $type != 'icon' ? $icon : ' ';
			
		$attr['class'] = ltrim($attr['class']); 

		return $url ? anchor($url,$content, $attr) : '<i ' . attr($attr) . '>'.$content.'</i>';
	}
	

	function tabs($tabs, $position = 'left', $active = FALSE, $id_prefix='')
	{	
		if (!$tabs)
			return '';
		
		if (!is_array($tabs)){
			
			return $tabs;
		}
		
		
		$menu = '<ul class="nav nav-tabs">';
		$content = '<div class="tab-content">';
		
		
		
		foreach($tabs as $name=>$tab)
		{		
			if ($tab)
			{
				$name = strtolower(underscore($name));	
				$menu .= '<li';	
				$content .= '<div id="' . $id_prefix . $name . '" class="tab-pane';	
				
				if ($active === FALSE || $active === $name)
				{					
					$menu .= ' class="active"';
					$content .= ' active';
					$active = TRUE;
				}

				$menu .= '><a href="#' . $id_prefix . $name . '" data-toggle="tab" class="no-ajax">' . humanize(preg_replace('/tab(\d)+_/i','',$name)) . '</a></li>'; 
				$content .= '">' . $tab . '</div>';
			}
		}
		
		$menu .= '</ul>';
		$content .= '</div>';
		
		return '<div class="tabbable tabs-' . $position . '">' . (($position != 'below') ? $menu . $content : $content . $menu) . '</div>';
	}

	
	function add_panel($side, $panels, $span = FALSE){
		
		$panel = array($side => array('add'=> $panels));
		
		if ($span !== FALSE){
			
			$panel[$side]['span'] = $span;
		}
		
		return $panel;
	}
	
	function remove_panel($side, $panels){
		
		return array($side => array('remove'=>$panels));
	}


	function icon_switch($case, $icons){
			
		$icon = '';	
		
		if (is_numeric($case) && !is_assoc($icons)){
				
			$case %= count($icons);
		}
		
		if (isset($icons[$case])){
			
			if (is_array($icons[$case])){
				
				$icon = $icons[$case]['icon'];
				$url = isset($icons[$case]['url']) ? $icons[$case]['url'] : FALSE;
				$attr = isset($icons[$case]['attr']) ? $icons[$case]['attr'] : array();
				
				$icon = icon($icon, $url, $attr);
			}
			else {
				
				$icon = icon($icons[$case]);
			} 
		}	
		
		return $icon;
		
	}
	
	function icon_switch_class($icon, $case, $classes){
			
		if (is_array($icon)) {
			
			if (!isset($icon['attr'])){
				
				$icon['attr'] = array();
			}
			
			if (!isset($icon['attr']['class'])){
				
				$icon['class'] = array();
			}
		}
		else {
			
			$icon = array('icon'=>$icon,'attr'=>array('class'=>''));
		}
			

		$icons = array();
		foreach($classes as $i=>$class){
				
			$class_icon = $icon;	
			$class_icon['attr']['class'] .=  ' ' . $class;
			ltrim($class_icon['attr']['class']);
			
			$icons[$i] = $class_icon;
		}
		
		return $case === FALSE ? $icons : icon_switch($case, $icons);
	}

	function label($text, $url = FALSE, $attr=array(), $state = ''){
		
		return icon($text, $url, $attr, $state, 'label');
	}
	
	function badge($text, $url = FALSE, $attr=array(), $state =''){
		
		return icon($text, $url, $attr, $state, 'badge');
	}
	
	
?>
