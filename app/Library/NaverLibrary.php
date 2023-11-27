<?php

namespace App\Library;

use MingyuKim\PhpKafka\Libraries\ApiLibrary;

class NaverLibrary extends BaseLibrary
{
    protected ApiLibrary $apiLibrary;
    protected string $outputDataPath;
    private string $client_id;
    private string $client_secret;

    public function __construct()
    {
        $this->client_id = config('naver.client_id');
        $this->client_secret = config('naver.client_secret');
        $this->outputDataPath = config('naver.output_data_path') ?? "";
    }

    /**
     * @param string $keyword
     * @return array
     * @description 블로그 서치 정보 가져오기
     */
    public function getSearchBlog(string $keyword): array
    {
        $requestData = [
            'query' => urlencode($keyword)
        ];


        $this->initApiLibrary(method: 'GET', url: 'https://openapi.naver.com/v1/search/blog', data: $requestData);

        $result = $this->apiLibrary->callAPI();

        if (!$result || empty($result)) {
            die("getDescription :: result not found");
        }

        $result = json_decode($result, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            die("getSearchBlog JSON decoding error: " . json_last_error_msg());
        }

        return $result;
    }


    /**
     * @param string $keyword
     * @return array
     * @description 백과사전 서치 정보 가져오기
     */
    public function getSearchDictionary(string $keyword): array
    {
        if (!$this->checkAlreadyExist(filePath: $this->outputDataPath, fileName: $keyword . ".json")) {

            $requestData = [
                'query' => $keyword
            ];

            $this->initApiLibrary(method: 'GET', url: 'https://openapi.naver.com/v1/search/encyc', data: $requestData);

            $result = $this->apiLibrary->callAPI();

            if (!$result || empty($result)) {
                die("getSearchDictionary :: result not found");
            }

            $result = json_decode($result, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                die("getSearchDictionary JSON decoding error: " . json_last_error_msg());
            }

            $this->makeDetailDictionary(keyword: $keyword, data: $result);

        } else {
            $result = file_get_contents($this->outputDataPath.$keyword.'.json');
            $result = json_decode($result,true);
        }
        return $result;
    }

    public function makeDetailDictionary(string $keyword, array $data)
    {
        try {
            $returnData = array();
            $returnData['outFileFullPath'][] = $this->makeJsonFile(data: $data, filePath: $this->outputDataPath, fileName: $keyword . ".json");

        } catch (\Exception $exception) {
            die("makeDetailCategory ERROR :: " . $exception->getMessage());
        }

        return $returnData;
    }

    protected function initApiLibrary(string $method, string $url, mixed $data): void
    {
        parent::initApiLibrary($method, $url, $data); // TODO: Change the autogenerated stub
        $header = [
            'Content-Type: application/json',
            'X-Naver-Client-Id: ' . $this->client_id,
            'X-Naver-Client-Secret: ' . $this->client_secret
        ];
        $this->apiLibrary->setHeader($header);
    }

}