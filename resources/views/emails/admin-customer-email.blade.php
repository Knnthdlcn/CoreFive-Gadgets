<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message from CoreFive Gadgets</title>
</head>
<body style="margin:0; padding:0; background:#f6f7fb; font-family: Arial, Helvetica, sans-serif;">
    <div style="max-width: 560px; margin: 0 auto; padding: 24px 16px;">
        <div style="background: #06131a; color:#fff; border-radius: 14px 14px 0 0; padding: 18px 20px;">
            <div style="font-weight: 800; font-size: 18px;">CoreFive Gadgets</div>
            <div style="opacity: 0.8; font-size: 13px; margin-top: 4px;">A message from our team</div>
        </div>

        <div style="background:#fff; border-radius: 0 0 14px 14px; padding: 22px 20px; border: 1px solid #e9ecf3; border-top: none;">
            <p style="margin: 0 0 12px 0; color:#1f2d3a; font-size: 14px;">
                Hi {{ $customerName }},
            </p>

            <div style="color:#1f2d3a; font-size: 14px; line-height: 1.7;">
                {!! nl2br(e($messageBody)) !!}
            </div>

            @if(!empty($originalMessage))
                <hr style="border:none; border-top: 1px solid #eef1f6; margin: 18px 0;">
                <p style="margin: 0 0 8px 0; color:#51606d; font-size: 13px; font-weight: 700;">Your original message:</p>
                <div style="color:#51606d; font-size: 13px; line-height: 1.6; background:#f8f9fa; border: 1px solid #eef1f6; border-radius: 12px; padding: 12px 14px; white-space: pre-wrap;">
                    {{ $originalMessage }}
                </div>
            @endif

            <hr style="border:none; border-top: 1px solid #eef1f6; margin: 18px 0;">

            <p style="margin: 0; color:#8794a1; font-size: 12px; line-height: 1.5;">
                If you need more help, reply to this email or contact our support.
            </p>
        </div>

        <div style="text-align:center; color:#9aa6b2; font-size: 12px; margin-top: 14px;">
            © {{ date('Y') }} CoreFive Gadgets
        </div>
    </div>
</body>
</html>
