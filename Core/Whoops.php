<?php

namespace Core;

use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use Whoops\Util\Misc;

class Whoops
{
	public function __construct()
	{
		$run = new Run();
		$handler = new PrettyPageHandler();

		// Set the title of the error page:
		$handler->setPageTitle("Whoops! There was a problem.");

		// Set the code editor
		$handler->setEditor("phpstorm");

		$run->pushHandler(new PrettyPageHandler());
		$run->pushHandler(new JsonResponseHandler());

		$run->pushHandler($handler);

		// Add a special handler to deal with AJAX requests with an
		// equally-informative JSON response. Since this handler is
		// first in the stack, it will be executed before the error
		// page handler, and will have a chance to decide if anything
		// needs to be done.
		if (Misc::isAjaxRequest()) {
			$run->pushHandler(new JsonResponseHandler);
		}

		// Register the handler with PHP, and you're set!
		$run->register();
	}

	public static function run(){
		new Whoops();
	}
}