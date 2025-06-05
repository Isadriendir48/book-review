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

        //$books = $query->get();

        // Cache can be implemented by using the following facade:
        //$books = \Illuminate\Support\Facades\Cache::remember('books', 3600, fn () => $query->get());
        // We can use the helper function as well:
        //$books = cache()->remember('books', 3600, fn () => $query->get());

        // We have to come up with a caching strategy that is unique for each request to prevent exposing
        // unwanted data to users. For instance, this request receives a `title` parameter and a `filter`
        // parameter. We can use the `title` and `filter` parameters to create a unique cache key to avoid
        // returning the same data for different requests.
        $cacheKey = "books:$filter:$title";
        $books = cache()->remember($cacheKey, 3600, fn () => $query->get());

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
