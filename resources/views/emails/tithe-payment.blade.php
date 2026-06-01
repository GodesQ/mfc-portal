@php
    $payerName = $transaction->payer_name ?: 'there';
    $referenceNumber = $transaction->reference_code ?: $transaction->transaction_code;
    $paymentAmount = '&#8369;' . number_format((float) $transaction->total_amount, 2);
    $paymentMethod = ucfirst(str_replace('_', ' ', $transaction->payment_mode ?: 'Maya'));
@endphp

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thank You for Your Tithe</title>
</head>

<body style="margin: 0; padding: 0; background: #f1f6fb; color: #1f2937; font-family: Arial, Helvetica, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
        style="border-collapse: collapse; background: #f1f6fb;">
        <tr>
            <td align="center" style="padding: 70px 16px 52px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                    style="border-collapse: collapse; width: 100%; max-width: 640px;">
                    <tr>
                        <td align="center" style="padding: 0 0 44px;">
                            <img src="{{ $message->embed($logoPath) }}" alt="MFC Portal" width="112"
                                style="display: block; border: 0; border-radius: 999px; max-width: 112px;">
                        </td>
                    </tr>
                    <tr>
                        <td style="background: #ffffff; padding: 70px 44px 54px;">
                            <p style="margin: 0 0 10px; color: #7b8794; font-size: 24px; line-height: 1.35;">
                                Hi {{ $payerName }},
                            </p>

                            <h1
                                style="margin: 0 0 38px; color: #1f2937; font-size: 34px; font-weight: 700; line-height: 1.25;">
                                Thank You for Your Tithe
                            </h1>

                            <p style="margin: 0 0 24px; color: #7b8794; font-size: 22px; line-height: 1.55;">
                                Thank you so much for your tithe donation to MFC Portal; your generosity means a lot to
                                us and is a big help in supporting our community.
                            </p>

                            <p style="margin: 0 0 24px; color: #7b8794; font-size: 22px; line-height: 1.55;">
                                We have received your payment with Reference Number: {{ $referenceNumber }}, Amount:
                                {!! $paymentAmount !!}, through {{ $paymentMethod }}.
                            </p>

                            <p style="margin: 0; color: #7b8794; font-size: 22px; line-height: 1.55;">
                                Your support helps us continue serving and growing together, and we truly appreciate
                                your kindness and faithfulness.
                            </p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                                style="border-collapse: collapse; margin-top: 58px;">
                                <tr>
                                    <td style="border-top: 1px solid #e5e9ef; padding-top: 18px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                                            style="border-collapse: collapse;">
                                            <tr>
                                                <td align="left"
                                                    style="color: #7b8794; font-size: 16px; line-height: 1.5;">
                                                    Sent by<br>
                                                    <span style="color: #ce5242;">MFC Portal</span>
                                                </td>
                                                <td align="right"
                                                    style="color: #7b8794; font-size: 16px; line-height: 1.5;">
                                                    Missionary Families of Christ
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="color: #a0aab6; font-size: 13px; line-height: 1.6; padding: 34px 12px 0;">
                            You are receiving this email because a tithe payment was completed through MFC Portal.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
