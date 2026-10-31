@props(['testimonial'])

<figure class="testimonial-card">
    <div class="stars" aria-label="{{ $testimonial->rating }}/5">
        @for ($i = 1; $i <= 5; $i++)
            <x-icon name="star-fill" :size="13" :class="$i > $testimonial->rating ? 'is-off' : ''" />
        @endfor
    </div>
    <blockquote>{{ $testimonial->tr('content') }}</blockquote>
    <figcaption>
        @if ($testimonial->photo)
            <img src="{{ media_url($testimonial->photo) }}" alt="" width="44" height="44" loading="lazy">
        @else
            <span class="avatar">{{ mb_substr($testimonial->tr('name'), 0, 1) }}</span>
        @endif
        <span>
            <strong>{{ $testimonial->tr('name') }}</strong>
            @if ($position = $testimonial->tr('position'))
                <small>{{ $position }}</small>
            @endif
        </span>
    </figcaption>
</figure>
