<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

/*
  Get All Galleries or all Galleries for specific Year.
*/
$router->get('/all', 'photoController@AllGalleryInfo_ConvertDBPath');
$router->get('/all/{year}', 'photoController@YearPhotoInfo');
$router->get('/allconvertzen', 'photoController@AllGalleryInfo_ConvertDBPath');
$router->get('/allloadrecipepaths', 'photoController@AllGalleryInfo_IncludingPathIDs');
$router->get('/gallerypathurls', 'photoController@AllGalleryPathURLs');

// Get Random Image
$router->get('/images/random/', 'photoController@GalleryImageRandom');
$router->get('/images/random/{year}/', 'photoController@GalleryImageRandomYear');
$router->get('/images/random/{year}/{month}/', 'photoController@GalleryImageRandomMonth');
$router->get('/images/random/{year}/{month}/{day}', 'photoController@GalleryImageRandomDay');


// Get Specific Gallery info for date.
$router->get('/gallery/{demodate}', 'photoController@GalleryAlbum');
$router->get('/gallery/{demodate}/nocache', 'photoController@GalleryAlbum_noCache');

// Return Gallery as basic HTML rather than JSON
$router->get('/gallery/{demodate}/html/', 'photoController@HTMLGalleryAlbum');


$router->get('/gallery/{demodate}/html/{template}', 'templateController@HTMLGalleryAlbum');



$router->get('/purgecache/', 'photoController@PurgeCache');



