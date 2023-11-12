<?php

namespace App\Library;

use MingyuKim\PhpKafka\Libraries\ApiLibrary;

class TistoryLibrary
{
    private ApiLibrary $apiLibrary;
    private string $client_id;
    private string $client_secret;
    private string $redirect_url;
    private string $grant_type;
    private string $output_type;

    public function __construct()
    {
        $this->client_id = config('tistory.client_id');
        $this->client_secret = config('tistory.client_secret');
        $this->redirect_url = config('tistory.redirect_url');
        $this->grant_type = config('tistory.grant_type');
        $this->output_type = config('tistory.output_type');
    }

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

    private function initApiLibrary(string $method, string $url, mixed $data): void
    {
        $this->apiLibrary = ApiLibrary::getInstance();
        $this->apiLibrary->setMethod($method);
        $this->apiLibrary->setApiUrl($url);
        $this->apiLibrary->setRequestData($data);
    }
}
