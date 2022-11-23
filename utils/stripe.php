<?php

require '../vendor/autoload.php';
// This is your test secret API key.
\Stripe\Stripe::setApiKey('sk_test_51M6YdrDAwqSpvGj6ZNNuTuldjX8xrQKuDAncxQ943FqRohSRiNPnyCW7uXFwtFQfV1d2FfaNEhwHf9XYeS4a2dwe00AXk7lCRo');


header('Content-Type: application/json');

try {
    // retrieve JSON from POST body
    $jsonStr = file_get_contents('php://input');
    $jsonObj = json_decode($jsonStr);

    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => $jsonObj->dinero * 100,
        'currency' => 'mxn',
        // 'automatic_payment_methods' => [
        //     'enabled' => true,
        // ]
    ]);
    $output = [
        'clientSecret' => $paymentIntent->client_secret,
    ];

    echo json_encode($output);
} catch (Error $e) {
    http_response_code(500);
    echo json_encode(['error' => "hola mundo"]);
}