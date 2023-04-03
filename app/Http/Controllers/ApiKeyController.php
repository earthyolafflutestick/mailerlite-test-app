<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiKeyController extends Controller
{
    public function create() {
        return view('apikeys.create');
    }
}
