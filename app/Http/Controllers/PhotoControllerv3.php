<?php

  namespace App\Http\Controllers;

  /**
   * THIS MUST BE CHANGED TO V3 AS THE OTHER CONTROLLER IS ACTUALLY V2
   */

  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Log;
  use Illuminate\Support\Facades\Cache;
  use App\Http\Responses\PhotoApiResponseV5;

  class PhotoControllerv3 extends PhotoController
  {

// Returns 1 random image from one random gallery.
public function
GalleryImageRandom(){
  $AllGalleries = $this->LoadGalleries();

  $RandomGalleryKey = array_rand($AllGalleries['allitems']);

  $AlbumImages = $this->getGalleryPhotos( $AllGalleries['allitems'][$RandomGalleryKey]);

  $RandomImageKey = array_rand($AlbumImages);

  $RandomImage = $AlbumImages[$RandomImageKey];


  return response()->json(['randomimage' => $RandomImage,
      'album' =>  $this->ConvertAlbumToV5( $AllGalleries['allitems'][$RandomGalleryKey])]);
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


  return $this->jsonresponse([
        'randomimage' => $RandomImage,
        'album' => $this->ConvertAlbumToV5($GalleryArray[$RandomGalleryKey])
  ]);



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
      return $this->jsonresponse(['randomimage' => [], 'album' => [],'status'=> 404, 'error' => ['msg' => 'No Matching Galleries']]);
  }
  $RandomGalleryKey = array_rand($GalleryArray);

    $ChosenGallery = $GalleryArray[$RandomGalleryKey];
    // Sometime Datetime is false if Folder isnt a date, check.
    if ($ChosenGallery['DemoDate'] != false){
        $AlbumImages = $this->getGalleryPhotos( $GalleryArray[$RandomGalleryKey]);
    } else {
        //recurse
        Log::debug('Photo Api Recursion  \\',$ChosenGallery);
        return $this->GalleryImageRandomMonth($request, $Year, $Month);
    }

  $RandomImageKey = array_rand($AlbumImages);

  $RandomImage = $AlbumImages[$RandomImageKey];

  return $this->jsonresponse([
      'randomimage' => $RandomImage,
      'album' => $this->ConvertAlbumToV5($GalleryArray[$RandomGalleryKey])

  ]);

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

      return $this->jsonresponse(['randomimage' => [], 'album' => [],'status'=> 404, 'requested_date' => $Year.$Month.$Day, 'error' => ['msg' => 'No Matching Galleries']]);
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

    return $this->jsonresponse(
     [
         'randomimage' => $RandomImage,
      'album' => $this->ConvertAlbumToV5( $GalleryArray[$RandomGalleryKey])
      ]

    );



}

      public function YearGallery(Request $request, $year){

            $Galleries = $this->LoadYearGallery($year);

            $ResponseGalleries = $Galleries;

            // tidy up for v3
            $ResponseGalleries['debug'] = $ResponseGalleries['Debug'];
            unset($ResponseGalleries['Debug']);

            unset($ResponseGalleries['recent'] );

            if ($ResponseGalleries['items_count'] > 0){

                // change items
                $ResponseGalleries['albums'] = [];
                $ResponseGalleries['albums_count'] = $Galleries['items_count'];
                unset($ResponseGalleries['items']);
                unset($ResponseGalleries['items_count']);

                foreach ($Galleries['items'] as $key => $gallery) {
                    // do not set dates as key
                    $ResponseGalleries['albums'][] = $this->ConvertAlbumToV5($gallery);
                }

              //  $AllGalleries['recent']['mostrecent'] = $this->ConvertAlbumToV5($AllGalleries['recent']['mostrecent']);
               // $AllGalleries['recent']['prevday'] = $this->ConvertAlbumToV5($AllGalleries['recent']['prevday']);
            } else {
                $ResponseGalleries['status'] = 404;
            }

            return $this->jsonresponse( $ResponseGalleries);
      }

          public function GalleryAlbum(Request $request, $demodate) {
                $AllGallery = $this->LoadGalleries();

                $DateofDemo = date('Ymd',strtotime($demodate));



                if (isset($AllGallery['allitems'][$DateofDemo])){
                  $GalleryInfo = $this->GetGalleryInfo($DateofDemo);

                  $GalleryInfo['Link'] = config('services.demophotos.host') . $GalleryInfo['Link'];
                  $gallery_details = $this->ConvertAlbumToV5($GalleryInfo);
                  $Photos = $this->getGalleryPhotos($AllGallery['allitems'][$DateofDemo]);
                  return $this->jsonresponse(array('status'=>200,
                                                'gallery' => $gallery_details,
                                                'images_count' => count($Photos),
                                                'images' => $Photos ));
                } else {
                  return $this->jsonresponse(array(
                                'status'=>404, 'images' => [],
                                'images_count' => 0 , 'request_time' => date('c'),
                                'demodate' => $DateofDemo ));
                }

          }

       public function RecentGallery()
       {
              $Galleries = $this->LoadYearGallery(now()->year);
             // $Galleries = $this->LoadYearGallery(2021);

            $AllGalleries = $Galleries;

            // tidy up for v3
            //$AllGalleries['debug'] = $AllGalleries['Debug'];
            unset($AllGalleries['Debug']);

          //  dd($AllGalleries);
            unset($AllGalleries['items'] );
            unset($AllGalleries['items_count'] );

        //    if ($AllGalleries['items_count'] > 0){
//
        //        // change items
        //        $AllGalleries['items'] = [];
        //        foreach ($Galleries['items'] as $key => $gallery) {
        //            // do not set dates as key
        //            $AllGalleries['items'][] = $this->ConvertAlbumToV5($gallery);
        //        }
//
        //      //  $AllGalleries['recent']['mostrecent'] = $this->ConvertAlbumToV5($AllGalleries['recent']['mostrecent']);
        //       // $AllGalleries['recent']['prevday'] = $this->ConvertAlbumToV5($AllGalleries['recent']['prevday']);
        //    } else {
        //        $AllGalleries['status'] = 404;
        //    }

            return $this->jsonresponse( $AllGalleries);
       }




      /**
       * Convert a gallery album to V3/5 and lowercase keys
       * @param array $Gallery
       * @return array
       */
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

      /**
       * Provide a standard response format
       * @param $data
       * @return PhotoApiResponseV5
       */
      public function jsonresponse($data = null, $statusCode = 200)
      {
              return new PhotoApiResponseV5(
                    $data,
                  $statusCode
            );
      }

  }
