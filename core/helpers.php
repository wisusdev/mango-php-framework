<?php

use Core\Debug;

function authedUser() {

    $token = (new class { 
        use \App\Traits\JWT; 
    })->bearerToken();

    return (new \App\Models\User())->find(['token' => $token]);
}

function dd($data, $return = false): void
{
	Debug::var_dump($data, $return);
}