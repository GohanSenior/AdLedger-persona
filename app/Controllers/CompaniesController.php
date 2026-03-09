<?php

class CompaniesController
{
    private $companyModel;

    public function __construct()
    {
        $this->companyModel = new Companies();
    }

    /**
     * Crée une nouvelle entreprise et retourne son ID
     * @return int|false L'ID de l'entreprise créée ou false en cas d'échec
     */
    public function createCompany($companyName, $companyAddress, $companyZipcode, $companyCity)
    {
        $created = $this->companyModel->createCompany(
            $companyName,
            $companyAddress,
            $companyZipcode,
            $companyCity
        );

        if (!$created) {
            return false;
        }

        // Récupérer l'ID de l'entreprise créée
        $pdo = Database::getConnection();
        return $pdo->lastInsertId();
    }

    /**
     * Affiche le formulaire de modification de l'entreprise
     */
    public function editCompany()
    {
        // Vérifier que l'utilisateur est connecté
        if (!UsersController::isLoggedIn()) {
            header('Location: index.php?action=login');
            exit();
        }

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer et valider les données du formulaire
            $companyName = trim($_POST['name'] ?? '');
            $companyAddress = trim($_POST['address'] ?? '');
            $companyZipcode = trim($_POST['zipcode'] ?? '');
            $companyCity = trim($_POST['city'] ?? '');

            // Récupérer le logo actuel
            $currentCompany = $this->companyModel->getCompanyById($_SESSION['id_company']);
            $logoUrl = $currentCompany['logo_url'];

            // Validation des champs obligatoires
            if (empty($companyName)) {
                $errors[] = "La raison sociale de l'entreprise est requise.";
            }
            if (empty($companyAddress)) {
                $errors[] = "L'adresse de l'entreprise est requise.";
            }
            if (empty($companyZipcode)) {
                $errors[] = "Le code postal de l'entreprise est requis.";
            } elseif (!preg_match('/^\d{5}$/', $companyZipcode)) {
                $errors[] = "Le code postal doit contenir exactement 5 chiffres.";
            }
            if (empty($companyCity)) {
                $errors[] = "La ville de l'entreprise est requise.";
            }


            // Gestion de l'upload du logo
            if (isset($_FILES['logo_url']) && $_FILES['logo_url']['error'] === UPLOAD_ERR_OK) {
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                $maxFileSize = 5 * 1024 * 1024; // 5 Mo

                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $fileType = $finfo->file($_FILES['logo_url']['tmp_name']); // ← analyse le fichier réel
                $fileSize = $_FILES['logo_url']['size'];
                $fileTmpName = $_FILES['logo_url']['tmp_name'];
                $fileExtension = strtolower(pathinfo($_FILES['logo_url']['name'], PATHINFO_EXTENSION));

                // Validation du type de fichier
                if (!in_array($fileType, $allowedTypes)) {
                    $errors[] = "Le fichier doit être une image (JPG, PNG, GIF, WEBP).";
                }

                // Validation de la taille du fichier
                if ($fileSize > $maxFileSize) {
                    $errors[] = "Le fichier ne doit pas dépasser 5 Mo.";
                }

                // Si pas d'erreurs de validation, déplacer le fichier
                if (empty($errors)) {
                    try {
                        $uploadDir = __DIR__ . '/../../assets/logo/';

                        // Créer le dossier s'il n'existe pas
                        if (!is_dir($uploadDir)) {
                            if (!mkdir($uploadDir, 0755, true)) {
                                throw new Exception("Impossible de créer le dossier de destination.");
                            }
                        }

                        // Vérifier les permissions d'écriture
                        if (!is_writable($uploadDir)) {
                            throw new Exception("Le dossier de destination n'est pas accessible en écriture.");
                        }

                        // Générer un nom de fichier unique
                        $uniqueFileName = uniqid() . '_' . time() . '.' . $fileExtension;
                        $uploadPath = $uploadDir . $uniqueFileName;

                        // Déplacer le fichier
                        if (!move_uploaded_file($fileTmpName, $uploadPath)) {
                            throw new Exception("Impossible de déplacer le fichier uploadé.");
                        }

                        // Supprimer l'ancien logo s'il existe
                        if (!empty($logoUrl)) {
                            $oldLogoPath = __DIR__ . '/../../' . $logoUrl;
                            if (file_exists($oldLogoPath) && !unlink($oldLogoPath)) {
                                error_log("Impossible de supprimer l'ancien logo : " . $oldLogoPath);
                            }
                        }

                        // Mettre à jour le chemin du logo
                        $logoUrl = 'assets/logo/' . $uniqueFileName;
                    } catch (Exception $e) {
                        $errors[] = "Erreur lors de l'upload du logo.";
                        error_log("Erreur upload logo entreprise: " . $e->getMessage());
                    }
                }
            }

            // Si pas d'erreurs, mettre à jour l'entreprise
            if (empty($errors)) {
                try {
                    $updated = $this->companyModel->updateCompany(
                        $_SESSION['id_company'],
                        $companyName,
                        $companyAddress,
                        $companyZipcode,
                        $companyCity,
                        $logoUrl
                    );

                    if ($updated) {
                        $_SESSION['success_message'] = "L'entreprise a été mise à jour avec succès.";
                        header('Location: index.php?action=dashboard');
                        exit();
                    } else {
                        $errors[] = "Erreur lors de la mise à jour de l'entreprise.";
                    }
                } catch (PDOException $e) {
                    $errors[] = "Erreur lors de la mise à jour de l'entreprise.";
                    error_log("Erreur updateCompany: " . $e->getMessage());
                }
            }
        }

        // Récupérer les données actuelles de l'entreprise
        $company = $this->companyModel->getCompanyById($_SESSION['id_company']);

        // Afficher le formulaire de modification avec les erreurs éventuelles
        $content = __DIR__ . '/../Views/edit_company.php';
        require_once __DIR__ . '/../Views/gabarit.php';
    }
}
