# Semestre4-R401-APIRest – API Équipe Sportive

## 🎯 Objectif

L’objectif de ce projet est de concevoir une API REST pour la gestion d'une équipe sportive.  
Les traitements relatifs à la gestion des joueurs, des matchs, des feuilles de match et des statistiques sont répartis dans différentes API REST.

Deux bases de données distinctes sont mises en œuvre :
- l’une contient les utilisateurs autorisés à accéder (authentification),
- l’autre contient toutes les données sportives (joueurs, matchs, etc.).

## 🔧 Travail réalisé

L’architecture du projet repose sur :
- Une séparation **authentification / traitement métier**,
- Des appels via **JavaScript** côté client,
- Des **requêtes REST** (GET/POST/...),
- Un accès sécurisé par **jeton JWT**.

---

## 🔐 API Authentification

Cette API est responsable de la création des jetons JWT qui doivent être transmis aux API de gestion de l’équipe.  
Elle utilise une base de données dédiée (`auth_db`) contenant les identifiants des utilisateurs autorisés.

| Méthode | Endpoint      | Description                     |
|---------|---------------|---------------------------------|
| POST    | `/login.php`  | Connexion, retour d'un token    |
| GET     | `/profile.php`| Données utilisateur via token   |

---

## ⚽ API de gestion des joueurs

Cette API permet la **création, modification, consultation et suppression** des joueurs de l’équipe.  
Elle requiert un jeton JWT valide.

| Méthode | Endpoint           | Description               |
|---------|--------------------|---------------------------|
| GET     | `/joueurs.php`     | Liste de tous les joueurs |
| GET     | `/joueurs.php?id=1`| Infos d’un joueur         |

---

## 🏟️ API de gestion des matchs

Elle permet de gérer les rencontres sportives : création, consultation, mise à jour, suppression.

| Méthode | Endpoint           | Description            |
|---------|--------------------|------------------------|
| GET     | `/matchs.php`      | Liste des matchs       |
| GET     | `/matchs.php?id=2` | Détails d’un match     |

---

## 📝 API de gestion des feuilles de match

Elle permet d’enregistrer et de consulter les participations aux matchs : qui a joué, à quel poste, avec quelle note, etc.

| Méthode | Endpoint            | Description                   |
|---------|---------------------|-------------------------------|
| GET     | `/feuilles.php`     | Toutes les participations     |
| GET     | `/feuilles.php?id=2`| Feuille pour un match donné   |

---

## 📊 API de demande de statistiques

Bien que les statistiques ne respectent pas pleinement le principe CRUD, elles sont exposées via un endpoint cohérent.

| Méthode | Endpoint     | Description                       |
|---------|--------------|-----------------------------------|
| GET     | `/stats.php` | Regroupe les stats par poste, etc.|

---

## 🧑‍💻 Interface cliente

L’interface client (`index.html`) est déployée séparément.  
Elle permet de :
- Se connecter,
- Consommer les API avec `fetch()` JavaScript,
- Afficher dynamiquement les joueurs, matchs, stats...

---

## 🧩 Déploiement multi-hébergeurs

Le projet est réparti sur un domaine (hébergement AlwaysData) :
- Authentification : `auth_api` 
- API de gestion : `back_end` 
- Interface client : `affichage/` 

---

## 📄 Base de données

- `auth_db.sql` → utilisateurs (login/mdp)
- `sport_db.sql` → joueurs, matchs, participations, 

---

