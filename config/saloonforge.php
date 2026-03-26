<?php

// config for Tobya/SaloonForge
return [

    'routes' => [
        //'selector' => \Tobya\SaloonForge\Selectors\RouteSelector::class,
        'selector' => \App\Http\Integrations\PhotoApiRouteSelector::class,
        'prefix' => ['/'],
        'exclude' =>
            [
             'filter' =>        ['/'],
             'unnamed' => true,
            ]

        ]


];
