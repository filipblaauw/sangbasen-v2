<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SongRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
      return [
        'artist' => 'required',
        'title' => 'required',
        'genre_id' => 'required|integer',
        'spotify' => 'nullable',
        'playback' => 'nullable',
        'image' => 'nullable',
        'key' => 'required',
        'time' => 'required',
        'tempo' => 'required|integer',
        'duration' => 'nullable|integer',
        'flow' => 'nullable',
        'chords' => 'nullable',
        'slug' => 'nullable'
      ];
    }

    /**
     * Translate fields with user friendly name.
     *
     * @return array
     */
    public function attributes()
    {
      return [
        'artist' => trans('song.artist'),
        'title' => trans('song.title'),
        'genre_id' => trans('song.genre'),
        'spotify' => trans('song.spotify'),
        'playback' => trans('song.playback'),
        'image' => trans('song.image'),
        'key' => trans('song.key'),
        'time' => trans('song.time'),
        'tempo' => trans('song.tempo'),
        'duration' => trans('song.duration'),
        'flow' => trans('song.flow'),
        'chords' => trans('song.chords'),
        'slug' => trans('song.title')
      ];
    }
}
