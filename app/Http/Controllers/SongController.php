<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Song;
use Illuminate\Http\Request;
use App\Http\Requests\SongRequest;
use App\Repositories\SongRepository;
use App\Repositories\ActivityLogRepository;

class SongController extends Controller
{
    protected $module = 'song';

    private $request;
    private $repo;
    protected $activity;

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, SongRepository $repo, ActivityLogRepository $activity)
    {
        $this->request  = $request;
        $this->repo     = $repo;
        $this->activity = $activity;

        $this->middleware('feature.available:song');
    }

    /**
     * Used to get all Songs
     * @get ("/api/song")
     * @return Response
     */
    public function index()
    {
        $this->authorize('view', Song::class);

        return $this->repo->paginate($this->request->all());
    }

    /**
     * Used to store Song
     * @post ("/api/song")
     * @param ({
     *      @Parameter("title", type="string", required="true", description="Title of Song"),
     *      @Parameter("date", type="date", required="true", description="Due date of Song"),
     * })
     * @return Response
     */
    public function store(SongRequest $request)
    {

        $this->authorize('create', Song::class);

        $song = $this->repo->create($this->request->all());

        $this->activity->record([
            'module'   => $this->module,
            'module_id' => $song->id,
            'activity' => 'added'
        ]);

        return $this->success(['message' => trans('song.added')]);
    }

    /**
     * Used to get Song detail
     * @get ("/api/song/{id}")
     * @param ({
     *      @Parameter("id", type="integer", required="true", description="Id of Song"),
     * })
     * @return Response
     */
    public function show($slug)
    {
        $song = $this->repo->findBySlugOrFail($slug);

        $this->authorize('show', $song);

        return $song;
    }

    /**
     * Used to update Song status
     * @post ("/api/song/{id}/status")
     * @param ({
     *      @Parameter("id", type="integer", required="true", description="Id of Song"),
     * })
     * @return Response
     */
    public function toggleStatus($slug)
    {
        $song = $this->repo->findBySlugOrFail($slug);

        $this->authorize('update', $song);

        $song = $this->repo->toggle($song);

        $this->activity->record([
            'module'   => $this->module,
            'module_id' => $song->id,
            'activity' => 'updated'
        ]);

        return $this->success(['message' => trans('song.updated'),'song' => $song]);
    }

    /**
     * Used to update Song
     * @patch ("/api/song/{id}")
     * @param ({
     *      @Parameter("id", type="integer", required="true", description="Id of Song"),
     *      @Parameter("title", type="string", required="true", description="Title of Song"),
     *      @Parameter("date", type="date", required="true", description="Due date of Song"),
     * })
     * @return Response
     */
    public function update($slug, SongRequest $request)
    {
        $song = $this->repo->findBySlugOrFail($slug);

        $this->authorize('update', $song);

        $song = $this->repo->update($song, $this->request->all());

        $this->activity->record([
            'module'   => $this->module,
            'module_id' => $song->id,
            'activity' => 'updated'
        ]);

        return $this->success(['message' => trans('song.updated')]);
    }

    /**
     * Used to delete Song
     * @delete ("/api/song/{id}")
     * @param ({
     *      @Parameter("id", type="integer", required="true", description="Id of Song"),
     * })
     * @return Response
     */
    public function destroy($slug)
    {
        $song = $this->repo->findBySlugOrFail($slug);

        $this->authorize('delete', $song);

        $this->activity->record([
            'module'   => $this->module,
            'module_id' => $song->id,
            'activity' => 'deleted'
        ]);

        $this->repo->delete($song);

        return $this->success(['message' => trans('song.deleted')]);
    }
}
