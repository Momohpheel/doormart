<?php
namespace App\Trait;

trait Response {

    public function success($message, $data, $status = 200)
    {
        return response()->json([
            "message" => $message,
            "data" => $data,
        ], $status);

    }

    public function error($message, $status = 400)
    {
        return response()->json([
            "error" => $message,
        ], $status);

    }
}
