<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class TaskController extends Controller
{
    public function index(): View
    {
        $tasks = auth()->user()?->tasks ?? [];

        return view('index', compact('tasks'));
    }

    public function create(): View
    {

        return view('tasks.create');
    }

    public function edit(Task $task): View
    {
        $this->authorize('update', $task);

        return view('tasks.edit', compact('task'));
    }

    public function store(CreateTaskRequest $request): RedirectResponse
    {
        Task::query()->create(
            array_merge(
                $request->validated(),
                ['user_id' => auth()->user()->id]
            )
        );

        return redirect()->to(route('tasks.home'))->with('success', 'Task created successfully');
    }

    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $this->authorize('update', $task);

        $task->update($request->validated());

        return redirect()->to(route('tasks.home'))->with('success', 'Task updated successfully');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);

        $task->delete();

        return redirect()->to(route('tasks.home'))->with('success', 'Task deleted successfully');
    }

    public function complete(Task $task): RedirectResponse
    {
        $this->authorize('update', $task);

        $task->complete = !$task->complete;
        $task->save();

        return redirect()->to(route('tasks.home'))->with('success', 'Task updated successfully');
    }

    public function assignTags(Task $task): View
    {
        $tags = auth()->user()?->tags ?? [];
        $selectedTags = $task?->tags ?? [];

        return view('tasks.assign-tags', compact('task', 'tags', 'selectedTags'));
    }
}
