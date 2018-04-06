<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fish extends Model
{
    protected $fillable = ['date', 'time', 'species', 'river', 'zone', 'weight', 'length', 'bait', 'line', 'lat', 'lng', 'waterLevel', 'waterTemp', 'sex', 'farmed', 'lice', 'released', 'photo', 'description'];
    protected $primaryKey = 'id';
    protected $table = 'fishes';

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function scopeFilterByTitleOrDescription($q, $keyword = null)
    {
        if (! $keyword) {
            return $q;
        }

        return $q->where('river', 'like', '%'.$keyword.'%')->orWhere('species', 'like', '%'.$keyword.'%')->orWhere('description', 'like', '%'.$keyword.'%');
    }

    public function scopeFilterCompletedFish($q, $status = null)
    {
        if (! $status) {
            return $q;
        }

        return $q->whereReleased(1);
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

        return $q->where('date', '>=', getStartOfDate($dates['start_date']))->where('date', '<=', getEndOfDate($dates['end_date']));
    }
}
