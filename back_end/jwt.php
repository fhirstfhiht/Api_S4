<?php

$secretKey = "cle_secrete"; 

function generateJWT($payload) {
    global $secretKey;

    $header = ['alg' => 'HS256', 'typ' => 'JWT'];

    $header_encoded = rtrim(strtr(base64_encode(json_encode($header)), '+/', '-_'), '=');
    $payload_encoded = rtrim(strtr(base64_encode(json_encode($payload)), '+/', '-_'), '=');

    $signature = hash_hmac('sha256', "$header_encoded.$payload_encoded", $secretKey, true);
    $signature_encoded = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

    return "$header_encoded.$payload_encoded.$signature_encoded";
}

function decodeJWT($jwt) {
    global $secretKey;

    $parts = explode('.', $jwt);
    if (count($parts) !== 3) return false;

    [$header64, $payload64, $signature64] = $parts;
    $signature = base64_encode(hash_hmac('sha256', "$header64.$payload64", $secretKey, true));

    $valid = rtrim(strtr($signature, '+/', '-_'), '=');

    if (!hash_equals($valid, $signature64)) {
        return false;
    }

    $payload = json_decode(base64_decode(strtr($payload64, '-_', '+/')), true);
    
    // Expiration
    if (isset($payload['exp']) && time() > $payload['exp']) {
        return false;
    }

    return (object) $payload;
}
