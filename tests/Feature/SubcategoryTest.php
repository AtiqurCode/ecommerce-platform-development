<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\User;

test('guests cannot access subcategory admin pages', function () {
    $this->get(route('admin.subcategories.index'))->assertRedirect(route('login'));
});

test('subcategory creation succeeds', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    $this->actingAs($user)
        ->post(route('admin.subcategories.store'), [
            'category_id' => $category->id,
            'name' => 'Mobile Phones',
            'description' => 'Smartphones',
        ])
        ->assertRedirect(route('admin.subcategories.index'));

    $this->assertDatabaseHas('subcategories', [
        'category_id' => $category->id,
        'name' => 'Mobile Phones',
        'slug' => 'mobile-phones',
    ]);
});

test('invalid category id is rejected', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('admin.subcategories.create'))
        ->post(route('admin.subcategories.store'), [
            'category_id' => 999999,
            'name' => 'Mobile Phones',
        ])
        ->assertSessionHasErrors('category_id')
        ->assertRedirect(route('admin.subcategories.create'));
});

test('subcategory update succeeds', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();
    $subcategory = Subcategory::factory()->create([
        'category_id' => $category->id,
        'name' => 'Old Sub',
    ]);

    $this->actingAs($user)
        ->put(route('admin.subcategories.update', $subcategory), [
            'category_id' => $category->id,
            'name' => 'Updated Sub',
            'description' => 'Changed',
        ])
        ->assertRedirect(route('admin.subcategories.index'));

    expect($subcategory->fresh()->name)->toBe('Updated Sub')
        ->and($subcategory->fresh()->slug)->toBe('updated-sub');
});

test('subcategory with products cannot be deleted', function () {
    $user = User::factory()->create();
    $subcategory = Subcategory::factory()->create();
    Product::factory()->create(['subcategory_id' => $subcategory->id]);

    $this->actingAs($user)
        ->from(route('admin.subcategories.index'))
        ->delete(route('admin.subcategories.destroy', $subcategory))
        ->assertRedirect(route('admin.subcategories.index'));

    $this->assertDatabaseHas('subcategories', ['id' => $subcategory->id]);
});

test('empty subcategory can be deleted', function () {
    $user = User::factory()->create();
    $subcategory = Subcategory::factory()->create();

    $this->actingAs($user)
        ->delete(route('admin.subcategories.destroy', $subcategory))
        ->assertRedirect(route('admin.subcategories.index'));

    $this->assertDatabaseMissing('subcategories', ['id' => $subcategory->id]);
});
