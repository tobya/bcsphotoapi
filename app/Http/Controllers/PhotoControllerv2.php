<?php

  namespace App\Http\Controllers;



  use Illuminate\Http\Request;
  use App\Http\Responses\PhotoApiResponseV5;

  class PhotoControllerv2 extends PhotoController
  {

// Returns 1 random image from one random gallery.
public function
GalleryImageRandom(){
  $AllGalleries = $this->LoadGalleries();

  $RandomGalleryKey = array_rand($AllGalleries['allitems']);

  $AlbumImages = $this->getGalleryPhotos( $AllGalleries['allitems'][$RandomGalleryKey]);

  $RandomImageKey = array_rand($AlbumImages);

  $RandomImage = $AlbumImages[$RandomImageKey];


  return response()->json(['randomimage' => $RandomImage, 'album' =>  $this->ConvertAlbumToV5( $AllGalleries['allitems'][$RandomGalleryKey])]);
}

public function GalleryImageRandomYear(Request $request, $Year){

  $AllGalleries = $this->LoadGalleries();


  foreach ($AllGalleries['allitems'] as $key => $G) {
    if ( substr($G['DemoDate'],0,4) == $Year){
      $GalleryArray[] = $G;
    }
  }


  // In an error no gallery will match
  if (!isset($GalleryArray)){

      return response()->json(['randomimage' => [], 'album' => [],'status'=> 404, 'error' => ['msg' => 'No Matching Galleries']]);
  }

  $RandomGalleryKey = array_rand($GalleryArray);

  $AlbumImages = $this->getGalleryPhotos( $GalleryArray[$RandomGalleryKey]);

  $RandomImageKey = array_rand($AlbumImages);

  $RandomImage = $AlbumImages[$RandomImageKey];


  return response()->json(['randomimage' => $RandomImage, 'album' => $GalleryArray[$RandomGalleryKey]]);



}


public function GalleryImageRandomMonth(Request $request, $Year, $Month){

  $AllGalleries = $this->LoadGalleries();

  $GalleryArray = [];
  if ($Month < 10){
      $Month = "0" . intval($Month);
  }
  foreach ($AllGalleries['allitems'] as $key => $G) {

    if ( substr($G['DemoDate'],0,6) == $Year.$Month){
      $GalleryArray[] = $G;
    }
  }

  // In an error no gallery will match
  if ($GalleryArray == []){
      return response()->json(['randomimage' => [], 'album' => [],'status'=> 404, 'error' => ['msg' => 'No Matching Galleries']]);
  }
  $RandomGalleryKey = array_rand($GalleryArray);

    $ChosenGallery = $GalleryArray[$RandomGalleryKey];
    // Sometime Datetime is false if Folder isnt a date, check.
    if ($ChosenGallery['DTFolder'] != false){
        $AlbumImages = $this->getGalleryPhotos( $GalleryArray[$RandomGalleryKey]);
    } else {
        //recurse
        Log::debug('Photo Api Recursion  \\',$ChosenGallery);
        return $this->GalleryImageRandomMonth($request, $Year, $Month);
    }

  $RandomImageKey = array_rand($AlbumImages);

  $RandomImage = $AlbumImages[$RandomImageKey];

  return response()->json(['randomimage' => $RandomImage, 'album' => $GalleryArray[$RandomGalleryKey]]);

}

public function GalleryImageRandomDay(Request $request, $Year, $Month, $Day){

  $AllGalleries = $this->LoadGalleries();
    if ($Month < 10){
      $Month = "0" . intval( $Month);
    }

    if ($Day < 10 ){
      $Day = "0". intval($Day);
    }

  foreach ($AllGalleries['allitems'] as $key => $G) {
    if ( $G['DemoDate'] == $Year.$Month.$Day){
      $GalleryArray[] = $G;
    }
  }


  // In an error no gallery will match
  if (!isset($GalleryArray)){

      return response()->json(['randomimage' => [], 'album' => [],'status'=> 404, 'requested_date' => $Year.$Month.$Day, 'error' => ['msg' => 'No Matching Galleries']]);
  }

  $RandomGalleryKey = array_rand($GalleryArray);

  $AlbumImages = $this->getGalleryPhotos( $GalleryArray[$RandomGalleryKey]);

  $RandomImageKey = array_rand($AlbumImages);

  $RandomImage = $AlbumImages[$RandomImageKey];


 // return response()->json(
 //     ['randomimage' => $RandomImage,
 //      'album' => $this->ConvertAlbumToV5( $GalleryArray[$RandomGalleryKey])
 //     ]
 // );

    return new PhotoApiResponseV5(
     [
         'randomimage' => $RandomImage,
      'album' => $this->ConvertAlbumToV5( $GalleryArray[$RandomGalleryKey])
      ]

    );



}

      private function ConvertAlbumToV5(array $Gallery) : array
      {
            // only include the following keys
          $includedKeys = collect(['Link','FolderName','DemoDate']);

          // lowercase the keys and remove keys not included.
            $c = collect($Gallery)->mapWithKeys(function ($item,$key) use ($includedKeys) {
                if ($includedKeys->contains($key)){
                    return [strtolower($key) => $item];
                }
                return [];
            })->filter()->all();

            return $c;

      }

  }
