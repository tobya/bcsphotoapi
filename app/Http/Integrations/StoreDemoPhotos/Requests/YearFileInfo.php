<?php

namespace App\Http\Integrations\StoreDemoPhotos\Requests;

use Spatie\Url\Url;
use Saloon\Enums\Method;
use Saloon\Http\Request;

class YearFileInfo extends Request
{
    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;


    public function __construct(
        public  $year
    )
    {
    }

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        $url = Url::fromString('/info_api_v2.php')
                 ->withQueryParameters([
                   'infotype' => 'yearfiles',
                   'year' => $this->year,
                   'cleanpaths' => 'true',
                ]);


         // dd($url, (string) $url);
        return (string) $url;
    }
}
