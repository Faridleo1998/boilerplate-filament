@php
    $logoPath = asset('storage/images/logo.webp');
@endphp

@if (file_exists(public_path('storage/images/logo.webp')))
    <img src="{{ $logoPath }}" style="max-height: 50px !important; max-width: 200px !important;" alt="Logo">
@else
    <span class="font-bold text-2xl">
        {{ \Illuminate\Support\Str::limit(config('app.name'), 25, '...') }}
    </span>
@endif
