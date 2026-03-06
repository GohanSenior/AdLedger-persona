<?php

class Swot_items extends Model
{
    // Récupère les items d'un SWOT par son ID
    public function getItemsBySwotId($swotId)
    {
        $sql = "SELECT * FROM swot_items WHERE id_swot = :swotId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['swotId' => $swotId]);
        return $stmt->fetchAll();
    }

    // Crée un nouvel item pour un SWOT
    public function createItem($swotId, $category, $content)
    {
        $sql = "INSERT INTO swot_items (id_swot, category, content, created_at) VALUES (:swotId, :category, :content, CURDATE())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['swotId' => $swotId, 'category' => $category, 'content' => $content]);
    }

    // Met à jour un item existant
    public function updateItem($id, $content)
    {
        $sql = "UPDATE swot_items SET content = :content WHERE id_swot_item = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id, 'content' => $content]);
    }

    // Supprime un item d'un SWOT
    public function deleteItem($id)
    {
        $sql = "DELETE FROM swot_items WHERE id_swot_item = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
    // Supprime tous les items d'un SWOT
    public function deleteItemsBySwotId($swotId)
    {
        $sql = "DELETE FROM swot_items WHERE id_swot = :swotId";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['swotId' => $swotId]);
    }}