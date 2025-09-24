<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $notification->title }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8fafc;
        }
        .container {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 30px;
        }
        .notification-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 24px;
            color: white;
        }
        .notification-title {
            font-size: 20px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 15px;
            text-align: center;
        }
        .notification-message {
            font-size: 16px;
            color: #4a5568;
            margin-bottom: 25px;
            line-height: 1.6;
        }
        .notification-data {
            background-color: #f7fafc;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .notification-data h3 {
            margin: 0 0 10px 0;
            color: #2d3748;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .notification-data p {
            margin: 5px 0;
            color: #4a5568;
            font-size: 14px;
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            margin: 0;
            color: #718096;
            font-size: 14px;
        }
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
        }
        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>📚 BookHaven</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="notification-icon">
                @switch($notification->type)
                    @case('loan_due')
                        ⏰
                        @break
                    @case('loan_overdue')
                        ⚠️
                        @break
                    @case('book_returned')
                        ✅
                        @break
                    @case('new_discussion')
                        💬
                        @break
                    @case('new_reply')
                        🔄
                        @break
                    @case('reading_group_invite')
                        👥
                        @break
                    @case('badge_earned')
                        🏆
                        @break
                    @case('level_up')
                        ⭐
                        @break
                    @default
                        🔔
                @endswitch
            </div>

            <h2 class="notification-title">{{ $notification->title }}</h2>
            
            <div class="notification-message">
                {{ $notification->message }}
            </div>

            @if($notification->data)
                <div class="notification-data">
                    <h3>Chi tiết thông báo</h3>
                    @foreach($notification->data as $key => $value)
                        @if($key === 'book_id' && $value)
                            <p><strong>Sách:</strong> {{ \App\Models\Book::find($value)->title ?? 'N/A' }}</p>
                        @elseif($key === 'due_date' && $value)
                            <p><strong>Hạn trả:</strong> {{ \Carbon\Carbon::parse($value)->format('d/m/Y') }}</p>
                        @elseif($key === 'days_overdue' && $value)
                            <p><strong>Số ngày quá hạn:</strong> {{ $value }} ngày</p>
                        @elseif($key === 'experience_points' && $value)
                            <p><strong>Điểm kinh nghiệm:</strong> +{{ $value }} XP</p>
                        @elseif($key === 'new_level' && $value)
                            <p><strong>Cấp độ mới:</strong> Level {{ $value }}</p>
                        @elseif($key === 'badge_name' && $value)
                            <p><strong>Huy hiệu:</strong> {{ $value }}</p>
                        @endif
                    @endforeach
                </div>
            @endif

            <div style="text-align: center;">
                <a href="{{ route('home') }}" class="button">Truy cập BookHaven</a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>
                Thông báo này được gửi từ <strong>BookHaven</strong><br>
                Bạn có thể <a href="{{ route('home') }}">truy cập website</a> để xem thêm chi tiết
            </p>
            <p style="margin-top: 15px; font-size: 12px; color: #a0aec0;">
                Nếu bạn không muốn nhận thông báo này, vui lòng cập nhật cài đặt thông báo trong tài khoản của bạn.
            </p>
        </div>
    </div>
</body>
</html>
