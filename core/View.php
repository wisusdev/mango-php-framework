<?php

namespace Core;

use Latte;

class View {

	private static string $path = './resources/views/';

	public static function render(string $view, array $parameters = [])
	{
		$viewFile = self::$path . $view . '.php';

		if(!is_file($viewFile)){
			die(sprintf('No existe la vista %s', $view));
		}

		return self::getContents($viewFile, $parameters);
	}

	public static function getContents(string $file, array $parameters = []): void
	{
		extract($parameters, EXTR_SKIP);
		unset($parameters);

		ob_start();
		require_once $file;
		unset($file);
	}
}