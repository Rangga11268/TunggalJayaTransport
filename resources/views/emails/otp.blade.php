<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-top: 40px;">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2 style="color: #BA1A1A;">TUJAGO</h2>
            <p style="color: #666;">Tunggal Jaya Transport</p>
        </div>
        
        <div style="text-align: center;">
            <p style="font-size: 16px; color: #333;">Berikut adalah kode verifikasi (OTP) Anda:</p>
            
            <div style="background-color: #f8f9fa; padding: 15px; border-radius: 8px; margin: 20px 0; display: inline-block;">
                <h1 style="margin: 0; color: #BA1A1A; letter-spacing: 5px; font-size: 32px;">{{ $otp }}</h1>
            </div>
            
            <p style="font-size: 14px; color: #666;">Kode ini akan kedaluwarsa dalam 10 menit.</p>
            <p style="font-size: 14px; color: #666;">Jangan berikan kode ini kepada siapa pun.</p>
        </div>
        
        <div style="text-align: center; margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
            <p style="font-size: 12px; color: #999;">&copy; {{ date('Y') }} TUJAGO. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
