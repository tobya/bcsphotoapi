<?php

namespace App\Http\Controllers;


use Smarty;

use Illuminate\Http\Request;


use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\PhotoController;


class TemplateController extends Controller
{

public function index($demodate)
{
    $storage = Storage::build([
        'driver' => 'local',
        'root' => resource_path('/views/gallery'),
    ]);
    $allfiles = collect($storage->allFiles())->map(function ($file) {
        return str($file)->beforeLast('.blade.php');
    });
    return view('gallery.index', compact('allfiles','demodate'));
}
public function HTMLGalleryAlbum(Request $request,  $demodate, $template){

    $PhotoGallery = new PhotoController();

    $AllGallery = $PhotoGallery->LoadGalleries();

    $DateofDemo = date('Ymd',strtotime($demodate));


    if (isset($AllGallery['allitems'][$DateofDemo])){
      $GalleryInfo = $PhotoGallery->GetGalleryInfo($DateofDemo);

      $Photos = $PhotoGallery->getGalleryPhotos($AllGallery['allitems'][$DateofDemo]);

      if (file_exists('../resources/views/gallery/' . $template . '.blade.php')){
        return view('gallery.' . $template,[
            'Photos' => json_decode(json_encode($Photos)),
            'Demo' => (object) $GalleryInfo,
        ]);

      } else {
        return response('No Template Found',404);
      }

    } else {
      return response('No Images');
    }
  }



}
