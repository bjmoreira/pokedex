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
    public function __construct()
    {
        $this->middleware('auth');
    }



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

                $response = $client->request('GET', "pokemon/".$data->id_pokemon."/");
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


    /**
     * Função que traz a evolução do pokemon, a mesma está incompleta não tive tempo para terminar
     *
     * @return $listPokemons
     */
    public function listEvolution($idPokemon)
    {

        try 
        {

            $client = new Client(['base_uri' => 'https://pokeapi.co/api/v2/',
            'http_errors' => false
            ]);
            $response = $client->request('GET', "pokemon/".$idPokemon."/");
            $details = (string) $response->getBody();
            $details_arr = json_decode($details, true);

            $client2 = new Client(['base_uri' => $details_arr['species']['url'],
            'http_errors' => false
            ]);
            $response2 = $client2->request('GET', "");
            $details2 = (string) $response2->getBody();
            $details_arr2 = json_decode($details2, true);

            $client3 = new Client(['base_uri' => $details_arr2['evolution_chain']['url'],
            'http_errors' => false
            ]);
            $response3 = $client3->request('GET', "");
            $details3 = (string) $response3->getBody();
            $details_arr3 = json_decode($details3, true);

            $response4 = $client->request('GET', "pokemon/".$details_arr3['chain']['evolves_to'][0]['species']['name']."/");
            $details4 = (string) $response4->getBody();
            $details_arr4 = json_decode($details4, true);

            $listPokemons[] = [
                'name' => $details_arr3['chain']['evolves_to'][0]['species']['name'],
                'image' => $details_arr4['sprites']['front_default'],
            ];

            $status = '200';
            $message = "Registro listado com sucesso!";
            $success = 'true';
            return ResponseBuilder::result($success,$status,$message,$listPokemons);

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
            $status = '201';
            $message = "Registro inserido com sucesso!";
            $success = 'true';
            return ResponseBuilder::result($success,$status,$message,$data);

        }
        catch (\Exception $e) 
        {
            
            return ResponseBuilder::result('false','500',$e->getMessage());

        }

    }



    public function update(Request $request)
    {

        try 
        {

            $data = CapturedRepository::update($request);
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



    public function delete(Request $request)
    {

        try 
        {

            $data = CapturedRepository::delete($request);
            $status = '202';
            $message = "Registro excluido com sucesso!";
            $success = 'true';
            return ResponseBuilder::result($success,$status,$message,$data);

        }
        catch (\Exception $e) 
        {
            
            return ResponseBuilder::result('false','500',$e->getMessage());

        }

    }

}
