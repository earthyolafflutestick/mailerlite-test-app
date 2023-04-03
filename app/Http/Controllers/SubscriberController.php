<?php

namespace App\Http\Controllers;

use App\MailerLite\Error;
use App\Services\MailerLiteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Input\Input;

class SubscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, MailerLiteService $mailerLiteService)
    {
        $request->mergeIfMissing([
            'per_page' => 10,
            'cursor' => ''
        ]);

        $validated = $request->validate([
            'per_page' => 'required|in:10,25,50',
            'cursor' => 'present',
        ]);

        $result = $mailerLiteService->getSubscribers($validated['per_page'], $validated['cursor']);
        $data = [
            'input' => $validated,
        ];

        if ($result instanceof Error) {
            $data['resultError'] = $result->message;
        } else {
            $data['result'] = $result;
        }

        return view('subscribers.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('subscribers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, MailerLiteService $mailerLiteService)
    {
        $result = $mailerLiteService->createSubscriber(
            $request->input('email'),
            $request->input('name'),
            $request->input('country')
        );

        if ($result instanceof Error) {
            return redirect()
                ->route('subscribers.create')
                ->withInput()
                ->withErrors([
                    'result' => $result->message,
                ]);
        }

        session()->flash('success', $result->message);

        return redirect()->route('subscribers.index');
    }

    /**
     * Search the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request, MailerLiteService $mailerLiteService)
    {
        $result = $mailerLiteService->searchSubscriber($request->input('email'));

        if ($result instanceof Error) {
            return redirect()
                ->route('subscribers.index')
                ->withInput()
                ->withErrors([
                    'result' => $result->message,
                ]);
        }

        $data['result'] = $result;

        return view('subscribers.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, MailerLiteService $mailerLiteService, $id)
    {
        $result = $mailerLiteService->searchSubscriber($id);

        if ($result instanceof Error) {
            return redirect()
                ->route('subscribers.index')
                ->withInput()
                ->withErrors([
                    'result' => $result->message,
                ]);
        }

        $data['result'] = $result;

        return view('subscribers.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MailerLiteService $mailerLiteService, $id)
    {
        $result = $mailerLiteService->updateSubscriber($id, $request->input('name'), $request->input('country'));

        if ($result instanceof Error) {
            return redirect()
                ->route('subscribers.edit')
                ->withInput()
                ->withErrors([
                    'result' => $result->message,
                ]);
        }

        session()->flash('success', $result->message);

        return redirect()->route('subscribers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, MailerLiteService $mailerLiteService, $id)
    {
        $result = $mailerLiteService->deleteSubscriber($id);

        if ($result instanceof Error) {
            return redirect()
                ->route('subscribers.index')
                ->withErrors([
                    'result' => $result->message,
                ]);
        }

        session()->flash('success', $result->message);

        return redirect()->route('subscribers.index');
    }
}
