<div>
    <x-admin.page-header :title="__('Site texts')" :subtitle="__('All titles, labels and buttons of the website. Changes are saved automatically when you leave the field.')" />

    <div class="card">
        <x-admin.toolbar :status="false" :placeholder="__('Search text or key...')">
            <select class="form-select" wire:model.live="group">
                <option value="">{{ __('All sections') }}</option>
                @foreach ($groups as $item)
                    <option value="{{ $item }}">{{ __(\Illuminate\Support\Str::headline($item)) }}</option>
                @endforeach
            </select>
        </x-admin.toolbar>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <x-admin.th field="key" :$sortField :$sortDirection style="width: 22%">{{ __('Key') }}</x-admin.th>
                        <th>{{ __('Arabic') }}</th>
                        <th>{{ __('English') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $row)
                        @php $long = mb_strlen($values[$row->id]['ar'] ?? '') > 70 || mb_strlen($values[$row->id]['en'] ?? '') > 70; @endphp
                        <tr wire:key="text-{{ $row->id }}">
                            <td><code class="small text-muted" dir="ltr">{{ $row->key }}</code></td>
                            @foreach (['ar', 'en'] as $locale)
                                <td style="min-width: 220px">
                                    @if ($long)
                                        <textarea rows="3" dir="{{ $locale === 'ar' ? 'rtl' : 'ltr' }}" class="form-control form-control-sm" wire:model.blur="values.{{ $row->id }}.{{ $locale }}"></textarea>
                                    @else
                                        <input type="text" dir="{{ $locale === 'ar' ? 'rtl' : 'ltr' }}" class="form-control form-control-sm" wire:model.blur="values.{{ $row->id }}.{{ $locale }}">
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <x-admin.empty :colspan="3" />
                    @endforelse
                </tbody>
            </table>
        </div>
        <x-admin.table-foot :rows="$rows" />
    </div>
</div>
