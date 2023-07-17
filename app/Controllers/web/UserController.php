<?php

namespace App\Controllers\Web;

use App\Controllers\BaseController;
use Core\Params;

class UserController extends BaseController {
    
    public function show(){
        dd(Params::all());
    }
}