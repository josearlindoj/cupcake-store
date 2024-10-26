<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that an admin can view the category creation form.
     */
    public function test_admin_can_view_category_creation_form()
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')
            ->get(route('categories.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.create');
    }

    /**
     * Test that an admin can create a category with valid data.
     */
    public function test_admin_can_create_category_with_valid_data()
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')
            ->post(route('categories.store'), [
                'name' => 'Test Category',
            ]);

        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('success', 'Category created successfully!');
        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category',
        ]);
    }

    /**
     * Test that an admin cannot create a category with invalid data.
     */
    public function test_admin_cannot_create_category_with_invalid_data()
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')
            ->from(route('categories.create'))
            ->post(route('categories.store'), [
                'name' => '', // Invalid name
            ]);

        $response->assertRedirect(route('categories.create'));
        $response->assertSessionHasErrors(['name']);
        $this->assertCount(0, Category::all());
    }

    /**
     * Test that an admin can view the category editing form.
     */
    public function test_admin_can_view_category_edit_form()
    {
        $admin = Admin::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($admin, 'admin')
            ->get(route('categories.edit', $category->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.edit');
        $response->assertViewHas('category', $category);
    }

    /**
     * Test that an admin can update a category with valid data.
     */
    public function test_admin_can_update_category_with_valid_data()
    {
        $admin = Admin::factory()->create();
        $category = Category::factory()->create([
            'name' => 'Original Name',
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->put(route('categories.update', $category->id), [
                'name' => 'Updated Category',
            ]);

        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('success', 'Category updated successfully!');
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Category',
        ]);
    }

    /**
     * Test that an admin cannot update a category with invalid data.
     */
    public function test_admin_cannot_update_category_with_invalid_data()
    {
        $admin = Admin::factory()->create();
        $category = Category::factory()->create([
            'name' => 'Original Name',
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->from(route('categories.edit', $category->id))
            ->put(route('categories.update', $category->id), [
                'name' => '', // Invalid name
            ]);

        $response->assertRedirect(route('categories.edit', $category->id));
        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Original Name', // Name remains unchanged
        ]);
    }

    /**
     * Test that a guest cannot access the category creation form.
     */
    public function test_guest_cannot_view_category_creation_form()
    {
        $response = $this->get(route('categories.create'));

        $response->assertRedirect(route('admin.login'));
    }

    /**
     * Test that a guest cannot access the category editing form.
     */
    public function test_guest_cannot_view_category_edit_form()
    {
        $category = Category::factory()->create();

        $response = $this->get(route('categories.edit', $category->id));

        $response->assertRedirect(route('admin.login'));
    }
}
