<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.categories.index', [
            'categories' => Category::withCount('reports')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:80|unique:categories,name',
            'description' => 'nullable|string|max:255',
        ]);

        Category::create($data + ['slug' => Str::slug($data['name'])]);

        return back()->with('success', 'Kategori ditambahkan.');
    }

    public function destroy(Category $category)
    {
        if ($category->reports()->exists()) {
            return back()->withErrors(['category' => 'Kategori masih dipakai laporan, jadi tidak bisa dihapus.']);
        }

        $category->delete();

        return back()->with('success', 'Kategori dihapus.');
    }
}
