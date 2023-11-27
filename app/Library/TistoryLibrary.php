<?php

namespace App\Library;

use MingyuKim\PhpKafka\Libraries\ApiLibrary;

class TistoryLibrary extends BaseLibrary
{
    protected ApiLibrary $apiLibrary;
    private string $client_id;
    private string $client_secret;
    private string $redirect_url;
    private string $grant_type;
    private string $output_type;
    protected string $outputDataPath;

    public function __construct()
    {
        $this->client_id = config('tistory.client_id');
        $this->client_secret = config('tistory.client_secret');
        $this->redirect_url = config('tistory.redirect_url');
        $this->grant_type = config('tistory.grant_type');
        $this->output_type = config('tistory.output_type');
        $this->outputDataPath = config('tistory.output_data_path') ?? "";
    }

    /**
     * @param string $access_token
     * @param string $blog_name
     * @param string|int $post_id
     * @return array|null
     * @description https://www.tistory.com/apis/post/read 호출 및 응답 데이터 관련 함수
     */
    public function getPostContent(string $access_token, string $blog_name, string|int $post_id): ?array
    {
        $requestData = [
            'access_token' => $access_token,
            'blogName' => $blog_name,
            'postId' => $post_id,
        ];

        $this->initApiLibrary(method: 'GET', url: "https://www.tistory.com/apis/post/read", data: $requestData);
        $result = $this->apiLibrary->callAPI();

        if (!$result || empty($result)) {
            die("getPostContent :: result not found");
        }

        // XML에서 유효하지 않은 문자 제거
        $result = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $result);
        // XML 문자열을 SimpleXMLElement 객체로 변환
        $xmlObject = simplexml_load_string($result);
        // SimpleXMLElement 객체를 JSON 문자열로 변환
        $jsonString = json_encode($xmlObject);
        // JSON 문자열을 PHP 배열로 변환
        $result = json_decode($jsonString, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            die("getPostContent JSON decoding error: " . json_last_error_msg());
        }

        return $result;
    }

    /**
     * @param string $access_token
     * @param string $blog_name
     * @return array
     * @description https://www.tistory.com/apis/category/list 호출 및 응답 데이터 관련 함수
     */
    public function getCategoryList(string $access_token, string $blog_name): array
    {
        $requestData = [
            'access_token' => $access_token,
            'output' => $this->output_type,
            'blogName' => $blog_name
        ];

        $this->initApiLibrary(method: 'GET', url: "https://www.tistory.com/apis/category/list", data: $requestData);
        $result = $this->apiLibrary->callAPI();

        if (!$result || empty($result)) {
            die("getPostList :: result not found");
        }

        $result = json_decode($result, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            die("getPostList JSON decoding error: " . json_last_error_msg());
        }

        return $result;
    }

    /**
     * @param string $access_token
     * @param string $blog_name
     * @param string|int $page
     * @return array|null
     * @description https://www.tistory.com/apis/post/list 호출 및 응답 데이터 관련 함수
     */
    public function getPostList(string $access_token, string $blog_name, string|int $page): ?array
    {
        $requestData = [
            'access_token' => $access_token,
            'output' => $this->output_type,
            'blogName' => $blog_name,
            'page' => $page
        ];
        $this->initApiLibrary(method: 'GET', url: "https://www.tistory.com/apis/post/list", data: $requestData);
        $result = $this->apiLibrary->callAPI();

        if (!$result || empty($result)) {
            die("getPostList :: result not found");
        }

        $result = json_decode($result, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            die("getPostList JSON decoding error: " . json_last_error_msg());
        }

        return $result;
    }

    /**
     * @param string|null $code
     * @return string
     * @description https://www.tistory.com/oauth/access_token 호출 및 응답 데이터 관련 함수
     */
    public function getAccessToken(?string $code): string
    {

        $requestData = [
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'redirect_uri' => $this->redirect_url,
            'code' => $code,
            'grant_type' => $this->grant_type
        ];
        $this->initApiLibrary(method: 'GET', url: "https://www.tistory.com/oauth/access_token", data: $requestData);
        $result = $this->apiLibrary->callAPI();

        if (!$result || empty($result)) {
            die("getAccessToken :: result not found");
        }

        return str_replace("access_token=", "", $result);
    }

    public function getBlogInfo(string $access_token): ?array
    {

        $requestData = [
            'access_token' => $access_token,
            'output' => $this->output_type
        ];
        $this->initApiLibrary(method: 'GET', url: "https://www.tistory.com/apis/blog/info", data: $requestData);
        $result = $this->apiLibrary->callAPI();

        if (!$result) {
            die("getAccessToken :: result not found");
        }

        $result = json_decode($result, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            die("getAccessToken JSON decoding error: " . json_last_error_msg());
        }

        return $result;
    }

    /**
     * @param array $blogInfo
     * @param int $selectBlogIndex
     * @return array
     * @description 블로그 정보를 지정한 path에 blogInfo.json명으로 저장한 후, 뒤에 다른 api를 사용하기 위해 필요 정보를 array로 리턴한다.
     */
    public function makeDetailBlogInfo(array $blogInfo, int $selectBlogIndex): array
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

    /**
     * @param array $category
     * @return array
     * @description category parent값을 기준으로 subcategory를 parent 하위 형태의 배열로 재가공하여 지정한 path의 categories.json 명으로 저장한 후 저장된 full path를 반환한다.
     */
    public function makeDetailCategory(array $category): array
    {

        try {
            $returnData = array();
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

            $returnData['outFileFullPath'][] = $this->makeJsonFile(data: $item, filePath: $this->outputDataPath, fileName: "categories.json");

        } catch (\Exception $exception) {
            die("makeDetailCategory ERROR :: " . $exception->getMessage());
        }

        return $returnData;
    }

    /**
     * @param array $data
     * @param string $access_token
     * @param string $blogName
     * @param int $page
     * @return array
     * @description 블로그 전체 게시글을 가져와 categoryId를 기준으로 데이터를 가공하여 지정한 path의 categories/{category-id}.json 명으로 저장한 후 필요한 데이터를 array로 리턴한다.
     */
    public function makeAllPostList(array $data, string $access_token, string $blogName, int $page): array
    {
        try {
            $returnData = array();

            $newItems = array();
            $item = $data['tistory']['item'];
            $totalPage = round($item['totalCount'] / $item['count']);

            if ($totalPage > 1) {
                $newItems['posts'][] = $item['posts'];
                for ($i = $page; $i <= $totalPage; $i++) {
                    $newData = $this->getPostList(access_token: $access_token, blog_name: $blogName, page: $i);
                    $newItems['posts'][] = $newData['tistory']['item']['posts'] ?? null;
                }

            }

            // 카테고리ID를 그룹으로 맵핑지어 posts 배열 데이터 재정의
            $posts = $newItems['posts'];
            $groupedPosts = array();

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

    /**
     * @param string $access_token
     * @param string $blogName
     * @param array $data
     * @return array
     * @description 블로그 게시글 content 내용들을 전체 지정한 path의 posts/{category-id}/{post-id}.json 명으로 저장한 후 저장된 full path를 반환한다.
     */
    public function makeAllPostDetailView(string $access_token, string $blogName, array $data): array
    {
        $returnData = array();

        try {
            dump($data);
            $postLists = $data['posts'];
            foreach ($postLists as $categoryId => $postList) {
                foreach ($postList as $key => $postItem) {
                    $newItem = $this->getPostContent(access_token: $access_token, blog_name: $blogName, post_id: $postItem['id']);
                    $returnData['outFileFullPath'][] = $this->makeJsonFile(data: $newItem['item'], filePath: $this->outputDataPath . "posts/" . $categoryId . "/", fileName: $newItem['item']['id'] . ".json");
                }
                usleep(500000); // 0.5초 딜레이
            }
        } catch (\Exception $exception) {
            die("makeAllPostDetailView ERROR :: " . $exception->getMessage());
        }

        return $returnData;
    }


}
