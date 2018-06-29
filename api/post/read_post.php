<?php

	include '../../includes/config.inc.php';
	define_header();

	$post = new Post($db);

	if(!empty($_GET)){
		$params = $_GET;
		$data = $post->read_post($params);
		echo $data;
	}else{
		
		$data = $post->read_post();
		echo $data;
	}