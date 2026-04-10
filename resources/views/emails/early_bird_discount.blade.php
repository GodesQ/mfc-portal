<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Early Bird Discount</title>
</head>

<body style="margin: 0; padding: 24px; background: #f4f7fb; font-family: Arial, sans-serif; color: #223047;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
                    style="max-width: 600px; background: #ffffff; border-radius: 16px; overflow: hidden;">
                    <tr>
                        <td style="background: #1f3c88; color: #ffffff; padding: 24px 32px;">
                            <h1 style="margin: 0; font-size: 28px;">Early Bird secured</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 32px;">
                            <p style="margin: 0 0 16px;">Hi {{ $recipientName }},</p>
                            <p style="margin: 0 0 16px; line-height: 1.6;">
                                Congratulations and thank you for registering for <strong>{{ $eventTitle }}</strong>.
                                Your booking qualified for the Early Bird discount.
                            </p>
                            <p style="margin: 0 0 20px; line-height: 1.6;">
                                You saved <strong>PHP {{ number_format($discount, 2) }}</strong> on your registration.
                            </p>
                            <p style="margin: 0; line-height: 1.6;">
                                We appreciate your early commitment and look forward to seeing you at the event.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
