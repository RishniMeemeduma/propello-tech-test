@props([
    'type' => 'success',
    'message' => null,
    ])

@php 
    $classes = match($type) {
        'success' => 'bg-green-100 border-green-400 text-green-700',
        'error' => 'bg-red-100 border-red-400 text-red-700',
        default => 'bg-gray-100 border-gray-400 text-gray-700'
    };

@endphp
<div class="{{ $classes }} p-2 rounded relative border" role="alert">
    <span class="block sm:inline">{{ $message }}</span>
</div>