<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        .reset-button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #499fb6;
            color: white !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
            margin: 25px 0;
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 20px 0;
        }
    </style>
</head>

<body style="font-family: 'Poppins', sans-serif; margin: 0; padding: 0; background-color: #f5f5f5;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div class="logo">
            <img src="https://missionaryfamiliesofchrist.org/wp-content/uploads/2019/10/MFC-Revised-Logo-1024x1024.jpg"
                alt="MFC Logo" height="60">
            <span style="font-size: 24px; font-weight: 600; color: #333;">MFC Portal</span>
        </div>

        <div style="background-color: white; border-radius: 10px; padding: 30px; text-align: center;">
            <h2 style="color: #333; margin-top: 0;">Reset Your Password</h2>

            {{-- <p style="color: #555; line-height: 1.6;">Hello {{ $user->name }},</p> --}}

            <p style="color: #555; line-height: 1.6;">We received a request to reset your password. Click the button
                below to create a new password:</p>

            <a href="{{ $resetLink }}" class="reset-button">Reset Password</a>

            <p style="color: #777; font-size: 13px; line-height: 1.5;">This link will expire in 24 hours. If you didn't
                request a password reset, please ignore this email or contact support.</p>
        </div>

        <div style="text-align: center; margin-top: 30px; color: #777; font-size: 12px;">
            <p>© 2025 MFC Portal. All rights reserved.</p>
            <p>123 Sample St., Lorem City, NCR</p>
            <p>Need help? <a href="mailto:mfcportalhelp@gmail.com" style="color: #499fb6;">Contact Support</a></p>
        </div>
    </div>
</body>

</html>
