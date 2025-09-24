<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;

class HistoryController extends Controller
{
    public function index()
{
    $loans = Loan::where('user_id', auth()->id())
                 ->with('book') // Eager load thông tin sách để tránh N+1 query
                 ->latest('loan_date')
                 ->paginate(10);

    return view('history.my', compact('loans'));
}
}
