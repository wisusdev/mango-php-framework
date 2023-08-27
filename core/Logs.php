<?php

namespace Core;

class Logs
{
	public static function debug($log_msg, $type = 'DEBUG'): void
	{
		$trace = debug_backtrace();

		if(is_array($log_msg)){
			$claveBuscada = 'password';
			
			array_walk($log_msg, function (&$valor, $clave) use ($claveBuscada) {
				if ($clave === $claveBuscada) {
					$valor = str_repeat('*', strlen($valor));
				}
			});

			$log_msg = json_encode($log_msg);
		}

		$dataToLog = array(
			'[' . date("Y-m-d H:i:s") . ']',
			$_SERVER['REMOTE_ADDR'],
			$type,
			$trace[0]['file'],
			$trace[0]['line'],
			$log_msg
		);

		$data = implode(" - ", $dataToLog);

		$log_filename = 'storage/logs';
		if (!file_exists($log_filename))
		{
			mkdir($log_filename, 0775, true);
		}

		$log_file_data = $log_filename . '/log-' . date('d-m-y') . '.log';
		file_put_contents($log_file_data, $data . PHP_EOL, FILE_APPEND);
	}
}