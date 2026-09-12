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
                   'cleanpaths' => null,
                ]);


         // dd($url, (string) $url);
        return (string) $url;
    }
}

/**
 *
 * {
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "title": "Generated Schema",
  "type": "object",
  "properties": {
    "files_count": {
      "type": "integer"
    },
    "path_clean": {
      "type": "boolean"
    },
    "cleaned_path": {
      "type": "string"
    },
    "root_path": {
      "type": "string"
    },
    "files": {
      "type": "object",
      "properties": {
        "/2016/12 Week Apr/Week/Fri 10th Jun 2016": {
          "type": "object",
          "properties": {
            "images": {
              "type": "array",
              "items": {
                "type": "string"
              }
            },
            "info": {
              "type": "array",
              "items": {
                "type": "string"
              }
            }
          },
          "required": [
            "images",
            "info"
          ]
        }
      },
      "required": [
        "/2016/12 Week Apr/Week/Fri 10th Jun 2016"
      ]
    }
  },
  "required": [
    "files_count",
    "path_clean",
    "cleaned_path",
    "root_path",
    "files"
  ]
}
 */
