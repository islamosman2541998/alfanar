@props(['id', 'light' => false, 'side' => false])

{{-- Prev / next buttons. "side" variant sits on both sides of the cards (phones). --}}
<div @class(['slider-nav', 'is-light' => $light, 'is-side' => $side])>
    <button type="button" class="slider-btn" data-prev="{{ $id }}" aria-label="{{ txt('general.previous') }}">
        <x-icon name="chevron-left" :size="16" class="flip-rtl" />
    </button>
    <button type="button" class="slider-btn" data-next="{{ $id }}" aria-label="{{ txt('general.next') }}">
        <x-icon name="chevron-right" :size="16" class="flip-rtl" />
    </button>
</div>
