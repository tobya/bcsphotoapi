<?php

return [

  'v2' => [
            'backoffice' => [
                'url' => env('BCSBACKOFFICE_APIURL',''),
                'key' => env('BCSBACKOFFICE_APIKEY',''),
            ],
            'demophoto' => [
                'url' => env('DEMOPHOTO_APIURL',''),

            ],
            'recipe' => [
                'url' => env('BCSRECIPE_APIURL',''),
                'key' => env('BCSRECIPE_APIKEY',''),

            ]
        ],
  'v3' => [
            'backoffice' => [
                'url' => env('BCSBACKOFFICE_V3_APIURL',''),
                'key' => env('BCSBACKOFFICE_V3_APIKEY',''),
            ],
        ]


];
