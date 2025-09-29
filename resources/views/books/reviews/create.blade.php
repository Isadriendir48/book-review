@extends('layouts.app')

@section('content')
    <h1 class="mb-10 text-2xl">Add review for {{ $book->title }}</h1>

    <form method="POST" action="{{ route('books.reviews.store', $book) }}">
        @csrf
        <label for="review" class="block mb-2">Review</label>
        <textarea name="review" id="review" class="input mb-4" required></textarea>

        <label for="rating" class="block mb-2">Rating</label>
        <select name="rating" id="rating" class="input mb-4" required>
            <option value="">Select a rating</option>
            @for ($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}">{{ $i }} / 5</option>
            @endfor
        </select>

        <button type="submit" class="btn">Add review</button>
    </form>
@endsection
