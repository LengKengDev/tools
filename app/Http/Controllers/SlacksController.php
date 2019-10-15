<?php

namespace App\Http\Controllers;

use App\Http\Requests\SlackRequest;
use App\Http\Requests\SlackSaveRequest;
use App\Http\Requests\SlackUpdateRequest;
use App\Slack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SlacksController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $slacks = Slack::paginate();
        return view('slacks.index', compact('slacks'));
    }
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('slacks.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SlackSaveRequest $request)
    {
        Slack::create(array_merge($request->only(
            ['cw', 'token', 'checkin', 'checkout', 'channel']
        ), ['user_id' => Auth::user()->id]));
        return redirect()->route('home');
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(SlackRequest $request, Slack $slack)
    {
        $logs = $slack->activities()
            ->orderBy('created_at', 'desc')
            ->limit(25)->get();
        return view('slacks.edit', compact('slack', 'logs'));
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SlackUpdateRequest $request, Slack $slack)
    {
        $slack->update($request->only(
            ['cw', 'token', 'checkin', 'checkout', 'channel']
        ));
        return back();
    }

    /**
     * @param SlackRequest $request
     * @param Slack $slack
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(SlackRequest $request, Slack $slack)
    {
        $slack->delete();
        return back();
    }
}
