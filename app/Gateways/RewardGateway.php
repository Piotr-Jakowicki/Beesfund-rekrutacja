<?php

namespace App\Gateways;

use App\Exceptions\InvalidIdException;
use App\Exceptions\InvalidInputException;
use App\Models\Project;
use App\Repositories\ProjectRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RewardGateway
{
    /**
     * Validate project object
     * 
     * @param array $data
     */
    public function validate($data)
    {
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

        return $data;
    }
}
