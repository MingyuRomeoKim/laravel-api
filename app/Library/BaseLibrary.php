<?php

namespace App\Library;

use MingyuKim\PhpKafka\Libraries\ApiLibrary;

abstract class BaseLibrary
{
    /**
     * @param array $data
     * @param string|null $filePath
     * @param string $fileName
     * @return void
     * @description 지정한 filePath 및 fileName 장소에 data를 json형태로 저장한 후 파일 저장 경로를 반환한다.
     */
    protected function makeJsonFile(array $data, ?string $filePath, string $fileName): string
    {
        try {
            $fileFullPath = !is_null($filePath) ? $filePath . $fileName : $fileName;

            // 디렉토리가 존재하는지 확인하고, 없으면 생성
            if (!is_null($filePath) && !file_exists($filePath)) {
                mkdir($filePath, 0777, true); // true는 중첩된 디렉토리 생성을 허용
            }

            $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            file_put_contents($fileFullPath, $jsonData);

            // 파일의 전체 경로 반환
            return $fileFullPath;

        } catch (\Exception $exception) {
            die("makeJsonFile ERROR :: " . $exception->getMessage());
        }
    }


    protected function checkAlreadyExist(?string $filePath, string $fileName): bool
    {
        $fileFullPath = !is_null($filePath) ? $filePath . $fileName : $fileName;

        if (file_exists($fileFullPath)) {
            return true;
        }

        return false;
    }

    protected function initApiLibrary(string $method, string $url, mixed $data): void
    {
        $this->apiLibrary = ApiLibrary::getInstance();
        $this->apiLibrary->setMethod($method);
        $this->apiLibrary->setApiUrl($url);
        $this->apiLibrary->setRequestData($data);
    }
}
