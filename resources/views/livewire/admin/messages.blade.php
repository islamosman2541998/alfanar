<div>
    <x-admin.page-header :title="__('Messages')" :subtitle="__('Requests sent from the contact forms.')">
        <button type="button" class="btn btn-light" wire:click="markAllRead"><i class="bi bi-check2-all"></i> {{ __('Mark all as read') }}</button>
    </x-admin.page-header>

    <div class="card">
        <x-admin.toolbar :selected="$selected" :status="false" :placeholder="__('Search name, phone or message...')">
            <select class="form-select" wire:model.live="read">
                <option value="">{{ __('All messages') }}</option>
                <option value="0">{{ __('Unread') }}</option>
                <option value="1">{{ __('Read') }}</option>
            </select>
            <select class="form-select" wire:model.live="service">
                <option value="">{{ __('All services') }}</option>
                @foreach ($services as $item)
                    <option value="{{ $item->id }}">{{ $item->tr('title') }}</option>
                @endforeach
            </select>
            <input type="date" class="form-control w-auto" wire:model.live="from" title="{{ __('From') }}">
            <input type="date" class="form-control w-auto" wire:model.live="to" title="{{ __('To') }}">
        </x-admin.toolbar>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 36px"></th>
                        <x-admin.th field="name" :$sortField :$sortDirection>{{ __('Name') }}</x-admin.th>
                        <th>{{ __('Phone') }}</th>
                        <th class="d-none d-lg-table-cell">{{ __('Service') }}</th>
                        <th class="d-none d-md-table-cell">{{ __('Message') }}</th>
                        <x-admin.th field="created_at" :$sortField :$sortDirection>{{ __('Date') }}</x-admin.th>
                        <x-admin.th field="is_read" :$sortField :$sortDirection>{{ __('Read') }}</x-admin.th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $row)
                        <tr wire:key="row-{{ $row->id }}" @class(['fw-semibold' => ! $row->is_read])>
                            <td><input type="checkbox" class="form-check-input" value="{{ $row->id }}" wire:model.live="selected"></td>
                            <td>
                                {{ $row->name }}
                                @unless ($row->is_read) <span class="badge badge-soft badge-new ms-1">{{ __('New') }}</span> @endunless
                            </td>
                            <td dir="ltr" class="text-nowrap text-start"><a href="tel:{{ $row->phone }}">{{ $row->phone }}</a></td>
                            <td class="d-none d-lg-table-cell">{{ $row->service?->tr('title') ?? '-' }}</td>
                            <td class="d-none d-md-table-cell text-muted small fw-normal" style="max-width: 300px">{{ \Illuminate\Support\Str::limit($row->message, 70) }}</td>
                            <td class="text-nowrap small fw-normal">{{ $row->created_at->format('Y-m-d H:i') }}</td>
                            <td><x-admin.switch :id="$row->id" field="is_read" :checked="$row->is_read" /></td>
                            <td>
                                <x-admin.row-actions :id="$row->id">
                                    <button type="button" class="btn btn-light btn-icon" wire:click="show({{ $row->id }})" title="{{ __('View') }}"><i class="bi bi-eye"></i></button>
                                </x-admin.row-actions>
                            </td>
                        </tr>
                    @empty
                        <x-admin.empty :colspan="8" :text="__('No messages yet')" />
                    @endforelse
                </tbody>
            </table>
        </div>
        <x-admin.table-foot :rows="$rows" />
    </div>

    <x-admin.modal id="messageModal" :title="__('Message details')" submit="$refresh">
        @if ($viewing)
            <dl class="row msg-meta mb-2">
                <dt class="col-sm-3">{{ __('Name') }}</dt><dd class="col-sm-9">{{ $viewing->name }}</dd>
                <dt class="col-sm-3">{{ __('Phone') }}</dt><dd class="col-sm-9" dir="ltr"><a href="tel:{{ $viewing->phone }}">{{ $viewing->phone }}</a></dd>
                @if ($viewing->email)
                    <dt class="col-sm-3">{{ __('Email') }}</dt><dd class="col-sm-9"><a href="mailto:{{ $viewing->email }}">{{ $viewing->email }}</a></dd>
                @endif
                <dt class="col-sm-3">{{ __('Service') }}</dt><dd class="col-sm-9">{{ $viewing->service?->tr('title') ?? '-' }}</dd>
                <dt class="col-sm-3">{{ __('Date') }}</dt><dd class="col-sm-9">{{ $viewing->created_at->format('Y-m-d H:i') }}</dd>
            </dl>
            <div class="msg-body">{{ $viewing->message }}</div>
        @endif
        <x-slot:footer>
            @if ($viewing)
                @php $wa = preg_replace('/\D/', '', $viewing->phone); $wa = str_starts_with($wa, '0') ? '2'.$wa : $wa; @endphp
                <a href="https://wa.me/{{ $wa }}" target="_blank" class="btn btn-light"><i class="bi bi-whatsapp text-success"></i> {{ __('WhatsApp') }}</a>
                <a href="tel:{{ $viewing->phone }}" class="btn btn-brand"><i class="bi bi-telephone"></i> {{ __('Call') }}</a>
            @endif
        </x-slot:footer>
    </x-admin.modal>
</div>
