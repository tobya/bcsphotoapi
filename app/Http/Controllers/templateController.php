<?php

namespace App\Http\Controllers;


use Smarty;

use Illuminate\Http\Request;

include 'photoController.php';


class TemplateController extends Controller
{


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
