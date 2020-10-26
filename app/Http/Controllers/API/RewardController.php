<?php

namespace App\Http\Controllers\API;

use App\Exceptions\InvalidInputException;
use App\Gateways\RewardGateway;
use App\Http\Controllers\Controller;
use App\Http\Resources\RewardResource;
use App\Models\Reward;
use Illuminate\Http\Request;

class RewardController extends Controller
{
    private $rewardGateway;

    public function __construct(RewardGateway $rewardGateway)
    {
        $this->rewardGateway = $rewardGateway;
    }

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

        $validatedData = $this->rewardGateway->validate($data);

        $reward = Reward::create($validatedData);

        return (new RewardResource($reward));
    }
}
