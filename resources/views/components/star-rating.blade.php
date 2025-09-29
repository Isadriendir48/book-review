@if($rating)
    @for($i = 1; $i <= 5; $i++)
        {{ $i <= round($rating, precision: 4, mode: PHP_ROUND_HALF_UP) ? '★' : '☆' }}
    @endfor
@else
    No rating yet
@endif
