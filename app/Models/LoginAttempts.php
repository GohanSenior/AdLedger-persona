<?php

class LoginAttempts
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    // Compte les tentatives récentes pour une IP (fenêtre de 15 minutes)
    public function countRecentAttempts(string $ip): int
    {
        $sql = "SELECT COUNT(*) FROM login_attempts
                WHERE ip = :ip AND attempted_at > NOW() - INTERVAL 15 MINUTE";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['ip' => $ip]);
        return (int) $stmt->fetchColumn();
    }

    // Enregistre une tentative échouée et nettoie les entrées de plus d'un jour
    public function recordAttempt(string $ip): void
    {
        $this->pdo->exec("DELETE FROM login_attempts WHERE attempted_at < NOW() - INTERVAL 1 DAY");

        $sql = "INSERT INTO login_attempts (ip) VALUES (:ip)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['ip' => $ip]);
    }

    // Supprime toutes les tentatives d'une IP (après connexion réussie)
    public function clearAttempts(string $ip): void
    {
        $sql = "DELETE FROM login_attempts WHERE ip = :ip";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['ip' => $ip]);
    }
}
