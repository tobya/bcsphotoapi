<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;



class PhotoController extends Controller
{
    //

  private $forceReload = false;

  public function AllPhotoInfo(Request $request){
      $info = $this->LoadGalleries(false);
      return response()->json($info);
  }


  public function AllGalleryInfo_ConvertDBPath(Request $request){


      $info = $this->LoadGalleries(false);
      if (isset($info['source']['hasDBPaths'])){
        // skip
      } else {

        $dbpaths = $this->getrecipeDBListPaths($info);

      $count = 0;
      foreach ($info['allitems'] as $key => $Gallery) {


         if (isset($dbpaths['recipedbpaths'][$key])){

         $info['allitems'][$key]['RecipeDBPath'] = $dbpaths['recipedbpaths'][$key]['RecipeDBPath'];

         $info['allitems'][$key]['RecipeDB_PathIDs'] = $dbpaths['recipedbpaths'][$key]['RecipeDB_PathIDs'];
         } else {
         $info['allitems'][$key]['RecipeDBPath'] = 'None';
         $info['allitems'][$key]['RecipeDB_PathIDs'] = [];

         }



      }
      $info['source']['hasDBPaths'] = true;


      $this->saveGalleries($info);
      }

      // Load Lists
      $info = $this->AllGalleryInfo_IncludingPathIDs_lookup();

      return response()->json($info);


  }

public function AllGalleryPathURLs(){
   $DBRecipes = $this->loadDBRecipes();
   return response()->json(['pathtourl' => $DBRecipes['pathtourl']]);
}

  public function AllGalleryInfo_IncludingPathIDs_lookup(){

    $GalleryInfo = $this->LoadGalleries();
    $DBRecipes = $this->loadDBRecipes();

    if (isset($GalleryInfo['source']['hasDBPaths'])){
      $count = 0;
      foreach ($GalleryInfo['allitems'] as $key => $Gallery) {

         if ($count > 1200){
          $count++;
          continue;
         }
         if ( count($Gallery['RecipeDB_PathIDs']) == 0){
        //  echo 'in..';
        $dbpath = $Gallery['RecipeDBPath'];
        if( $dbpath !== 'None' )    {
          $count++;

         if (isset($DBRecipes['dbrecipepathids'][$key] )){
          $pathids = $DBRecipes['dbrecipepathids'][$key] ;
         }  else {

         $pathids = $this->getPathIDs_FromRecipeDBList($dbpath);
         }

         foreach ($pathids as  $Path) {
          $DBRecipes['pathtourl'][$Path['pathid']] = $Gallery['Link'];
          }


         $DBRecipes['dbrecipepathids'][$key] = $pathids;

         $GalleryInfo['allitems'][$key]['RecipeDB_PathIDs'] = $pathids;
       } else {
         $GalleryInfo['allitems'][$key]['RecipeDB_PathIDs'] = [];

       }
         }


      }
    $GalleryInfo['remaining'] = $count -200;
      $this->saveDBRecipes($DBRecipes);
      $this->saveGalleries($GalleryInfo);
      return $GalleryInfo;
    } else {
      return ['status'=> 404, 'msg'=> 'Plesae load RecipeDBPath first'];
    }

  }

  public function AllGalleryInfo_IncludingPathIDs(){
   $GalleryInfo = $this->AllGalleryInfo_IncludingPathIDs_lookup();
      return response()->json($GalleryInfo);
  }

  public function YearPhotoInfo(Request $request, $year) {
        abort(501);
  }

  public function YearGallery(Request $request, $year){
        $AllGallery = $this->LoadYearGallery($year);


        return response()->json( $AllGallery);
  }

  public function GalleryAlbum(Request $request, $demodate) {
        $AllGallery = $this->LoadGalleries();

        $DateofDemo = date('Ymd',strtotime($demodate));

        return $this->LoadGalleryAlbum($AllGallery, $DateofDemo);
  }

  public function GalleryAlbum_noCache(Request $request, $demodate){

    $this->forceReload = true;

      $AllGallery = $this->LoadGalleries();
      // date may be 'today'
      $DateofDemo = date('Ymd',strtotime($demodate));

    return $this->LoadGalleryAlbum($AllGallery, $DateofDemo);
  }


  private function LoadYearGallery($Year) {

  // Load Gallery Cache for today
  $GalleryFilename =   storage_path('app/data/' . config('services.demophotos.marker') . "/galleryjson$Year.json");

  if (file_exists($GalleryFilename) && (!$this->forceReload || $Year <> date('Y') )){

    $AllAlbumInfo = file_get_contents($GalleryFilename) ;
    $AllGalleries = json_decode($AllAlbumInfo, true);

  } else {

    $AllAlbumInfo =  file_get_contents(config('services.demophotos.host') . '/info_api_v2.php?infotype=year&year=' . $Year );

    // Add cache marker to json that is written to disk but not to returned.
    $AllGalleries = json_decode($AllAlbumInfo,true);
    $AllGalleries['items'] = $AllGalleries['allitems'];
    $AllGalleries['items_count'] = $AllGalleries['allitems_count'];
    unset($AllGalleries['allitems']);
    unset($AllGalleries['allitems_count']);
    $AllGalleries['source'] = ['source' => 'diskcache', 'retrievaldate' => date('c')];
    $this->saveGalleries($AllGalleries,$GalleryFilename);
    $AllGalleries['source']['source'] = 'fetch';

  }
  if ($AllGalleries == NULL){
    // AllGalleries will be NULL if the json file on disk is not valid json.
    // if so delete the file.
    unlink($GalleryFilename);
    return ['status' => 405, 'message' => 'Error reading cache file from disk'];
  } else {
    return $AllGalleries;
  }



  }

  /**
     * Return json list with details of Gallery and all images .
     * @param $AllGallery
     * @param $DemoDate
     * @return \Illuminate\Http\JsonResponse
     */
  private function LoadGalleryAlbum($AllGallery, $DemoDate) {

    $DateofDemo = date('Ymd',strtotime($DemoDate));
    if (isset($AllGallery['allitems'][$DateofDemo])){
      $GalleryInfo = $this->GetGalleryInfo($DateofDemo);

      $GalleryInfo['Link'] = config('services.demophotos.host') . $GalleryInfo['Link'];
      $Photos = $this->getGalleryPhotos($AllGallery['allitems'][$DateofDemo]);
      return response()->json(array('status'=>200,
                                    'api' => ['version' => config('app.version')],
                                    'gallery' => $GalleryInfo,
                                    'images_count' => count($Photos),
                                    'images' => $Photos ));
    } else {
      return response()->json(array(
                    'status'=>404, 'images' => [],
                    'images_count' => 0 , 'request_time' => date('c'),
                    'demodate' => $DateofDemo ));
    }
  }

    /**
     * Return basic html page of images for gallery
     * @param Request $request
     * @param $demodate
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
  public function HTMLGalleryAlbum(Request $request, $demodate){
     $AllGallery = $this->LoadGalleries();
     $DateofDemo = date('Ymd',strtotime($demodate));

    if (isset($AllGallery['allitems'][$DateofDemo])){
      $GalleryInfo = $this->GetGalleryInfo($DateofDemo);

      $Photos = $this->getGalleryPhotos($AllGallery['allitems'][$DateofDemo]);
      $HTML = "";
      foreach ($Photos as $key => $P) {

        $HTML .= "<div><img src='$P[src]'><BR><span>$P[caption]</span></div>";
      }

      return response($HTML);
    } else {
      return response('No Images');
    }
  }

  public function PurgeCache(Request $request){

    $FilesToDelete = glob(  storage_path('app/data/' . config('services.demophotos.marker') . '/*.*'));
    foreach ($FilesToDelete as $key => $F) {
      # code...
      if (
        stripos( $F , 'donotdelete.txt' ) === false &&
        stripos( $F , 'allarchivegallery_dbpathids_json.json' ) === false  &&
        stripos( $F , 'allarchivegallery_dbpaths_json.json' ) === false
        ){
        unlink($F);
      }
    }

    $FilesAfter = glob(  storage_path('app/data/' . config('services.demophotos.marker') . '/*.*'));
    return response()->json(['filestopurge' => $FilesToDelete, 'filesremain' => $FilesAfter, 'status' => 200]);
  }


// Returns 1 random image from one random gallery.
public function GalleryImageRandom(){
  $AllGalleries = $this->LoadGalleries();

  $RandomGalleryKey = array_rand($AllGalleries['allitems']);

  $AlbumImages = $this->getGalleryPhotos( $AllGalleries['allitems'][$RandomGalleryKey]);

  $RandomImageKey = array_rand($AlbumImages);

  $RandomImage = $AlbumImages[$RandomImageKey];


  return response()->json(['randomimage' => $RandomImage, 'album' =>  $AllGalleries['allitems'][$RandomGalleryKey]]);
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


  return response()->json(['randomimage' => $RandomImage, 'album' => $GalleryArray[$RandomGalleryKey]]);



}



function GetRecentGalleryInfo(){
  $AllAlbumInfo = $this->LoadRecentGalleries();
  return $AllAlbumInfo;
}

function GetMostRecentGalleryPhotos(){
  $AllAlbumInfo = $this->GetRecentGalleryInfo();
  return $this->getGalleryPhotos($AllAlbumInfo['MostRecent']);
}

function getGalleryByDate($DemoDate ){

  $AllAlbumInfo = $this->LoadGalleries();

  if (isset($AllAlbumInfo['allitems'][$DemoDate])){
    return $this->getGalleryPhotos($AllAlbumInfo['allitems'][$DemoDate]);
  } else {
    return [];
  }
}

function getRecentGalleryByDate($DemoDate ){
  $AllAlbumInfo = $this->GetRecentGalleryInfo();

  if (isset($AllAlbumInfo['allitems'][$DemoDate])){
    return $this->getGalleryPhotos($AllAlbumInfo['allitems'][$DemoDate]);
  } else {
    return [];
  }
  }


function LoadRecentGalleries(){

  //Load Gallery for today
  $GalleryFilename =  storage_path('app/data/' . config('services.demophotos.marker') . '/recentgalleryjson-' . date("Ymd") . '.json');

  if (file_exists($GalleryFilename) && !$this->forceReload){
    $AllAlbumInfo = file_get_contents($GalleryFilename) ;
  } else {
    $AllAlbumInfo =  file_get_contents(config('services.demophotos.host') . '/info_api_v2.php?infotype=all');
    file_put_contents($GalleryFilename, $AllAlbumInfo);
  }

  $AllGalleries = json_decode($AllAlbumInfo, true);
  if ($AllGalleries == NULL){
    unlink($GalleryFilename);
  } else {

  return $AllGalleries;
  }
}

function LoadGalleries(){

  // Load Gallery Cache for today
  $GalleryFilename =   storage_path('app/data/' . config('services.demophotos.marker') . '/allarchivegalleryjson_'  . date('Ymd')  .".json");

  if (file_exists($GalleryFilename) && !$this->forceReload){

    $AllAlbumInfo = file_get_contents($GalleryFilename) ;
    $AllGalleries = json_decode($AllAlbumInfo, true);

  } else {

    $AllAlbumInfo =  file_get_contents(config('services.demophotos.host') . '/info_api_v2.php?infotype=allyears');

    // Add cache marker to json that is written to disk but not to returned.
    $AllGalleries = json_decode($AllAlbumInfo,true);
    $AllGalleries['source'] = ['source' => 'diskcache', 'retrievaldate' => date('c')];
    $this->saveGalleries($AllGalleries,$GalleryFilename);
    $AllGalleries['source']['source'] = 'fetch';

  }
  if ($AllGalleries == NULL){
    // AllGalleries will be NULL if the json file on disk is not valid json.
    // if so delete the file.
    unlink($GalleryFilename);
    return ['status' => 405, 'message' => 'Error reading cache file from disk'];
  } else {
    return $AllGalleries;
  }


}

function SaveGalleries($Data, $GalleryFilename = NULL){

  if ($GalleryFilename == null){
    $GalleryFilename =   storage_path('app/data/' . config('services.demophotos.marker') . '/allarchivegalleryjson_'  . date('Ymd')  .".json");
  }

  // Ensure directory exists first time.
  if (!file_exists(  storage_path('app/data/' . config('services.demophotos.marker') . '/'))){

    mkdir(storage_path('app/data/' . config('services.demophotos.marker') . '/'),0777,true);
  }
   file_put_contents($GalleryFilename, json_encode($Data, JSON_PRETTY_PRINT));
}

function saveDBPaths($Data){


    $Filename = storage_path('app/data/' . config('services.demophotos.marker') . '/allarchivegallery_dbpaths_json.json');


   file_put_contents($Filename, json_encode($Data, JSON_PRETTY_PRINT));
}

function loadDBPaths() {

    $Filename =storage_path('app/data/' . config('services.demophotos.marker') . '/allarchivegallery_dbpaths_json.json');

  if (file_exists($Filename)){
   return json_decode( file_get_contents($Filename), true);
  } else {
    return [];
  }
}


function loadDBRecipes(){
    $Filename =storage_path('app/data/' . config('services.demophotos.marker') . '/allarchivegallery_dbpathids_json.json');

  if (file_exists($Filename)){
   return json_decode( file_get_contents($Filename), true);
  } else {
    return [];
  }
}

function saveDBRecipes($Data){


    $Filename =storage_path('app/data/' . config('services.demophotos.marker') . '/allarchivegallery_dbpathids_json.json');


   file_put_contents($Filename, json_encode($Data, JSON_PRETTY_PRINT));
}


function GetGalleryInfo($GalleryDate ){
   $all = $this->LoadGalleries();

   return  $all['allitems'][$GalleryDate];

}

    /**
     * Get All details for Gallery including images.
     * @param $Gallery
     * @return mixed|void
     */
function LoadPhotoGallery($Gallery){

    $AllPhotos = $this->LoadAllPhotos(date('Y',$Gallery['DTFolder']));

    foreach ($AllPhotos['files'] as $GalleryName => $GalleryFiles ){
        if (stripos($GalleryName,$Gallery['FolderName']) !== false){
            // If there was ever a scenario when 2 folders could have the same date, you would need to build up array.
            // sometimes if the forlder for whatever reason is set to just the year, the first gallery will be returned.
            foreach ($GalleryFiles['images'] as $fn){
                if ($AllPhotos['path_clean']){
                   $Files[] = $fn;
                } else {
                    $Files[] = str_replace($AllPhotos['root_path'],'',$fn);
                }
            }
            $GalleryFiles['images'] = $Files;
            return $GalleryFiles;
        }
    }

    return ['info' => '', 'images' => []];


}

    /**
     * Return all Images for a Gallery
     * @param $Gallery
     * @return array
     */
function GetGalleryPhotos($Gallery){


  $GalleryImages =  $this->LoadPhotoGallery($Gallery);
    Log::debug('calling gallery', $Gallery);

  $i = 0;
  $imgs = [];
  foreach ($GalleryImages['images'] as $filename) {
    $i++;

    // Now using imgix to resize.
    $imgurl = config('services.imgix.host') . $filename ;
    $imgurlsized = $imgurl . '?w=600';
    $link = config('services.demophotos.host') . '/allimages/Gallery'. $filename;
    $gallerylink = config('services.demophotos.galleryhost') . $filename;

    $imgs[] = array('caption'=> '' , 'src' => $imgurlsized,
                    'basesrc' => $imgurl,
                    'photolink' => $link,
                    'gallerylink' => $gallerylink,
                    'tags' => '', 'recipeversionid' => '' );
  }

  return $imgs;
}

function LoadAllPhotos($year = null){

    $PhotosFilename =storage_path('app/data/' . config('services.demophotos.marker') . '/allimages'.$year.'.json');
   if (file_exists($PhotosFilename) && !$this->forceReload){
    $json = file_get_contents($PhotosFilename);

  } else {
       if ($year){
            $galleryurl =   config('services.demophotos.host') .  '/info_api_v2.php?infotype=yearfiles&year='.$year.'&cleanpaths';
       } else {
            $galleryurl =   config('services.demophotos.host') .  '/info_api_v2.php?infotype=files&cleanpaths';
       }

    $json = file_get_contents($galleryurl);
    if ($json){
        file_put_contents($PhotosFilename, $json);
    }
  }
  return json_decode( $json,true);
}


    /**
     * Tags are stored alphabetically so need to find non RID tag
     * @param $tags
     * @return array
     */
function getGalleryCaption($tags) {
  $tagarray = explode(';', $tags);

  $TagBreakdown = ['id' => -1, 'caption'=> ''];

  foreach ($tagarray as $tag) {
    if (strpos($tag, 'RID') !== false){
      $TagBreakdown['id'] = $tag;
    } else {
      $TagBreakdown['caption'] = str_replace('-', ' ', $tag);

    }
  }
  return $TagBreakdown;
}


function DownloadGalleries($Galleries, $StartDate, $EndDate){

  $Dates = $this->getDatesBetween($StartDate, $EndDate);

  foreach ($Dates as $key => $D) {
    # code...
    if (isset($Galleries['allitems'][ $D['Date']])){

    $this->LoadPhotoGallery($Galleries['allitems'][ $D['Date']]);
    }
  }



}

    /**
     * Basically returns an array or arrays listing all days between two dates.
     * @param $fromDate
     * @param $toDate
     * @return array
     */
function getDatesBetween($fromDate, $toDate) {

  $dateMonthYearArr = array();
  $fromDateSTR = strtotime($fromDate);
  $toDateSTR = strtotime($toDate);

  for ($currentDateSTR = $fromDateSTR; $currentDateSTR <= $toDateSTR; $currentDateSTR += (60 * 60 * 24)) {
    // use date() and $currentDateSTR to format the dates in between
    $currentDateStr = date("Ymd", $currentDateSTR);
    $Day = array('Date' => $currentDateStr, 'Day' => date('l', $currentDateSTR));
    $dateMonthYearArr[] = $Day;
  }

  return $dateMonthYearArr;

}

function getrecipeDBListPaths($info){

      $dbpaths = $this->loadDBPaths();

      if ($dbpaths == []) {
        $dbpaths['pathtourl'] = [];

          foreach ($info['allitems'] as $key => $Gallery) {
             $dbpath = $this->getRecipeDBListPathFromZenPath($Gallery['Link']);
             $dbpaths['recipedbpaths'][$key]['RecipeDBPath'] = $dbpath;
             $dbpaths['recipedbpaths'][$key]['RecipeDB_PathIDs'] = [];
          }
          $info['source']['hasDBPaths'] = true;

          $this->saveDBPaths($dbpaths);

      }
      return $dbpaths;
}

function getRecipeDBListPathFromZenPath($ZenLink){

    $ZenLink =    trim($ZenLink, '/');
    @list($Year,$Course,$Week,$Date,$tmp) = explode('/', $ZenLink);

    $Course = "12 Week " . $this->MonthName($Course);
    $Week = $this->WeekName($Week);

    if ($Week == ''){$RecipeLink = 'None';}else {
    $DayofWeek = date('l',strtotime(urldecode($Date)));

    //Get list link, no % after week, may bec aught out by extra space, but is needed to avoid 1, 10, 11, 12
    $RecipeLink = "Lists\\Courses\\$Year\\$Course%\\%Week $Week\\%$DayofWeek%\\";
  //  echo $RecipeLink;
  }

    return $RecipeLink;
    //Lists\Courses\2013\12 Week January\Week 4\Thursday\PM Demo\

    ///2013/12%20Week%20Jan/Week9/Thu%207th%20Mar%202013/page/2
  }


  function getPathIDs_FromRecipeDBList($RecipeLink){
    $url = 'https://recipeapi.cookingisfun.ie/7e1974d12f8f41db919b935290bffdba/lists/bypath/' . urlencode($RecipeLink);

    $raw = file_get_contents($url);
    $Details = json_decode($raw,true);
    //print_r($Details);
   // die();
    $paths = [];

    if ($Details['paths_count'] > 0){

    foreach ($Details['paths'] as $key => $P) {
      $paths[] = ['pathid' => $P['PathID'], 'path' => $P['Path']];

    }
    }
    return $paths;
  }


  function MonthName($CourseNameString){
    //echo $CourseNameString;
    if (strpos($CourseNameString, 'Jan') != false){
      return 'January';
    }

    if (strpos($CourseNameString, 'Apr') != false){
      return 'April';
    }

    if (strpos($CourseNameString, 'May') != false){
      return 'May';
    }

    if (strpos($CourseNameString, 'Sep') != false){
      return 'September';
    }
  }

  function WeekName($WeekString){
  //echo $WeekString;
      @list($tmp,$WeekNo)  = explode('Week',$WeekString);

    return $WeekNo;
  }

}
