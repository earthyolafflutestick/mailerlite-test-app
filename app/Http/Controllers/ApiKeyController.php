<?php

namespace App\Http\Controllers;

use App\MailerLite\Error;
use App\Services\ApiKeyService;
use App\Services\MailerLiteService;
use Illuminate\Http\Request;

class ApiKeyController extends Controller
{
    public function create()
    {
        return view('apikeys.create');
    }

    public function store(Request $request, MailerLiteService $mailerLiteService)
    {
        $validated = $request->validate([
            'api_key' => [
                'required',
                function ($attribute, $value, $fail) use ($mailerLiteService) {
                    $mailerLiteService->setApiKey($value);
                    $response = $mailerLiteService->getSubscribers();

                    if ($response instanceof Error) {
                        $fail($response->message);
                    }
                }
            ],
        ]);

        ApiKeyService::set($validated['api_key']);

        return redirect()->route('subscribers.index');
    }
}
