<?php


namespace Bcsapi\V5\Photo;



 use Bcsapi\V5\Photo\PhotoConnector;
   use Bcsapi\V5\Photo\Requests\RandomImage;
   use Bcsapi\V5\Photo\Requests\GalleryListForYear;
   use Bcsapi\V5\Photo\Requests\DemoGallery;
   use Bcsapi\V5\Photo\Requests\AllImages;
   use Bcsapi\V5\Photo\Requests\PurgeCache;
  use Saloon\Traits\Plugins\AcceptsJson;
 use Saloon\Http\Response;
 use Saloon\Http\Request;

// Client library must composer require tobya/saloon
 // use Tobya\Saloon\SaloonFire;

class PhotoApi extends \Tobya\Saloon\SaloonFire
{

      protected PhotoConnector $connector;
     /**
     * @var null | Request
     */

      private $shouldReturnRequest = false;

      public function __construct(  )
      {
            $this->connector = new PhotoConnector();
      }


      public function getRequest() : static
      {
          $this->shouldReturnRequest = true;
          return $this;
      }

      public function send(Request $request ) : Response
      {
            return $this->connector->send($request);
      }

            
    /**
        * RandomImage
        * @return Response | RandomImage
        */
        public function RandomImage($year,$month,$day) : Response | RandomImage
        {

            $request = new RandomImage($year,$month,$day);

            // apply any modifiers
            $request = $this->applymodifiers($request);

            // if getRequest() has been called, don't actually send request to server,
            // just return the request to caller.
            if ($this->shouldReturnRequest){
                return $request;
            }

            return $this->send($request);

        }


            
    /**
        * GalleryListForYear
        * @return Response | GalleryListForYear
        */
        public function GalleryListForYear($year) : Response | GalleryListForYear
        {

            $request = new GalleryListForYear($year);

            // apply any modifiers
            $request = $this->applymodifiers($request);

            // if getRequest() has been called, don't actually send request to server,
            // just return the request to caller.
            if ($this->shouldReturnRequest){
                return $request;
            }

            return $this->send($request);

        }


            
    /**
        * DemoGallery
        * @return Response | DemoGallery
        */
        public function DemoGallery($demodate) : Response | DemoGallery
        {

            $request = new DemoGallery($demodate);

            // apply any modifiers
            $request = $this->applymodifiers($request);

            // if getRequest() has been called, don't actually send request to server,
            // just return the request to caller.
            if ($this->shouldReturnRequest){
                return $request;
            }

            return $this->send($request);

        }


            
    /**
        * AllImages
        * @return Response | AllImages
        */
        public function AllImages() : Response | AllImages
        {

            $request = new AllImages();

            // apply any modifiers
            $request = $this->applymodifiers($request);

            // if getRequest() has been called, don't actually send request to server,
            // just return the request to caller.
            if ($this->shouldReturnRequest){
                return $request;
            }

            return $this->send($request);

        }


            
    /**
        * PurgeCache
        * @return Response | PurgeCache
        */
        public function PurgeCache() : Response | PurgeCache
        {

            $request = new PurgeCache();

            // apply any modifiers
            $request = $this->applymodifiers($request);

            // if getRequest() has been called, don't actually send request to server,
            // just return the request to caller.
            if ($this->shouldReturnRequest){
                return $request;
            }

            return $this->send($request);

        }


    




}

