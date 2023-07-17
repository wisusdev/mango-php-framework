<?php

namespace Core;

use App\Exceptions\NotFoundException;
use Exception;

class Route
{
	/**
	 * Keep all the routes
	 *
	 * @var array
	 */
	private static array $routes = array();

	/**
	 * Route Request Method
	 *
	 * @var string
	 */
	private string $method;

	/**
	 * Route Path
	 *
	 * @var string
	 */
	private string $path;

	/**
	 * Route Action
	 *
	 * @var string
	 */
	private string $action;

	/**
	 * Constructor
	 *
	 * @param string $method
	 * @param string $path
	 * @param string $action
	 */
	public function __construct(string $method, string $path, string $action)
	{
		$this->method = $method;
		$this->path = $path;
		$this->action = $action;
	}

	/**
	 * Add GET requests to routes
	 *
	 * @param string $path
	 * @param string $action
	 * @return void
	 */
	public static function get(string $path, string $action): void
	{
		self::$routes[] = new Route('get', $path, $action);
	}

	/**
	 * Add POST requests to routes
	 *
	 * @param string $path
	 * @param string $action
	 * @return void
	 */
	public static function post(string $path, string $action)
	{
		self::$routes[] = new Route('post', $path, $action);
	}

	public static function put(string $path, string $action)
	{
		self::$routes[] = new Route('put', $path, $action);
	}

	public static function delete(string $path, string $action)
	{
		self::$routes[] = new Route('delete', $path, $action);
	}

	/**
	 * Get the routes array
	 *
	 * @return array
	 */
	public static function getRoutes(): array
	{
		return self::$routes;
	}

	/**
	 * Handle route to destinated controller function
	 *
	 * @param string $path
	 * @return void
	 */
	public static function handle(string $path): void
	{
		$desired_route = null;
		foreach (self::$routes as $route) {
			$pattern = $route->path;
			$pattern = str_replace('/', '\/', $pattern);

			$pattern = '/^' . $pattern . '$/i';
			$pattern = preg_replace('/{[A-Za-z0-9]+}/', '([A-Za-z0-9]+)', $pattern);

			if (preg_match($pattern, $path, $match)) {
				$desired_route = $route;
			}
		}

		$url_parts = explode('/', $path);
		$route_parts = explode('/', $desired_route->path);

		foreach ($route_parts as $key => $value) {
			if (!empty($value)) {
				$value = str_replace('{', '', $value, $count1);
				$value = str_replace('}', '', $value, $count2);

				if ($count1 == 1 && $count2 == 1) {
					Params::set($value, $url_parts[$key]);
				}
			}
		}


		if ($desired_route) {
			if ($desired_route->method != strtolower($_SERVER['REQUEST_METHOD'])) {
				http_response_code(404);

				echo '<h1>Route Not Allowed</h1>';

				die();
			} else {
				$actions = explode('@', $desired_route->action);

				$controllerRoute = str_replace('/', '\\', $actions[0]);
				$controllerClass = '\\App\\Controllers\\' . $controllerRoute;

				$controller = new $controllerClass();

				$middlewares = $controller->getMiddlewares();

				foreach ($middlewares as $middleware) {
					$middleware->handle();
				}

				if(!method_exists($controller, $actions[1])){
					throw new Exception(
						"{$controller} does not have the {$actions[1]} method."
					);
				}

				echo call_user_func(array($controller, $actions[1]));
			}
		} else {
			throw new NotFoundException();
		}
	}
}
