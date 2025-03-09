@extends('layouts.app')

@section('content')
<div>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
        <div class="p-6 text-gray-900">
            <form method="POST" action="{{ route('tags.store') }}">
                @csrf
                <div class="pb-4">
                    <x-forms.input-label for="name" :value="__('Tag Name')" />
                    <x-forms.text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                    <x-forms.input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <x-elements.primary-button>
                    Create
                </x-elements.primary-button>
            </form>
        </div>
        <div class="p-6 text-gray-900">
            @if($tags->isNotEmpty())
                <div class="w-full flex pb-2 border-b border-gray-200">
                    <div class="w-5/12 font-semibold">Name</div>
                    <div class="w-2/12 font-semibold">Created At</div>
                    <div class="w-5/12 font-semibold">Actions</div>
                </div>
            @endif

            @foreach($tags as $tag)
                <x-partials.tags-row :tag="$tag" />
            @endforeach
        </div>
    </div>
</div>
@endsection