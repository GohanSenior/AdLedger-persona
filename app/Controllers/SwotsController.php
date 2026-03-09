<?php

class SwotsController
{
    private $swotsModel;
    private $swotItemsModel;

    public function __construct()
    {
        $this->swotsModel = new Swots();
        $this->swotItemsModel = new Swot_items();
    }

    // Affiche le profil SWOT d'une compagnie
    public function showSwot()
    {
        if (!UsersController::isLoggedIn()) {
            header('Location: index.php?action=login');
            exit();
        }

        $companyId = (int)($_GET['company_id'] ?? 0);
        if (!$companyId) {
            header('Location: index.php?action=dashboard');
            exit();
        }

        $this->authorizeCompanyAccess($companyId);

        $companyModel = new Companies();
        $company = $companyModel->getCompanyById($companyId);
        if (!$company) {
            header('Location: index.php?action=dashboard');
            exit();
        }

        $swot = $this->swotsModel->getSwotByCompanyId($companyId);
        if ($swot) {
            $items = $this->swotItemsModel->getItemsBySwotId($swot['id_swot']);
            $swot['strengths']    = [];
            $swot['weaknesses']   = [];
            $swot['opportunities'] = [];
            $swot['threats']      = [];
            foreach ($items as $item) {
                switch ($item['category']) {
                    case 'strength':    $swot['strengths'][]     = $item['content']; break;
                    case 'weakness':    $swot['weaknesses'][]    = $item['content']; break;
                    case 'opportunity': $swot['opportunities'][] = $item['content']; break;
                    case 'threat':      $swot['threats'][]       = $item['content']; break;
                }
            }
        }

        $content = __DIR__ . '/../Views/profile_swot.php';
        require_once __DIR__ . '/../Views/gabarit.php';
    }

    // Affiche le formulaire de création/édition du SWOT
    public function showSwotForm()
    {
        if (!UsersController::isLoggedIn()) {
            header('Location: index.php?action=login');
            exit();
        }

        $companyId = (int)($_GET['company_id'] ?? 0);
        if (!$companyId) {
            header('Location: index.php?action=dashboard');
            exit();
        }

        $this->authorizeCompanyAccess($companyId);

        $companyModel = new Companies();
        $company = $companyModel->getCompanyById($companyId);
        if (!$company) {
            header('Location: index.php?action=dashboard');
            exit();
        }

        $swot = $this->swotsModel->getSwotByCompanyId($companyId);
        $itemsByCategory = [
            'strength'    => [],
            'weakness'    => [],
            'opportunity' => [],
            'threat'      => [],
        ];

        if ($swot) {
            $items = $this->swotItemsModel->getItemsBySwotId($swot['id_swot']);
            foreach ($items as $item) {
                if (array_key_exists($item['category'], $itemsByCategory)) {
                    $itemsByCategory[$item['category']][] = $item['content'];
                }
            }
        }

        $errors = [];
        $content = __DIR__ . '/../Views/swot_form.php';
        require_once __DIR__ . '/../Views/gabarit.php';
    }

    // Enregistre (crée ou met à jour) le SWOT et ses items
    public function saveSwot()
    {
        if (!UsersController::isLoggedIn()) {
            header('Location: index.php?action=login');
            exit();
        }

        $companyId = (int)($_POST['company_id'] ?? 0);
        $swotId    = (int)($_POST['swot_id']    ?? 0);
        $title     = trim($_POST['title']        ?? '');

        $this->authorizeCompanyAccess($companyId);

        $companyModel = new Companies();
        if (!$companyId || !$companyModel->getCompanyById($companyId)) {
            header('Location: index.php?action=dashboard');
            exit();
        }

        $errors = [];

        if (!$companyId) {
            $errors[] = "Entreprise invalide.";
        }
        if (empty($title)) {
            $errors[] = "Le titre du SWOT est obligatoire.";
        }

        if (!empty($errors)) {
            $companyModel = new Companies();
            $company = $companyModel->getCompanyById($companyId);
            $swot = $swotId ? ['id_swot' => $swotId, 'title' => $title] : ['title' => $title];
            $itemsByCategory = [
                'strength'    => $_POST['items']['strength']    ?? [],
                'weakness'    => $_POST['items']['weakness']    ?? [],
                'opportunity' => $_POST['items']['opportunity'] ?? [],
                'threat'      => $_POST['items']['threat']      ?? [],
            ];
            $content = __DIR__ . '/../Views/swot_form.php';
            require_once __DIR__ . '/../Views/gabarit.php';
            return;
        }

        $pdo = Database::getConnection();
        $pdo->beginTransaction();

        try {
            if ($swotId) {
                $this->swotsModel->updateSwot($swotId, $title);
            } else {
                $this->swotsModel->createSwot($companyId, $title);
                $swotId = (int)$pdo->lastInsertId();
            }

            // Remplacer tous les items existants
            $this->swotItemsModel->deleteItemsBySwotId($swotId);

            $categories = ['strength', 'weakness', 'opportunity', 'threat'];
            foreach ($categories as $category) {
                $items = $_POST['items'][$category] ?? [];
                foreach ($items as $content) {
                    $content = trim($content);
                    if ($content !== '') {
                        $this->swotItemsModel->createItem($swotId, $category, $content);
                    }
                }
            }

            $pdo->commit();

            header("Location: index.php?action=view-swot&company_id={$companyId}");
            exit();
        } catch (Exception $e) {
            $pdo->rollBack();
            $errors[] = "Une erreur est survenue lors de l'enregistrement.";
            $companyModel = new Companies();
            $company = $companyModel->getCompanyById($companyId);
            $swot = $swotId ? ['id_swot' => $swotId, 'title' => $title] : ['title' => $title];
            $itemsByCategory = [
                'strength'    => $_POST['items']['strength']    ?? [],
                'weakness'    => $_POST['items']['weakness']    ?? [],
                'opportunity' => $_POST['items']['opportunity'] ?? [],
                'threat'      => $_POST['items']['threat']      ?? [],
            ];
            $content = __DIR__ . '/../Views/swot_form.php';
            require_once __DIR__ . '/../Views/gabarit.php';
        }
    }

    // Archive un SWOT en le marquant comme inactif
    public function archiveSwot($id)
    {
        return $this->swotsModel->archiveSwot($id);
    }

    /**
     * Vérifie que l'utilisateur connecté appartient bien à la compagnie demandée.
     * Les admins ont accès à toutes les compagnies.
     * Redirige vers le dashboard et arrête l'exécution si l'accès est refusé.
     */
    private function authorizeCompanyAccess(int $companyId): void
    {
        if (UsersController::isAdmin()) {
            return;
        }

        if ((int)($_SESSION['id_company'] ?? 0) !== $companyId) {
            header('Location: index.php?action=dashboard');
            exit();
        }
    }
}

