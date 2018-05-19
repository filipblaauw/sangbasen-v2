<?php

namespace App\Http\Controllers;

use App\Genre;
use Illuminate\Http\Request;
use App\Http\Requests\GenreRequest;
//use Spatie\Permission\Models\Genre;
use App\Repositories\GenreRepository;
use App\Http\Requests\PermissionRequest;
use App\Repositories\ActivityLogRepository;

class GenreController extends Controller
{
    protected $request;
    protected $repo;
    protected $activity;
    protected $module = 'genre';

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, GenreRepository $repo, ActivityLogRepository $activity)
    {
        $this->request = $request;
        $this->repo = $repo;
        $this->activity = $activity;

        $this->middleware('feature.available:song');
    }

    /**
     * Used to get all Genres
     * @get ("/api/genre")
     * @return Response
     */
    public function list()
    {
        return $this->repo->getAll();
        ;
    }
    public function index()
    {
        return $this->repo->paginate($this->request->all());

    }

    /**
     * Used to store Genre
     * @post ("/api/genre")
     * @param ({
     *      @Parameter("name", type="string", required="true", description="Name of Genre"),
     * })
     * @return Response
     */
    public function store(GenreRequest $request)
    {
        $genre = Genre::create(['name' => request('name')]);

        $this->activity->record([
            'module' => $this->module,
            'module_id' => $genre->id,
            'activity' => 'added'
        ]);

        return $this->success(['message' => trans('genre.added')]);
    }

    /**
     * Used to get Genre detail
     * @post ("/api/genre/{id}")
     * @param ({
     *      @Parameter("id", type="integer", required="true", description="Id of Genre"),
     * })
     * @return Response
     */
    public function show($id)
    {
        return $this->repo->findOrFail($id);
    }

    /**
     * Used to update Genre
     * @patch ("/api/genre/{id}")
     * @param ({
     *      @Parameter("id", type="integer", required="true", description="Id of Genre"),
     *      @Parameter("name", type="string", required="true", description="Name of Genre"),
     * })
     * @return Response
     */
     public function update(GenreRequest $request, $id)
     {

         $genre = $this->repo->findOrFail($id);

         $genre = $this->repo->update($genre, $this->request->all());

         $this->activity->record([
             'module' => $this->module,
             'module_id' => $genre->id,
             'activity' => 'updated'
         ]);

         return $this->success(['message' => trans('genre.updated')]);
     }

    /**
     * Used to delete genre
     * @delete ("/api/genre")
     * @param ({
     *      @Parameter("id", type="integer", required="true", description="Id of Genre to be deleted"),
     * })
     * @return Response
     */
    public function destroy($id)
    {
        $genre = $this->repo->deletable($id);

        $this->activity->record([
            'module' => $this->module,
            'module_id' => $genre->id,
            'activity' => 'deleted'
        ]);

        $this->repo->delete($genre);

        return $this->success(['message' => trans('genre.deleted')]);
    }
}
