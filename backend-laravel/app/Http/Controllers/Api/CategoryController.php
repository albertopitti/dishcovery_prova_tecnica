<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\RealWorld\Transformers\CategoryTransformer;

class CategoryController extends ApiController
{
    /**
     * TagController constructor.
     *
     * @param CategoryTransformer $transformer
     */
    public function __construct(CategoryTransformer $transformer)
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
        $categories = Category::all();

        return $this->respondWithTransformer($categories);
    }}
