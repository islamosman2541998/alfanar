<div>
    <x-admin.page-header :title="__('Welcome, :name', ['name' => auth()->user()->name])" :subtitle="__('Overview of your website content and messages.')">
        <a href="{{ route('admin.projects.create') }}" class="btn btn-brand"><i class="bi bi-plus-lg"></i> {{ __('Add project') }}</a>
    </x-admin.page-header>

    <div class="row g-3 mb-3">
        @foreach ($tiles as $tile)
            <div class="col-6 col-xl-3">
                <a href="{{ route($tile['route']) }}" class="card stat-tile h-100">
                    <div>
                        <div class="value">{{ number_format($tile['value']) }}</div>
                        <div class="label">{{ $tile['label'] }}</div>
                    </div>
                    <i class="bi {{ $tile['icon'] }}"></i>
                </a>
            </div>
        @endforeach
    </div>

    <div class="row g-3">
        <div class="col-xl-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    {{ __('Latest messages') }}
                    <a href="{{ route('admin.messages') }}" class="small fw-normal text-muted">{{ __('View all') }}</a>
                </div>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Phone') }}</th>
                                <th class="d-none d-md-table-cell">{{ __('Service') }}</th>
                                <th>{{ __('Date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($messages as $message)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.messages', ['open' => $message->id]) }}" class="fw-semibold">{{ $message->name }}</a>
                                        @unless ($message->is_read) <span class="badge badge-soft badge-new ms-1">{{ __('New') }}</span> @endunless
                                    </td>
                                    <td dir="ltr" class="text-nowrap">{{ $message->phone }}</td>
                                    <td class="d-none d-md-table-cell">{{ $message->service?->tr('title') ?? '-' }}</td>
                                    <td class="text-nowrap small text-muted">{{ $message->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <x-admin.empty :colspan="4" :text="__('No messages yet')" />
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card mb-3">
                <div class="card-header">{{ __('Content') }}</div>
                <div class="list-group list-group-flush">
                    @foreach ($counts as $count)
                        <a href="{{ route($count['route']) }}" class="list-group-item d-flex justify-content-between align-items-center py-3">
                            {{ $count['label'] }} <span class="badge badge-soft">{{ $count['value'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="card">
                <div class="card-header">{{ __('Latest projects') }}</div>
                <div class="list-group list-group-flush">
                    @forelse ($projects as $project)
                        <a href="{{ route('admin.projects.edit', $project) }}" class="list-group-item d-flex gap-3 align-items-center py-2">
                            @if ($thumb = $project->coverThumbUrl())
                                <img src="{{ $thumb }}" alt="" width="52" height="40" class="rounded object-fit-cover">
                            @endif
                            <div class="small">
                                <div class="fw-semibold">{{ $project->tr('title') }}</div>
                                <div class="text-muted">{{ $project->service?->tr('title') }}</div>
                            </div>
                        </a>
                    @empty
                        <div class="list-group-item text-muted small">{{ __('No records found') }}</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
