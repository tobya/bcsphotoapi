<?php

namespace  App\Http\Integrations\StoreDemoPhotos\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Spatie\Url\Url;

class InfoTypeAllYears extends Request
{
    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
       $url = Url::fromString('/info_api_v2.php')
                 ->withQueryParameters([
                   'infotype' => 'allyears',
                ]);


         // dd($url, (string) $url);
        return (string) $url;
    }
}
