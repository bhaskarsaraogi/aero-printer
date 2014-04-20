<?php 

function view($view, $data = null) {
	if($data) extract($data);	
	$view_path = 'views/'.$view.'.view.php';
	require 'views/layout.php';
}

function getcss($view) {
	// if($view == "views/index.view.php") {
		return "css/full-slider.css";
	// }
}