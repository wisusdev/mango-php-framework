<?php

namespace App\Controllers;

class HomeController
{
	public function index(){
		echo "index YES";
	}

	public function show($params){
		print_r($params);
		echo 'show YES';
	}
}