<?php

namespace App\Repositories\Captured;

use Illuminate\Http\Request;

interface CapturedInterface
{
    public static function list();

    public static function insert($params);

    public static function update($params);

    public static function delete($params);


}