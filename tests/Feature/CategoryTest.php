<?php

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\User;

test('guests cannot access category admin pages', function () {
    $this->get(route('admin.categories.index'))->assertRedirect(route('login'));
});

test('authenticated users can view categories', function () {
    $user = User::factory()->create();
    Category::factory()->count(2)->create();

    $this->actingAs($user)
        ->get(route('admin.categories.index'))
        ->assertOk();
});

test('category creation succeeds', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('admin.categories.store'), [
            'name' => 'Electronics',
            'description' => 'Gadgets and devices',
        ])
        ->assertRedirect(route('admin.categories.index'));

    $this->assertDatabaseHas('categories', [
        'name' => 'Electronics',
        'slug' => 'electronics',
    ]);
});

test('invalid category data is rejected', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('admin.categories.create'))
        ->post(route('admin.categories.store'), [
            'name' => '',
        ])
        ->assertSessionHasErrors('name')
        ->assertRedirect(route('admin.categories.create'));
});

test('category update succeeds', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create(['name' => 'Old Name']);

    $this->actingAs($user)
        ->put(route('admin.categories.update', $category), [
            'name' => 'New Name',
            'description' => 'Updated',
        ])
        ->assertRedirect(route('admin.categories.index'));

    expect($category->fresh()->name)->toBe('New Name')
        ->and($category->fresh()->slug)->toBe('new-name');
});

test('empty category can be deleted', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    $this->actingAs($user)
        ->delete(route('admin.categories.destroy', $category))
        ->assertRedirect(route('admin.categories.index'));

    $this->assertDatabaseMissing('categories', ['id' => $category->id]);
});

test('category with subcategories cannot be deleted', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();
    Subcategory::factory()->create(['category_id' => $category->id]);

    $this->actingAs($user)
        ->from(route('admin.categories.index'))
        ->delete(route('admin.categories.destroy', $category))
        ->assertRedirect(route('admin.categories.index'));

    $this->assertDatabaseHas('categories', ['id' => $category->id]);
});
