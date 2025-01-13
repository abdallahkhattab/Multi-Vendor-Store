<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Dashboard\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Services\CategoryService;
use Illuminate\Support\Facades\Storage;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $categoryService;

    public function __construct(CategoryService $categoryService){

        $this->categoryService = $categoryService;
    }
 


    public function index(Request $request)
    {

      // $categories = Category::with('parent')->get();
      
        
       $categories = Category::when($request->has('search') && !empty($request->search), function ($query) use ($request) {
            $searchTerm = $request->search;
            $query->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('slug', 'like', "%{$searchTerm}%");
        })
        ->with('parent')
        ->withCount(['products as products_number'=>function($query){
        $query->where('status','=','active');
        }]) //number of Active products , the default products_count
        ->paginate(5);
    
        // Return the view with the filtered categories
        return view('dashboardPages.categories.index', compact('categories'));
    }
    
    
    /**
     * Show the form for creating a new resource.
     */
    public function create(Category $categories)
    {
        $categories = Category::whereNull('parent_id')->get(); 
       
      return view('dashboardPages.categories.create',compact('categories'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
       
            // Validate the request
            $cleanData = $request->validated();


           $category = $this->categoryService->createCategory($cleanData, $request->file('image'));

            // Redirect with success message
            return redirect()->route('categories.index')->with('success', 'Category Added Successfully');

             // Handle image upload
           
           /* $imagePath = $request->hasFile('image') 
                ? $request->file('image')->store('categories', 'public') 
                : null;
                */
           // $imagePath = $this->handleImageUpload($request->file('image'));
    
            // Merge the slug and image path
          //  $cleanData['slug'] = Str::slug($cleanData['name']) . '-' . uniqid();
          //  $cleanData['image'] = $imagePath;
    
            // Create the category
           // $category = new Category($cleanData);

           // $category->save();  
         
    }
    
    

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //

        $products = $category->products()->paginate();

        return view('dashboardPages.categories.show',compact('category','products'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        
        return view('dashboardPages.categories.edit',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        

        $cleanData = $request->validated();

        $this->categoryService->updateCategory($category,$cleanData,$request->file('image'));

        return redirect()->route('categories.index')->with('success', 'Category updated successfully!');

        /*
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
        
           // $imagePath = $request->file('image')->store('categories', 'public');

            $imagePath = $this->handleImageUpload($request->file('image'));
            $cleanData['image'] = $imagePath;
        }
        
        $cleanData['slug'] = Str::slug($cleanData['name']) . '-' . uniqid();

        $category->update($cleanData);*/

    }


  /*  public function handleImageUpload($image){

            if(!$image){
                return null;
            }
        // Create a unique filename for the image
        $imageName = uniqid() . '.' . $image->getClientOriginalExtension();

        // Store the image in the public storage
        $path = $image->storeAs('categories', $imageName, 'public');
        return $path;
    }*/




    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Category $category)
    {
        //
        
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully!');

    }

    public function deletedCategories(Category $category)
    {
        $trashedCategories = Category::onlyTrashed()->paginate(10);

        return view('dashboardPages.categories.trashed',compact('trashedCategories'));
    }

    public function restoreDeletedCategory($id){

        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();
        return redirect()->route('categories.index')->with('success', 'Category restored successfully!');

    }

    public function forceDelete($id){
        $category = Category::onlyTrashed()->findOrFail($id);

        if($category->image){
            Storage::disk('public')->delete($category->image);
        }

        $category->forceDelete();
        return redirect()->route('categories.index')->with('success', 'Category permanently deleted!');


    }

   

}
