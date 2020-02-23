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



    public static function listForId($id)
    {

        $captured = Captured::select(
            [
                'id',
                'id_user',
                'id_pokemon',
                'nickname',
                'level',
                'detail_note'
            ]);
            
        $captured->where('id', $id);

        return $captured->get();
            
    }



    public static function insert($params){

        $captured = new Captured;

        $captured->intermediador  = $params->intermediador;
        $captured->plataforma     = $params->plataforma;
        $captured->situacao       = $params->situacao;
        $captured->liaceito       = $params->liaceito;
        $captured->corretor       = $params->corretor;
        $captured->empresa        = $params->empresa;
        $captured->cargo          = $params->cargo;

        $captured->save();

        return $captured;

    }
    


    public static function update($params){

        $captured = Captured::find($params->membro);
        
        $captured->situacao   = $params->situacao;

        $captured->save();

        return $captured;

    }

}
