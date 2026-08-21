<?php

  namespace App\Http\Responses;


  use Illuminate\Http\JsonResponse;
  use Illuminate\Support\Collection;
  use Illuminate\Support\Facades\Http;
  use Illuminate\Contracts\Support\Responsable;

  class PhotoApiResponseV5 implements Responsable
  {

    public function __construct(
        protected array | Collection | null $data,
        protected int $statusCode = 200,
    )
    {
    }

      /**
     * @inheritDoc
     */
    public function toResponse($request,)
    {
        if(isset($this->data['api'])){
            $this->data['api']['version'] = '3.0';
        } else {

        $this->data['api'] = ['version' => '3.0'];
        }

      return new JsonResponse(
          $this->data,
          $this->statusCode
      );
    }
  }
