<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

/**
 * 
 *
 * @property int $id
 * @property string $review
 * @property int $rating
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $book_id
 * @property-read \App\Models\Book $book
 * @method static \Database\Factories\ReviewFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereBookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereReview($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'rating',
        'review',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    protected static function booted(): void
    {
        // This method will not be called in these cases:
        // - The model is being modified directly in the database.
        // - The data is being updated via the `update` or `updateOrCreate` methods on the model class.
        //      e.g., Review::where('id', 1)->update(['rating' => 5]);
        // - The data is updated using raw SQL queries.
        // - The data is being updated inside a transaction if the transaction is rolled back.
        // This is because the model is not being retrieved from the database.
        static::updated(fn (Review $review) => Cache::forget("book:$review->book_id"));

        static::deleted(fn (Review $review) => Cache::forget("book:$review->book_id"));
    }
}
