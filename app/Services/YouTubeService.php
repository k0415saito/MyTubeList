<?php

namespace App\Services;

use Auth;
use App\Models\Video;

class YoutubeService
{
    private $endpoint   =   'https://www.googleapis.com/youtube/v3/';
    private $pattern    =   "/(youtube.com\/watch\?v=|youtu.be\/)(?<id>[a-zA-Z0-9_-]{11})/";
    private $part       =   'snippet,contentDetails';
    private $fields     =   [
                            'id', 
                            'snippet(title,channelTitle,liveBroadcastContent)', 
                            'contentDetails(duration)'
    ];
    private $api_key;
    
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->api_key = env('YT_API_KEY');
    }

    /**
     * YouTube動画のデータを取得
     * 
     * @param string $url   YouTube動画のURL
     * @param bool   $json  JSONデータを文字列のまま受け取るか
     * @return mixed[]|string 取得したJSONデータの連想配列
     */
    public function getVideoData($id)
    {
        $result = [];
        if (!$this->api_key) {
            return ['errors' => ['error_no_api_key' => 'YouTube Data APIキーが取得できませんでした']];
        }

        try {
            if ($data = file_get_contents($this->buildUrl($id))){
                $data = json_decode($data, true);
                $result = [
                    'user_id'   => auth()->user()->id, 
                    'video_id'  => $id, 
                    'title'     => "", 
                    'artist'    => "", 
                    'user_tag'  => "", 
                    'duration'  => "0H0M0S", 
                ];
                if (array_key_exists('items', $data) && count($data['items']) > 0){
                    $data = $data['items'][0];
                    if (array_key_exists('snippet', $data)){
                        // ライブ配信動画の場合登録拒否
                        if (array_key_exists('liveBroadcastContent', $data['snippet']) &&
                        $data['snippet']['liveBroadcastContent'] == "live") {
                            return ['errors' => ['error_is_live' => 'ライブ動画は登録できません']];
                        }
                        // タイトル
                        if (array_key_exists('title', $data['snippet'])) {
                            $result['title']  = $data['snippet']['title'];
                        }
                        // チャンネル名(アーティストカラム)
                        if (array_key_exists('channelTitle', $data['snippet'])) {
                            $result['artist'] = $data['snippet']['channelTitle'];
                        }
                    }
                    if (array_key_exists('contentDetails', $data)) {
                        if (array_key_exists('duration', $data['contentDetails'])) {
                            $result['duration'] = $this->durationStr($data['contentDetails']['duration']);
                        }
                    }
                }
                return $result;
            }else{
                return ['errors' => ['error_get_data' => '動画データが取得できませんでした']];
            }
        }catch (Exception $e) {
            return ['errors' => ['error_get_data' => '動画データが取得できませんでした']];
        }
    }

    /**
     * YouTubeのURLから動画IDを抽出
     * 
     * @param string $url   YouTube動画のURL
     * 
     * @return string 取得した動画ID
     */
    public function getVideoId($url)
    {
        $id = "";
        if (preg_match($this->pattern, $url, $matches)){
            if (array_key_exists('id', $matches)){
                $id = $matches['id'];
            }
        }
        return $id;
    }

    /**
     * YouTube Data APIのURLを作成
     * 
     * @param string    $id     動画ID
     * @param string    $part   APIのpartパラメータ
     * @param string[]  $fields APIのfieldsパラメータ
     * 
     * @return string パラメータを指定したAPIのURL
     */
    private function buildUrl($id, $part = null, $fields = null)
    {
        if (!$part && !$fields) {
            $part = $this->part;
            $fields = $this->fields;
        }
        $f_param = 'items('.implode(',', $fields).')';
        $url = "{$this->endpoint}videos?id={$id}&part={$part}&fields={$f_param}&key={$this->api_key}";
        return $url;
    }

    /**
     * APIで取得した再生時間形式を変換
     * 
     * @param string $duration  時間の文字列 "PTxxHyyMzzS"形式(HとMは無い可能性あり)
     * 
     * @return string   時間 "xxHyyMzzS"形式
     */
    private function durationStr($duration)
    {

        $h = $m = $s = 0;
        if (preg_match("/PT((?<hour>\d+)H)?((?<minute>\d+)M)?((?<second>\d+)S)?/", $duration, $matches)){
            if (array_key_exists('hour', $matches) && !empty($matches['hour'])){
                $h = (int)$matches['hour'];
            }
            if (array_key_exists('minute', $matches) && !empty($matches['minute'])){
                $m = (int)$matches['minute'];
            }
            if (array_key_exists('second', $matches) && !empty($matches['second'])){
                $s = (int)$matches['second'];
            }
        }
        return "{$h}H{$m}M{$s}S";
    }
}