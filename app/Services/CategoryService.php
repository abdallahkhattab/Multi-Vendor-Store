<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Dashboard\Category;
use Illuminate\Support\Facades\Storage;

class CategoryService
{
    /**
     * Handle image upload and enhancement.
     */
    public function handleImageUpload($image)
    {
        if (!$image) {
            return null;
        }

        // Create a unique filename for the image
        $imageName = uniqid() . '.' . $image->getClientOriginalExtension();

        // Store the image in the public storage
        $path = $image->storeAs('categories', $imageName, 'public');
        return $path;
    }


    /**
     * Create a new category.
     */
    public function createCategory($data, $image)
    {
        // Handle image upload
        $imagePath = $this->handleImageUpload($image);

        // Generate slug and prepare data for saving
        $data['slug'] = Str::slug($data['name']) . '-' . uniqid();
        $data['image'] = $imagePath;

        // Create and save the category
        return Category::create($data);
    }

    /**
     * Update the category, including image handling.
     */
    public function updateCategory(Category $category, $data, $image)
    {
        // Handle image upload if new image is provided
        if ($image) {
            // Delete old image if exists
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            // Handle new image upload
            $imagePath = $this->handleImageUpload($image);
            $data['image'] = $imagePath;
        }

        // Update slug and save category
        $data['slug'] = Str::slug($data['name']) . '-' . uniqid();
        $category->update($data);

        return $category;
    }
}
