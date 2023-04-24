<?php 

namespace App\Middleware;

abstract class BaseMiddleware {
	abstract public function handle();
}