<!DOCTYPE html>
<html>
<head>
    <title>Tin nhắn liên hệ mới</title>
</head>
<body style="font-family: sans-serif; line-height: 1.6;">
    <h2>Bạn có một tin nhắn liên hệ mới từ trang web BookHaven</h2>
    <hr>
    <p><strong>Từ:</strong> {{ $name }}</p>
    <p><strong>Email:</strong> <a href="mailto:{{ $email }}">{{ $email }}</a></p>
    <p><strong>Nội dung:</strong></p>
    <div style="background-color: #f4f4f4; border: 1px solid #ddd; padding: 15px;">
        {!! nl2br(e($messageContent)) !!}
    </div>
    <hr>
    <p><small>Email này được gửi tự động từ form liên hệ của trang web.</small></p>
</body>
</html>