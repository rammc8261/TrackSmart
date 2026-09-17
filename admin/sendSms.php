<?php

function sendSMS($mobile, $message)
{
    $apiUrl = getenv('SMS_API_URL');
    $apiKey = getenv('SMS_API_KEY');
    $senderId = getenv('SMS_SENDER_ID');

    if (!$apiUrl || !$apiKey || !$senderId) {
        error_log('SMS was not sent: SMS_API_URL, SMS_API_KEY, and SMS_SENDER_ID must be configured.');
        return false;
    }

    $data = [
        'sender_id' => $senderId,
        'message' => $message,
        'language' => getenv('SMS_LANGUAGE') ?: 'english',
        'route' => getenv('SMS_ROUTE') ?: 'q',
        'numbers' => $mobile,
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: ' . $apiKey,
        'Content-Type: application/json',
        'Accept: application/json',
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}
