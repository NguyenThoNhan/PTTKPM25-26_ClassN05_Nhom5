<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Book;

class VirtualLibraryController extends Controller
{
    public function index(): View
    {
        $books = Book::with('categories')->latest()->take(26)->get();

        return view('virtual-library.index', compact('books'));
    }
}
