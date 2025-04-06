<?php
require 'jwt.php';

header("Content-Type: application/json");

$headers = getallheaders();
$authHeader = $headers['Authorization'] ?? '';

if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
    echo json_encode(["message" => "Token manquant"]);
    exit;
}

$token = $matches[1];
$decoded = verifyJWT($token);

if (!$decoded) {
    echo json_encode(["message" => "Token invalide"]);
    exit;
}

echo json_encode($decoded["user"]);
?>
