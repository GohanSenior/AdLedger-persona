<?php

class OperationsController
{
    private $operationModel;
    private $personaModel;

    public function __construct()
    {
        $this->operationModel = new Operations();
        $this->personaModel = new Personas();
    }

    /**
     * Affiche la liste des opérations de l'utilisateur
     */
    public function listOperations()
    {
        // Vérifier que l'utilisateur est connecté
        if (!UsersController::isLoggedIn()) {
            header('Location: index.php?action=login');
            exit();
        }

        $operations = $this->operationModel->getOperationsByUserId($_SESSION['user_id']);
        $content = __DIR__ . '/../Views/list_operations.php';
        require_once __DIR__ . '/../Views/gabarit.php';
    }

    /**
     * Affiche le formulaire de modification d'une opération
     */
    public function editOperation()
    {
        // Vérifier que l'utilisateur est connecté
        if (!UsersController::isLoggedIn()) {
            header('Location: index.php?action=login');
            exit();
        }

        $errors = [];
        $id_operation = $_GET['id'] ?? null;

        if (!$id_operation) {
            header('Location: index.php?action=list-operations');
            exit();
        }

        $operation = $this->operationModel->getOperationById($id_operation);
        if (!$operation) {
            header('Location: index.php?action=list-operations');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $operationName = trim($_POST['name'] ?? '');

            if (empty($operationName)) {
                $errors[] = "Le nom de l'opération est requis.";
            }

            if (empty($errors)) {
                try {
                    $updated = $this->operationModel->updateOperation($id_operation, $operationName);

                    if ($updated) {
                        $_SESSION['success_message'] = "L'opération a été mise à jour avec succès.";
                        header('Location: index.php?action=list-operations');
                        exit();
                    } else {
                        $errors[] = "Erreur lors de la mise à jour de l'opération.";
                    }
                } catch (PDOException $e) {
                    $errors[] = "Erreur lors de la mise à jour de l'opération.";
                    error_log("Erreur updateOperation: " . $e->getMessage());
                }
            }
        }

        $content = __DIR__ . '/../Views/edit_operation.php';
        require_once __DIR__ . '/../Views/gabarit.php';
    }

    /**
     * Affiche la fiche d'une opération avec la liste des personas associés
     */
    public function viewOperation()
    {
        // Vérifier que l'utilisateur est connecté
        if (!UsersController::isLoggedIn()) {
            header('Location: index.php?action=login');
            exit();
        }

        $id_operation = $_GET['id'] ?? null;

        if (!$id_operation) {
            header('Location: index.php?action=list-operations');
            exit();
        }

        $operation = $this->operationModel->getOperationById($id_operation);
        if (!$operation) {
            header('Location: index.php?action=list-operations');
            exit();
        }

        $personas = $this->personaModel->getPersonasByOperationId($id_operation);
        $content = __DIR__ . '/../Views/profile_operation.php';
        require_once __DIR__ . '/../Views/gabarit.php';
    }

    /**
     * Supprime une opération
     */
    public function deleteOperation()
    {
        // Vérifier que l'utilisateur est connecté
        if (!UsersController::isLoggedIn()) {
            header('Location: index.php?action=login');
            exit();
        }

        $id_operation = $_GET['id'] ?? null;

        if (!$id_operation) {
            header('Location: index.php?action=list-operations');
            exit();
        }

        $operation = $this->operationModel->getOperationById($id_operation);
        if (!$operation) {
            header('Location: index.php?action=list-operations');
            exit();
        }

        try {
            // Dissocier l'opération de tous les personas
            $dissociated = $this->personaModel->removeOperationFromPersonas($id_operation);
            
            if ($dissociated === false) {
                $_SESSION['error_message'] = "Erreur lors de la dissociation de l'opération des personas.";
                header('Location: index.php?action=list-operations');
                exit();
            }

            // Supprimer l'opération
            $deleted = $this->operationModel->deleteOperation($id_operation);

            if ($deleted) {
                $_SESSION['success_message'] = "L'opération a été supprimée avec succès.";
            } else {
                $_SESSION['error_message'] = "Erreur lors de la suppression de l'opération.";
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Erreur lors de la suppression de l'opération.";
            // Log pour le développement (à adapter selon votre système de logs)
            error_log("Erreur suppression opération: " . $e->getMessage());
        }

        header('Location: index.php?action=list-operations');
        exit();
    }
}
