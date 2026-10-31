<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\Concerns\WithToast;
use App\Services\MediaService;
use App\Support\Settings as SettingsStore;
use App\Support\SettingsSchema;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

/** Settings screen generated from SettingsSchema (one tab saved at a time). */
#[Layout('layouts.admin')]
class Settings extends Component
{
    use WithFileUploads, WithToast;

    public string $tab = 'general';

    public array $data = [];

    public array $uploads = [];

    /** Images kept in their original format (transparency, SVG, social previews). */
    protected const ORIGINAL_IMAGES = ['logo', 'favicon', 'og_image'];

    public function mount(?string $tab = null): void
    {
        $this->tab = array_key_exists($tab ?? '', SettingsSchema::tabs()) ? $tab : 'general';

        foreach (SettingsSchema::fields() as $field) {
            $value = SettingsStore::raw($field['key']);

            $this->data[$field['key']] = match (true) {
                ! empty($field['tr']) => array_merge(['ar' => '', 'en' => ''], is_array($value) ? $value : []),
                $field['type'] === 'toggle' => (bool) $value,
                default => (string) ($value ?? ''),
            };
        }
    }

    protected function rules(): array
    {
        $rules = [];

        foreach (SettingsSchema::fields($this->tab) as $field) {
            $key = 'data.'.$field['key'];

            match ($field['type']) {
                'image' => $rules['uploads.'.$field['key']] = ['nullable', 'image', 'max:10240'],
                'email' => $rules[$key] = ['nullable', 'email', 'max:190'],
                'url' => $rules[$key] = ['nullable', 'url', 'max:2000'],
                'number' => $rules[$key] = ['nullable', 'numeric', 'min:0', 'max:120'],
                'color' => $rules[$key] = ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
                'toggle' => $rules[$key] = ['boolean'],
                'select' => $rules[$key] = ['nullable', 'in:'.implode(',', array_keys($field['options']))],
                'code' => $rules[$key] = ['nullable', 'string', 'max:20000'],
                default => $rules[! empty($field['tr']) ? $key.'.*' : $key] = ['nullable', 'string', 'max:5000'],
            };
        }

        return $rules;
    }

    public function updatedUploads($value, string $key): void
    {
        $this->validateOnly('uploads.'.$key, $this->rules());
    }

    public function removeImage(string $key): void
    {
        unset($this->uploads[$key]);
        $this->data[$key] = '';
    }

    public function save(): void
    {
        $this->validateWithToast($this->rules());

        $media = app(MediaService::class);
        $values = [];

        foreach (SettingsSchema::fields($this->tab) as $field) {
            $key = $field['key'];
            $value = $this->data[$key];

            if ($field['type'] === 'image') {
                $previous = SettingsStore::raw($key);

                if (! empty($this->uploads[$key])) {
                    $value = in_array($key, self::ORIGINAL_IMAGES, true)
                        ? $media->storeOriginal($this->uploads[$key], 'settings')
                        : $media->store($this->uploads[$key], 'settings')['path'];
                }

                if ($previous && $previous !== $value) {
                    $media->delete(['path' => $previous, 'thumb' => str_replace('.webp', '-thumb.webp', $previous)]);
                }

                $this->data[$key] = $value;
            }

            $values[$key] = match ($field['type']) {
                'toggle' => $value ? '1' : '0',
                default => $value,
            };
        }

        SettingsStore::set($values);
        $this->uploads = [];

        $this->toast(__('Settings saved'));
    }

    public function render()
    {
        $tabs = SettingsSchema::tabs();

        return view('livewire.admin.settings', [
            'tabs' => $tabs,
            'fields' => $tabs[$this->tab]['fields'],
        ])->title(__('Settings').' - '.__($tabs[$this->tab]['label']));
    }
}
