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
		exit(json_encode($text));
	}
	
	/**
	* Define api header
	*/

	function define_header() {
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json');
	}

	function mysql_clean($id,$replacer=true) {
		global $db;
		
		$id = strip_tags(trim($id));
		$id = htmlspecialchars($id);
		if($replacer) {
			$id = Replacer($id);
		}
		return $id;
	}

	/**
	* Cleans a string by putting it through multiple layers
	* @param : { string } { string to be cleaned }
	* @return : { string } { $string } { cleaned string }
	*/

	function Replacer($string) {
		//Wp-Magic Quotes
		$string = preg_replace("/'s/", '&#8217;s', $string);
		$string = preg_replace("/'(\d\d(?:&#8217;|')?s)/", "&#8217;$1", $string);
		$string = preg_replace('/(\s|\A|")\'/', '$1&#8216;', $string);
		$string = preg_replace('/(\d+)"/', '$1&#8243;', $string);
		$string = preg_replace("/(\d+)'/", '$1&#8242;', $string);
		$string = preg_replace("/(\S)'([^'\s])/", "$1&#8217;$2", $string);
		$string = preg_replace('/(\s|\A)"(?!\s)/', '$1&#8220;$2', $string);
		$string = preg_replace('/"(\s|\S|\Z)/', '&#8221;$1', $string);
		$string = preg_replace("/'([\s.]|\Z)/", '&#8217;$1', $string);
		$string = preg_replace("/ \(tm\)/i", ' &#8482;', $string);
		$string = str_replace("''", '&#8221;', $string);
		$array = array('/& /');
		$replace = array('&amp; ') ;
		return $string = preg_replace($array,$replace,$string);
	}
	
	function now() {
		return date('Y-m-d H:i:s', time());
	}
	
	function throw_msg($msg=NULL,$data=NULL)
	{
		$data = array('status' => "success", "msg" => $msg, "data" => $data);
		return json_encode($data);
	}

	function throw_err($msg)
    {
        throw new Exception($msg); 
    }
    function catchException($msg=NULL,$data=NULL)
    {
      	$data = array('status' => 'failure' , "msg" => $msg, "data" => $data);
    	return json_encode($data);
    }

    function validate_int($integer=NULL)
    {
    	if(is_int($integer)){
    		return true;
    	}else{
    		return false;
    	}
    }

    function define_post_header()
    {
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json');
		header('Access-Control-Allow-Methods: POST');
		header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

    }


?>