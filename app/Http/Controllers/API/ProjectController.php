<?php

namespace App\Http\Controllers\API;

use App\Exceptions\InvalidIdException;
use App\Exceptions\InvalidInputException;
use App\Gateways\ProjectGateway;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProjectController extends Controller
{
    private $projectGateway;

    public function __construct(ProjectGateway $projectGateway)
    {
        $this->projectGateway = $projectGateway;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (empty($request->body)) throw new InvalidInputException();

        $data = json_decode($request->body, true);

        $validatedData = $this->projectGateway->validate($data);

        $project = Project::create($validatedData);

        if (!empty($validatedData['rewards'])) {
            foreach ($validatedData['rewards'] as $reward) {
                Reward::create($reward);
            }
        }

        return (new ProjectResource($project));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\ProjectResource
     */
    public function updateObject(Request $request)
    {
        $data = json_decode($request->body, true);

        if (!is_numeric($data['id']) || $data['id'] < 0) throw new InvalidIdException();

        $project = Project::findOrFail($data['id']);

        $validatedData = $this->projectGateway->updateValidate($data);

        $project->update($validatedData);

        if (!empty($validatedData['rewards'])) {
            foreach ($validatedData['rewards'] as $reward) {
                if (empty($reward['id'])) {
                    Reward::create($reward);
                } else {
                    $rewardInstance = Reward::find($reward['id']);
                    $rewardInstance->update($reward);
                }
            }
        }

        return (new ProjectResource($project));
    }

    /**
     * Find the specified resource by status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return App\Http\Resources\ProjectResource
     */
    public function findByStatus(Request $request)
    {
        $statuses = $this->projectGateway->filterStatuses($request);

        $data = Project::whereIn('status', $statuses)->get();

        $data = $this->cacheResponse($data);

        return ProjectResource::collection($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \App\Http\Resources\ProjectResource
     */
    public function show($id)
    {
        if (!is_numeric($id) || $id < 0) throw new InvalidIdException();

        $project = Project::findOrFail($id);

        $project = $this->cacheResponse($project);

        return (new ProjectResource($project));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \App\Http\Resources\ProjectResource
     */
    public function update(Request $request, Project $project)
    {
        $this->projectGateway->formDataValidation($request);

        if (isset($request->name)) {
            $project->name = $request->name;
        }

        if (isset($request->status)) {
            $project->status = $request->status;
        }

        $project->save();

        return (new ProjectResource($project));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!is_numeric($id) || $id < 0) throw new InvalidIdException();

        $project = Project::findOrFail($id);

        $project->delete();

        return response()->json($project);
    }

    protected function cacheResponse($data)
    {
        $url = request()->url();
        $queryParams = request()->query();

        $queryString = http_build_query($queryParams);

        $fullUrl = "{$url}?{$queryString}";

        return Cache::remember($fullUrl, 30, function () use ($data) {
            return $data;
        });
    }
}
