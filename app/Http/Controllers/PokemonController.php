<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Http\Helper\ResponseBuilder;

class PokemonController extends Controller
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

  /**
   * Show the application list.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function list()
  {

    try
        {

          $client = new Client(['base_uri' => 'https://pokeapi.co/api/v2/',
          'http_errors' => false
          ]);
      
          $response = $client->request('GET', "pokemon/?limit=90");
          $data = (string) $response->getBody();
      
          $pokemon_arr = json_decode($data, true);      
      
          foreach ($pokemon_arr['results'] as $result) 
          {
            
            $response2 = $client->request('GET', "pokemon/".$result['name']."/");
            $details = (string) $response2->getBody();
      
            $details_arr = json_decode($details, true);
      
      
            $pokemon[] = array (
              'name' => $result['name'],
              'id' => $details_arr['id'],
              'type' => $details_arr['types']['0']['type']['name'],
              'image' => $details_arr['sprites']['front_default'],
              'height' => $details_arr['height'],
              'weight' => $details_arr['weight'],
              'base_experience' => $details_arr['base_experience']
            );
      
          }
      
          $status = '200';
          $message = "Registros listados com sucesso!";
          $success = 'true';
          return ResponseBuilder::result($success,$status,$message,$pokemon);

        }
        catch (\Exception $e) 
        {
            
            return ResponseBuilder::result('false','500',$e->getMessage());

        }
        
  }

}
