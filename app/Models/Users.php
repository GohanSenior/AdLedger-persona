<?php

class Users extends Model
{

    // Récupère tous les utilisateurs
    public function getUsers()
    {
        $sql = "SELECT id_user, username, lastname, firstname, email, phone, client, boss, enabled, role, id_company FROM users";
        return $this->pdo->query($sql)->fetchAll();
    }

    // Récupère un utilisateur par son ID
    public function getUserById($id)
    {
        $sql = "SELECT id_user, username, lastname, firstname, email, phone, client, boss, enabled, role, id_company FROM users WHERE id_user = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    // Récupère un utilisateur par son nom d'utilisateur
    public function getUserByUsername($username)
    {
        $sql = "SELECT id_user, username, password, lastname, firstname, email, phone, enabled, role, id_company FROM users WHERE username = :username";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }

    // Récupère un utilisateur par son adresse e-mail
    public function getUserByEmail($email)
    {
        $sql = "SELECT id_user, username, password, lastname, firstname, email, phone, enabled, role, id_company FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    // Crée un nouvel utilisateur
    public function createUser($username, $password, $lastname, $firstname, $email, $phone, $id_company)
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
    public function updateUser($id, $username, $password, $lastname, $firstname, $email, $phone, $id_company)
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
    public function updateUserEnabled($id, $enabled)
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
    public function getUserByUsernameOrEmail($login)
    {
        $sql = "SELECT id_user, username, lastname, firstname, email, phone, enabled, role, id_company FROM users WHERE username = :login OR email = :login";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['login' => $login]);
        return $stmt->fetch();
    }

    // Enregistre un token de réinitialisation de mot de passe
    public function setResetToken($userId, $token)
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
    public function getUserByResetToken($token)
    {
        $sql = "SELECT id_user, firstname, lastname, email FROM users WHERE reset_token = :token AND reset_token_expiry > NOW()";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['token' => $token]);
        return $stmt->fetch();
    }

    // Réinitialise le mot de passe et supprime le token
    public function resetPassword($userId, $newPassword)
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
