<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\photoControllerV2;
use App\Http\Controllers\TemplateController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
| prefix : api/v2/
*/



Route::Get('/', function () use ($router) {
    return Response()->json(
        [ 'version' =>
            ['laravel' => app()->version(),
             'app' => config('app.version'),
             'api' => '2.0'],
           'message' => 'PhotoApi Details for Ballymaloe Cookery School Demonstration Photos. Version 2.0'
             ]);
});

/*
  Get All Galleries or all Galleries for specific Year.
*/
Route::Get('/all', [photoControllerV2::class, 'AllGalleryInfo_ConvertDBPath'])->name('AllGalleries');

// Get Random Image
Route::Get('/images/random/', [photoControllerV2::class, 'GalleryImageRandom']);
Route::Get('/images/random/{year}/', [photoControllerV2::class, 'GalleryImageRandomYear']);
Route::Get('/images/random/{year}/{month}/', [photoControllerV2::class, 'GalleryImageRandomMonth']);
Route::Get('/images/random/{year}/{month}/{day}', [photoControllerV2::class, 'GalleryImageRandomDay'])->name('RandomImage');

// years
Route::Get('/galleries/list/{year}', [photoControllerV2::class,'YearGallery'])->name('GalleryListForYear');

// Get Specific Gallery info for date.
Route::Get('/gallery/{demodate}', [photoControllerV2::class, 'GalleryAlbum'])->name('DemoGallery');
Route::Get('/gallery/{demodate}/nocache', [photoControllerV2::class, 'GalleryAlbum_noCache']);

Route::Get('/files/all', [photoControllerV2::class, 'LoadAllPhotos'])->name('AllImages');


Route::Get('/purgecache/', [photoControllerV2::class, 'PurgeCache'])->name('PurgeCache');

