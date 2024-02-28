<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $categories = Category::orderBy('order')->get();

        return $categories;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        //

        $data = $request->validated();

        $category = Category::create($data);
        return response()->json([
            'message' => "Created category Successfully"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
        return $category;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        //
        $data = $request->validated();
        $category->update($data);

        return response()->json([
            'message' => "Updated Successfully"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
        $category->delete();
        return response()->json([
            'message' => "Deleted Successfully"
        ]);
    }

    public function orderCategory(Request $request)
    {

        $orderedCategories = $request->categoryOrder;
        foreach ($orderedCategories as $order => $categoryId) {
            Category::where('id', $categoryId)->update(['order' => $order]);
        }

        return response()->json(['message' => 'Order of categories saved successfully']);
    }
}
