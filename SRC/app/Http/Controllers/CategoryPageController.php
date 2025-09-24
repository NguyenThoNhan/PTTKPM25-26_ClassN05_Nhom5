<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminatate\Support\Str;
use App\Models\Book;

class CategoryPageController extends Controller
{
     public function show(Category $category)
    {
        $books = $category->books()->latest()->paginate(12);
        return view('categories.show', compact('category', 'books'));
    }
}
