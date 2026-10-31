@php
    $primaryFont = setting('primary_font', 'Cairo') === 'Tajawal' ? 'Tajawal' : 'Cairo';
    $headingFont = $primaryFont === 'Cairo' ? 'Tajawal' : 'Cairo';
@endphp
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&family=Tajawal:wght@400;500;700&display=swap">
@if ($favicon = setting('favicon'))
    <link rel="icon" href="{{ media_url($favicon) }}">
    <link rel="apple-touch-icon" href="{{ media_url($favicon) }}">
@endif
<style>
    :root {
        --brand: {{ setting('color_primary', '#e3a01b') }};
        --dark: {{ setting('color_dark', '#1f1e1c') }};
        --font-body: '{{ $primaryFont }}', system-ui, sans-serif;
        --font-head: '{{ $headingFont }}', system-ui, sans-serif;
    }
</style>
