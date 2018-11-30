<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Playlist;
use App\Models\Video;
use Illuminate\Support\Facades\DB;

class PlaylistController extends Controller
{
    /**
     * 全プレイリスト取得
     */
    public function getPlaylists()
    {
        return auth()->user()->playlists()->get();
    }

    /**
     * プレイリスト作成
     */
    public function createPlaylist()
    {
        $data = request(['title']);
        $data['user_id'] = auth()->user()->id;
        
        // バリデーション作成
        $validator = Validator::make($data,  [
            'title' => 'required|max:100', 
            'user_id' => 'required|min:1'
        ]);

        if ($validator->passes()) {
            $playlist = Playlist::create($data);
            return $playlist;
        } else {
            return response()->json(['validate_error' => 'バリデーションエラー'], 400);
        }
    }

    /**
     * プレイリスト削除
     */
    public function deletePlaylist()
    {
        $data = request(['playlist_id']);
        Playlist::destroy($data['playlist_id']);
    }

    /**
     * プレイリストに動画を追加
     */
    public function addVideo()
    {
        $data = request(['playlist_id', 'video_id']);
        if (Playlist::find($data['playlist_id']) && Video::find($data['video_id'])) {
            DB::insert('insert into playlist_video (playlist_id, video_id) values (?, ?)', [$data['playlist_id'], $data['video_id']]);
            return Playlist::find($data['playlist_id'])->videos()->get();
        } else {
            return response()->json(['error' => 'failed to add video to playlist']);
        }
    }

    /**
     * プレイリストの動画を削除
     */
    public function deleteVideo()
    {

        $data = request(['playlist_id', 'index']);
        $id = DB::table('playlist_video')->where('playlist_id', $data['playlist_id'])->offset($data['index'])->limit(1)->first()->id;
        DB::table('playlist_video')->where('id', $id)->delete();
        return Playlist::find($data['playlist_id'])->videos()->get();
    }

    /**
     * プレイリスト内の全動画を取得
     */
    public function getVideos($id)
    {
        return Playlist::find($id)->videos()->get();
    }

    /**
     * プレイリストのタイトルを変更
     */
    public function editTitle()
    {
        $data = request(['playlist_id', 'title']);
        $playlist = Playlist::find($data['playlist_id']);
        $playlist->title = $data['title'];
        $playlist->save();
        return response()->json(['message' => 'success edit playlist title']);
    }
}
