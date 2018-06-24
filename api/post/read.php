<?php

include '../../includes/config.inc.php';
define_header();
$post = new POST($db);
$data = $post->read();
echo $data;