<?php

namespace App\Http\Integrations\StoreDemoPhotos\Requests;

use Spatie\Url\Url;
use Saloon\Enums\Method;
use Saloon\Http\Request;

class YearInfo extends Request
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
         //$AllAlbumInfo =  file_get_contents(config('services.demophotos.host') . '/info_api_v2.php?infotype=year&year=' . $Year );
        $url = Url::fromString('/info_api_v2.php')
                 ->withQueryParameters([
                   'infotype' => 'year',
                   'year' => $this->year,
                ]);



        return (string) $url;
    }
}
