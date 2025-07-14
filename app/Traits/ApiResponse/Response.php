<?php
namespace App\Traits\ApiResponse;

use Illuminate\Database\RecordNotFoundException;
use Exception;

Trait Response
{
    protected $statusCode;
    protected $message;

    public function response($data, $exception = '')
    {
        $this->statusCode = 200;
        $this->message = 'Data retrieved successfully';

        if(!$data) {
            $this->statusCode = 404;
            $this->message = 'Data not found';
            throw new RecordNotFoundException($this->message, $this->statusCode = 404);
        }

        if($exception) {
            $this->message = $exception->getMessage();
            $this->statusCode = 500;
        }

        return response()->json(['data' => $data, 'message' => $this->message], $this->statusCode);
    }
}