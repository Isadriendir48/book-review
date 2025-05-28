<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 * 
 *
 * @property int $id
 * @property string $title
 * @property string $author
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Review> $reviews
 * @property-read int|null $reviews_count
 * @method static \Database\Factories\BookFactory factory($count = null, $state = [])
 * @method static Builder<static>|Book highestRated(?string $from = null, ?string $to = null)
 * @method static Builder<static>|Book highestRatedLastMonth()
 * @method static Builder<static>|Book highestRatedLastSixMonths()
 * @method static Builder<static>|Book minimumReviews(int $count)
 * @method static Builder<static>|Book newModelQuery()
 * @method static Builder<static>|Book newQuery()
 * @method static Builder<static>|Book popular(?string $from = null, ?string $to = null)
 * @method static Builder<static>|Book popularLastMonth()
 * @method static Builder<static>|Book popularLastSixMonths()
 * @method static Builder<static>|Book query()
 * @method static Builder<static>|Book title(string $title)
 * @method static Builder<static>|Book whereAuthor($value)
 * @method static Builder<static>|Book whereCreatedAt($value)
 * @method static Builder<static>|Book whereId($value)
 * @method static Builder<static>|Book whereTitle($value)
 * @method static Builder<static>|Book whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Book extends Model
{
    use HasFactory;

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function scopeTitle(Builder $query, string $title): Builder
    {
        return $query->where('title', 'like', '%' . $title . '%');
    }

    public function scopePopular(Builder $query, ?string $from = null, ?string $to = null): Builder
    {
        return $query->withCount([
            'reviews' => fn (Builder $q) => $this->dateRangeFilter($q, $from, $to)
        ])
            ->orderBy('reviews_count', 'desc');
    }

    public function scopeHighestRated(Builder $query, ?string $from = null, ?string $to = null): Builder
    {
        return $query->withAvg([
            'reviews' => fn (Builder $q) => $this->dateRangeFilter($q, $from, $to)
        ], 'rating')
            ->orderBy('reviews_avg_rating', 'desc');
    }

    public function scopeMinimumReviews(Builder $query, int $count): Builder
    {
        return $query->having('reviews_count', '>=', $count);
    }

    public function scopePopularLastMonth(Builder $query): Builder
    {
        return $query
            ->popular(now()->subMonth(), now())
            ->highestRated(now()->subMonth(), now())
            ->minimumReviews(2);
    }

    public function scopePopularLastSixMonths(Builder $query): Builder
    {
        return $query
            ->popular(now()->subMonths(6), now())
            ->highestRated(now()->subMonths(6), now())
            ->minimumReviews(5);
    }

    public function scopeHighestRatedLastMonth(Builder $query): Builder
    {
        return $query
            ->highestRated(now()->subMonth(), now())
            ->popular(now()->subMonth(), now())
            ->minimumReviews(2);
    }

    public function scopeHighestRatedLastSixMonths(Builder $query): Builder
    {
        return $query
            ->highestRated(now()->subMonths(6), now())
            ->popular(now()->subMonths(6), now())
            ->minimumReviews(5);
    }

    private function dateRangeFilter(Builder $query, ?string $from = null, ?string $to = null): void
    {
        if (!empty($from) && !empty($to)) {
            $query->whereBetween('created_at', [$from, $to]);
        } elseif (!empty($from)) {
            $query->where('created_at', '>=', $from);
        } elseif (!empty($to)) {
            $query->where('created_at', '<=', $to);
        }
    }
}
