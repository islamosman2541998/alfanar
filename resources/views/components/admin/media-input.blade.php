@props(['model' => 'coverFile', 'current' => null, 'file' => null, 'remove' => 'removeCover', 'accept' => 'image/*,video/mp4,video/webm', 'hint' => null])

{{-- Single image / video picker with preview, upload progress and remove --}}
@php
    $isVideo = $file
        ? str_starts_with((string) $file->getMimeType(), 'video')
        : (($current['type'] ?? null) === 'video');
    $previewUrl = $file
        ? (method_exists($file, 'isPreviewable') && $file->isPreviewable() ? $file->temporaryUrl() : null)
        : media_url($current['path'] ?? null);
    $poster = ! $file ? media_url($current['poster'] ?? null) : null;
@endphp

<div x-data="{ progress: 0, uploading: false }"
     x-on:livewire-upload-start="uploading = true"
     x-on:livewire-upload-finish="uploading = false; progress = 0"
     x-on:livewire-upload-error="uploading = false; progress = 0"
     x-on:livewire-upload-progress="progress = $event.detail.progress">

    <div @class(['media-box', 'has-media' => $previewUrl])>
        @if ($previewUrl)
            @if ($isVideo)
                <video src="{{ $previewUrl }}" @if ($poster) poster="{{ $poster }}" @endif controls preload="metadata" playsinline></video>
            @else
                <img src="{{ $previewUrl }}" alt="">
            @endif
            <button type="button" class="btn btn-light btn-icon media-remove text-danger" wire:click="{{ $remove }}" title="{{ __('Remove') }}">
                <i class="bi bi-trash"></i>
            </button>
        @elseif ($file)
            <div class="py-4 small text-muted"><i class="bi bi-file-earmark-play fs-3 d-block"></i>{{ $file->getClientOriginalName() }}</div>
        @else
            <label>
                <i class="bi bi-cloud-arrow-up"></i>
                {{ __('Click to upload an image or video') }}
                <input type="file" class="d-none" wire:model="{{ $model }}" accept="{{ $accept }}">
            </label>
        @endif
    </div>

    @if ($previewUrl)
        <label class="btn btn-light btn-sm w-100 mt-2">
            <i class="bi bi-arrow-repeat"></i> {{ __('Replace') }}
            <input type="file" class="d-none" wire:model="{{ $model }}" accept="{{ $accept }}">
        </label>
    @endif

    <div class="upload-progress" x-show="uploading" x-cloak><span :style="`width:${progress}%`"></span></div>
    @if ($hint)<div class="form-text mt-2">{{ $hint }}</div>@endif
    @error($model) <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
</div>
