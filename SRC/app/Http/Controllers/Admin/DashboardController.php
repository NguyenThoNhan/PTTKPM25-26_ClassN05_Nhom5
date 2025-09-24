<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function index()
{
    // --- DỮ LIỆU CHO CÁC CARD THỐNG KÊ ---
        $stats = [
            'total_users' => User::count(),
            'total_books' => Book::count(),
            'loans_active' => Loan::where('status', 'borrowed')->count(),
            'upcoming_events' => Event::where('start_time', '>=', now())->count(),
        ];

        // --- DỮ LIỆU CHO CÁC BẢNG "HOẠT ĐỘNG GẦN ĐÂY" ---
        $recentLoans = Loan::with('user', 'book')->latest()->take(5)->get();
        $recentUsers = User::latest()->take(5)->get();

        // --- DỮ LIỆU CHO BIỂU ĐỒ ---
        
        // 1. Biểu đồ Đường: Lượt mượn sách trong 30 ngày qua
        $loansByDay = DB::table('loans')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subDays(29))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Xử lý để đảm bảo đủ 30 ngày, kể cả ngày không có lượt mượn
        $loansChartData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $loansChartData['labels'][] = date('d/m', strtotime($date));
            $loansChartData['data'][] = $loansByDay->firstWhere('date', $date)->count ?? 0;
        }

        // 2. Biểu đồ Tròn: Phân bố sách theo danh mục
        $categoryDistribution = Category::withCount('books')->get();
        $categoryChartData = [
            'labels' => $categoryDistribution->pluck('name'),
            'data' => $categoryDistribution->pluck('books_count'),
        ];

        return view('admin.dashboard', compact(
            'stats',
            'recentLoans',
            'recentUsers',
            'loansChartData',
            'categoryChartData'
        ));
    }
}
