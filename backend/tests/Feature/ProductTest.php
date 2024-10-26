<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_session_driver_is_array()
    {
        $this->assertEquals('array', config('session.driver'));
    }

    /**
     * Test that an admin can view the product creation form.
     */
    public function test_admin_can_view_product_creation_form()
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get(route('products.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.products.create');
    }

    /**
     * Test that an admin can create a product with valid data.
     */
    public function test_admin_can_create_product_with_valid_data()
    {
        $admin = Admin::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($admin, 'admin')
            ->post(route('products.store'), [
                'name' => 'Test Product',
                'description' => 'This is a test product.',
                'price' => 99.99,
                'stock' => 10,
                'category_id' => $category->id,
            ]);

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('success', 'Product created successfully!');
        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'category_id' => $category->id,
        ]);
    }

    /**
     * Test that an admin cannot create a product with invalid data.
     */
    public function test_admin_cannot_create_product_with_invalid_data()
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')
            ->from(route('products.create'))
            ->post(route('products.store'), [
                'name' => '', // Invalid name
                'price' => 'invalid', // Invalid price
                'stock' => -1, // Invalid stock
                'category_id' => null, // Missing category
            ]);

        $response->assertRedirect(route('products.create'));
        $response->assertSessionHasErrors(['name', 'price', 'stock', 'category_id']);
        $this->assertCount(0, Product::all());
    }

    /**
     * Test that an admin can view the product editing form.
     */
    public function test_admin_can_view_product_edit_form()
    {
        $admin = Admin::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($admin, 'admin')
            ->get(route('products.edit', $product->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.products.edit');
        $response->assertViewHas('product', $product);
    }

    /**
     * Test that an admin can update a product with valid data.
     */
    public function test_admin_can_update_product_with_valid_data()
    {
        $admin = Admin::factory()->create();
        $product = Product::factory()->create();
        $newCategory = Category::factory()->create();

        $response = $this->actingAs($admin, 'admin')
            ->put(route('products.update', $product->id), [
                'name' => 'Updated Product',
                'description' => 'Updated description.',
                'price' => 199.99,
                'stock' => 20,
                'category_id' => $newCategory->id,
            ]);

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('success', 'Product updated successfully!');
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product',
            'category_id' => $newCategory->id,
        ]);
    }

    /**
     * Test that an admin cannot update a product with invalid data.
     */
    public function test_admin_cannot_update_product_with_invalid_data()
    {
        $admin = Admin::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($admin, 'admin')
            ->from(route('products.edit', $product->id))
            ->put(route('products.update', $product->id), [
                'name' => '', // Invalid name
                'price' => 'invalid', // Invalid price
                'stock' => -1, // Invalid stock
                'category_id' => null, // Missing category
            ]);

        $response->assertRedirect(route('products.edit', $product->id));
        $response->assertSessionHasErrors(['name', 'price', 'stock', 'category_id']);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => $product->name, // Unchanged
        ]);
    }

    /**
     * Test that a guest cannot access the product creation form.
     */
    public function test_guest_cannot_view_product_creation_form()
    {
        $response = $this->get(route('products.create'));

        $response->assertRedirect(route('admin.login'));
    }

    /**
     * Test that a guest cannot access the product editing form.
     */
    public function test_guest_cannot_view_product_edit_form()
    {
        $product = Product::factory()->create();

        $response = $this->get(route('products.edit', $product->id));

        $response->assertRedirect(route('admin.login'));
    }
}
