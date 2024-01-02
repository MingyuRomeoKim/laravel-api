<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Library\YoutubeLibrary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class YoutubeController extends Controller
{

    private YoutubeLibrary $youtubeLibrary;
    private string $youtube_api_v3_key;

    public function __construct(YoutubeLibrary $youtubeLibrary)
    {
        $this->youtubeLibrary = $youtubeLibrary;
        $this->youtube_api_v3_key = config('google.youtube_api_v3_key');
        $this->youtubeLibrary->init(youtubeApiKey: $this->youtube_api_v3_key, applicationName: "TestMingyu");
    }

    /**
     * 채널 ID 가져오기
     *
     * @param string $channelName
     * @return JsonResponse
     */
    public function getChannelId(string $channelName): JsonResponse
    {
        $channelId = $this->youtubeLibrary->getYoutubeChannelId(channelName: $channelName);
        return response()->json(['channelName' => $channelName, 'channelId' => $channelId]);
    }

    /**
     * 특정 채널의 비디오 목록 가져오기
     *
     * @param string $channelId
     * @return JsonResponse
     */
    public function getVideoList(string $channelId): JsonResponse
    {
        try {
            $response = $this->youtubeLibrary->getYoutubeVideoLists(channelId: $channelId, duration: "-1 month", maxResults: 10, searchQuery: '"연애 대상"');
        } catch (\Exception $exception) {
            $response = ['errorCode' => $exception->getCode(), 'errorMessage' => $exception->getMessage()];
        }
        return response()->json(['channelId' => $channelId, 'response' => $response]);
    }

    // 특정 비디오의 상세 정보 가져오기
    public function getVideoDetail(string $videoId)
    {
        try {
            $response = $this->youtubeLibrary->getYoutubeVideoDetail(videoId: $videoId );
        } catch (\Exception $exception) {
            $response = ['errorCode' => $exception->getCode(), 'errorMessage' => $exception->getMessage()];
        }
        return response()->json(['videoId' => $videoId, 'response' => $response]);
    }
}
