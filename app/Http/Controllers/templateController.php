<?php

namespace App\Http\Controllers;


use Smarty;

use Illuminate\Http\Request;

include 'photoController.php';


class TemplateController extends Controller
{

  private $smarty;

   protected function setupSmarty()
   {
    $smarty = new Smarty();
  $smarty->setTemplateDir('../resources/views');
  $smarty->setCompileDir('../storage/smarty/compile');
  $smarty->setCacheDir('../storage/smarty/cache/');
  $smarty->left_delimiter = '{[';
  $smarty->right_delimiter = ']}';

  $smarty->error_reporting = E_ALL & ~E_NOTICE;

   $this->smarty = $smarty;
   }

public function HTMLGalleryAlbum(Request $request,  $demodate, $template){

    $PhotoGallery = new PhotoController();
    //$this->setupSmarty();
    $AllGallery = $PhotoGallery->LoadGalleries();

    $DateofDemo = date('Ymd',strtotime($demodate));


    if (isset($AllGallery['allitems'][$DateofDemo])){
      $GalleryInfo = $PhotoGallery->GetGalleryInfo($DateofDemo);

      $Photos = $PhotoGallery->getGalleryPhotos($AllGallery['allitems'][$DateofDemo]);
      $HTML = "";

    //  $this->smarty->assign('Photos', $Photos);
    //  $this->smarty->assign('Demo', $GalleryInfo);
     // $this->smarty->assign('Debug', print_r($GalleryInfo,true));

        return view('gallery.' . $template,[
            'Photos' => json_decode(json_encode($Photos)),
            'Demo' => (object) $GalleryInfo,
        ]);
      if (file_exists('../resources/views/gallery_' . $template . '.html')){
       // $HTML =  $this->smarty->fetch("gallery_$template" . '.html');
        return response($HTML);
      } else {
        return response('no template');
      }

    } else {
      return response('No Images');
    }
  }



}
