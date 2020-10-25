<?php

namespace App\Http\Controllers\API;

use App\Exceptions\InvalidIdException;
use App\Exceptions\InvalidInputException;
use App\Exceptions\InvalidStatusException;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
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

        $rules = [
            'id' => 'integer|unique:projects',
            'name' => 'required|string',
            'description' => 'required|string',
            'status' => 'string|in:started,finished,draft',
        ];

        if (!empty($data['rewards'])) {
            $rewardsRules = [
                'rewards.*.id' => 'integer|unique:rewards',
                'rewards.*.projectId' => 'required|integer|same:id',
                'rewards.*.name' => 'required|string',
                'rewards.*.description' => 'required|string',
                'rewards.*.amount' => 'required|regex:/^\d+(\.\d{1,2})?$/'
            ];

            $rules = array_merge($rules, $rewardsRules);
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new InvalidInputException();
        }

        $project = Project::create($data);

        if (!empty($data['rewards'])) {
            foreach ($data['rewards'] as $reward) {
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

        if (!$project) {
            return $this->responseError('404', 'Client Error', 'Project not found');
        }

        $rules = [
            'id' => 'required|exists:projects,id|integer',
            'name' => 'required|string',
            'description' => 'required|string',
            'status' => 'string|in:started,finished,draft',
        ];

        if (!empty($data['rewards'])) {
            $rewardRules = [
                'rewards.*.id' => 'integer|exists:rewards,id',
                'rewards.*.projectId' => 'required|integer|same:id',
                'rewards.*.name' => 'required|string',
                'rewards.*.description' => 'required|string',
                'rewards.*.amount' => 'required|regex:/^\d+(\.\d{1,2})?$/'
            ];

            $rules = array_merge($rules, $rewardRules);
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new InvalidInputException();
        }

        $project->update($data);

        if (!empty($data['rewards'])) {
            foreach ($data['rewards'] as $reward) {
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
        $input = $request->input('status');

        $statuses = explode(',', $input);

        $whitelist = ['started', 'finished', 'draft'];

        if (!empty(array_diff($statuses, $whitelist))) {
            throw new InvalidStatusException();
        }

        return ProjectResource::collection(Project::whereIn('status', $statuses)->get());
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
    }
}
