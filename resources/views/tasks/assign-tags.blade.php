@extends('layouts.app')

@section('content')
<div>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    @if (session('success') || session('error'))
        <x-elements.message :type="session('success') ? 'success' : 'error'"
         :message="session('success') ?? session('error')"></x-elements.message>
    @endif
        <div class="p-6 text-gray-900">
            <form method="POST" action="{{ route('task-tags.store') }}">
                @csrf
                <div class="pb-4">
                    <input type="hidden" name="task_id" value="{{ $task->id }}" />
                    <x-forms.input-label for="name" :value="__('Assign Tags')" />
                    <x-forms.multiselect-input
                        name="tags[]"
                        :options="$tags->pluck('name', 'id')->toArray()"
                        :selected="$selectedTags->pluck('id')->toArray()"
                        class="w-full"
                    />      
                    <x-forms.input-error :messages="$errors->get('name')" class="mt-2" />
        
                    <x-elements.primary-button>
                        Assign
                    </x-elements.primary-button>
                </div>
            </form>
        </div>
        <div class="p-6 text-gray-900">
            @if($selectedTags->isNotEmpty())
                <div class="w-full flex pb-2 border-b border-gray-200">
                    <div class="w-5/12 font-semibold">Name</div>
                    <div class="w-5/12 font-semibold">Actions</div>
                </div>
            @endif

            @foreach($selectedTags as $tag)
                <x-partials.tags-row :tag="$tag" :task_tag="true" :task="$task->id"/>
            @endforeach
        </div>
    </div>
</div>
@endsection