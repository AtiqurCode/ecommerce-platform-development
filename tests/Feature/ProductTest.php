<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('guests cannot access product admin pages', function () {
    $this->get(route('admin.products.index'))->assertRedirect(route('login'));
});

test('public product pages are accessible without authentication', function () {
    $product = Product::factory()->create([
        'name' => 'Public Phone',
        'slug' => 'public-phone',
    ]);

    $this->get(route('products.index'))->assertOk();
    $this->get(route('products.show', $product))->assertOk();
});

test('invalid product slug returns 404', function () {
    $this->get('/products/does-not-exist')->assertNotFound();
});

test('product creation succeeds', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $category = Category::factory()->create();
    $subcategory = Subcategory::factory()->create(['category_id' => $category->id]);

    $this->actingAs($user)
        ->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id,
            'name' => 'iPhone 17 Pro',
            'description' => 'Flagship phone',
            'old_price' => 1499,
            'new_price' => 1299,
            'image' => UploadedFile::fake()->image('phone.jpg'),
        ])
        ->assertRedirect(route('admin.products.index'));

    $product = Product::query()->where('slug', 'iphone-17-pro')->first();

    expect($product)->not->toBeNull()
        ->and($product->subcategory_id)->toBe($subcategory->id);

    Storage::disk('public')->assertExists($product->image);
});

test('product validation works', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $category = Category::factory()->create();
    $subcategory = Subcategory::factory()->create(['category_id' => $category->id]);

    $this->actingAs($user)
        ->from(route('admin.products.create'))
        ->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id,
            'name' => '',
            'old_price' => 100,
            'new_price' => 150,
            'image' => UploadedFile::fake()->image('phone.jpg'),
        ])
        ->assertSessionHasErrors(['name', 'new_price'])
        ->assertRedirect(route('admin.products.create'));
});

test('invalid image uploads are rejected', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $category = Category::factory()->create();
    $subcategory = Subcategory::factory()->create(['category_id' => $category->id]);

    $this->actingAs($user)
        ->from(route('admin.products.create'))
        ->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id,
            'name' => 'Broken Upload',
            'old_price' => 100,
            'new_price' => 80,
            'image' => UploadedFile::fake()->create('notes.pdf', 100, 'application/pdf'),
        ])
        ->assertSessionHasErrors('image')
        ->assertRedirect(route('admin.products.create'));
});

test('product update succeeds and replaces image', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $category = Category::factory()->create();
    $subcategory = Subcategory::factory()->create(['category_id' => $category->id]);

    $oldImage = UploadedFile::fake()->image('old.jpg')->store('products', 'public');
    $product = Product::factory()->create([
        'subcategory_id' => $subcategory->id,
        'image' => $oldImage,
        'name' => 'Old Product',
    ]);

    $this->actingAs($user)
        ->put(route('admin.products.update', $product), [
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id,
            'name' => 'Updated Product',
            'description' => 'Updated description',
            'old_price' => 200,
            'new_price' => 150,
            'image' => UploadedFile::fake()->image('new.jpg'),
        ])
        ->assertRedirect(route('admin.products.index'));

    $product->refresh();

    expect($product->name)->toBe('Updated Product')
        ->and($product->slug)->toBe('updated-product');

    Storage::disk('public')->assertMissing($oldImage);
    Storage::disk('public')->assertExists($product->image);
});

test('product deletion succeeds and removes image', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $image = UploadedFile::fake()->image('delete-me.jpg')->store('products', 'public');
    $product = Product::factory()->create(['image' => $image]);

    $this->actingAs($user)
        ->delete(route('admin.products.destroy', $product))
        ->assertRedirect(route('admin.products.index'));

    $this->assertDatabaseMissing('products', ['id' => $product->id]);
    Storage::disk('public')->assertMissing($image);
});

test('subcategory must belong to selected category', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $category = Category::factory()->create();
    $otherCategory = Category::factory()->create();
    $subcategory = Subcategory::factory()->create(['category_id' => $otherCategory->id]);

    $this->actingAs($user)
        ->from(route('admin.products.create'))
        ->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id,
            'name' => 'Mismatched Product',
            'old_price' => 100,
            'new_price' => 80,
            'image' => UploadedFile::fake()->image('phone.jpg'),
        ])
        ->assertSessionHasErrors('subcategory_id')
        ->assertRedirect(route('admin.products.create'));
});

test('authenticated users can load dependent subcategories', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();
    $subcategory = Subcategory::factory()->create([
        'category_id' => $category->id,
        'name' => 'Laptops',
    ]);

    $this->actingAs($user)
        ->getJson(route('admin.categories.subcategories', $category))
        ->assertOk()
        ->assertJsonFragment([
            'id' => $subcategory->id,
            'name' => 'Laptops',
        ]);
});
