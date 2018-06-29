<?php

include 'common_functions.php';
include 'classes/Dbconnection.class.php';
include 'classes/Post.class.php';
include 'classes/Category.class.php';

$conn = new Dbconnection();
$db = $conn->connect();
