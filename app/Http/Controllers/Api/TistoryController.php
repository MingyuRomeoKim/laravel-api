<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Library\TistoryLibrary;
use Illuminate\Http\Request;

class TistoryController extends Controller
{
    public function index(Request $request)
    {
        $tistoryLibrary = new TistoryLibrary();
        $access_token = $request->input('token');
        $blogInfo = $tistoryLibrary->getBlogInfo(access_token: $access_token);
        $postList = $tistoryLibrary->getPostList(access_token: $access_token, blog_name: 'min-nine', page: 1);
        $postContent = $tistoryLibrary->getPostContent(access_token: $access_token, blog_name: 'min-nine', post_id: '280');
        $category = $tistoryLibrary->getCategoryList(access_token: $access_token,blog_name: 'min-nine');
        dd('블로그정보', $blogInfo['tistory']['item'], '글 목록', $postList, '글 읽기', $postContent,'카테고리',$category);
    }

    public function accessToken(Request $request)
    {
        if ($request->has('error')) {
            die('accessToken 오류 :: ' . $request->input('error_reason'));
        }

        $code = $request->input('code') ?? null;

        $token = $this->tistoryLibrary->getAccessToken(code: $code);

        return redirect()->route('tistory.index', ['token' => $token]);
    }

}
