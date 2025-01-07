<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Dashboard\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $categories = Category::all();
        return view('dashboardPages.categories.index',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

        return view('dashboardPages.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
       
            // Validate the request
            $cleanData = $request->validated();


            $data = $request->validate([
                'name'=> 'required',
            ]);
        
            // Handle image upload
            $imagePath = $request->hasFile('image') 
                ? $request->file('image')->store('categories', 'public') 
                : null;
    
            // Merge the slug and image path
            $cleanData['slug'] = Str::slug($cleanData['name']) . '-' . uniqid();
            $cleanData['image'] = $imagePath;
    
            // Create the category
            Category::create($cleanData);
    
            // Redirect with success message
            return redirect()->route('categories.index')->with('success', 'Category Added Successfully');

            
      
    }
    
    

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
