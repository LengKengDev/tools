<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SlackUpdateRequest extends SlackRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'token' => 'required|unique:slacks,token,'.$this->route('slack')->id,
            'checkin' => 'required|numeric',
            'checkout' => 'required|numeric'
        ];
    }
}
