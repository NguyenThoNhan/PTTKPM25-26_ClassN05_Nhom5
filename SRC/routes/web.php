<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\BookPageController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\CategoryPageController;
use App\Http\Controllers\Admin\LoanManagementController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\EventPageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\UserDocumentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\VirtualLibraryController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\GamificationController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\ReadingGroupController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\OfflineController;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [BookPageController::class, 'index'])->name('home');

// Fallback phục vụ file từ disk 'public' khi symlink public/storage gặp vấn đề (đặc biệt trên Windows)
Route::get('/storage/{path}', function (string $path) {
    $normalized = ltrim(str_replace('..', '', str_replace('\\', '/', $path)), '/');
    if (!Storage::disk('public')->exists($normalized)) {
        abort(404);
    }
    $fullPath = Storage::disk('public')->path($normalized);
    return response()->file($fullPath);
})->where('path', '.*');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/my-documents/{loan}/edit', [UserDocumentController::class, 'edit'])->name('documents.edit');
Route::patch('/my-documents/{loan}', [UserDocumentController::class, 'update'])->name('documents.update');
Route::get('/my-favorites', [FavoriteController::class, 'index'])->name('favorites.index');
// Route để xử lý việc thêm/xóa yêu thích (sẽ được gọi bằng AJAX)
Route::post('/favorites/{book}/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

// Review routes
Route::get('/books/{book}/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::get('/books/{book}/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
Route::post('/books/{book}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
Route::post('/reviews/{review}/helpful', [ReviewController::class, 'helpful'])->name('reviews.helpful');

// Gamification routes
Route::get('/gamification', [GamificationController::class, 'dashboard'])->name('gamification.dashboard');
Route::get('/leaderboard', [GamificationController::class, 'leaderboard'])->name('gamification.leaderboard');
Route::get('/badges', [GamificationController::class, 'badges'])->name('gamification.badges');
Route::get('/challenges', [GamificationController::class, 'challenges'])->name('gamification.challenges');
Route::post('/challenges/update', [GamificationController::class, 'updateChallenge'])->name('gamification.update-challenge');
Route::get('/gamification/stats', [GamificationController::class, 'getStats'])->name('gamification.stats');

// Social Features routes
Route::get('/social/feed', [SocialController::class, 'feed'])->name('social.feed');
Route::get('/users/{user}/profile', [SocialController::class, 'profile'])->name('social.profile');
Route::get('/users/{user}/reviews', [SocialController::class, 'reviews'])->name('social.reviews');
Route::get('/users/{user}/discussions', [SocialController::class, 'discussions'])->name('social.discussions');
Route::get('/users/search', [SocialController::class, 'searchUsers'])->name('social.search-users');
Route::get('/users/suggestions', [SocialController::class, 'getUserSuggestions'])->name('social.user-suggestions');
Route::post('/profile/update', [SocialController::class, 'updateProfile'])->name('social.update-profile');

// Discussion routes
Route::get('/discussions', [DiscussionController::class, 'index'])->name('discussions.index');
Route::get('/discussions/create', [DiscussionController::class, 'create'])->name('discussions.create');
Route::post('/discussions', [DiscussionController::class, 'store'])->name('discussions.store');
Route::get('/discussions/{discussion}', [DiscussionController::class, 'show'])->name('discussions.show');
Route::get('/discussions/{discussion}/edit', [DiscussionController::class, 'edit'])->name('discussions.edit');
Route::put('/discussions/{discussion}', [DiscussionController::class, 'update'])->name('discussions.update');
Route::delete('/discussions/{discussion}', [DiscussionController::class, 'destroy'])->name('discussions.destroy');
Route::post('/discussions/{discussion}/like', [DiscussionController::class, 'toggleLike'])->name('discussions.toggle-like');
Route::post('/discussions/{discussion}/pin', [DiscussionController::class, 'togglePin'])->name('discussions.toggle-pin');
Route::post('/discussions/{discussion}/lock', [DiscussionController::class, 'toggleLock'])->name('discussions.toggle-lock');

// Reading Group routes
Route::get('/reading-groups', [ReadingGroupController::class, 'index'])->name('reading-groups.index');
Route::get('/reading-groups/create', [ReadingGroupController::class, 'create'])->name('reading-groups.create');
Route::post('/reading-groups', [ReadingGroupController::class, 'store'])->name('reading-groups.store');
Route::get('/reading-groups/{readingGroup}', [ReadingGroupController::class, 'show'])->name('reading-groups.show');
Route::get('/reading-groups/{readingGroup}/edit', [ReadingGroupController::class, 'edit'])->name('reading-groups.edit');
Route::put('/reading-groups/{readingGroup}', [ReadingGroupController::class, 'update'])->name('reading-groups.update');
Route::delete('/reading-groups/{readingGroup}', [ReadingGroupController::class, 'destroy'])->name('reading-groups.destroy');
Route::post('/reading-groups/{readingGroup}/join', [ReadingGroupController::class, 'join'])->name('reading-groups.join');
Route::post('/reading-groups/{readingGroup}/leave', [ReadingGroupController::class, 'leave'])->name('reading-groups.leave');
Route::post('/reading-groups/{readingGroup}/members/{user}/role', [ReadingGroupController::class, 'updateMemberRole'])->name('reading-groups.update-member-role');
Route::delete('/reading-groups/{readingGroup}/members/{user}', [ReadingGroupController::class, 'removeMember'])->name('reading-groups.remove-member');

// Notification routes
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::get('/notifications/preferences', [NotificationController::class, 'preferences'])->name('notifications.preferences');
Route::post('/notifications/preferences', [NotificationController::class, 'updatePreferences'])->name('notifications.update-preferences');
Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
Route::delete('/notifications', [NotificationController::class, 'clearAll'])->name('notifications.clear-all');
Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
Route::get('/notifications/recent', [NotificationController::class, 'getRecent'])->name('notifications.recent');

// QR Code routes
Route::get('/qr-scanner', [QRCodeController::class, 'scanner'])->name('qr.scanner');
Route::post('/qr-scan', [QRCodeController::class, 'scan'])->name('qr.scan');
Route::get('/books/{book}/qr-code', [QRCodeController::class, 'show'])->name('books.qr-code');
Route::post('/books/{book}/qr-generate', [QRCodeController::class, 'generate'])->name('books.qr-generate');
Route::post('/books/{book}/qr-borrow', [QRCodeController::class, 'quickBorrow'])->name('books.qr-borrow');

// Offline routes
Route::get('/offline', [OfflineController::class, 'index'])->name('offline.index');
Route::post('/books/{book}/download', [OfflineController::class, 'download'])->name('books.download');
Route::get('/books/{book}/offline-content', [OfflineController::class, 'getOfflineContent'])->name('books.offline-content');
Route::post('/books/{book}/sync', [OfflineController::class, 'sync'])->name('books.sync');
Route::get('/offline/status', [OfflineController::class, 'status'])->name('offline.status');
});

// =========== USER ROUTES ============

// Trang chủ hiển thị danh sách sách/tài liệu (ai cũng xem được)
Route::get('/', [BookPageController::class, 'index'])->name('home');

// Trang chi tiết sách/tài liệu
Route::get('/books/{book}', [BookPageController::class, 'show'])->name('books.show');

// Các route yêu cầu đăng nhập
Route::middleware('auth')->group(function () {
    // Mượn sách
    Route::post('/loans/{book}', [LoanController::class, 'store'])->name('loans.store');
    
    // Trả sách
    Route::patch('/loans/{loan}', [LoanController::class, 'update'])->name('loans.return');

    // Xem lịch sử mượn của cá nhân
    Route::get('/my-history', [HistoryController::class, 'index'])->name('history.my');
});


// Routes dành cho Admin
Route::middleware(['auth', 'can:is-admin'])->group(function () {
    // Đặt tiền tố 'admin' cho tất cả các route bên trong
    Route::prefix('admin')->name('admin.')->group(function () {
     // Route cho Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Route này sẽ tự động tạo các route cho index, create, store, show, edit, update, destroy
    Route::resource('books', BookController::class);
            // Routes cho Quản lý Người dùng
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::resource('categories', CategoryController::class)->except(['show']); // Bỏ route show vì không cần thiết
        Route::get('/loans', [LoanManagementController::class, 'index'])->name('loans.index');
        Route::resource('events', EventController::class);
        // Sau này chúng ta sẽ thêm các route quản lý user, loan ở đây
    });
});

// --- ROUTES CHO TRANG SỰ KIỆN CỦA USER ---
Route::get('/events', [EventPageController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventPageController::class, 'show'])->name('events.show');

// Route để đăng ký sự kiện (cần đăng nhập)
Route::post('/events/{event}/register', [EventPageController::class, 'register'])->middleware('auth')->name('events.register');
// Route để hủy đăng ký sự kiện (cần đăng nhập)
Route::delete('/events/{event}/unregister', [EventPageController::class, 'unregister'])->middleware('auth')->name('events.unregister');

Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

Route::get('/categories/{category:slug}', [CategoryPageController::class, 'show'])->name('categories.show');
Route::get('/about-us', [AboutUsController::class, 'index'])->name('about.index');
Route::get('/virtual-library', [VirtualLibraryController::class, 'index'])->name('virtual-library.index');
require __DIR__.'/auth.php';
