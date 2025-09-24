<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail; // <-- Thêm use này
use App\Mail\ContactFormMail;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact.show');
    }

    /**
     * Xử lý dữ liệu từ form và gửi email.
     */
    public function send(Request $request)
    {
        // 1. Validate dữ liệu đầu vào
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|min:10',
        ]);

        // 2. Lấy địa chỉ email của Admin từ file .env
        $adminEmail = config('mail.admin_support_email', env('ADMIN_SUPPORT_EMAIL'));

        // Kiểm tra xem email admin đã được cấu hình chưa
        if (!$adminEmail) {
            // Có thể ghi log hoặc xử lý lỗi một cách phù hợp
            return back()->with('error', 'Lỗi hệ thống: Email người nhận chưa được cấu hình.');
        }

        // 3. Gửi email
        try {
            Mail::to($adminEmail)->send(new ContactFormMail(
                $validated['name'],
                $validated['email'],
                $validated['message']
            ));
        } catch (\Exception $e) {
            // Xử lý nếu gửi email thất bại
            return back()->with('error', 'Gửi tin nhắn thất bại. Vui lòng thử lại sau.')->withInput();
        }

        // 4. Chuyển hướng về trang liên hệ với thông báo thành công
        return redirect()->route('contact.show')->with('success', 'Cảm ơn bạn! Tin nhắn của bạn đã được gửi thành công.');
    }
}
