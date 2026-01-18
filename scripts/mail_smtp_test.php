<?php

// Usage:
//   php scripts/mail_smtp_test.php [to_email]
//
// Boots the Laravel app and sends a single test email using the configured mailer.

use Illuminate\Support\Facades\Mail;

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$to = $argv[1] ?? (string) env('MAIL_USERNAME');
if ($to === '') {
    fwrite(STDERR, "No recipient provided and MAIL_USERNAME is empty.\n");
    exit(2);
}

try {
    Mail::raw('SMTP test email from CoreFive Gadgets (Laravel).', function ($message) use ($to) {
        $message->to($to)->subject('CoreFive SMTP Test');
    });

    echo "MAIL_OK to {$to}\n";
    echo 'Mailer=' . config('mail.default') . ', From=' . config('mail.from.address') . "\n";
    exit(0);
} catch (Throwable $e) {
    fwrite(STDERR, "MAIL_FAIL: " . get_class($e) . " :: " . $e->getMessage() . "\n");
    fwrite(STDERR, 'Mailer=' . config('mail.default') . ', Host=' . (string) config('mail.mailers.smtp.host') . ", Port=" . (string) config('mail.mailers.smtp.port') . "\n");
    exit(1);
}
