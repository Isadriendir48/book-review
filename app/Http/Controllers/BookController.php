<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $title = $request->string('title');
        $filter = $request->input('filter', '');

        $query = Book::when(
            $title, fn ($query, $title) => $query->title($title)
        );

        $query = match ($filter) {
            'popular_last_month' => $query->popularLastMonth(),
            'popular_last_six_months' => $query->popularLastSixMonths(),
            'highest_rated_last_month' => $query->highestRatedLastMonth(),
            'highest_rated_last_six_months' => $query->highestRatedLastSixMonths(),
            default => $query->latest()
        };

        $books = $query->get();

        return view('books.index', ['books' => $books]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book): View
    {
        return view(
            'books.show',
            [
                'book' => $book->load([
                    'reviews' => fn ($query) => $query->latest()
                ])
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
