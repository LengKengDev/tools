<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SlackSaveRequest extends SlackRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'token' => 'required|unique:slacks',
            'checkin' => 'required|numeric',
            'checkout' => 'required|numeric',
            'workspace' => 'required',
            'channel' => 'required_if:workspace,0'
        ];
    }
}
