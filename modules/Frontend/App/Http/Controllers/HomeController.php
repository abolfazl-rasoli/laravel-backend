<?php


namespace Frontend\App\Http\Controllers;


use App\Http\Controllers\Controller;

class HomeController  extends Controller
{

    public function index()
    {
        return view("Frontend::home");
    }

}
