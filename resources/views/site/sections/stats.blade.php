@if ($stats->isNotEmpty())
    <section class="stats-band">
        <div class="container">
            <div class="stats-grid" data-reveal>
                @foreach ($stats as $stat)
                    <div class="stat">
                        <strong dir="ltr"><span data-count="{{ $stat->value }}">0</span>{{ $stat->suffix }}</strong>
                        <span>{{ $stat->tr('label') }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
