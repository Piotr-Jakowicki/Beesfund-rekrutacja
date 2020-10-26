<?php

namespace App\Gateways;

use App\Exceptions\InvalidInputException;
use Illuminate\Support\Facades\Validator;

class RewardGateway
{
    /**
     * Validate reward object
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
