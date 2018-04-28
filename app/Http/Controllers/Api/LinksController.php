<?php

namespace App\Http\Controllers\Api;

use App\Models\Link;
use App\Transformers\LinksTransformer;
use Illuminate\Http\Request;

class LinksController extends Controller
{
    public function index(Link $link)
    {
        $links=$link->getAllCached();
        return $this->response->collection($links,new LinksTransformer())->setStatusCode(201);
    }

}
