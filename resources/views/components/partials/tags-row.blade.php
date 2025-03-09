@props([
    'tag'  => null,
    'task_tag' => false,
    'task'  => null,
])

<div class="w-full flex py-2 border-b border-gray-100">
    <div class="w-5/12 flex items-center {{ $tag?->complete ? 'line-through' : '' }}">{{ $tag?->name }}</div>
    @if(!$task_tag)
        <div class="w-2/12 flex items-center">{{ $tag?->created_at->format('jS M Y') }}</div>
    @endif
    <div class="w-5/12 flex flex-wrap">
        @if(!$task_tag)
            <x-elements.link-button class="mr-2 my-1 w-[110px]" href="{{ route('tags.edit', ['tag' => $tag]) }}">
                Edit
            </x-elements.link-button>
        @endif
        <x-elements.link-button-danger class="mr-2 my-1 w-[110px]" href="{{ $task_tag ? route('task-tags.delete', ['task' => $task ,'tag' => $tag]) : route('tags.destroy', ['tag' => $tag]) }}">
            Delete
        </x-elements.link-button-danger>
    </div>
</div>
