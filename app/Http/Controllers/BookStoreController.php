<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookStoreRequest;
use App\Models\Book;

class BookStoreController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function __invoke(BookStoreRequest $request)
    {
        $book = Book::create($request->only('title', 'author'));

        $request->user()->books()->attach($book, [
            'status' => $request->status
        ]);

        return redirect('/');
    }
}
