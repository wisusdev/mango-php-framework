<?php

namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
use App\Middlewares\AuthMiddleware;
use App\Models\User;

class UserController extends BaseController {

    public function __construct()
    {
        $this->registerMiddleware(new AuthMiddleware());
    }

    public function show(){
        echo 'YES';
        $user = new User();
        $user->find();
    }
}