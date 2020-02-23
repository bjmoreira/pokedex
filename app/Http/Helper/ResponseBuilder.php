<?php

namespace App\Http\Helper;


class ResponseBuilder
{

    public static function result($success="",$status="",$message="",$data="")
    {
        
        $retorno = [
            "success" => $success,
            "status" => $status,
            "message" => $message,
            "data" => $data
        ];

        return response()->json($retorno, $status);

    }

}