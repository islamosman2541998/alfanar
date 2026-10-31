<form class="contact-form" wire:submit="submit" novalidate>
    <div class="row g-3">
        <div class="col-sm-6">
            <label class="field-label" for="cf-name">{{ txt('contact.form.name') }} <span>*</span></label>
            <input type="text" id="cf-name" autocomplete="name" class="field @error('name') is-invalid @enderror" wire:model="name">
            @error('name') <div class="field-error">{{ $message }}</div> @enderror
        </div>
        <div class="col-sm-6">
            <label class="field-label" for="cf-phone">{{ txt('contact.form.phone') }} <span>*</span></label>
            <input type="tel" id="cf-phone" dir="ltr" autocomplete="tel" inputmode="tel" class="field @error('phone') is-invalid @enderror" wire:model="phone">
            @error('phone') <div class="field-error">{{ $message }}</div> @enderror
        </div>
        <div class="col-sm-6">
            <label class="field-label" for="cf-email">{{ txt('contact.form.email') }}</label>
            <input type="email" id="cf-email" dir="ltr" autocomplete="email" class="field @error('email') is-invalid @enderror" wire:model="email">
            @error('email') <div class="field-error">{{ $message }}</div> @enderror
        </div>
        <div class="col-sm-6">
            <label class="field-label" for="cf-service">{{ txt('contact.form.service') }}</label>
            <select id="cf-service" class="field @error('service_id') is-invalid @enderror" wire:model="service_id">
                <option value="">{{ txt('contact.form.service_placeholder') }}</option>
                @foreach ($services as $service)
                    <option value="{{ $service->id }}">{{ $service->tr('title') }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12">
            <label class="field-label" for="cf-message">{{ txt('contact.form.message') }} <span>*</span></label>
            <textarea id="cf-message" rows="4" class="field @error('message') is-invalid @enderror" wire:model="message"></textarea>
            @error('message') <div class="field-error">{{ $message }}</div> @enderror
        </div>
        <div class="d-none" aria-hidden="true">
            <input type="text" tabindex="-1" autocomplete="off" wire:model="website">
        </div>
        <div class="col-12">
            <button type="submit" class="btn-site" wire:loading.attr="disabled" wire:target="submit">
                <span wire:loading.remove wire:target="submit">{{ txt('contact.form.submit') }}</span>
                <span wire:loading wire:target="submit"><span class="spinner"></span> {{ txt('contact.form.sending') }}</span>
            </button>
        </div>
    </div>
</form>
