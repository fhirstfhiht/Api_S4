<?php
$secret_key = "cle_secrete";

function generateJWT($payload) {
    global $secret_key;
    $header = json_encode(["alg" => "HS256", "typ" => "JWT"]);
    $payload = json_encode($payload);

    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

    $signature = hash_hmac('sha256', "$base64UrlHeader.$base64UrlPayload", $secret_key, true);
    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

    return "$base64UrlHeader.$base64UrlPayload.$base64UrlSignature";
}

function verifyJWT($token) {
    global $secret_key;
    $parts = explode('.', $token);

    if (count($parts) !== 3) return false;

    list($header, $payload, $signature) = $parts;

    $expected_signature = hash_hmac('sha256', "$header.$payload", $secret_key, true);
    $expected_signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($expected_signature));

    $decoded = json_decode(base64_decode($payload), true);

    // Vérification de l'expiration du jeton
    if (isset($decoded['exp']) && $decoded['exp'] < time()) {
        return false;  
    }

    return hash_equals($expected_signature, $signature) ? $decoded : false;
}
?>
