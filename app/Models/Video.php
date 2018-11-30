<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function playlists()
    {
        return $this->belongsToMany('App\Models\Playlist');
    }

    public function scopeGetData($query)
    {
        return $query->
        where('id', $this->id)->
        select('video_id', 'title', 'artist', 'user_tag', 'duration', 'id')->first();
    }
    

}
