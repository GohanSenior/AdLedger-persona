<?php

class Users extends Model
{

    // Récupère tous les utilisateurs
    public function getUsers(): array
    {
        $sql = "SELECT id_user, username, lastname, firstname, email, phone, client, boss, enabled, role, id_company FROM users";
        return $this->pdo->query($sql)->fetchAll();
    }

    // Récupère un utilisateur par son ID
    public function getUserById(int $id): array|false
    {
        $sql = "SELECT id_user, username, lastname, firstname, email, phone, client, boss, enabled, role, id_company FROM users WHERE id_user = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    // Récupère un utilisateur par son nom d'utilisateur
    public function getUserByUsername(string $username): array|false
    {
        $sql = "SELECT id_user, username, password, lastname, firstname, email, phone, enabled, role, id_company FROM users WHERE username = :username";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }

    // Récupère un utilisateur par son adresse e-mail
    public function getUserByEmail(string $email): array|false
    {
        $sql = "SELECT id_user, username, password, lastname, firstname, email, phone, enabled, role, id_company FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    // Crée un nouvel utilisateur
    public function createUser(string $username, string $password, string $lastname, string $firstname, string $email, ?string $phone, int $id_company): bool
    {
        $sql = "INSERT INTO users (username, password, lastname, firstname, email, phone, id_company) 
                VALUES (:username, :password, :lastname, :firstname, :email, :phone, :id_company)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'username' => $username,
            'password' => $password,
            'lastname' => $lastname,
            'firstname' => $firstname,
            'email' => $email,
            'phone' => $phone,
            'id_company' => $id_company
        ]);
    }

    // Met à jour un utilisateur existant
    public function updateUser(int $id, string $username, string $password, string $lastname, string $firstname, string $email, ?string $phone, int $id_company): bool
    {
        $sql = "UPDATE users SET 
                    username = :username, 
                    password = :password, 
                    lastname = :lastname, 
                    firstname = :firstname, 
                    email = :email, 
                    phone = :phone, 
                    id_company = :id_company 
                WHERE id_user = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'username' => $username,
            'password' => $password,
            'lastname' => $lastname,
            'firstname' => $firstname,
            'email' => $email,
            'phone' => $phone,
            'id_company' => $id_company
        ]);
    }

    // Met à jour le statut activé/désactivé d'un utilisateur
    public function updateUserEnabled(int $id, int $enabled): bool
    {
        $sql = "UPDATE users SET 
                    enabled = :enabled
                WHERE id_user = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'enabled' => $enabled
        ]);
    }

    // Récupère un utilisateur par son nom d'utilisateur ou email
    public function getUserByUsernameOrEmail(string $login): array|false
    {
        $sql = "SELECT id_user, username, lastname, firstname, email, phone, enabled, role, id_company FROM users WHERE username = :login OR email = :login";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['login' => $login]);
        return $stmt->fetch();
    }

    // Enregistre un token de réinitialisation de mot de passe
    public function setResetToken(int $userId, string $token): bool
    {
        $sql = "UPDATE users SET 
                    reset_token = :token,
                    reset_token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR)
                WHERE id_user = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id' => $userId,
            'token' => $token
        ]);
    }

    // Récupère un utilisateur par son token de réinitialisation
    public function getUserByResetToken(string $token): array|false
    {
        $sql = "SELECT id_user, firstname, lastname, email FROM users WHERE reset_token = :token AND reset_token_expiry > NOW()";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['token' => $token]);
        return $stmt->fetch();
    }

    // Réinitialise le mot de passe et supprime le token
    public function resetPassword(int $userId, string $newPassword): bool
    {
        $sql = "UPDATE users SET 
                    password = :password,
                    reset_token = NULL,
                    reset_token_expiry = NULL
                WHERE id_user = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id' => $userId,
            'password' => $newPassword
        ]);
    }
    
}
