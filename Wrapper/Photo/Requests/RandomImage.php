<?php

namespace Bcsapi\V5\Photo\Requests;

use Saloon\Enums\Method;

// for caching
// if you wish to use this you must composer require saloonphp/cache-plugin "^3.0"
use Illuminate\Support\Facades\Cache;
use Saloon\CachePlugin\Traits\HasCaching;
use Saloon\CachePlugin\Contracts\Driver;
use Saloon\CachePlugin\Contracts\Cacheable;
use Saloon\CachePlugin\Drivers\LaravelCacheDriver;


class RandomImage extends \Saloon\Http\Request  // implements Cacheable
{
      // CACHING
      // to use  caching uncomment lines and methods and some changes
      // [ ] implements
      // [ ] methods
      // [ ] has

      // use HasCaching;

    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;


    public function __construct(  
           public string $year, 
           public string $month, 
           public string $day, 
    )
    {  }


    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
         return str('api/v2/images/random/{year}/{month}/{day}')
                             ->replace(
                                    ['{year}','{year?}','{month}','{month?}','{day}','{day?}'],
                                    [$this->year, $this->year,$this->month, $this->month,$this->day, $this->day]
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
