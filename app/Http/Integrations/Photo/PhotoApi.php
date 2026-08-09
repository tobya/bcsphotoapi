<?php


namespace Bcsapi\V5\Photo;



 use Bcsapi\V5\Photo\PhotoConnector;
   use Bcsapi\V5\Photo\Requests\ApiV2;
   use Bcsapi\V5\Photo\Requests\AllGalleries;
   use Bcsapi\V5\Photo\Requests\ApiV2ImagesRandom;
   use Bcsapi\V5\Photo\Requests\ApiV2ImagesRandomYear;
   use Bcsapi\V5\Photo\Requests\ApiV2ImagesRandomYearMonth;
   use Bcsapi\V5\Photo\Requests\RandomImage;
   use Bcsapi\V5\Photo\Requests\GalleryListForYear;
   use Bcsapi\V5\Photo\Requests\DemoGallery;
   use Bcsapi\V5\Photo\Requests\DemoGallery_Uncached;
   use Bcsapi\V5\Photo\Requests\AllImages;
   use Bcsapi\V5\Photo\Requests\ApiV2GalleryDemodateHtml;
   use Bcsapi\V5\Photo\Requests\ApiV2GalleryDemodateHtmlIndex;
   use Bcsapi\V5\Photo\Requests\ApiV2GalleryDemodateHtmlList;
   use Bcsapi\V5\Photo\Requests\ApiV2GalleryDemodateHtmlTemplate;
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
        * ApiV2
        * @return Response | ApiV2
        */
        public function ApiV2() : Response | ApiV2
        {

            $request = new ApiV2();

            // don't actually send request to server, just return the request to caller.
            if ($this->shouldReturnRequest){
                return $request;
            }

            return $this->send($request);

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
        * ApiV2ImagesRandom
        * @return Response | ApiV2ImagesRandom
        */
        public function ApiV2ImagesRandom() : Response | ApiV2ImagesRandom
        {

            $request = new ApiV2ImagesRandom();

            // don't actually send request to server, just return the request to caller.
            if ($this->shouldReturnRequest){
                return $request;
            }

            return $this->send($request);

        }


            
    /**
        * ApiV2ImagesRandomYear
        * @return Response | ApiV2ImagesRandomYear
        */
        public function ApiV2ImagesRandomYear($year) : Response | ApiV2ImagesRandomYear
        {

            $request = new ApiV2ImagesRandomYear($year);

            // don't actually send request to server, just return the request to caller.
            if ($this->shouldReturnRequest){
                return $request;
            }

            return $this->send($request);

        }


            
    /**
        * ApiV2ImagesRandomYearMonth
        * @return Response | ApiV2ImagesRandomYearMonth
        */
        public function ApiV2ImagesRandomYearMonth($year,$month) : Response | ApiV2ImagesRandomYearMonth
        {

            $request = new ApiV2ImagesRandomYearMonth($year,$month);

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
        * ApiV2GalleryDemodateHtml
        * @return Response | ApiV2GalleryDemodateHtml
        */
        public function ApiV2GalleryDemodateHtml($demodate) : Response | ApiV2GalleryDemodateHtml
        {

            $request = new ApiV2GalleryDemodateHtml($demodate);

            // don't actually send request to server, just return the request to caller.
            if ($this->shouldReturnRequest){
                return $request;
            }

            return $this->send($request);

        }


            
    /**
        * ApiV2GalleryDemodateHtmlIndex
        * @return Response | ApiV2GalleryDemodateHtmlIndex
        */
        public function ApiV2GalleryDemodateHtmlIndex($demodate) : Response | ApiV2GalleryDemodateHtmlIndex
        {

            $request = new ApiV2GalleryDemodateHtmlIndex($demodate);

            // don't actually send request to server, just return the request to caller.
            if ($this->shouldReturnRequest){
                return $request;
            }

            return $this->send($request);

        }


            
    /**
        * ApiV2GalleryDemodateHtmlList
        * @return Response | ApiV2GalleryDemodateHtmlList
        */
        public function ApiV2GalleryDemodateHtmlList($demodate) : Response | ApiV2GalleryDemodateHtmlList
        {

            $request = new ApiV2GalleryDemodateHtmlList($demodate);

            // don't actually send request to server, just return the request to caller.
            if ($this->shouldReturnRequest){
                return $request;
            }

            return $this->send($request);

        }


            
    /**
        * ApiV2GalleryDemodateHtmlTemplate
        * @return Response | ApiV2GalleryDemodateHtmlTemplate
        */
        public function ApiV2GalleryDemodateHtmlTemplate($demodate,$template) : Response | ApiV2GalleryDemodateHtmlTemplate
        {

            $request = new ApiV2GalleryDemodateHtmlTemplate($demodate,$template);

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

