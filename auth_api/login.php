<?php
require 'config.php';
require 'jwt.php';

header("Content-Type: application/json");

// Debug 
$data_raw = file_get_contents("php://input");


$data = json_decode($data_raw, true);


if (!isset($data['login'], $data['password'])) {
    echo json_encode(["message" => "Champs obligatoires manquants", "data_received" => $data]);
    exit;
}

$login = $data['login'];
$password = $data['password'];

$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE login = ?");
$stmt->execute([$login]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    $payload = [
        "iss" => "auth_api",
        "iat" => time(),
        "exp" => time() + 3600,  // Expiration 1 heure
        "user" => ["id" => $user['id'], "login" => $user['login'], "role" => $user['role']]
    ];

    echo json_encode(["token" => generateJWT($payload)]);
} else {
    echo json_encode(["message" => "Id incorrects"]);
}
?>
