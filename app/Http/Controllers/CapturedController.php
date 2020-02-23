<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Helper\ResponseBuilder;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

use App\Repositories\Captured\CapturedRepository;

class CapturedController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }



    public function list()
    {

        try
        {
            
            

            $client = new Client(['base_uri' => 'https://pokeapi.co/api/v2/',
            'http_errors' => false
            ]);

            $listPokemons = [];
            
            $captured = CapturedRepository::list();

            foreach($captured as $data)
            {

                $response = $client->request('GET', "pokemon/".$data->id."/");
                $details = (string) $response->getBody();

                $details_arr = json_decode($details, true);

                $listPokemons[] = [
                    "id" => $data->id,
                    "id_user" => $data->id_user,
                    "id_pokemon" => $data->id_pokemon,
                    "nickname" => $data->nickname,
                    "level" => $data->level,
                    "detail_note" => $data->detail_note,
                    'name' => $details_arr['name'],
                    'id' => $details_arr['id'],
                    'type' => $details_arr['types']['0']['type']['name'],
                    'image' => $details_arr['sprites']['front_default'],
                    'height' => $details_arr['height'],
                    'weight' => $details_arr['weight'],
                    'base_experience' => $details_arr['base_experience']
                ];

            }

            $status = '200';
            $message = "Registros listados com sucesso!";
            $success = 'true';
            return ResponseBuilder::result($success,$status,$message,$listPokemons);

        }
        catch (\Exception $e) 
        {
            
            return ResponseBuilder::result('false','500',$e->getMessage());

        }

    }


    
    public function listForId($id)
    {

        try 
        {

            $data = CapturedRepository::listarPorId($id);
            $status = '200';
            $message = "Registro listado com sucesso!";
            $success = 'true';
            return ResponseBuilder::result($success,$status,$message,$data);

        }
        catch (\Exception $e) 
        {
            
            return ResponseBuilder::result('false','500',$e->getMessage());

        }

    }



    public function insert(Request $request)
    {

        try 
        {

            $data = CapturedRepository::insert($request);
            $status = '200';
            $message = "Registro inserido com sucesso!";
            $success = 'true';
            return ResponseBuilder::result($success,$status,$message,$data);

        }
        catch (\Exception $e) 
        {
            
            return ResponseBuilder::result('false','500',$e->getMessage());

        }

    }



    public function update($params)
    {

        try 
        {

            $data = CapturedRepository::update($params);
            $status = '200';
            $message = "Registro atualizado com sucesso!";
            $success = 'true';
            return ResponseBuilder::result($success,$status,$message,$data);

        }
        catch (\Exception $e) 
        {
            
            return ResponseBuilder::result('false','500',$e->getMessage());

        }

    }

}
