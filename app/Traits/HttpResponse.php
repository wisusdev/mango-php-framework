<?php

namespace App\Traits;

use App\Constants\HttpStatus;

trait HttpResponse
{
	/**
	 * Send Success API Response
	 *
	 * @param array $data
	 * @param string $message
	 * @param int $status
	 * @return bool|string
	 */
    public function sendSuccess(array $data, string $message = 'Data Fetched Successfully', int $status = HttpStatus::OK): bool|string
	{
        $this->headers($status);

        return json_encode(array(
            'response' => $data,
            'message' => $message,
            'code' => $status,
        ));
    }

    public function sendError(string $message = 'Data Fetch Failed', int $status = HttpStatus::BAD_REQUEST, ?array $data = []): bool|string
	{
        $this->headers($status);

        return json_encode(array(
            'errors' => $data,
            'message' => $message,
            'code' => $status,
        ));
    }

    public function sendValidationError(array $data = []): void
	{
        $status = HttpStatus::UNPROCESSABLE_ENTITY;

        $this->headers($status);

        echo json_encode(array(
            'errors' => $data,
            'message' => 'Data Validation Error',
            'code' => $status,
        ));
    }


    private function headers($status): void
	{
        // clear the old headers
        header_remove();
        // set the actual code
        http_response_code($status);
        // set the header to make sure cache is forced
        header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");
        // treat this as json
        header('Content-Type: application/json');
        // validation error, or failure
        header('Status: ' . HttpStatus::getLabel($status));
    }
}