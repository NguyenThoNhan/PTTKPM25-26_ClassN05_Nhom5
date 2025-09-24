<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loan;

class LoanManagementController extends Controller
{
     public function index(Request $request)
    {
        $query = Loan::with(['user', 'book'])->latest('loan_date');

        // Lọc theo trạng thái toàn vẹn nếu có request
        if ($request->has('filter') && in_array($request->filter, ['failed', 'passed', 'borrowed'])) {
            if ($request->filter === 'borrowed') {
                 $query->where('status', 'borrowed');
            } else {
                $query->where('integrity_status', $request->filter);
            }
        }

        $loans = $query->paginate(15);
        
        return view('admin.loans.index', compact('loans'));
    }
}
