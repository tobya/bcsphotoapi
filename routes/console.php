<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');



Artisan::command('photoapi:generate', function () {
    $rz = \Illuminate\Support\Facades\Route::getRoutes();

    foreach ($rz as $route) {
        echo "\n $route->uri()";
       //print_r($route->parameterNames());
        $params = collect($route->parameterNames());
        $json_params = json_encode($params);
        echo $json_params;
        if ($route->getName() != null){

        $name = str($route->getName())->replace(['.','-',' '],['','','']);
        } else {

            $name = str($route->uri())->slug();
        }
        Artisan::call('saloon:request', ['integration' => 'photoApi',
            'name' => $name,
            '--method' => $route->methods()[0],
            '--route' => $route->uri() ,
            '--params' => $json_params,
        ]);

            //saloon:request PhotoApi Gallery --route=/gallery/xdemodate
    }
    //print_r($r);
});
