<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    protected $fillable = ['artist', 'title', 'genre_id', 'spotify', 'playback', 'image', 'key', 'time', 'tempo', 'duration', 'author', 'chords', 'comments', 'slug'];
    protected $primaryKey = 'id';
    protected $table = 'songs';

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function genre()
    {
        return $this->belongsTo('App\Genre');
    }

    public function scopeFilterByTitleOrDescription($q, $keyword = null)
    {
        if (! $keyword) {
            return $q;
        }

        return $q ->where('artist', 'like', '%'.$keyword.'%')
                  ->orWhere('title', 'like', '%'.$keyword.'%')
                  ->orWhere('chords', 'like', '%'.$keyword.'%');
    }

    public function scopeFilterGenre($q, $genre = null)
    {
        if (! $genre) {
            return $q;
        }

        return $q->where('genre_id', 'like', $genre);
    }

    public function scopeFilterByUserId($q, $user_id = null)
    {
        if (! $user_id) {
            return $q;
        }

        return $q->whereUserId($user_id);
    }

    public function scopeDateBetween($q, $dates)
    {
        if ((! $dates['start_date'] || ! $dates['end_date']) && $dates['start_date'] <= $dates['end_date']) {
            return $q;
        }

        return $q->where('updated_at', '>=', getStartOfDate($dates['start_date']))->where('updated_at', '<=', getEndOfDate($dates['end_date']));
    }
}
