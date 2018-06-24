<?php
	/**
	* Print an array in pretty way 
	* @param : { string / array } { $text } { Element to be printed }
	* @param : { boolean } { $pretty } { false by default, prnints in pretty way if true }
	*/

	function pr($text,$pretty=false) {
		if(!$pretty) {
			print_r($text);
		} else {
			echo "<pre>";
			print_r($text);
			echo "</pre>";
		}
	}

	/**
	* Print an array in pretty way and exit right after
	* @param : { string / array } { $text } { Element to be printed }
	*/

	function pex($text) {
		exit(pr($text,true));
	}
	
	/**
	* Define api header
	*/

	function define_header() {
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json');
	}


?>