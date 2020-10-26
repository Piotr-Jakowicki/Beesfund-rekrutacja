<?php

namespace App\Gateways;

use App\Exceptions\InvalidIdException;
use App\Exceptions\InvalidInputException;
use App\Exceptions\InvalidStatusException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectGateway
{
    /**
     * Validate project object
     * 
     * @param array $data
     */
    public function validate($data)
    {
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

        return $data;
    }

    /**
     * Validate project object for update
     * 
     * @param array $data
     * @return array $data
     */
    public function updateValidate($data)
    {
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

        return $data;
    }

    /**
     * Whitelist for statuses
     * 
     * @param \Illuminate\Http\Request  $request
     * @return array $statuses
     */
    public function filterStatuses(Request $request)
    {
        $input = $request->input('status');

        $statuses = explode(',', $input);

        $whitelist = ['started', 'finished', 'draft'];

        if (!empty(array_diff($statuses, $whitelist))) {
            throw new InvalidStatusException();
        }

        return $statuses;
    }

    /**
     * Validate form data
     * 
     * @param \Illuminate\Http\Request  $request
     * @return void
     */
    public function formDataValidation(Request $request)
    {
        $rules = [
            'name' => 'string',
            'status' => 'string|in:started,finished,draft'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            throw new InvalidInputException();
        }
    }
}
