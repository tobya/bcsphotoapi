<?php

use App\Http\Controllers\PhotoController;
use App\Http\Controllers\TemplateController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::Get('/', function () use ($router) {
    return Response()->json(
        [ 'version' =>
            ['laravel' => app()->version(),
             'app' => config('app.version'),
             'api' => '1.0'],
           'message' => 'PhotoApi Details for Ballymaloe Cookery School Demonstration Photos'
             ]);
});

/*
  Get All Galleries or all Galleries for specific Year.
*/
Route::Get('/all', [photoController::class, 'AllGalleryInfo_ConvertDBPath'])->name('AllGalleries');

// deprecated
Route::Get('/all/{year}', [photoController::class, 'YearPhotoInfo']);

Route::Get('/allconvertzen', [photoController::class, 'AllGalleryInfo_ConvertDBPath']);
Route::Get('/allloadrecipepaths', [photoController::class, 'AllGalleryInfo_IncludingPathIDs']);
Route::Get('/gallerypathurls', [photoController::class, 'AllGalleryPathURLs']);


// Get Random Image
Route::Get('/images/random/', [photoController::class, 'GalleryImageRandom']);
Route::Get('/images/random/{year}/', [photoController::class, 'GalleryImageRandomYear']);
Route::Get('/images/random/{year}/{month}/', [photoController::class, 'GalleryImageRandomMonth']);
Route::Get('/images/random/{year}/{month}/{day}', [photoController::class, 'GalleryImageRandomDay'])->name('RandomImage');

// years
Route::Get('/galleries/list/{year}', [photoController::class,'YearGallery'])->name('GalleryListForYear');

// Get Specific Gallery info for date.
Route::Get('/gallery/{demodate}', [photoController::class, 'GalleryAlbum'])->name('DemoGallery');
Route::Get('/gallery/{demodate}/nocache', [photoController::class, 'GalleryAlbum_noCache'])->name('DemoGallery_Uncached');


// Return Gallery as basic HTML rather than JSON
Route::Get('/gallery/{demodate}/html/', [photoController::class, 'HTMLGalleryAlbum']);


Route::Get('/gallery/{demodate}/html/index', [templateController::class,'index']);
Route::Get('/gallery/{demodate}/html/list', [templateController::class,'index']);
Route::Get('/gallery/{demodate}/html/{template}', [templateController::class,'HTMLGalleryAlbum']);


Route::Get('/files/all', [photoController::class, 'LoadAllPhotos']);

Route::Get('/purgecache/', [photoController::class, 'PurgeCache'])->name('PurgeCache');

