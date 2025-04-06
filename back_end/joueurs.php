<?php
header("Content-Type: application/json");
require_once 'config.php';
require_once 'middleware.php';

$decoded = verifierToken();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $stmt = $conn->query("SELECT * FROM joueurs");
        $joueurs = $stmt->fetchAll();
        echo json_encode($joueurs);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));

        if (!isset(
            $data->Numero_Licence,
            $data->Nom,
            $data->Prenom,
            $data->Date_De_Naissance,
            $data->Taille,
            $data->Poids,
            $data->Poste_Joueur,
            $data->Id_Statut
        )) {
            http_response_code(400);
            echo json_encode(["message" => "Champs obligatoires manquants"]);
            exit();
        }

        $stmt = $conn->prepare("INSERT INTO joueurs (Numero_Licence, Nom, Prenom, Date_De_Naissance, Taille, Poids, Commentaires, Poste_Joueur, Id_Statut)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data->Numero_Licence,
            $data->Nom,
            $data->Prenom,
            $data->Date_De_Naissance,
            $data->Taille,
            $data->Poids,
            $data->Commentaires ?? null,
            $data->Poste_Joueur,
            $data->Id_Statut
        ]);

        echo json_encode(["message" => "Joueur ajouté avec succès"]);
        break;

    case 'PUT':
        parse_str($_SERVER['QUERY_STRING'], $params);
        if (!isset($params['Numero_Licence'])) {
            http_response_code(400);
            echo json_encode(["message" => "Numero_Licence manquant"]);
            exit();
        }

        $data = json_decode(file_get_contents("php://input"));

        $stmt = $conn->prepare("UPDATE joueurs SET Nom = ?, Prenom = ?, Date_De_Naissance = ?, Taille = ?, Poids = ?, Commentaires = ?, Poste_Joueur = ?, Id_Statut = ? WHERE Numero_Licence = ?");
        $stmt->execute([
            $data->Nom,
            $data->Prenom,
            $data->Date_De_Naissance,
            $data->Taille,
            $data->Poids,
            $data->Commentaires ?? null,
            $data->Poste_Joueur,
            $data->Id_Statut,
            $params['Numero_Licence']
        ]);

        echo json_encode(["message" => "Joueur modifié avec succès"]);
        break;

    case 'DELETE':
        parse_str($_SERVER['QUERY_STRING'], $params);
        if (!isset($params['Numero_Licence'])) {
            http_response_code(400);
            echo json_encode(["message" => "Numero_Licence manquant"]);
            exit();
        }

        $stmt = $conn->prepare("DELETE FROM joueurs WHERE Numero_Licence = ?");
        $stmt->execute([$params['Numero_Licence']]);

        echo json_encode(["message" => "Joueur supprimé avec succès"]);
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Méthode non autorisée"]);
        break;
}
