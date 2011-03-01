<?php

/** 
@file poop.php
@brief File containing the poop class definition
@author er453r
*/

/**
@mainpage Poop
@version 0.1
@author er453r

@section intro_sec Introduction

This is poop. A Php Object Oriented Portal for all your needs!

More info at:
http://er453r.pl/poop

@section install_sec Installation

Just copy the files!

@section about About

Everybody likes poop.

*/

/** 
@brief poop - object definition
*/
class poop{
	/** Title of the site */
	var $title = 'Poop';
	/** Title of the homepage */
	var $homepage_title = 'Home';
	/** Url of the site */
	var $site_addr = '';
	/** Timezone to use by the PHP */
	var $time_zone = 'Europe/Warsaw';
	/** Internal PHP encoding */
	var $encoding = 'UTF-8';
	
	/** Path to search for includes */
	var $includes_dir = 'includes';
	/** Path to search for classes */
	var $classes_dir = 'classes';
	/** Path to search for pages */
	var $pages_dir = 'pages';
	/** Path to search for scripts */
	var $scripts_dir = 'scripts';
	/** Path to search for styles */
	var $styles_dir = 'styles';
	
	/** Constructs poop */
	function __construct(){
		$GLOBALS['poop_engine'] = $this;
		ob_start();
		session_start();
	}

	/** Starts POOP after config */
	function init(){
		$this->start_time = microtime(1);
		
		$this->base_title = $this->title;
		date_default_timezone_set($this->time_zone);
		mb_internal_encoding($this->encoding);
		
		function __autoload($class){
			$file = $this->classes_dir.'/'.$class.'.php';
			
			if(file_exists($file))
				require_once($file);
			else
				exit('Error! No definition of class: <b>'.$class.'</b>!');
		}
		
		foreach(glob($this->includes_dir.'/*.php') as $file)
			include($file);
		
		$this->process_request();
		$this->add_path($this->homepage_title,'',0);
		$this->process_content();
	}

	/** Processes the request uri */
	function process_request(){		
		$this->request_uri = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		
		if(strpos($this->request_uri,$this->site_addr)===FALSE)
			$this->teleport($this->site_addr);
			
		if($this->request_uri == $this->site_addr.'/'.$this->styles_dir.'/'){
			header('content-type: text/css'); 

			foreach(glob($this->styles_dir.'/*.css') as $file)
				$string .= file_get_contents($file);
				
			exit($string);
		}
		
		if($this->request_uri == $this->site_addr.'/'.$this->scripts_dir.'/'){
			header('content-type: text/javascript'); 

			foreach(glob($this->scripts_dir.'/*.js') as $file)
				$string .= file_get_contents($file);
				
			exit($string);
		}

		$this->request = str_replace($this->site_addr.'/','',$this->request_uri);
		$this->request = explode('/',$this->request);

		$this->url = $this->site_addr.'/';
	}
	
	/** Gets the current part from request uri */
	function get_request(){
		return($this->request[0]);
	}
	
	/** Gets content from pages based on a request */
	function process_content(){
		if($request = $this->get_request()){
			$file = $this->pages_dir.'/'.$request.'.php';
			
			if(file_exists($file))
				$this->content = $this->inject($file);
			else{
				$this->content = 'Nie ma takiej strony';
				$this->add_path('Błąd','Nie ma tu nic.');
			}
		}
		else{
			$index = $this->pages_dir.'/index.php';
			
			if(file_exists($index))
				$this->content = $this->inject($index);
			else
				$this->content = 'Nie ma jeszce nic';
		}
	}
	
	/** Sets the current path info
	@param name The name to be displayed
	@param title The title to be set
	@param to_title If it should affect the page title
	*/
	function add_path($name,$title = '',$to_title = 1){
		if(isset($this->path))
			$this->url .= '/'.array_shift($this->request);

		if($title == '')
			$title = $name;
			
		if($to_title)
			$this->title = $name.' | '.$this->title;

		$this->path []= array('name'=>$name,'title'=>$title,'link'=>$this->url);
	}

	/** Displays the path
	@return String containing path in a format of ul>li 
	*/	
	function get_path(){
		$string .= '<ul id="poop-path">';
		
		foreach($this->path as $step)
				$string .= '<li><a href="'.$step['link'].'" title="'.$step['title'].'">'.$step['name'].'</a></li>';

		$string .= '</ul>';
		
		return($string);
	}

	/** Includes the file, but buffers the output in a string
	@return String with the buffered output
	*/	
	function inject($file){
		ob_start();
		include($file);
		$buffer = ob_get_contents();
		ob_end_clean();
		return($buffer);
	}

	/** Sends a header location with a given url
	@param url The url to teleport to 
	*/		
	function teleport($url){
		header('location: '.$url);
		exit;
	}

	/** Returns a time since poop init function.
	@param precision How many digits to display after the decimal point 
	@note Use this in your footer.
	*/		
	function get_running_time($precision = 4){
		return(round(microtime(1)-$this->start_time,$precision));
	}
}
?>