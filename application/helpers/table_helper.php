<?php if (!defined("BASEPATH")) exit("No direct script access allowed");

function column_slider($data, $func = FALSE, $params=array(), $delim='|'){
		
		$values = explode($delim, $data);
		
		if (count($values) == 1){
			
			if ($func){
				array_unshift($params,$data);
				$data = call_user_func_array($func, $params);
				array_shift($params);
			}
			
			return $data;
		}
		
		$slider = '<div class="column-slider">';
		foreach ($values as $v){
			if ($func){
				array_unshift($params,$v);
				$v = call_user_func_array($func, $params);
				array_shift($params);
			}
			
			$slider .= 	'<div>' . $v . '</div>';
			
		}
		
		return $slider . icon('arrow-right',FALSE,array('title'=>implode(", ",$values))) . '</div>';
		
}


function icon_column_array(){
	
	return array('class'=>'col-icon', 'searchable'=>FALSE, 'sortable'=>FALSE, 'visibility_toggle'=>FALSE, 'title'=>'');
}


function icon_column_switch($cell_value, $icons, $icon_column = array()){
		
	if (!isset($icon_column['sortable'])){
		
		$icon_column['sortable'] = TRUE;
	}	
	
	$icon_column += icon_column_array();
	
	
	
	$icon_column['cell_value'] = $cell_value;
	
	$icon_column['callbacks'] = array(array('function'=>'icon_switch','parameters'=>array('cell_value',$icons)));
		
	return $icon_column;
}

function icon_column_switch_class($cell_value, $icon, $classes, $icon_column = array()){
	
	return icon_column_switch($cell_value, icon_switch_class($icon, FALSE, $classes), $icon_column);
}


function icon_column_popover($popover){
	
	$popover += icon_column_array();
	
	$popover['cell_value'] = icon($popover['icon'], FALSE, array('title'=>$popover['title'],'data-placement'=>'bottom','data-content'=>$popover['content'],'class'=>'pop-over'));
	
	$popover['title'] = '';
	
	return $popover;
}

function badge_column($text, $tip = FALSE, $state = ''){
	
	$badge['tip'] = $tip === FALSE ? $text : $tip;
	$badge['badge'] = $text;

	return build_link_column($badge, $state, 'badge');
}

function label_column($text, $tip = FALSE, $state = ''){
	
	$label['tip'] = $tip === FALSE ? $text : $tip;
	$label['label'] = $text;

	return build_link_column($badge, $state, 'label');
}


function build_link_column($link, $state = '', $type = 'icon'){
		
	if (!isset($link['sortable']) && $type != 'icon'){
		
		$icon_column['sortable'] = TRUE;
	}	
	
	$link += icon_column_array();
	
	$link['cell_value'] = icon($link[$type],isset($link['url']) ? $link['url'] : FALSE,array('title'=>$link['tip']), $state, $type);
	
	return $link;
}


function link_view($url){
	
	return build_link_column(array('url'=>$url, 'icon'=>'file', 'tip'=>'View _SINGULARNAME_'));
}

function link_edit($url){

	return build_link_column(array('url'=>$url, 'icon'=>'pencil', 'tip'=>'Edit _SINGULARNAME_'));
}

function link_delete($url){
	
	return build_link_column(array('url'=>$url, 'icon'=>'remove', 'tip'=>'Delete _SINGULARNAME_'));
}






function action_delete($ajax){
		
		$action = array('name'=>'delete', 'icon'=>'remove', 'tip'=>'Delete _SINGULARNAME_');
		
		$action['js'] = "rowDelete(table, row, '$ajax', '_SINGULARNAME_');";
		
		return build_action_column($action);
}


function action_inline_edit($ajax, $selector = 'td:not(.col-icon)'){
		
	$action = array('name'=>'inline_edit', 'icon'=>'pencil', 'tip'=>'Edit _SINGULARNAME_');
	
	$action['js'] = "rowEdit(table, row, '$ajax', '$selector');";
	
	return build_action_column($action);	
}

function action_details($js = ''){
	
	$action = array('name'=>'details', 'icon'=>'circle-plus', 'tip'=>'View _SINGULARNAME_ details');

	$action['js'] = "rowDetails(table, row);" . $js;	
	
	return build_action_column($action);
}


function build_action_column($action){
	
	$action += icon_column_array();

	$action['cell_value'] = icon($action['icon'],FALSE, array('title'=>$action['tip']));
	
	unset($action['icon'], $action['tip']);
	
	return $action;
}
 
 
 
 

