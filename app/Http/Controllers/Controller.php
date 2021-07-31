<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function index ()
    {
        console.log("ggevdddddddd" );

        $car= Car::all();
        foreach(car as $c)
            console.log("ggevdddddddd" );
      //  return view('car', ['car' => $car, ]);
    //
    }
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
