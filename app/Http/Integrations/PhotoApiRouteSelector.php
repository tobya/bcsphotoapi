<?php

  namespace App\Http\Integrations;


  use Illuminate\Routing\Route;
  use Illuminate\Routing\RouteCollection;
  use Tobya\SaloonForge\Selectors\RouteSelector;

  class PhotoApiRouteSelector extends RouteSelector
  {
      /**
     * Get the underlying route collection.
     *

     */
      public function getRoutes()
      {
          $routes = collect(parent::getRoutes());

          $r = $routes->filter(function (Route $route) {

              $result = false;
             ray( $route->uri() . "\n");
              if (str($route->uri() )->startsWith('gallery')) {
                 ray( "\n starts with gallery");
                  $result = true;
              }
              //  echo $route->getAction('as') . "\nz";
              if ($route->getAction('as') <> ''){
                  $result = true;
              }

              if ($result){
                //  echo "\n adding";
              }

              return $result;

          });
          ray('set of routes');
         ray($r);
         return $r;

      }

  }
