<?php

namespace Core;

use App\Exceptions\NotFoundException;
use Exception;
use http\Url;

class Route
{
	public $routes = [
		'GET' => [],
		'POST' => [],
		'PUT' => [],
		'DELETE' => [],
	];

	public static function load(string $file): Route
	{
		$route = new static;
		require $file;
		return $route;
	}

	public function get(string $uri, array $controller): void
	{
		$this->routes['GET'][$uri] = $controller;
	}

	public function post(string $uri, array $controller): void
	{
		$this->routes['POST'][$uri] = $controller;
	}

	public function put(string $uri, array $controller): void
	{
		$this->routes['PUT'][$uri] = $controller;
	}

	public function delete(string $uri, array $controller): void
	{
		$this->routes['DELETE'][$uri] = $controller;
	}

	public function call(string $uri, string $requestMethodType)
	{
		

		/*$callback = array_key_exists($uri, $this->routes[$requestMethodType]);

		if (!$callback){
			throw new NotFoundException();
		}

		return $this->callAction(
			reset($this->routes[$requestMethodType][$uri]),
			end($this->routes[$requestMethodType][$uri])
		);*/
	}

	protected function callAction(string $controller, string $action){
		$controller = "App\\Controllers\\{$controller}";
		$controller = new $controller;

		if (!method_exists($controller, $action)){
			throw new Exception("{$controller} does not have the {$action} method");
		}

		return $controller->$action();
	}
}