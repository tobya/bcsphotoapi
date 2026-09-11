<?php

// config for Tobya/SaloonForge
return [
    'integrations' => [
        'Photos2' => [
            'routes' => [

          //  'selector_class' => \App\Http\Integrations\PhotoApiRouteSelector::class,
            'selector_class' => \Tobya\SaloonForge\Selectors\RouteSelector::class,
            'forgeroute_class' => \Tobya\SaloonForge\Extensions\ForgeRoute::class,

            'exclude' =>
                [
                  'middleware'  =>  ['web'],
                 'filter'       =>  ['somenonese*'],
                 'unnamed' => true,
                ],

            'include' =>
                [
                'filter' =>       [],

                'middleware' => ['api'],


                ]
        ],
             'namespace' => 'Bcsapi\\V5\\Photo',
          'output' => [
              'dir' => base_path('Modules/bcsapi/V5/'),
              'copy' => [
                  'active' => true,
                  'destination' => 'C:\\Development\\github\\packages\\bcsapi\\bcsapiwrapper\\src\\V5\\Photo\\',
              ]
          ]
        ]
]


];
