<?php

// config for Tobya/SaloonForge
return [
    'integrations' => [
        'Photo' => [
            'routes' => [

            'selector_class' => \App\Http\Integrations\PhotoApiRouteSelector::class,
            'forgeroute_class' => \Tobya\SaloonForge\Extensions\ForgeRoute::class,


            'prefix' => ['/'],
            'exclude' =>
                [
                  'middleware' => ['web'],
                 'filter' =>        ['somenonese*'],
                 'unnamed' => false,
                ],
            'include' =>
                [
                'filter' =>       ['*'],
                'middleware' => ['web'],
                'route-parameters' => [
                    'any' =>    [],
                    'all' => [],  // not implemented
                ],
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
