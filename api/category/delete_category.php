<?php
	include '../../includes/config.inc.php';
	define_post_header();

	$category = new Category($db);

	$params = $_POST;
	$response = $category->delete_category($params);
	echo $response;