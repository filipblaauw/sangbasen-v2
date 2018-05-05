<?php
namespace App\Repositories;

use App\Genre;
use Illuminate\Validation\ValidationException;

class GenreRepository
{
    protected $genre;

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(Genre $genre)
    {
        $this->genre = $genre;
    }

    /**
     * Get all genres
     *
     * @return Genre
     */

    public function getAll()
    {
        return $this->genre->orderBy('name')->get();
    }

    /**
     * List all genres by name & id
     *
     * @return Genre
     */

    public function list()
    {
        return $this->genre->all()->pluck('name', 'id')->all();
    }

    /**
     * Get genre by name
     *
     * @return Genre
     */

    public function findByName($name = null)
    {
        return $this->genre->filterByName($name)->first();
    }

    /**
     * List (name,id) all genres by name where given name is not included
     *
     * @return Genre
     */

    public function listExceptName($names = array())
    {
        return $this->genre->whereNotIn('name', $names)->get()->pluck('name', 'id')->all();
    }

    /**
     * List (name) all genres by id
     *
     * @return Genre
     */

    public function listNameById($ids = array())
    {
        $ids = is_array($ids) ? $ids : ($ids) ? [$ids] : [];

        return $this->genre->whereIn('id', $ids)->get()->pluck('name')->all();
    }

    /**
     * List all names
     *
     * @return Genre
     */

    public function listName()
    {
        return $this->genre->all()->pluck('name')->all();
    }

    /**
     * Find activity log with given id or throw an error.
     *
     * @param integer $id
     * @return Genre
     */

    public function findOrFail($id)
    {
        $genre = $this->genre->find($id);

        if (! $genre) {
            throw ValidationException::withMessages(['message' => trans('genre.could_not_find')]);
        }

        return $genre;
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
            'name' => isset($params['name']) ? $params['name'] : null
        ];

        return $formatted;
    }

    /**
     * Update given genre.
     *
     * @param Genre $genre
     * @param array $params
     *
     * @return Genre
     */
    public function update(Genre $genre, $params)
    {

        $genre->forceFill($this->formatParams($params, 'update'))->save();

        return $genre;
    }

    /**
     * Paginate all activity logs using given params.
     *
     * @param array $params
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */

    public function paginate($params)
    {
        $sort_by     = isset($params['sort_by']) ? $params['sort_by'] : 'name';
        $order      = isset($params['order']) ? $params['order'] : 'asc';
        $page_length = isset($params['page_length']) ? $params['page_length'] : config('config.page_length');

        return $this->genre->with('songs')->orderBy($sort_by, $order)->paginate($page_length);
    }

    /**
     * Find genre & check it can be deleted or not.
     *
     * @param integer $id
     * @return Locale
     */
    public function deletable($id)
    {
        $genre = $this->findOrFail($id);

        return $genre;
    }

    /**
     * Delete activity log.
     *
     * @param integer $id
     * @return bool|null
     */

    public function delete(Genre $genre)
    {
        return $genre->delete();
    }

}
