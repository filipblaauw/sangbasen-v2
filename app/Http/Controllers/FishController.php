<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Fish;
use Illuminate\Http\Request;
use App\Http\Requests\FishRequest;
use App\Repositories\FishRepository;
use App\Repositories\ActivityLogRepository;

class FishController extends Controller
{
    protected $module = 'fish';

    private $request;
    private $repo;
    protected $activity;

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, FishRepository $repo, ActivityLogRepository $activity)
    {
        $this->request  = $request;
        $this->repo     = $repo;
        $this->activity = $activity;

        $this->middleware('feature.available:fish');
    }

    /**
     * Used to get all Fishs
     * @get ("/api/fish")
     * @return Response
     */
    public function index()
    {
        $this->authorize('view', Fish::class);

        return $this->repo->paginate($this->request->all());
    }

    /**
     * Used to store Fish
     * @post ("/api/fish")
     * @param ({
     *      @Parameter("title", type="string", required="true", description="Title of Fish"),
     *      @Parameter("date", type="date", required="true", description="Due date of Fish"),
     * })
     * @return Response
     */
    public function store(FishRequest $request)
    {

        $this->authorize('create', Fish::class);

        $fish = $this->repo->create($this->request->all());

        $this->activity->record([
            'module'   => $this->module,
            'module_id' => $fish->id,
            'activity' => 'added'
        ]);

        return $this->success(['message' => trans('fish.added')]);
    }

    /**
     * Used to get Fish detail
     * @get ("/api/fish/{id}")
     * @param ({
     *      @Parameter("id", type="integer", required="true", description="Id of Fish"),
     * })
     * @return Response
     */
    public function show($uuid)
    {
        $fish = $this->repo->findByUuidOrFail($uuid);

        $this->authorize('show', $fish);

        return $fish;
    }

    /**
     * Used to update Fish status
     * @post ("/api/fish/{id}/status")
     * @param ({
     *      @Parameter("id", type="integer", required="true", description="Id of Fish"),
     * })
     * @return Response
     */
    public function toggleStatus($uuid)
    {
        $fish = $this->repo->findByUuidOrFail($uuid);

        $this->authorize('update', $fish);

        $fish = $this->repo->toggle($fish);

        $this->activity->record([
            'module'   => $this->module,
            'module_id' => $fish->id,
            'activity' => 'updated'
        ]);

        return $this->success(['message' => trans('fish.updated'),'fish' => $fish]);
    }

    /**
     * Used to update Fish
     * @patch ("/api/fish/{id}")
     * @param ({
     *      @Parameter("id", type="integer", required="true", description="Id of Fish"),
     *      @Parameter("title", type="string", required="true", description="Title of Fish"),
     *      @Parameter("date", type="date", required="true", description="Due date of Fish"),
     * })
     * @return Response
     */
    public function update($uuid, FishRequest $request)
    {
        $fish = $this->repo->findByUuidOrFail($uuid);

        $this->authorize('update', $fish);

        $fish = $this->repo->update($fish, $this->request->all());

        $this->activity->record([
            'module'   => $this->module,
            'module_id' => $fish->id,
            'activity' => 'updated'
        ]);

        return $this->success(['message' => trans('fish.updated')]);
    }

    /**
     * Used to delete Fish
     * @delete ("/api/fish/{id}")
     * @param ({
     *      @Parameter("id", type="integer", required="true", description="Id of Fish"),
     * })
     * @return Response
     */
    public function destroy($uuid)
    {
        $fish = $this->repo->findOrFail($uuid);

        $this->authorize('delete', $fish);

        $this->activity->record([
            'module'   => $this->module,
            'module_id' => $fish->id,
            'activity' => 'deleted'
        ]);

        $this->repo->delete($fish);

        return $this->success(['message' => trans('fish.deleted')]);
    }
}
