<?php
namespace App\Repositories\Captured;


use App\Models\Captured;
use Illuminate\Http\Request;


use App\Repositories\Captured\CapturedInterface as CapturedInterface;

class CapturedRepository implements CapturedInterface
{

    public static function list()
    {

        return Captured::select(
            [
                'id',
                'id_user',
                'id_pokemon',
                'nickname',
                'level',
                'detail_note'
            ])->get();
    
    }


    public static function insert($params){

        $captured = new Captured;

        $captured->id_user      = $params->id_user;
        $captured->id_pokemon   = $params->id_pokemon;
        $captured->nickname     = $params->nickname;
        $captured->level        = $params->level;
        $captured->detail_note  = $params->detail_note;

        $captured->save();

        return $captured;

    }
    


    public static function update($params){

        $captured = Captured::find($params->id);
        
        $captured->nickname     = $params->nickname;
        $captured->level        = $params->level;
        $captured->detail_note  = $params->detail_note;

        $captured->save();

        return $captured;

    }



    public static function delete($params){

        $captured = Captured::find($params->id);
        
        $captured->delete();

        return '';

    }

}
