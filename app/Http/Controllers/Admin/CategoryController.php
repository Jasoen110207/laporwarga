<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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

        $category = Category::create($data + ['slug' => Str::slug($data['name'])]);
        AuditLog::record('kategori_dibuat', $category->name);

        return back()->with('success', 'Kategori ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80', Rule::unique('categories', 'name')->ignore($category->id)],
            'description' => 'nullable|string|max:255',
        ]);

        $category->update($data + ['slug' => Str::slug($data['name'])]);
        AuditLog::record('kategori_diubah', $category->name);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori diperbarui.');
    }

    public function destroy(Category $category)
    {
        if ($category->reports()->exists()) {
            return back()->with('error', 'Kategori masih dipakai laporan, jadi tidak bisa dihapus.');
        }

        $name = $category->name;
        $category->delete();
        AuditLog::record('kategori_dihapus', $name);

        return back()->with('success', 'Kategori dihapus.');
    }
}
