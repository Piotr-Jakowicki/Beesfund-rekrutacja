<?php

namespace App\Http\Controllers\API;

use App\Exceptions\InvalidInputException;
use App\Http\Controllers\Controller;
use App\Http\Resources\RewardResource;
use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RewardController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\RewardResource
     */
    public function store(Request $request)
    {
        if (empty($request->body)) throw new InvalidInputException();

        $data = json_decode($request->body, true);

        $rules = [
            'id' => 'nullable|integer|unique:rewards',
            'projectId' => 'required|integer|exists:projects,id',
            'name' => 'required|string',
            'description' => 'required|string',
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/'
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new InvalidInputException();
        }

        $reward = Reward::create($data);

        return (new RewardResource($reward));
    }
}
