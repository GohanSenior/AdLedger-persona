<?php

class Criteria extends Model
{
    // Récupère tous les critères
    public function getCriterias()
    {
        $sql = "SELECT * FROM criteria";
        return $this->pdo->query($sql)->fetchAll();
    }

    // Récupère les critères par type
    public function getCriteriasByType($id_criteria_type)
    {
        $sql = "SELECT * FROM criteria WHERE id_criteria_type = :id_criteria_type";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_criteria_type' => $id_criteria_type]);
        return $stmt->fetchAll();
    }

    // Récupère un critère par son ID
    public function getCriteriaById($id_criterion)
    {
        $sql = "SELECT * FROM criteria WHERE id_criterion = :id_criterion";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_criterion' => $id_criterion]);
        return $stmt->fetch();
    }

    // Crée un nouveau critère
    public function createCriteria($criterion_description, $id_criteria_type)
    {
        $sql = "INSERT INTO criteria (criterion_description, id_criteria_type) 
                VALUES (:criterion_description, :id_criteria_type)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'criterion_description' => $criterion_description,
            'id_criteria_type' => $id_criteria_type
        ]);
    }

    // Récupère un critère par sa description et son type
    public function getCriteriaByDescriptionAndType($criterion_description, $id_criteria_type)
    {
        $sql = "SELECT * FROM criteria WHERE criterion_description = :criterion_description AND id_criteria_type = :id_criteria_type";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'criterion_description' => $criterion_description,
            'id_criteria_type' => $id_criteria_type
        ]);
        return $stmt->fetch();
    }

    // Recherche des critères par type et description (autocomplétion)
    public function searchCriteriaByType($query, $id_criteria_type)
    {
        $sql = "SELECT * FROM criteria
                WHERE id_criteria_type = :id_criteria_type
                AND criterion_description LIKE :query
                ORDER BY criterion_description ASC
                LIMIT 10";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id_criteria_type' => $id_criteria_type,
            'query' => '%' . $query . '%'
        ]);
        return $stmt->fetchAll();
    }

    // Met à jour un critère existant
    public function updateCriteria($id_criterion, $criterion_description, $id_criteria_type)
    {
        $sql = "UPDATE criteria SET 
                    criterion_description = :criterion_description, 
                    id_criteria_type = :id_criteria_type 
                WHERE id_criterion = :id_criterion";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id_criterion' => $id_criterion,
            'criterion_description' => $criterion_description,
            'id_criteria_type' => $id_criteria_type
        ]);
    }
}