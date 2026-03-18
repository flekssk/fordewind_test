<?php

declare(strict_types=1);

namespace App\Services\Cars\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarVoteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'car_id' => 'required|integer|exists:cars,id',
        ];
    }
}
