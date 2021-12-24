<?php

namespace tweeterapp\model;

class Tweet extends \Illuminate\Database\Eloquent\Model
{
    protected $table      = 'tweet';
    protected $primaryKey = 'id';
    public $timestamps = true;

    public function tweeter()
    {
        return $this->belongsTo(User::class, 'author');
    }

    public function likedBy()
    {
        return $this->BelongsToMany(User::class, Like::class, 'tweet_id', 'user_id');
    }
}
