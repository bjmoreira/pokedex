<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Captured extends Model
{

    protected $table = "captured";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_user',
        'id_pokemon',
        'nickname',
        'level',
        'detail_note'
    ];
    

}