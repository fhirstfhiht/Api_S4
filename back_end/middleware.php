<?php
require_once 'jwt.php';

function verifierToken() {
    $headers = apache_request_headers();

    if (!isset($headers['Authorization'])) {
        http_response_code(401);
        echo json_encode(["message" => "Token manquant"]);
        exit();
    }

    $authHeader = $headers['Authorization'];
    if (!str_starts_with($authHeader, 'Bearer ')) {
        http_response_code(401);
        echo json_encode(["message" => "Format du token invalide"]);
        exit();
    }

    $token = substr($authHeader, 7); // Enlève "Bearer "

    $decoded = decodeJWT($token);

    if (!$decoded) {
        http_response_code(401);
        echo json_encode(["message" => "Token invalide ou expiré"]);
        exit();
    }

    return $decoded;
}
