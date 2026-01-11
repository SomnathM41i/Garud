<?php

namespace App\Http\Controllers;

use App\Models\JewelleryCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JewelleryCategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index()
    {
        $categories = JewelleryCategory::latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:100|unique:jewellery_categories,category_name',
            'status' => 'required|in:active,inactive',
        ]);

        JewelleryCategory::create($request->only('category_name', 'status'));

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category added successfully.');
    }

    /**
     * Show the form for editing the category.
     */
    public function edit(JewelleryCategory $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, JewelleryCategory $category)
    {
        $request->validate([
            'category_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('jewellery_categories')->ignore($category->id),
            ],
            'status' => 'required|in:active,inactive',
        ]);

        $category->update($request->only('category_name', 'status'));

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }



    /**
     * Remove the specified category.
     */
    public function destroy(JewelleryCategory $category)
    {
        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }

}
