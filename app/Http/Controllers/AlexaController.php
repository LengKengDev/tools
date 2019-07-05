<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class AlexaController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function set(Request $request) {
        setting(['alexa' => [
            'turn' => $request->input('turn'),
            'count' => $request->input('count'),
        ]])->save();
        return response()->json(['setting' => setting('alexa')]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function get() {
        return response()->json(['setting' => setting('alexa')]);
    }

}
