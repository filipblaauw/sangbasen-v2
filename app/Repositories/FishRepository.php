<?php
namespace App\Repositories;

use App\Fish;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class FishRepository
{
    private $fish;

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(Fish $fish)
    {
        $this->fish = $fish;
    }

    /**
     * Get fish query
     *
     * @return Fish query
     */

    public function getQuery()
    {
        return $this->fish;
    }

    /**
     * Find fish with given id or throw an error.
     *
     * @param integer $id
     * @return Fish
     */

    public function findOrFail($id)
    {
        $fish = $this->fish->find($id);

        if (! $fish) {
            throw ValidationException::withMessages(['message' => trans('fish.could_not_find')]);
        }
        elseif ($fish->user_id != \Auth::user()->id) {
            throw ValidationException::withMessages(['message' => trans('fish.unauthorized')]);
        }

        return $fish;
    }

    public function findByUuidOrFail($uuid)
    {
        $fish = $this->fish->whereUuid($uuid)->first();

        if (! $fish) {
            throw ValidationException::withMessages(['message' => trans('fish.could_not_find')]);
        }

        return $fish;
    }

    /**
     * Paginate all fishs using given params.
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
        $status     = isset($params['status']) ? $params['status'] : 0;
        $start_date = isset($params['start_date']) ? $params['start_date'] : null;
        $end_date   = isset($params['end_date']) ? $params['end_date'] : null;

        $query = $this->fish->filterByUserId(\Auth::user()->id)->filterByTitleOrDescription($keyword)->filterCompletedFish($status)->dateBetween([
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);

        return $query->orderBy($sort_by, $order)->paginate($page_length);
    }

    /**
     * Create a new fish.
     *
     * @param array $params
     * @return Fish
     */
    public function create($params)
    {
        return $this->fish->forceCreate($this->formatParams($params));
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
          'date'        => isset($params['date']) ? $params['date'] : null,
          'time'        => isset($params['time']) ? $params['time'] : null,
          'species'     => isset($params['species']) ? $params['species'] : null,
          'river'       => isset($params['river']) ? $params['river'] : null,
          'zone'        => isset($params['zone']) ? $params['zone'] : null,
          'lat'         => isset($params['lat']) ? $params['lat'] : null,
          'lng'         => isset($params['lng']) ? $params['lng'] : null,
          'weight'      => isset($params['weight']) ? $params['weight'] : null,
          'length'      => isset($params['length']) ? $params['length'] : null,
          'bait'        => isset($params['bait']) ? $params['bait'] : null,
          'line'        => isset($params['line']) ? $params['line'] : null,
          'waterTemp'   => isset($params['waterTemp']) ? $params['waterTemp'] : null,
          'waterLevel'  => isset($params['waterLevel']) ? $params['waterLevel'] : null,
          'sex'         => isset($params['sex']) ? $params['sex'] : null,
          'farmed'      => isset($params['farmed']) ? $params['farmed'] : null,
          'lice'        => isset($params['lice']) ? $params['lice'] : null,
          'released'    => isset($params['released']) ? $params['released'] : null,
          'photo'       => isset($params['photo']) ? $params['photo'] : null,
          'description' => isset($params['description']) ? $params['description'] : null

        ];

        if ($action === 'create') {
            $formatted['user_id'] = \Auth::user()->id;
            $formatted['uuid'] = Str::uuid();
        }

        return $formatted;
    }

    /**
     * Update given fish.
     *
     * @param Fish $fish
     * @param array $params
     *
     * @return Fish
     */
    public function update(Fish $fish, $params)
    {
        if ($fish->user_id != \Auth::user()->id) {
            throw ValidationException::withMessages(['message' => trans('fish.unauthorized')]);
        }

        $fish->forceFill($this->formatParams($params, 'update'))->save();

        return $fish;
    }

    /**
     * Delete fish.
     *
     * @param integer $id
     * @return bool|null
     */
    public function delete(Fish $fish)
    {
        if ($fish->user_id != \Auth::user()->id) {
            throw ValidationException::withMessages(['message' => trans('fish.unauthorized')]);
        }

        return $fish->delete();
    }

    /**
     * Delete multiple fishs.
     *
     * @param array $ids
     * @return bool|null
     */
    public function deleteMultiple($ids)
    {
        return $this->fish->whereIn('id', $ids)->delete();
    }

    /**
     * Toggle given fish status.
     *
     * @param Fish $fish
     * @param array $params
     *
     * @return Fish
     */
    public function toggle(Fish $fish)
    {
        if ($fish->user_id != \Auth::user()->id) {
            throw ValidationException::withMessages(['message' => trans('fish.unauthorized')]);
        }

        $fish->forceFill([
            'completed_at' => (! $fish->status) ? Carbon::now() : null,
            'status'       => ! $fish->status
        ])->save();

        return $fish;
    }
}
