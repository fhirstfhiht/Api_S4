<?php
header("Content-Type: application/json");
require_once 'config.php';
require_once 'middleware.php';

$decoded = verifierToken();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $stmt = $conn->query("SELECT * FROM matchs");
        $matchs = $stmt->fetchAll();
        echo json_encode($matchs);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));

        if (!isset($data->Id_Match, $data->Date_Heure_Match, $data->Lieu, $data->Adversaire, $data->Score_Equipe, $data->Score_Adversaire, $data->Victoire, $data->Egalite)) {
            http_response_code(400);
            echo json_encode(["message" => "Champs obligatoires manquants"]);
            exit();
        }

        $stmt = $conn->prepare("INSERT INTO matchs (Id_Match, Date_Heure_Match, Lieu, Adversaire, Score_Equipe, Score_Adversaire, Victoire, Egalite)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data->Id_Match,
            $data->Date_Heure_Match,
            $data->Lieu,
            $data->Adversaire,
            $data->Score_Equipe,
            $data->Score_Adversaire,
            $data->Victoire,
            $data->Egalite
        ]);

        echo json_encode(["message" => "Match ajouté avec succès"]);
        break;

    case 'PUT':
        parse_str($_SERVER['QUERY_STRING'], $params);
        if (!isset($params['Id_Match'])) {
            http_response_code(400);
            echo json_encode(["message" => "Id_Match manquant"]);
            exit();
        }

        $data = json_decode(file_get_contents("php://input"));

        $stmt = $conn->prepare("UPDATE matchs SET Date_Heure_Match = ?, Lieu = ?, Adversaire = ?, Score_Equipe = ?, Score_Adversaire = ?, Victoire = ?, Egalite = ? WHERE Id_Match = ?");
        $stmt->execute([
            $data->Date_Heure_Match,
            $data->Lieu,
            $data->Adversaire,
            $data->Score_Equipe,
            $data->Score_Adversaire,
            $data->Victoire,
            $data->Egalite,
            $params['Id_Match']
        ]);

        echo json_encode(["message" => "Match modifié avec succès"]);
        break;

    case 'DELETE':
        parse_str($_SERVER['QUERY_STRING'], $params);
        if (!isset($params['Id_Match'])) {
            http_response_code(400);
            echo json_encode(["message" => "Id_Match manquant"]);
            exit();
        }

        $stmt = $conn->prepare("DELETE FROM matchs WHERE Id_Match = ?");
        $stmt->execute([$params['Id_Match']]);

        echo json_encode(["message" => "Match supprimé avec succès"]);
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Méthode non autorisée"]);
        break;
}
