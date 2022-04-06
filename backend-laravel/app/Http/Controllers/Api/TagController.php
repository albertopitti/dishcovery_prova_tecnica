<?php

namespace App\Http\Controllers\Api;

use App\Tag;
use App\RealWorld\Transformers\TagTransformer;
use Illuminate\Http\JsonResponse;

class TagController extends ApiController
{
    /**
     * TagController constructor.
     *
     * @param TagTransformer $transformer
     */
    public function __construct(TagTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * Get all the tags.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $tags = Tag::all()->pluck('name');
        $count = Tag::all()->count();

        $response = new JsonResponse();
        $response->setData(
            array_merge(
                $this->respondWithTransformer($tags)->getData(true),
                ['count' => $count]
            )
        );

        return $response;
    }
}
