<?php

namespace App\Http\Integrations\StoreDemoPhotos;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;

class StoreDemoPhotos extends Connector
{
    use AcceptsJson;

    /**
     * The Base URL of the API
     */
    public function resolveBaseUrl(): string
    {
        return   config('services.demophotos.host') ;
    }

    /**
     * Default headers for every request
     */
    protected function defaultHeaders(): array
    {
        return [];
    }

    /**
     * Default HTTP client options
     */
    protected function defaultConfig(): array
    {
        return [];
    }
}
