<div class="space-y-6">
    <div class="pb-4 border-gray-200 border-b">
        <h2 class="font-light text-2xl">@lang('Account Details')</h2>
        <p class="mt-1 text-gray-500 text-sm">@lang("Your personal information.")</p>
    </div>
    <dl class="space-y-4 text-sm">
        <div class="flex sm:flex-row flex-col sm:justify-between gap-1 py-3 border-gray-100 border-b">
            <dt class="text-gray-500">@lang('Full Name')</dt>
            <dd class="font-medium text-gray-900">{{ $this->currentUser?->full_name ?? '—' }}</dd>
        </div>
        <div class="flex sm:flex-row flex-col sm:justify-between gap-1 py-3 border-gray-100 border-b">
            <dt class="text-gray-500">@lang('Email')</dt>
            <dd class="font-medium text-gray-900">{{ $this->currentUser?->email }}</dd>
        </div>
        <div class="flex sm:flex-row flex-col sm:justify-between gap-1 py-3 border-gray-100 border-b">
            <dt class="text-gray-500">@lang('Phone Number')</dt>
            <dd class="font-medium text-gray-900">{{ $this->currentUser?->phone ?: '—' }}</dd>
        </div>
        <div class="flex sm:flex-row flex-col sm:justify-between gap-1 py-3 border-gray-100 border-b">
            <dt class="text-gray-500">@lang('Gender')</dt>
            <dd class="font-medium text-gray-900">{{ $this->currentUser?->gender ?: '—' }}</dd>
        </div>
        <div class="flex flex-col gap-1 py-3 border-gray-100 border-b">
            <dt class="text-gray-500">@lang('Primary shipping address')</dt>
            <dd class="font-medium text-gray-900 leading-relaxed">{{ $this->primaryAddress?->full_address ?? 'No saved address.' }}</dd>
        </div>
    </dl>
</div>
