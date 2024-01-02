<?php

namespace App\Library;

use Google\Client;
use Google\Service\YouTube;

class YoutubeLibrary
{
    private YouTube $youTube;

    /**
     * @param string $youtubeApiKey
     * @param string $applicationName
     * @return void
     * @Description 해당 라이브러리 클래스 사용시 초기화
     */
    public function init(string $youtubeApiKey, string $applicationName)
    {
        $client = new Client();
        $client->setApplicationName(applicationName: $applicationName);
        $client->setDeveloperKey(developerKey: $youtubeApiKey);
        $this->youTube = new YouTube($client);
    }

    /**
     * @param string $channelName
     * @return string|null
     * @Description Youtube 채널명으로 채널 ID 가져오기
     */
    public function getYoutubeChannelId(string $channelName): ?string
    {
        $queryParams = ['forUsername' => $channelName];
        $response = $this->youTube->channels->listChannels('id',$queryParams);

        return $response['items'][0]['id'] ?? null;
    }

    /**
     * @param string $channelId
     * @param string|null $duration
     * @param int $maxResults
     * @param string|null $searchQuery
     * @return array|null
     * @description Youtube ChannelId에 해당하는 리스트를 params 옵션으로 필터링하여 가져오기
     * @throws \Exception
     */
    public function getYoutubeVideoLists(string $channelId, ?string $duration, int $maxResults, ?string $searchQuery = null): ?array
    {
        $videoLists = [];
        $dateTime = new \DateTime('now', new \DateTimeZone("GMT"));
        $nowTime = date("Y-m-d\TH:i:s\Z", strtotime($duration, $dateTime->getTimestamp()));

        $queryParams = [
            'channelId' => $channelId,
            'maxResults' => $maxResults,
            'order' => 'date',
            'publishedAfter' => $nowTime
        ];

        if (!is_null($searchQuery)) {
            $queryParams['q'] = $searchQuery;
        }

        $response = $this->youTube->search->listSearch('snippet', $queryParams);
        $videoLists = array_merge($videoLists, $response['items']);


        do {
            if (count($videoLists) > $maxResults) {
                break;
            }

            if (isset($response['nextPageToken'])) {
                $queryParams['pageToken'] = $response['nextPageToken'];
            } else {
                if (array_key_exists('pageToken', $queryParams)) {
                    unset($queryParams['pageToken']);
                }
            }

            $response = $this->youTube->search->listSearch('snippet', $queryParams);
            $videoLists = array_merge($videoLists, $response['items']);
        } while (isset($response['nextPageToken']));


        return $videoLists ?? null;
    }

    /**
     * @param array|null $items
     * @return array
     * @Description Youtube Video Items에서 Video ID값만 배열로 필터링하여 가져오기
     */
    public function getYoutubeVideoIds(?array $items): array
    {
        $videoIds = [];

        foreach ($items as $item) {
            if (isset($item['id']) && $item['id']['videoId'] !== null) {
                $videoIds[] = $item['id']['videoId'];
            }
        }

        return $videoIds;
    }

    /**
     * @param array|null $videoIds
     * @return array
     * @description Youtube Video Id를 사용하여 디테일한 정보 가공하여 가져오기
     */
    public function getYoutubeVideoDetails(?array $videoIds): array
    {
        $videos = [];
        $chunkDefault = 50;
        try {
            if (!empty($videoIds)) {
                $videoDetails = [];

                if (count($videoIds) > $chunkDefault) {
                    $chunkVideoIds = array_chunk($videoIds, $chunkDefault);

                    foreach ($chunkVideoIds as $chunkVideoId) {
                        $response = $this->youTube->videos->listVideos('snippet,statistics', ['id' => implode(',', $chunkVideoId)]);
                        $videoDetails = array_merge($videoDetails, $response['items']);
                    }
                } else {
                    $response = $this->youTube->videos->listVideos('snippet,statistics', ['id' => implode(',', $videoIds)]);
                    $videoDetails = $response['items'];
                }

                foreach ($videoDetails as $videoDetail) {
                    $videos[] = [
                        'videoId' => $videoDetail['id'],
                        'url' => 'https://www.youtube.com/watch?v=' . $videoDetail['id'],
                        'title' => $videoDetail['snippet']['title'],
                        'description' => $videoDetail['snippet']['description'],
                        'thumbnail' => $videoDetail['snippet']['thumbnails']['high']['url'],
                        'publishTime' => date("Y-m-d H:i:s", strtotime($videoDetail["snippet"]["publishedAt"])),
                        'viewCount' => $videoDetail['statistics']['viewCount'],
                        'likeCount' => $videoDetail['statistics']['likeCount'],
                        'dislikeCount' => $videoDetail['statistics']['dislikeCount'],
                        'commentCount' => $videoDetail['statistics']['commentCount'],
                    ];
                }
            }
        } catch (\Exception $exception) {
            dump($exception->getMessage());
        }

        return $videos;
    }
}
