<?php
	include '../../includes/config.inc.php';
	define_post_header();

	$post = new Post($db);

	$params = $_POST;
	$response = $post->update_post($params);
	echo $response;