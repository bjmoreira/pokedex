<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class PokemonController extends Controller
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

  /**
   * Show the application list.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function list()
  {
    $client = new Client();

    try {



        $client = new Client();
        $response = $client->get('https://pokeapi.co/api/v2/pokemon/?limit=964');
        $pokemon = $response->getBody(true);

        return $pokemon;


    } catch (RequestException $e) {
        return $e->getRequest();

    }



  }

}
