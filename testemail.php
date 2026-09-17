<?php

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

function requiredMailEnv(string $name): string
{
    $value = getenv($name);

    if ($value === false || $value === '') {
        throw new RuntimeException("Missing required environment variable: {$name}");
    }

    return $value;
}

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = requiredMailEnv('SMTP_HOST');
    $mail->SMTPAuth = true;
    $mail->Username = requiredMailEnv('SMTP_USERNAME');
    $mail->Password = requiredMailEnv('SMTP_PASSWORD');
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = (int) (getenv('SMTP_PORT') ?: 587);

    $fromAddress = requiredMailEnv('SMTP_FROM_ADDRESS');
    $mail->setFrom($fromAddress, getenv('SMTP_FROM_NAME') ?: 'TrackSmart');
    $mail->addAddress(requiredMailEnv('SMTP_TO_ADDRESS'));
    $mail->Subject = 'TrackSmart SMTP test';
    $mail->Body = 'This is a test message from TrackSmart.';
    $mail->send();

    echo 'Message sent successfully.';
} catch (Exception $e) {
    http_response_code(500);
    echo 'Message could not be sent. Check the SMTP environment settings.';
}
