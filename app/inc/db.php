<?php
/**
 * db.php — Connexion à la base de données + helpers communs.
 *
 * Inclus par toutes les pages et tous les endpoints API.
 * Utilise PDO (requêtes préparées) pour se prémunir des injections SQL.
 */

declare(strict_types=1);

// ----- Paramètres de connexion -----
// Valeurs alignées sur docker-compose.yml (service "db").
const DB_HOST = 'db';
const DB_NAME = 'cv_platform';
const DB_USER = 'cv_user';
const DB_PASS = 'password123';

/**
 * Retourne une instance PDO unique (singleton).
 */
function db(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   // exceptions sur erreur SQL
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,         // tableaux associatifs
                PDO::ATTR_EMULATE_PREPARES   => false,                   // vraies requêtes préparées
            ]);
        } catch (PDOException $e) {
            // En contexte API on renvoie du JSON, sinon un message simple.
            http_response_code(500);
            if (str_contains($_SERVER['REQUEST_URI'] ?? '', '/api/')) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Connexion BDD impossible']);
            } else {
                echo 'Erreur : connexion à la base de données impossible.';
            }
            exit;
        }
    }

    return $pdo;
}

// ----- Session -----
// Démarrée une seule fois, partagée par les pages et l'API.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// =====================================================
// Helpers d'authentification / session
// =====================================================

/** L'utilisateur courant est-il connecté ? */
function est_connecte(): bool
{
    return isset($_SESSION['user_id'], $_SESSION['user_type']);
}

/** Type de l'utilisateur courant : 'etudiant' | 'entreprise' | 'admin' | null */
function type_utilisateur(): ?string
{
    return $_SESSION['user_type'] ?? null;
}

/** Identifiant de l'utilisateur courant, ou null. */
function id_utilisateur(): ?int
{
    return isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
}

/**
 * Exige une connexion d'un type donné, sinon redirige (pages) ou
 * renvoie 401 (API). $types peut être une chaîne ou un tableau.
 */
function exiger_connexion(string|array $types = [], bool $estApi = false): void
{
    $types = (array) $types;

    $ok = est_connecte() && ($types === [] || in_array(type_utilisateur(), $types, true));
    if ($ok) {
        return;
    }

    if ($estApi) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Authentification requise']);
    } else {
        header('Location: /pages/connexion.php');
    }
    exit;
}

// =====================================================
// Helpers de réponse JSON (pour les endpoints /api)
// =====================================================

/** Envoie une réponse JSON et termine le script. */
function repondre_json(array $data, int $code = 200): never
{
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

/** Raccourci erreur JSON. */
function erreur_json(string $message, int $code = 400): never
{
    repondre_json(['error' => $message], $code);
}

/** Lit et décode le corps JSON d'une requête (POST). */
function corps_json(): array
{
    $data = json_decode(file_get_contents('php://input') ?: '', true);
    return is_array($data) ? $data : [];
}

/** Échappe une chaîne pour affichage HTML (anti-XSS). */
function e(?string $valeur): string
{
    return htmlspecialchars($valeur ?? '', ENT_QUOTES, 'UTF-8');
}
