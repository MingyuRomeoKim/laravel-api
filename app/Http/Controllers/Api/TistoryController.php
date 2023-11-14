<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Library\TistoryLibrary;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class TistoryController extends Controller
{

    public function index(Request $request)
    {
        $tistoryLibrary = new TistoryLibrary();
        $access_token = $request->input('token');
        // 블로그 정보 관련
        $blogInfo = $tistoryLibrary->getBlogInfo(access_token: $access_token);
        $detailBlogInfo = $tistoryLibrary->makeDetailBlogInfo(blogInfo: $blogInfo, selectBlogIndex: 0);
        $blogName = $detailBlogInfo['blog']['name'];

        // 블로그 카테고리 관련
        $category = $tistoryLibrary->getCategoryList(access_token: $access_token, blog_name: $blogName);
        $detailCategory = $tistoryLibrary->makeDetailCategory($category);

        // 블로그 게시글 관련
        $startPage = 1;
        $postList = $tistoryLibrary->getPostList(access_token: $access_token, blog_name: $blogName, page: $startPage);
        $allPostList = $tistoryLibrary->makeAllPostList(data: $postList, access_token: $access_token, blogName: $blogName, page: $startPage++);

        // 블로그 게시글 상세뷰 관련
        $postDetailViewPath = $tistoryLibrary->makeAllPostDetailView(access_token: $access_token, blogName: $blogName, data: $allPostList);
        dd('블로그정보', $detailBlogInfo, '카테고리', $detailCategory, '글 목록', $allPostList, '글 읽기', $postDetailViewPath);
    }

    public function accessToken(Request $request)
    {
        $tistoryLibrary = new TistoryLibrary();

        if ($request->has('error')) {
            die('accessToken 오류 :: ' . $request->input('error_reason'));
        }

        $code = $request->input('code') ?? null;
        $token = $tistoryLibrary->getAccessToken(code: $code);

        return redirect()->route('tistory.index', ['token' => $token]);
    }


}
