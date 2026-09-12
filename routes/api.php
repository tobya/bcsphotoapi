<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PhotoControllerv3;
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



Route::Get('/', function ()  {
    return Response()->json(
        [ 'version' =>
            ['laravel' => app()->version(),
             'app' => config('version.version'),
             'api' => config('version.apiv3'),],
             'message' => 'PhotoApi Details for Ballymaloe Cookery School Demonstration Photos. Version 3.0'
             ]);
});

/*
  Get All Galleries or all Galleries for specific Year.
*/

// Get Random Image
Route::Get('/images/random/', [PhotoControllerv3::class, 'GalleryImageRandom']);
Route::Get('/images/random/{year}/', [PhotoControllerv3::class, 'GalleryImageRandomYear']);
Route::Get('/images/random/{year}/{month}/', [PhotoControllerv3::class, 'GalleryImageRandomMonth']);
Route::Get('/images/random/{year}/{month}/{day}', [PhotoControllerv3::class, 'GalleryImageRandomDay'])->name('RandomImage');

// years
Route::Get('/galleries/list/{year}', [PhotoControllerv3::class,'YearGallery']);
Route::Get('/gallery/list/{year}', [PhotoControllerv3::class,'YearGallery'])->name('GalleryListForYear');
Route::Get('/albums/list/{year}', [PhotoControllerv3::class,'YearGallery'])->name('AlbumListForYear');
Route::Get('/galleries/recent', [PhotoControllerv3::class,'RecentGallery']);
Route::Get('/albums/recent', [PhotoControllerv3::class,'RecentGallery'])->name('RecentAlbum');


// Get Specific Gallery info for date.
Route::Get('/gallery/{demodate}', [PhotoControllerv3::class, 'GalleryAlbum'])->name('DemoGallery');



Route::Get('/purgecache/', [PhotoControllerv3::class, 'PurgeCache'])->name('PurgeCache');

