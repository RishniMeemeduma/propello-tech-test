<?php

use App\Models\Tag;
use App\Models\Task;
use App\Models\User;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses()->group('tags');

test('create tags', function () {
    loginUser();

    $tags = Tag::factory()->create();
    
    get(route('tags.create'))
    ->assertOk()
    ->assertSeeText('Tag Name');
});

test('store tags', function () {
    loginUser();
    $tag = Tag::factory()->create();

    $this->post(route('tags.store'), $tag->toArray())
    ->assertRedirect(route('tags.create'));

    $this->assertDatabaseHas('tags', ['name' => $tag->name]);
});

test('edit tags', function () {
    $user = User::factory()->create();
    $tag = Tag::factory()->create(['user_id' => $user->id]);
    
    loginUser($user);

    get(route('tags.update', $tag))
        ->assertOk();

    $this->assertDatabaseHas('tags', [
        'id' => $tag->id,
        'name' => $tag->name
    ]);
});

test('delete tags', function () {
    $user = User::factory()->create();
    $tag = Tag::factory()->create(['user_id' => $user->id]);

    loginUser($user);

    get(route('tags.destroy', $tag))
    ->assertRedirect(route('tags.create'));

    $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
});

test ('Tags are only accessible by the user who created them', function () {
   $owner = User::factory()->create();
   $otherUser = User::factory()->create();
   
   // Create a tag owned by the first user
   $tag = Tag::factory()->create(['user_id' => $owner->id]);
   
   // Login as the other user
   loginUser($otherUser);
   
   // Try to access the tag
   get(route('tags.edit', $tag))
       ->assertForbidden(); 
});
    

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
    $task = Task::factory()->has(Tag::factory()->count(3))->create(['user_id' => $user->id]);

    loginUser($user);
    
    $tagIds = $task->tags->pluck('id')->toArray();

    post(route('task-tags.store'), [
        'task_id' => $task->id,
        'tags' => $tagIds
    ])
    ->assertRedirect(route('tasks.home'))
    ->assertSessionHas('success');

    foreach ($tagIds as $tagId) {
        $this->assertDatabaseHas('task_tag', [
            'task_id' => $task->id,
            'tag_id' => $tagId
        ]);
    }
});

test('Tags associated with a Task can be removed', function() {
    $task = Task::has(Tag::factory()->count(3))->create();

    get(route('tag.remove', $task, $task->tags->first()))
    ->assertOk()
    ->assertDontSeeText($task->tags->first()->name);
});

