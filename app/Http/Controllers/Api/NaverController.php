<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Library\NaverLibrary;
use Illuminate\Http\Request;

class NaverController extends Controller
{
    public function index(?string $keyword)
    {
        if (!is_null($keyword)) {
            $naverLibrary = new NaverLibrary();
            $result = $naverLibrary->getSearchDictionary(keyword: $keyword);

            return response()->json($result);
        }
    }
}
