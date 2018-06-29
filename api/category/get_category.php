<?php
	include '../../includes/config.inc.php';
	define_header();

	$category = new Category($db);

	if(!empty($_GET)){
		$params = $_GET;
		$data = $category->get_category($params);
		echo $data;
	}else{
		
		$data = $category->get_category();
		echo $data;
	}