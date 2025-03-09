<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTagReqeust;
use App\Http\Requests\UpdateTagsRequest;
use App\Models\Tag;
use App\Models\Task;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function create(): View
    {
        $tags = auth()->user()?->tags ?? [];
        return view('tags.create', compact('tags'));
    }

    public function edit(Tag $tag): View
    {
        $this->authorize('update', $tag);
        return view('tags.edit', compact('tag'));
    }

    public function store(CreateTagReqeust $request): RedirectResponse
    {
        Tag::query()->create(
            array_merge(
                $request->validated(),
                ['user_id' => auth()->user()->id]
            )
        );

        return redirect()->to(route('tags.create'))->with('success', 'Tag created successfully');
    }

    public function update( UpdateTagsRequest $request, Tag $tag): RedirectResponse
    {
        $this->authorize('update', $tag);

        $tag->update($request->validated());

        return redirect()->to(route('tags.create'))->with('success', 'Tag updated successfully');
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        $this->authorize('delete', $tag);

        $tag->delete();

        return redirect()->to(route('tags.create'))->with('success', 'Tag deleted successfully');
    }
}
