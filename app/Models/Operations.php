<?php

class Operations extends Model
{
    // Récupère toutes les opérations
    public function getOperations(): array
    {
        $sql = "SELECT * FROM operations";
        return $this->pdo->query($sql)->fetchAll();
    }

    // Récupère une opération par son ID
    public function getOperationById(int $id_operation): array|false
    {
        $sql = "SELECT * FROM operations WHERE id_operation = :id_operation";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_operation' => $id_operation]);
        return $stmt->fetch();
    }

    // Récupère une opération par son nom
    public function getOperationByName(string $operation_name): array|false
    {
        $sql = "SELECT * FROM operations WHERE operation_name = :operation_name LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['operation_name' => $operation_name]);
        return $stmt->fetch();
    }

    // Récupère une opération par son nom pour un utilisateur donné
    public function getOperationByNameAndUserId(string $operation_name, int $id_user): array|false
    {
        $sql = "SELECT DISTINCT op.* FROM operations op
                INNER JOIN personas p ON op.id_operation = p.id_operation
                WHERE op.operation_name = :operation_name AND p.id_user = :id_user
                LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['operation_name' => $operation_name, 'id_user' => $id_user]);
        return $stmt->fetch();
    }

    // Récupère les opérations associées à un utilisateur
    public function getOperationsByUserId(int $id_user): array
    {
        $sql = "SELECT DISTINCT p.* FROM operations p
                INNER JOIN personas pers ON p.id_operation = pers.id_operation
                WHERE pers.id_user = :id_user";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_user' => $id_user]);
        return $stmt->fetchAll();
    }

    // Crée une nouvelle opération
    public function createOperation(string $operation_name): bool
    {
        $sql = "INSERT INTO operations (operation_name) VALUES (:operation_name)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['operation_name' => $operation_name]);
    }

    // Met à jour une opération existante
    public function updateOperation(int $id_operation, string $operation_name): bool
    {
        $sql = "UPDATE operations SET operation_name = :operation_name WHERE id_operation = :id_operation";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id_operation' => $id_operation,
            'operation_name' => $operation_name
        ]);
    }

    // Supprime une opération
    public function deleteOperation(int $id_operation): bool
    {
        $sql = "DELETE FROM operations WHERE id_operation = :id_operation";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id_operation' => $id_operation]);
    }

    // Vérifie qu'une opération appartient à un utilisateur (via ses personas)
    public function isOperationOwnedByUser(int $id_operation, int $id_user): bool
    {
        $sql = "SELECT 1 FROM personas
                WHERE id_operation = :id_operation AND id_user = :id_user
                LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_operation' => $id_operation, 'id_user' => $id_user]);
        return (bool) $stmt->fetch();
    }
}