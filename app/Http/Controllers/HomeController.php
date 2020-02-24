<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class HomeController extends Controller
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
     * Show the application index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data['url'] = env('APP_URL');
        $data['id_user'] = Auth::user()->id;

        return view('home',$data);
    }

    /**
     * Show the application index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function capturados()
    {
        $data['url'] = env('APP_URL');
        $data['id_user'] = Auth::user()->id;

        return view('capturados',$data);
    }

}
