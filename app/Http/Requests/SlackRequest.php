<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SlackRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->route('slack')) {
            return $this->route('slack')->user_id == Auth::user()->id || Auth::user()->id == 1;
        }
        return true;
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'token' => 'Token',
            'checkin' => 'Checkin',
            'checkout' => 'Checkout'
        ];
    }

    public function rules(){
        return [];
    }
}
