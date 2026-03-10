<?php

class Criteria_types extends Model
{
    // Récupère tous les types de critères
    public function getCriteriaTypes(): array
    {
        $sql = "SELECT * FROM criteria_types";
        return $this->pdo->query($sql)->fetchAll();
    }

    // Récupère un type de critère par son ID
    public function getCriteriaTypeById(int $id_criteria_type): array|false
    {
        $sql = "SELECT * FROM criteria_types WHERE id_criteria_type = :id_criteria_type";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_criteria_type' => $id_criteria_type]);
        return $stmt->fetch();
    }

    // Crée un nouveau type de critère
    public function createCriteriaType(string $criteria_type_name): bool
    {
        $sql = "INSERT INTO criteria_types (criteria_type_name) 
                VALUES (:criteria_type_name)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'criteria_type_name' => $criteria_type_name,
        ]);
    }

    // Met à jour un type de critère existant
    public function updateCriteriaType(int $id_criteria_type, string $criteria_type_name): bool
    {
        $sql = "UPDATE criteria_types SET 
                    criteria_type_name = :criteria_type_name 
                WHERE id_criteria_type = :id_criteria_type";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id_criteria_type' => $id_criteria_type,
            'criteria_type_name' => $criteria_type_name,
        ]);
    }
}