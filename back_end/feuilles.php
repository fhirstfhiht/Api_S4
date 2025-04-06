<?php
header("Content-Type: application/json");
require_once 'config.php';
require_once 'middleware.php';

$decoded = verifierToken();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (!isset($_GET['Id_Match'])) {
            http_response_code(400);
            echo json_encode(["message" => "Id_Match manquant"]);
            exit();
        }

        $stmt = $conn->prepare("SELECT p.Numero_Licence, j.Nom, j.Prenom, p.Statut_Participation, p.Poste_Match, p.Note
                                 FROM participer p
                                 JOIN joueurs j ON p.Numero_Licence = j.Numero_Licence
                                 WHERE p.Id_Match = ?");
        $stmt->execute([$_GET['Id_Match']]);
        $joueurs = $stmt->fetchAll();
        echo json_encode($joueurs);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));

        if (!isset($data->Numero_Licence, $data->Id_Match, $data->Statut_Participation, $data->Poste_Match, $data->Note)) {
            http_response_code(400);
            echo json_encode(["message" => "Champs obligatoires manquants"]);
            exit();
        }

        $stmt = $conn->prepare("INSERT INTO participer (Numero_Licence, Id_Match, Statut_Participation, Poste_Match, Note)
                                VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data->Numero_Licence,
            $data->Id_Match,
            $data->Statut_Participation,
            $data->Poste_Match,
            $data->Note
        ]);

        if (!is_numeric($data->Note) || $data->Note < 0 || $data->Note > 5) {
            http_response_code(400);
            echo json_encode(["message" => "Note invalide. Elle doit être entre 0 et 5."]);
            exit();
        }        

        echo json_encode(["message" => "Participation ajoutée avec succès"]);
        break;

    case 'PUT':
        parse_str($_SERVER['QUERY_STRING'], $params);
        if (!isset($params['Numero_Licence'], $params['Id_Match'])) {
            http_response_code(400);
            echo json_encode(["message" => "Numero_Licence ou Id_Match manquant"]);
            exit();
        }

        $data = json_decode(file_get_contents("php://input"));

        $stmt = $conn->prepare("UPDATE participer SET Statut_Participation = ?, Poste_Match = ?, Note = ?
                                WHERE Numero_Licence = ? AND Id_Match = ?");
        $stmt->execute([
            $data->Statut_Participation,
            $data->Poste_Match,
            $data->Note,
            $params['Numero_Licence'],
            $params['Id_Match']
        ]);

        if (!is_numeric($data->Note) || $data->Note < 0 || $data->Note > 5) {
            http_response_code(400);
            echo json_encode(["message" => "Note invalide. Elle doit être entre 0 et 5."]);
            exit();
        }        

        echo json_encode(["message" => "Participation modifiée avec succès"]);
        break;

    case 'DELETE':
        if (!isset($_GET['Numero_Licence'], $_GET['Id_Match'])) {
            http_response_code(400);
            echo json_encode(["message" => "Numero_Licence ou Id_Match manquant"]);
            exit();
        }

        $stmt = $conn->prepare("DELETE FROM participer WHERE Numero_Licence = ? AND Id_Match = ?");
        $stmt->execute([$_GET['Numero_Licence'], $_GET['Id_Match']]);

        echo json_encode(["message" => "Participation supprimée"]);
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Méthode non autorisée"]);
        break;
}