<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Library\TistoryLibrary;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class TistoryController extends Controller
{
    private string $outputDataPath;

    public function __construct()
    {
        $this->outputDataPath = config('tistory.output_data_path') ?? "";
    }

    public function index(Request $request)
    {
        $tistoryLibrary = new TistoryLibrary();
        $access_token = $request->input('token');
        // 블로그 정보 관련
        $blogInfo = $tistoryLibrary->getBlogInfo(access_token: $access_token);
        $detailBlogInfo = $this->makeDetailBlogInfo(blogInfo: $blogInfo, selectBlogIndex: 0);
        $blogName = $detailBlogInfo['blog']['name'];

        // 블로그 카테고리 관련
        $category = $tistoryLibrary->getCategoryList(access_token: $access_token, blog_name: $blogName);
        $detailCategory = $this->makeDetailCategory($category);

        // 블로그 게시글 관련
        $startPage = 1;
        $postList = $tistoryLibrary->getPostList(access_token: $access_token, blog_name: $blogName, page: $startPage);
        $allPostList = $this->makeAllPostList(data: $postList, access_token: $access_token, blogName: $blogName, page: $startPage++, tistoryLibrary: $tistoryLibrary);

        $postContent = $tistoryLibrary->getPostContent(access_token: $access_token, blog_name: $blogName, post_id: '280');
        dd('블로그정보', $detailBlogInfo, '카테고리', $detailCategory, '글 목록', $allPostList, '글 읽기', $postContent);
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

    private function makeAllPostList(array $data, string $access_token, string $blogName, int $page, TistoryLibrary $tistoryLibrary): array
    {
        try {
            $returnData = [];
            $item = $data['tistory']['item'];
            $totalPage = round($item['totalCount'] / $item['count']);

            if ($totalPage > 1) {
                $returnData['posts'][] = $item['posts'];
                for ($i = $page; $i <= $totalPage; $i++) {
                    $newData = $tistoryLibrary->getPostList(access_token: $access_token, blog_name: $blogName, page: $i);
                    $returnData['posts'][] = $newData['tistory']['item']['posts'] ?? null;
                }

            }

            // 카테고리ID를 그룹으로 맵핑지어 posts 배열 데이터 재정의
            $posts = $returnData['posts'];
            $groupedPosts = [];

            foreach ($posts as $postGroup) {
                foreach ($postGroup as $post) {
                    $categoryId = $post['categoryId'];

                    // 해당 categoryId가 이미 배열에 존재하면, 현재 post를 그 배열에 추가
                    if (!isset($groupedPosts[$categoryId])) {
                        $groupedPosts[$categoryId] = [];
                    }

                    $groupedPosts[$categoryId][] = $post;
                }
            }

            foreach ($groupedPosts as $categoryId => $post) {
                $this->makeJsonFile(data: $post, filePath: $this->outputDataPath . "categories/", fileName: $categoryId . ".json");
            }

            $returnData['posts'] = $groupedPosts;
        } catch (\Exception $exception) {
            die("makeAllPostList ERROR :: " . $exception->getMessage());
        }

        return $returnData;
    }

    private function makeJsonFile(array $data, ?string $filePath, string $fileName): void
    {
        try {
            $fileFullPath = !is_null($filePath) ? $filePath . $fileName : $fileName;

            // 디렉토리가 존재하는지 확인하고, 없으면 생성
            if (!is_null($filePath) && !file_exists($filePath)) {
                mkdir($filePath, 0777, true); // true는 중첩된 디렉토리 생성을 허용합니다.
            }

            $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            file_put_contents($fileFullPath, $jsonData);
        } catch (\Exception $exception) {
            die("makeJsonFile ERROR :: " . $exception->getMessage());
        }
    }

    private function makeDetailCategory(array $category): array
    {

        try {
            $item = $category['tistory']['item'];
            $parents = array();
            $parentsKey = array();

            foreach ($item['categories'] as $key => $category) {
                if (!$category['parent']) {
                    $parents[] = $category;
                    $parentsKey[] = $key;
                }
            }

            foreach ($parentsKey as $key) {
                unset($item['categories'][$key]);
            }


            foreach ($parents as $parentKey => $parent) {
                $subCategoryKey = array();
                foreach ($item['categories'] as $key => $category) {
                    if ($parent['id'] === $category['parent']) {
                        $parents[$parentKey]['subCategories'][] = $category;
                        $subCategoryKey[] = $key;
                    }
                }

                foreach ($subCategoryKey as $key) {
                    unset($item['categories'][$key]);
                }
            }

            $item['categories'] = $parents;

            $this->makeJsonFile(data: $item, filePath: $this->outputDataPath, fileName: "categories.json");

        } catch (\Exception $exception) {
            die("makeDetailCategory ERROR :: " . $exception->getMessage());
        }

        return $item;
    }

    private function makeDetailBlogInfo(array $blogInfo, int $selectBlogIndex): array
    {
        $returnData = array();
        try {
            $item = $blogInfo['tistory']['item'];
            $returnData['userId'] = $item['userId'];
            $returnData['blog'] = $item['blogs'][$selectBlogIndex];
            $this->makeJsonFile(data: $returnData, filePath: $this->outputDataPath, fileName: "blogInfo.json");
        } catch (\Exception $exception) {
            die("makeDetailBlogInfo ERROR :: " . $exception->getMessage());
        }

        return $returnData;
    }

}
