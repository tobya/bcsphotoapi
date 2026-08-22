<?php

namespace App\Http\Integrations\StoreDemoPhotos\Requests;

use Spatie\Url\Url;
use Saloon\Enums\Method;
use Saloon\Http\Request;

class AllFileInfo extends Request
{
    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;


    public function __construct(
        public mixed $year = null
    )
    {
    }

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        $url = Url::fromString('/info_api_v2.php?infotype=files&cleanpaths');
        if ($this->year) {
            $url->withQueryParameter('year',$this->year);
        }
      //  dd($url);
        return (string) $url;
    }
}
