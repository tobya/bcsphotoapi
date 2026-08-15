<?php

namespace Bcsapi\V5\Photo\Requests;

use Saloon\Enums\Method;
use Saloon\CachePlugin\Traits\HasCaching;
use Saloon\CachePlugin\Contracts\Cacheable;


class DemoGallery extends \Saloon\Http\Request  // implements Cacheable
{
      // to use  caching uncomment lines and methods and some changes xxx
      // use HasCaching;

    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;


    public function __construct(  
           public string $demodate, 
    )
    {  }


    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
         return str('api/v2/gallery/{demodate}')
                             ->replace(
                                    ['{demodate}','{demodate?}'],
                                    [$this->demodate, $this->demodate]
                              );
    }

/**
* CACHING
* If you wish to implement caching , you can uncomment these two methods, the implements and has statements above.
*/

/*
    public function resolveCacheDriver(): Driver
     {
         return new LaravelCacheDriver(Cache::store(config('cache.default')));
     }

     public function cacheExpiryInSeconds(): int
     {
         return 300;
     }
*/
}
