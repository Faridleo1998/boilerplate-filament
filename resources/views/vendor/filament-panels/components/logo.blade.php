@php
    $logoPath = asset('storage/images/logo.webp');
@endphp

@if (file_exists(public_path('storage/images/logo.webp')))
    <img src="{{ $logoPath }}" class="h-16" alt="Logo">
@else
    <span class="font-bold text-lg">
        {{ \Illuminate\Support\Str::limit(config('app.name'), 25, '...') }}
    </span>
@endif
