<?php
header("Content-Type: application/json");
require_once 'config.php';
require_once 'middleware.php';

$decoded = verifierToken();

// Statistiques disponibles
$stats = [];

// Moyenne de notes par joueur
$stmt = $conn->query("SELECT j.Numero_Licence, j.Nom, j.Prenom, ROUND(AVG(p.Note), 2) AS Moyenne_Note
                      FROM participer p
                      JOIN joueurs j ON j.Numero_Licence = p.Numero_Licence
                      GROUP BY j.Numero_Licence, j.Nom, j.Prenom
                      ORDER BY Moyenne_Note DESC");
$stats['moyenne_notes'] = $stmt->fetchAll();

// Nombre de titularisations par joueur
$stmt = $conn->query("SELECT j.Numero_Licence, j.Nom, j.Prenom, COUNT(*) AS Nombre_Titularisations
                      FROM participer p
                      JOIN joueurs j ON j.Numero_Licence = p.Numero_Licence
                      WHERE p.Statut_Participation = 'Titulaire'
                      GROUP BY j.Numero_Licence, j.Nom, j.Prenom
                      ORDER BY Nombre_Titularisations DESC");
$stats['titularisations'] = $stmt->fetchAll();

// Nombre total de participations par joueur
$stmt = $conn->query("SELECT j.Numero_Licence, j.Nom, j.Prenom, COUNT(*) AS Nombre_Participations
                      FROM participer p
                      JOIN joueurs j ON j.Numero_Licence = p.Numero_Licence
                      GROUP BY j.Numero_Licence, j.Nom, j.Prenom
                      ORDER BY Nombre_Participations DESC");
$stats['participations'] = $stmt->fetchAll();

// Répartition des statuts (Titulaire, Remplaçant, etc.)
$stmt = $conn->query("SELECT Statut_Participation, COUNT(*) AS Occurrences
                      FROM participer
                      GROUP BY Statut_Participation");
$stats['statuts'] = $stmt->fetchAll();

// Renvoi du résultat
echo json_encode($stats);
