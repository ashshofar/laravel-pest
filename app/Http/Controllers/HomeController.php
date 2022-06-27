<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('home', [
            'bookByStatus' => $request->user()?->books->groupBy('pivot.status')
        ]);
    }
}
