<?php

class Swots extends Model
{
    // Récupère le SWOT d'une compagnie par son ID
    public function getSwotByCompanyId($companyId)
    {
        $sql = "SELECT * FROM swots WHERE id_company = :companyId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['companyId' => $companyId]);
        return $stmt->fetch();
    }

    // Crée un SWOT pour une compagnie
    public function createSwot($companyId, $title)
    {
        $sql = "INSERT INTO swots (id_company, title, created_at) VALUES (:companyId, :title, CURDATE())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['companyId' => $companyId, 'title' => $title]);
    }

    // Met à jour un SWOT existant
    public function updateSwot($id, $title)
    {
        $sql = "UPDATE swots SET title = :title WHERE id_swot = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id, 'title' => $title]);
    }

    // Archive un SWOT en le marquant comme inactif
    public function archiveSwot($id)
    {
        $sql = "UPDATE swots SET is_archived = 1 WHERE id_swot = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

}