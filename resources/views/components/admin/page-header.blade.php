@props(['title', 'subtitle' => null])

<div class="page-head">
    <div>
        <h1>{{ $title }}</h1>
        @if ($subtitle)
            <p>{{ $subtitle }}</p>
        @endif
    </div>
    @if ($slot->isNotEmpty())
        <div class="d-flex gap-2 flex-wrap">{{ $slot }}</div>
    @endif
</div>
