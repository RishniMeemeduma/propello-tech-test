<?php

use App\Models\Tag;
use App\Models\Task;
use App\Models\User;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses()->group('task-tags');

test('assign multiple tags to task view', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create(['user_id' => $user->id]);

    loginUser($user);

    get(route('tasks.assignTags', $task))
    ->assertOk()
    ->assertSeeText('Assign Tags');
});

test('store multiple tags to task', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create(['user_id' => $user->id]);
    $tags = Tag::factory()->count(3)->create(['user_id' => $user->id]);

    loginUser($user);
    
    $tagIds = $task->tags->pluck('id')->toArray();

    post(route('task-tags.store'), [
        'task_id' => $task->id,
        'tags' => $tagIds
    ])
    ->assertRedirect(route('tasks.assignTags', $task))
    ->assertSessionHas('success');

    foreach ($tagIds as $tagId) {
        $this->assertDatabaseHas('task_tag', [
            'task_id' => $task->id,
            'tag_id' => $tagId
        ]);
    }

    
});

test('Tags associated with a Task can be removed', function() {
    $user = User::factory()->create();
    $task = Task::factory()->has(Tag::factory()->count(3))->create(['user_id' => $user->id]);
    
    loginUser($user);

    get(route('task-tags.delete', [$task->id, $task->tags->first()->id]))
    ->assertRedirect(route('tasks.assignTags', $task))
    ->assertSessionHas('success');

    $this->assertDatabaseMissing('task_tag', [
        'task_id' => $task->id,
        'tag_id' => $task->tags->first()->id
    ]);
});

test('cannot assign same tag to task twice', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create(['user_id' => $user->id]);
    $tags = Tag::factory()->count(3)->create(['user_id' => $user->id]);

    loginUser($user);

    $tagIds = $tags->pluck('id')->toArray();

    post(route('task-tags.store'), [
        'task_id' => $task->id,
        'tags' => $tagIds
    ])
    ->assertRedirect(route('tasks.assignTags', $task))
    ->assertSessionHas('success');

    $this->assertDatabaseCount('task_tag', 3);

    post(route('task-tags.store'), [
        'task_id' => $task->id,
        'tags' => $tagIds
    ])
    ->assertRedirect(route('tasks.assignTags', $task))
    ->assertSessionHas('error');

    $this->assertDatabaseCount('task_tag', 3);
});
