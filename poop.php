<?php

/** 
@file poop.php
@brief File containing the poop class definition
@author er453r
*/

/**
@mainpage Poop
@version 0.3
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
	/** Title array */
	private $title = array();
	/** Path array */
	private $path = array();
	/** Site addr */
	private $site_addr;
	/** Site contents */
	private $content;
	
	/** Path to search for includes */
	private $includes_dir = 'includes';
	/** Path to search for classes */
	private $classes_dir = 'classes';
	/** Path to search for pages */
	private $pages_dir = 'pages';
	/** Path to search for scripts */
	private $scripts_dir = 'scripts';
	/** Path to search for styles */
	private $styles_dir = 'styles';
	
	private $ini_section = 'poop';
	
	/** Current url */
	private $url;
	
	static private $instance;
	
	/** Constructs poop */
	public function __construct(){
		ob_start();
		session_start();
		poop::$instance = $this;
		
		$this->init();
	}

	/** Starts POOP after config */
	private function init(){
		$this->start_time = microtime(1);
		
		$this->config = parse_ini_file('config.ini', true);
		
		$this->site_addr = $this->config[$this->ini_section]['site_addr'];	
		$this->title []= $this->config[$this->ini_section]['site_title'];
		
		
		date_default_timezone_set( $this->config[$this->ini_section]['time_zone'] );
		mb_internal_encoding( $this->config[$this->ini_section]['encoding'] );
		
		// Enables autoloading classes
		function __autoload($class){
			$file = $this->classes_dir.'/'.$class.'.php';
			
			if(file_exists($file))
				require_once($file);
			else
				exit('Error! No definition of class: <b>'.$class.'</b>!');
		}
		
		// Includes other things
		foreach(glob($this->includes_dir.'/*.php') as $file)
			include($file);
		
		// Analyzes the request uri
		$this->process_request();
		
		$this->add_path($this->config[$this->ini_section]['homepage_title'], '', 0);
				
		// Processes content
		$this->process_content();
	}

	/** Processes the request uri */
	private function process_request(){		
		$this->request_uri = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
		
		// If this script is trying to handle something no in its address -> go home.
		//if(strpos($this->request_uri, $this->site_addr) === false)
		//	$this->teleport($this->site_addr);

		// If styles are requested
		if($this->request_uri == $this->site_addr.'/'.$this->styles_dir.'/'){
			header('content-type: text/css'); 
			
			$string = '';
			foreach(glob($this->styles_dir.'/*.css') as $file)
				$string .= file_get_contents($file);
				
			exit($string);
		}
		
		// If javascript is requested
		if($this->request_uri == $this->site_addr.'/'.$this->scripts_dir.'/'){
			header('content-type: text/javascript'); 

			$string = '';
			foreach(glob($this->scripts_dir.'/*.js') as $file)
				$string .= file_get_contents($file);
				
			exit($string);
		}
		
		// Creating an array of requests
		$this->request = str_replace($this->site_addr.'/', '', $this->request_uri);
		$this->request = explode('/', $this->request);
		
		// Current url
		$this->url = $this->site_addr;
	}
	
	/** Gets the current part from request uri */
	public function get_request(){
		return($this->request[0]);
	}
	
	/** Gets the prefix for uris */
	public function prefix(){
		return($this->site_addr);
	}
	
	/** Gets content from pages based on a request */
	private function process_content(){
		$requests = $this->request;
		$path = $this->pages_dir.'/';
		$n = 0;
		
		foreach($requests as $x => $request){
			if(is_dir($path.$request)){
				$path .= $request.'/';
				$n = $x + 1;
				
				if($n != count($requests))
					$this->inject($path.'index.php');
			}
			else
				break;
		}
		
		if($n == count($requests))
			$file = 'index.php';
		else
			$file = $requests[$n].'.php';
			
		$file = $path.$file;
		
		if(file_exists($file))
			$this->content = $this->inject($file);
		else{
			$this->content = 'Nie ma takiej strony';
			$this->add_path('B³¹d','Nie ma tu nic.');
		}
	}
		
	/**
	 * Sets the current path info
	 * This shifts current request stack!
	 * @param $name The name to be displayed
	 * @param $title The title to be set
	 * @param $to_title If it should affect the page title
	 */
	public function add_path($name, $title = '', $to_title = 1){
		// Shifts current request stack!
		if(count($this->path))
			$this->url .= '/'.array_shift($this->request);

		if($title == '')
			$title = $name;
			
		if($to_title)
			$this->title []= $name;

		$this->path []= array('name' => $name, 'title' => $title, 'link' => $this->url);
	}
	
	static public function add_to_path($name, $title = '', $to_title = 1){
		poop::$instance->add_path($name, $title, $to_title);
	}
	
	public function get_current_url(){
		return $this->url;		
	}
	
	static public function current_url(){
		return poop::$instance->get_current_url();
	}

	/**
	 * Return the path array
	 * 
	 * @return path array
	 */
	public function get_path(){		
		return($this->path);
	}
	
	/**
	 * Return the title array
	 * 
	 * @return title array
	 */
	public function get_title(){		
		return($this->title);
	}
	
	/**
	 * Returns the content string
	 * 
	 * @return content string
	 */
	public function get_content(){		
		return($this->content);
	}
	
	/**
	 * Includes the file, but buffers the output in a string
	 * 
	 * @param $file File to include
	 * @return String with the buffered output
	 */
	private function inject($file){
		ob_start();
		include($file);
		$buffer = ob_get_contents();
		ob_end_clean();
		return($buffer);
	}

	/**
	 * Sends a header location with a given url
	 * 
	 * @param $url The url to teleport to 
	 */
	private function teleport($url){
		header('location: '.$url);
		exit;
	}

	/**
	 * Returns a time since poop init function
	 * 
	 * @param $precision How many digits to display after the decimal point
	 * @note Use this in your footer
	 * @return Seconds since poop init
	 */
	public function get_running_time($precision = 4){
		return(round(microtime(1)-$this->start_time,$precision));
	}
}
?>