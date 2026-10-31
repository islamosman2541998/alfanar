{{-- Photos and videos grid opened in a lightbox at full quality --}}
@if ($items->isNotEmpty())
    <div class="gallery" data-reveal>
        @foreach ($items as $item)
            <a href="{{ $item->url() }}" class="gallery-item glightbox" data-gallery="{{ $group }}"
               @if ($item->isVideo()) data-type="video" @else data-type="image" @endif
               aria-label="{{ $label ?? '' }} {{ $loop->iteration }}">
                <x-site.media :media="$item" thumb video="poster" :alt="($label ?? '').' '.$loop->iteration" />
                @if ($item->isVideo())
                    <span class="play-icon"><x-icon name="play-fill" :size="22" /></span>
                @endif
            </a>
        @endforeach
    </div>
@endif
