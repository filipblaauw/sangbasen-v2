<?php
namespace App\Repositories;

use App\Song;
use App\Genre;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class SongRepository
{
    private $song;

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(Song $song)
    {
        $this->song = $song;
    }

    /**
     * Get song query
     *
     * @return Song query
     */

    public function getQuery()
    {
        return $this->song;
    }

    /**
     * Find song with given slug or throw an error.
     *
     * @param integer $slug
     * @return Song
     */

    public function findBySlugOrFail($slug)
    {
        $song = $this->song->whereSlug($slug)->with('genre', 'user')->first();

        if (! $song) {
            throw ValidationException::withMessages(['message' => trans('song.could_not_find')]);
        }
        //elseif ($song->user_id != \Auth::user()->id) {
        //    throw ValidationException::withMessages(['message' => trans('song.unauthorized')]);
        //}

        return $song;
    }

    /**
     * Paginate all songs using given params.
     *
     * @param array $params
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */

    public function paginate($params)
    {
        $sort_by     = isset($params['sort_by']) ? $params['sort_by'] : 'created_at';
        $order      = isset($params['order']) ? $params['order'] : 'desc';
        $page_length = isset($params['page_length']) ? $params['page_length'] : config('config.page_length');
        $keyword     = isset($params['keyword']) ? $params['keyword'] : null;
        $genre     = isset($params['genre']) ? $params['genre'] : 0;
        $start_date = isset($params['start_date']) ? $params['start_date'] : null;
        $end_date   = isset($params['end_date']) ? $params['end_date'] : null;

        $query = $this->song->with('genre')->filterByTitleOrDescription($keyword)->filterGenre($genre)->dateBetween([
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);

        return $query->orderBy($sort_by, $order)->paginate($page_length);
    }

    /**
     * Create a new song.
     *
     * @param array $params
     * @return Song
     */
    public function create($params)
    {
        return $this->song->forceCreate($this->formatParams($params));
    }

    /**
     * Prepare given params for inserting into database.
     *
     * @param array $params
     * @param string $type
     * @return array
     */
    private function formatParams($params, $action = 'create')
    {
        $formatted = [
          'artist'    => isset($params['artist']) ? $params['artist'] : null,
          'title'     => isset($params['title']) ? $params['title'] : null,
          'genre_id'  => isset($params['genre_id']) ? $params['genre_id'] : null,
          'spotify'   => isset($params['spotify']) ? $params['spotify'] : null,
          'playback'  => isset($params['playback']) ? $params['playback'] : null,
          'image'     => isset($params['image']) ? $params['image'] : null,
          'key'       => isset($params['key']) ? $params['key'] : null,
          'time'      => isset($params['time']) ? $params['time'] : null,
          'tempo'     => isset($params['tempo']) ? $params['tempo'] : null,
          'duration'  => isset($params['duration']) ? $params['duration'] : null,
          'author'    => isset($params['author']) ? $params['author'] : null,
          'chords'    => isset($params['chords']) ? $params['chords'] : null,
          'comments'  => isset($params['comments']) ? $params['comments'] : null,
          //'slug'      => isset($params['title']) ? createSlug($params['title']) : null

        ];

        if ($action === 'create') {
            $formatted['user_id'] = \Auth::user()->id;
            $formatted['uuid'] = Str::uuid();
            $formatted['slug'] = createSlug($params['title']);
        }

        return $formatted;
    }

    /**
     * Update given song.
     *
     * @param Song $song
     * @param array $params
     *
     * @return Song
     */
    public function update(Song $song, $params)
    {
      // Check if current user or admin 1 is updating song
      if ($song->user_id === \Auth::user()->id || 1) {
        $song->forceFill($this->formatParams($params, 'update'))->save();
        return $song;
      } else {
        throw ValidationException::withMessages(['message' => trans('song.unauthorized')]);
      }
    }

    /**
     * Delete song.
     *
     * @param integer $id
     * @return bool|null
     */
    public function delete(Song $song)
    {
        if ($song->user_id != \Auth::user()->id) {
            throw ValidationException::withMessages(['message' => trans('song.unauthorized')]);
        }

        return $song->delete();
    }

}
