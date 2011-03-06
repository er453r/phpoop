<?php

function poop(){
	return($GLOBALS['poop_engine']);
}

function prefix(){
	return($GLOBALS['poop_engine']->site_addr);
}

function get_request(){
	return($GLOBALS['poop_engine']->request[0]);
}

function get_running_time(){
	return($GLOBALS['poop_engine']->get_running_time());
}

function get_title(){
	return($GLOBALS['poop_engine']->title);
}

function get_base_title(){
	return($GLOBALS['poop_engine']->base_title);
}

function get_site_addr(){
	return($GLOBALS['poop_engine']->site_addr);
}

function get_keywords(){
	return($GLOBALS['poop_engine']->keywords);
}

function get_description(){
	return($GLOBALS['poop_engine']->description);
}

function get_path(){
	return($GLOBALS['poop_engine']->get_path());
}

function get_url(){
	return($GLOBALS['poop_engine']->url);
}

function add_path($name,$title = ''){
	return($GLOBALS['poop_engine']->add_path($name,$title));
}

function get_content(){
	return($GLOBALS['poop_engine']->content);
}

?>