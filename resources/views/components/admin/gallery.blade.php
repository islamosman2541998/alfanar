@props(['items' => [], 'uploads' => []])

{{-- Gallery manager: saved items (drag to reorder / remove) + pending uploads --}}
<div x-data="{ progress: 0, uploading: false }"
     x-on:livewire-upload-start="uploading = true"
     x-on:livewire-upload-finish="uploading = false; progress = 0"
     x-on:livewire-upload-error="uploading = false; progress = 0"
     x-on:livewire-upload-progress="progress = $event.detail.progress">

    <div class="gallery-grid" wire:sort="sortGallery">
        @foreach ($items as $item)
            <div class="gallery-item" wire:key="gallery-{{ $item['id'] }}" wire:sort:item="{{ $item['id'] }}">
                @if ($item['type'] === 'video')
                    @if ($item['thumb'])
                        <img src="{{ $item['thumb'] }}" alt="" loading="lazy">
                    @else
                        <video src="{{ $item['url'] }}#t=0.5" preload="metadata" muted playsinline></video>
                    @endif
                    <span class="tag">{{ __('Video') }}</span>
                @else
                    <img src="{{ $item['thumb'] ?? $item['url'] }}" alt="" loading="lazy">
                @endif
                <span class="handle" wire:sort:handle title="{{ __('Drag to reorder') }}"><i class="bi bi-arrows-move"></i></span>
                <button type="button" class="remove" wire:sort:ignore wire:click="removeGalleryItem({{ $item['id'] }})" title="{{ __('Remove') }}"><i class="bi bi-x-lg"></i></button>
            </div>
        @endforeach
    </div>

    <div class="gallery-grid mt-2">
        @foreach ($uploads as $index => $upload)
            <div class="gallery-item pending" wire:key="upload-{{ $index }}-{{ $upload->getFilename() }}">
                @if (str_starts_with((string) $upload->getMimeType(), 'video'))
                    <video src="{{ $upload->temporaryUrl() }}#t=0.5" preload="metadata" muted playsinline></video>
                    <span class="tag">{{ __('Video') }}</span>
                @elseif ($upload->isPreviewable())
                    <img src="{{ $upload->temporaryUrl() }}" alt="">
                @endif
                <button type="button" class="remove" wire:click="removeGalleryUpload({{ $index }})"><i class="bi bi-x-lg"></i></button>
            </div>
        @endforeach

        <label class="gallery-add">
            <i class="bi bi-plus-lg"></i>
            {{ __('Add images / videos') }}
            <input type="file" class="d-none" multiple accept="image/*,video/mp4,video/webm" wire:model="galleryUploads">
        </label>
    </div>

    <div class="upload-progress" x-show="uploading" x-cloak><span :style="`width:${progress}%`"></span></div>
    @if (count($uploads))
        <div class="form-text mt-2">{{ __('New files will be saved when you click Save.') }}</div>
    @endif
    @error('galleryUploads.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
</div>
