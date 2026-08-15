<?php


namespace Bcsapi\V5\Photo;



 use Bcsapi\V5\Photo\PhotoConnector;
   use Bcsapi\V5\Photo\Requests\AllGalleries;
   use Bcsapi\V5\Photo\Requests\RandomImage;
   use Bcsapi\V5\Photo\Requests\GalleryListForYear;
   use Bcsapi\V5\Photo\Requests\DemoGallery;
   use Bcsapi\V5\Photo\Requests\DemoGallery_Uncached;
   use Bcsapi\V5\Photo\Requests\AllImages;
   use Bcsapi\V5\Photo\Requests\PurgeCache;
  use Saloon\Traits\Plugins\AcceptsJson;
 use Saloon\Http\Response;
 use Saloon\Http\Request;

class PhotoApi
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
        * AllGalleries
        * @return Response | AllGalleries
        */
        public function AllGalleries() : Response | AllGalleries
        {

            $request = new AllGalleries();

            // don't actually send request to server, just return the request to caller.
            if ($this->shouldReturnRequest){
                return $request;
            }

            return $this->send($request);

        }


            
    /**
        * RandomImage
        * @return Response | RandomImage
        */
        public function RandomImage($year,$month,$day) : Response | RandomImage
        {

            $request = new RandomImage($year,$month,$day);

            // don't actually send request to server, just return the request to caller.
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

            // don't actually send request to server, just return the request to caller.
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

            // don't actually send request to server, just return the request to caller.
            if ($this->shouldReturnRequest){
                return $request;
            }

            return $this->send($request);

        }


            
    /**
        * DemoGallery_Uncached
        * @return Response | DemoGallery_Uncached
        */
        public function DemoGallery_Uncached($demodate) : Response | DemoGallery_Uncached
        {

            $request = new DemoGallery_Uncached($demodate);

            // don't actually send request to server, just return the request to caller.
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

            // don't actually send request to server, just return the request to caller.
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

            // don't actually send request to server, just return the request to caller.
            if ($this->shouldReturnRequest){
                return $request;
            }

            return $this->send($request);

        }


    




}

