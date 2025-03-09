<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskTag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssignTagsToTaskController extends Controller
{
    public function store(Request $request) : RedirectResponse
    {
        $task = Task::query()->findOrFail($request->task_id);

        $taskTags = TaskTag::query()->where('task_id',$request->task_id)->whereIn('tag_id', $request->tags)->exists();
        
        if ($taskTags) {
            return redirect()->to(route('tasks.assignTags', $task))->with('error', 'Tag already assigned');
        }

        
        if ($request->tags) {
            foreach ($request->tags as $tag) {
                $task->tags()->attach($tag);
            }
        }

        return redirect()->route('tasks.assignTags',['task' => $task])->with('success', 'Tags assigned successfully');
    }

    public function destroy(Task $task, $tag) : RedirectResponse
    {
        $task->tags()->detach($tag);

        return redirect()->to(route('tasks.assignTags', $task))->with('success', 'Tag detached successfully');
    }
}
