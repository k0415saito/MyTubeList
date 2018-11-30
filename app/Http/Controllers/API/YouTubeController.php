<?php

namespace App\Http\Controllers\API;

use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\YouTubeService;
use App\Models\Video;
use Illuminate\Support\Facades\DB;

class YouTubeController extends Controller
{
    private $youtube_service;
    private $data;
    private $errors = [];

    public function __construct()
    {
        $this->youtube_service = new YouTubeService();
    }

    /**
     * 動画を登録する
     * @param url
     * @return 追加した動画の情報
     */
    public function addVideoData()
    {
        // リクエストにurlパラメータが存在しない場合
        if (!(request('url') && !empty(request('url')))) {
            $this->errors['error_no_url'] = 'URLが取得できません';
            return $this->error();
        }
        
        // 動画情報を取得
        $id = $this->youtube_service->getVideoId(request('url'));
        if ($this->data = $this->youtube_service->getVideoData($id)) {
            // 取得データにエラーが含まれる場合
            if (array_key_exists('errors', $this->data)) {
                $this->errors = $this->data['errors'];
                return $this->error();
            }

            $rules = [
                'user_id'   => 'required|min:1', 
                'video_id'  => ['required', 
                                'size:11', 
                                Rule::unique('videos')->where(function($q) {
                                    return $q->where('user_id', $this->data['user_id']);})], 
                'title'     => 'required|max:100', 
                'artist'    => 'nullable', 
                'user_tag'  => 'nullable', 
                'duration'  => 'required'
            ];
            $validator = Validator::make($this->data, $rules);

            if ($validator->passes()) {
                $video = Video::create($this->data);
                return $video->getData();
            } else {
                $this->errors['error_validate'] = $validator->errors()->all();
            }
        }
        else {
            $this->errors['error_get_data'] = '動画データが取得できませんでした';
        }
        return $this->error();
    }

    /**
     * ユーザーの全動画の情報を取得
     */
    public function getAllVideos()
    {
        return auth()->user()->videos();
    }

    /**
     * 動画を削除する
     */
    public function deleteVideoData()
    {
        if ($video = Video::find(request('video_id'))) {
            $video->delete();
            // プレイリストとの紐づけ情報も削除
            DB::table('playlist_video')->where('video_id', request('video_id'))->delete();
            return auth()->user()->videos();
        }
        $errors['error_invalid_id'] = '無効なIDです';
        return $this->error();
    }

    /**
     * 動画の情報を編集する
     */
    public function editVideoData()
    {
        $data = request(['id', 'title', 'artist', 'usertag']);

        $rules = [
            'title'     => 'required|max:100', 
            'artist'    => 'nullable', 
            'user_tag'  => 'nullable', 
        ];

        $validator = Validator::make($data, $rules);
        if ($validator->passes()) {
            $video = Video::find($data['id']);
            $video->title = $data['title'];
            $video->artist = $data['artist'] ? $data['artist'] : '';
            $video->user_tag = $data['usertag'] ? $data['usertag'] : '';
            $video->save();
            return response()->json(['message' => 'success edit video info']);
        }
    }

    /**
     * エラーレスポンス
     * 
     * @return Response JSON形式のエラーレスポンス
     */
    private function error()
    {
        return response()->json($this->errors, 400);
    }
}
