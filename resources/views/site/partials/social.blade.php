@php
    $networks = ['facebook' => 'facebook', 'instagram' => 'instagram', 'tiktok' => 'tiktok', 'youtube' => 'youtube', 'x' => 'twitter-x', 'linkedin' => 'linkedin'];
@endphp
<div class="social-links">
    @foreach ($networks as $key => $icon)
        @if ($link = setting($key))
            <a href="{{ $link }}" target="_blank" rel="noopener" aria-label="{{ ucfirst($key) }}"><x-icon :name="$icon" :size="16" /></a>
        @endif
    @endforeach
</div>
