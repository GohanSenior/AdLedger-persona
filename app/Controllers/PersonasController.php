<?php

class PersonasController
{
    private $personaModel;
    private $operationModel;
    private $criteriaModel;
    private $criteriaTypesModel;
    private $userModel;

    public function __construct()
    {
        $this->personaModel = new Personas();
        $this->operationModel = new Operations();
        $this->criteriaModel = new Criteria();
        $this->criteriaTypesModel = new Criteria_types();
        $this->userModel = new Users();
    }

    /**
     * Affiche le formulaire et traite la création d'un nouveau persona
     */
    public function createPersona()
    {
        // Vérifier que l'utilisateur est connecté
        if (!UsersController::isLoggedIn()) {
            header('Location: index.php?action=login');
            exit();
        }

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer et valider les données du formulaire
            $firstname = trim($_POST['firstname'] ?? '');
            $lastname = trim($_POST['lastname'] ?? '');
            $age = trim($_POST['age'] ?? '');
            $sexe = trim($_POST['sexe'] ?? '');
            $city = trim($_POST['city'] ?? '');
            $job = trim($_POST['job'] ?? '');
            $operationName = trim($_POST['operation'] ?? '');
            $selectedCriteria = $_POST['criteria'] ?? [];
            $customCriteria = $_POST['custom_criteria'] ?? [];

            // Validation des champs obligatoires
            if (empty($firstname) || empty($lastname) || empty($age) || empty($sexe) || empty($city) || empty($job)) {
                $errors[] = "Veuillez remplir tous les champs obligatoires.";
            } else {
                if (!is_numeric($age) || $age < 0) {
                    $errors[] = "L'âge doit être un nombre positif.";
                }
                if (!in_array($sexe, ['homme', 'femme', 'autre'])) {
                    $errors[] = "Le sexe doit être 'homme', 'femme' ou 'autre'.";
                }
            }

            // Si pas d'erreurs, créer le persona
            if (empty($errors)) {
                try {
                    // Démarrer une transaction
                    $pdo = Database::getConnection();
                    $pdo->beginTransaction();

                    $operationId = null;

                    // Créer ou récupérer l'opération si un nom d'opération est fourni
                    if (!empty($operationName)) {
                        // Vérifier si l'opération existe déjà pour cet utilisateur
                        $existingOperation = $this->operationModel->getOperationByNameAndUserId($operationName, $_SESSION['user_id']);

                        if ($existingOperation) {
                            // Utiliser l'ID de l'opération existante
                            $operationId = $existingOperation['id_operation'];
                        } else {
                            // Créer une nouvelle opération
                            $operationCreated = $this->operationModel->createOperation($operationName);
                            if (!$operationCreated) {
                                throw new Exception("Erreur lors de la création de l'opération.");
                            }
                            $operationId = $pdo->lastInsertId();
                        }
                    }

                    // Générer les options d'avatar
                    $avatarRandomizer = new AvatarRandomizer($sexe);
                    $avatarOptions = json_encode($avatarRandomizer->generate());

                    // Créer le persona avec l'ID de l'opération
                    $created = $this->personaModel->createPersona(
                        $firstname,
                        $lastname,
                        $age,
                        $sexe,
                        $city,
                        $job,
                        $_SESSION['user_id'],
                        $operationId,
                        $avatarOptions
                    );

                    if (!$created) {
                        throw new Exception("Erreur lors de la création du persona.");
                    }

                    // Récupérer l'ID du persona créé
                    $personaId = $pdo->lastInsertId();

                    // Traiter les critères personnalisés et les ajouter à la base de données
                    $allCriteriaIds = [];

                    // Ajouter les critères sélectionnés existants
                    if (!empty($selectedCriteria)) {
                        foreach ($selectedCriteria as $criterionId) {
                            if (!empty($criterionId) && $criterionId !== 'custom') {
                                $allCriteriaIds[] = $criterionId;
                            }
                        }
                    }

                    // Traiter les critères personnalisés
                    if (!empty($customCriteria)) {
                        foreach ($customCriteria as $rawTypeId => $customValues) {
                            $typeId = (int) $rawTypeId;
                            if ($typeId <= 0) {
                                continue;
                            }
                            foreach ($customValues as $customValue) {
                                $customValue = trim($customValue);
                                if (!empty($customValue)) {
                                    // Vérifier si le critère existe déjà
                                    $existingCriterion = $this->criteriaModel->getCriteriaByDescriptionAndType($customValue, $typeId);

                                    if ($existingCriterion) {
                                        // Utiliser l'ID du critère existant
                                        $allCriteriaIds[] = $existingCriterion['id_criterion'];
                                    } else {
                                        // Créer un nouveau critère
                                        $created = $this->criteriaModel->createCriteria($customValue, $typeId);
                                        if ($created) {
                                            $newCriterionId = $pdo->lastInsertId();
                                            $allCriteriaIds[] = $newCriterionId;
                                        }
                                    }
                                }
                            }
                        }
                    }

                    // Associer tous les critères au persona
                    if (!empty($allCriteriaIds)) {
                        foreach ($allCriteriaIds as $criterionId) {
                            $this->personaModel->associateCriteria($personaId, $criterionId);
                        }
                    }

                    // Valider la transaction
                    $pdo->commit();

                    // Envoyer un email de notification à l'admin (sauf si c'est l'admin lui-même qui crée)
                    if (!UsersController::isAdmin()) {
                        try {
                            $adminEmail = Config::get('app', 'admin_email');
                            $adminName  = Config::get('app', 'admin_name');

                            if (!empty($adminEmail)) {
                                $userModel = new Users();
                                $creator = $userModel->getUserById($_SESSION['user_id']);

                                $mailer = new MailerService();
                                $personaFullName = $firstname . ' ' . $lastname;
                                $creatorName = $creator ? $creator['firstname'] . ' ' . $creator['lastname'] : 'Utilisateur inconnu';

                                // Variables personnalisées pour l'admin
                                $subject = 'Nouveau persona créé';
                                $templatePath = __DIR__ . '/../templates/persona_creation.html';
                                $baseUrl = Config::get('app', 'base_url');
                                $personaUrl = $baseUrl . '/index.php?action=view-persona&id=' . $personaId;

                                $variables = [
                                    'title' => 'Nouveau Persona Créé',
                                    'message' => "Bonjour {$adminName},<br><br>
                                        Un nouveau persona <strong>{$personaFullName}</strong> a été créé par <strong>{$creatorName}</strong>.<br><br>
                                        Vous pouvez consulter son profil en cliquant sur le bouton ci-dessous.",
                                    'lienboutonprincipal' => $personaUrl
                                ];

                                $mailer->sendWithTemplate($adminEmail, $adminName, $subject, $templatePath, $variables);
                            }
                        } catch (Exception $e) {
                            // L'erreur d'envoi de mail ne doit pas bloquer la création du persona
                        }
                    }

                    // Message de succès
                    $_SESSION['success_message'] = "Le persona a bien été créé.";

                    // Rediriger vers la liste des personas
                    header('Location: index.php?action=list-personas');
                    exit();
                } catch (Exception $e) {
                    // Annuler la transaction en cas d'erreur
                    if ($pdo->inTransaction()) {
                        $pdo->rollBack();
                    }
                    $errors[] = "Une erreur est survenue. Veuillez réessayer.";
                    error_log("Erreur createPersona: " . $e->getMessage());
                }
            }
        }

        // Récupérer les types de critères pour le formulaire
        $criteriaTypes = $this->criteriaTypesModel->getCriteriaTypes();

        // Afficher le formulaire avec les erreurs éventuelles
        $editMode = false;
        $content = __DIR__ . '/../Views/persona_form.php';
        require_once __DIR__ . '/../Views/gabarit.php';
    }

    /**
     * Endpoint AJAX : recherche de critères par type pour l'autocomplétion
     */
    public function searchCriteria()
    {
        if (!UsersController::isLoggedIn()) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode([]);
            exit();
        }

        $q = trim($_GET['q'] ?? '');
        $typeId = (int)($_GET['type_id'] ?? 0);

        if (empty($q) || $typeId === 0) {
            header('Content-Type: application/json');
            echo json_encode([]);
            exit();
        }

        $results = $this->criteriaModel->searchCriteriaByType($q, $typeId);
        header('Content-Type: application/json');
        echo json_encode($results);
        exit();
    }

    /**
     * Affiche la liste des personas de l'utilisateur
     * Si un admin consulte avec un paramètre user_id, affiche les personas de cet utilisateur
     */
    public function listPersonas()
    {
        // Vérifier que l'utilisateur est connecté
        if (!UsersController::isLoggedIn()) {
            header('Location: index.php?action=login');
            exit();
        }

        // Déterminer quel utilisateur consulter
        $targetUserId = $_SESSION['user_id']; // Par défaut : utilisateur connecté
        $viewingOtherUser = false;
        $targetUserName = null;

        // Si un paramètre user_id est passé et que l'utilisateur est admin
        if (isset($_GET['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            $requestedUserId = (int)$_GET['user_id'];

            // Récupérer les infos de l'utilisateur ciblé pour afficher son nom
            $targetUser = $this->userModel->getUserById($requestedUserId);

            if ($targetUser) {
                $targetUserId = $requestedUserId;
                $viewingOtherUser = true;
                $targetUserName = $targetUser['firstname'] . ' ' . $targetUser['lastname'];
            }
        }

        // Récupérer les personas de l'utilisateur ciblé
        $personas = $this->personaModel->getPersonasByIdUser($targetUserId);

        // Afficher la liste
        $content = __DIR__ . '/../Views/list_personas.php';
        require_once __DIR__ . '/../Views/gabarit.php';
    }

    /**
     * Affiche la liste des personas types (visibles par tous les utilisateurs)
     */
    public function listPersonasTypes()
    {
        // Vérifier que l'utilisateur est connecté
        if (!UsersController::isLoggedIn()) {
            header('Location: index.php?action=login');
            exit();
        }

        // Récupérer tous les personas types (visibles par tous)
        $personas = $this->personaModel->getPersonasTypes();

        // Afficher la liste
        $content = __DIR__ . '/../Views/list_personas.php';
        require_once __DIR__ . '/../Views/gabarit.php';
    }

    /**
     * Affiche la liste de tous les personas (pour les admins)
     */
    public function listAllPersonas()
    {
        // Vérifier que l'utilisateur est connecté
        if (!UsersController::isLoggedIn()) {
            header('Location: index.php?action=login');
            exit();
        }

        // Vérifier que l'utilisateur est admin
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?action=list-personas');
            exit();
        }

        // Récupérer tous les personas (normaux et types)
        $personas = $this->personaModel->getPersonas();

        // Afficher la liste
        $content = __DIR__ . '/../Views/list_personas.php';
        require_once __DIR__ . '/../Views/gabarit.php';
    }


    /**
     * Affiche le formulaire de modification d'un persona
     */
    public function editPersona()
    {
        // Vérifier que l'utilisateur est connecté
        if (!UsersController::isLoggedIn()) {
            header('Location: index.php?action=login');
            exit();
        }

        $errors = [];
        $id_persona = (int)($_GET['id'] ?? 0);

        if (!$id_persona) {
            header('Location: index.php?action=list-personas');
            exit();
        }

        // Récupérer le persona
        $persona = $this->personaModel->getPersonaById($id_persona);

        // Vérifier que le persona appartient à l'utilisateur
        if (!$persona || (int)$persona['id_user'] !== (int)$_SESSION['user_id']) {
            header('Location: index.php?action=list-personas');
            exit();
        }

        // Récupérer le nom de l'opération si disponible
        $operation_name = '';
        if (!empty($persona['id_operation'])) {
            $operation = $this->operationModel->getoperationById($persona['id_operation']);
            if ($operation) {
                $operation_name = $operation['operation_name'];
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer et valider les données du formulaire
            $firstname = trim($_POST['firstname'] ?? '');
            $lastname = trim($_POST['lastname'] ?? '');
            $age = trim($_POST['age'] ?? '');
            $sexe = trim($_POST['sexe'] ?? '');
            $city = trim($_POST['city'] ?? '');
            $job = trim($_POST['job'] ?? '');
            $operationName = trim($_POST['operation'] ?? '');
            $selectedCriteria = $_POST['criteria'] ?? [];
            $customCriteria = $_POST['custom_criteria'] ?? [];
            $regenerateAvatar = isset($_POST['regenerate_avatar']);

            // Validation des champs obligatoires
            if (empty($firstname) || empty($lastname) || empty($age) || empty($sexe) || empty($city) || empty($job)) {
                $errors[] = "Veuillez remplir tous les champs obligatoires.";
            } else {
                if (!is_numeric($age) || $age < 0) {
                    $errors[] = "L'âge doit être un nombre positif.";
                }
                if (!in_array($sexe, ['homme', 'femme', 'autre'])) {
                    $errors[] = "Le sexe doit être 'homme', 'femme' ou 'autre'.";
                }
            }

            // Si pas d'erreurs, mettre à jour le persona
            if (empty($errors)) {
                try {
                    // Démarrer une transaction
                    $pdo = Database::getConnection();
                    $pdo->beginTransaction();

                    $operationId = $persona['id_operation'];

                    // Gérer l'opération
                    if (!empty($operationName)) {
                        // Si le nom de l'opération a changé
                        if ($operationName !== $operation_name) {
                            // Si le persona a déjà une opération assignée, mettre à jour cette opération
                            if (!empty($operationId)) {
                                $updated = $this->operationModel->updateOperation($operationId, $operationName);
                                if (!$updated) {
                                    throw new Exception("Erreur lors de la mise à jour de l'opération.");
                                }
                            } else {
                                // Sinon, vérifier si l'opération existe déjà pour cet utilisateur
                                $existingOperation = $this->operationModel->getOperationByNameAndUserId($operationName, $_SESSION['user_id']);

                                if ($existingOperation) {
                                    // Utiliser l'ID de l'opération existante
                                    $operationId = $existingOperation['id_operation'];
                                } else {
                                    // Créer une nouvelle opération
                                    $operationCreated = $this->operationModel->createOperation($operationName);
                                    if (!$operationCreated) {
                                        throw new Exception("Erreur lors de la création de l'opération.");
                                    }
                                    $operationId = $pdo->lastInsertId();
                                }
                            }
                        }
                    } else {
                        // Si le champ opération est vide, désassocier le persona de l'opération
                        $operationId = null;
                    }

                    // Gérer l'avatar : régénérer si demandé, sinon conserver l'existant
                    if ($regenerateAvatar) {
                        $avatarRandomizer = new AvatarRandomizer($sexe);
                        $avatarOptions = json_encode($avatarRandomizer->generate());
                    } else {
                        $avatarOptions = $persona['avatar_options'];
                    }

                    // Mettre à jour le persona
                    $updated = $this->personaModel->updatePersona(
                        $id_persona,
                        $firstname,
                        $lastname,
                        $age,
                        $sexe,
                        $city,
                        $job,
                        $operationId,
                        $avatarOptions
                    );

                    if (!$updated) {
                        throw new Exception("Erreur lors de la mise à jour du persona.");
                    }

                    // Supprimer les anciens critères associés
                    $this->personaModel->removeCriteriaByPersona($id_persona);

                    // Traiter les critères personnalisés et les ajouter à la base de données
                    $allCriteriaIds = [];

                    // Ajouter les critères sélectionnés existants
                    if (!empty($selectedCriteria)) {
                        foreach ($selectedCriteria as $criterionId) {
                            if (!empty($criterionId) && $criterionId !== 'custom') {
                                $allCriteriaIds[] = $criterionId;
                            }
                        }
                    }

                    // Traiter les critères personnalisés
                    if (!empty($customCriteria)) {
                        foreach ($customCriteria as $rawTypeId => $customValues) {
                            $typeId = (int) $rawTypeId;
                            if ($typeId <= 0) {
                                continue;
                            }
                            foreach ($customValues as $customValue) {
                                $customValue = trim($customValue);
                                if (!empty($customValue)) {
                                    // Vérifier si le critère existe déjà
                                    $existingCriterion = $this->criteriaModel->getCriteriaByDescriptionAndType($customValue, $typeId);

                                    if ($existingCriterion) {
                                        // Utiliser l'ID du critère existant
                                        $allCriteriaIds[] = $existingCriterion['id_criterion'];
                                    } else {
                                        // Créer un nouveau critère
                                        $created = $this->criteriaModel->createCriteria($customValue, $typeId);
                                        if ($created) {
                                            $newCriterionId = $pdo->lastInsertId();
                                            $allCriteriaIds[] = $newCriterionId;
                                        }
                                    }
                                }
                            }
                        }
                    }

                    // Associer tous les critères au persona
                    if (!empty($allCriteriaIds)) {
                        foreach ($allCriteriaIds as $criterionId) {
                            $this->personaModel->associateCriteria($id_persona, $criterionId);
                        }
                    }

                    // Valider la transaction
                    $pdo->commit();

                    // Message de succès
                    $_SESSION['success_message'] = "Le persona a bien été modifié.";

                    // Rediriger vers la page d'origine ou la liste des personas
                    $redirectUrl = sanitizeRedirect($_GET['redirect'] ?? null, 'index.php?action=list-personas');
                    header('Location: ' . $redirectUrl);
                    exit();
                } catch (Exception $e) {
                    // Annuler la transaction en cas d'erreur
                    if ($pdo->inTransaction()) {
                        $pdo->rollBack();
                    }
                    $errors[] = "Une erreur est survenue. Veuillez réessayer.";
                    error_log("Erreur editPersona: " . $e->getMessage());
                }
            }
        }

        // Récupérer les types de critères pour le formulaire
        $criteriaTypes = $this->criteriaTypesModel->getCriteriaTypes();

        // Récupérer les critères actuellement associés au persona (avec description et type)
        $personaCriteria = $this->personaModel->getCriteriaByPersona($id_persona);
        $personaCriteriaForJs = array_map(fn($c) => [
            'id'          => $c['id_criterion'],
            'description' => $c['criterion_description'],
            'type_id'     => $c['id_criteria_type'],
        ], $personaCriteria);

        // Construire l'URL de l'avatar pour l'affichage
        // Pour les personas types, utiliser le seed du persona original
        // Sinon utiliser l'ID du persona actuel pour garantir la stabilité
        if (!empty($persona['id_persona_original'])) {
            $seed = 'persona_' . $persona['id_persona_original'];
        } else {
            $seed = 'persona_' . $id_persona;
        }
        $avatarOptions = json_decode($persona['avatar_options'] ?? '[]', true);
        if (empty($avatarOptions)) {
            $avatarRandomizer = new AvatarRandomizer($persona['persona_sexe']);
            $avatarOptions = $avatarRandomizer->generate();
        }
        $avatarBuilder = new AvatarBuilder($seed, $avatarOptions);
        $avatarUrl = $avatarBuilder->buildUrl();

        // Afficher le formulaire de modification
        $editMode = true;
        $redirect = sanitizeRedirect($_GET['redirect'] ?? null, 'index.php?action=list-personas');
        $content = __DIR__ . '/../Views/persona_form.php';
        require_once __DIR__ . '/../Views/gabarit.php';
    }

    /**
     * Supprime un persona
     */
    public function deletePersona()
    {
        // Vérifier que l'utilisateur est connecté
        if (!UsersController::isLoggedIn()) {
            header('Location: index.php?action=login');
            exit();
        }

        $id_persona = (int)($_GET['id'] ?? 0);

        if (!$id_persona) {
            header('Location: index.php?action=list-personas');
            exit();
        }

        // Récupérer le persona
        $persona = $this->personaModel->getPersonaById($id_persona);

        if (!$persona) {
            header('Location: index.php?action=list-personas');
            exit();
        }

        // Vérifier les permissions de suppression
        $canDelete = false;
        $redirectAction = 'list-personas';
        $personaOriginalId = null;

        if ($persona['is_type'] === 1) {
            // Pour supprimer un persona type, il faut être admin
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                $canDelete = true;
                $redirectAction = 'list-personas-types';

                // Stocker l'ID du persona original pour le traiter dans le try-catch
                if (!empty($persona['id_persona_original'])) {
                    $personaOriginalId = $persona['id_persona_original'];
                }
            }
        } else {
            // Pour supprimer un persona normal, il faut être le propriétaire
            if ((int)$persona['id_user'] === (int)$_SESSION['user_id']) {
                $canDelete = true;
                $redirectAction = 'list-personas';
            }
        }

        if (!$canDelete) {
            header('Location: index.php?action=' . $redirectAction);
            exit();
        }

        try {
            // Si c'est un persona type, remettre le statut typed du persona original à 0
            if ($personaOriginalId) {
                $this->personaModel->updateTypedStatus($personaOriginalId, 0);
            }

            // Supprimer les associations de critères du persona
            $criteriaRemoved = $this->personaModel->removeCriteriaByPersona($id_persona);

            if ($criteriaRemoved === false) {
                $_SESSION['error_message'] = "Erreur lors de la suppression des critères associés.";
                $redirectUrl = sanitizeRedirect($_GET['redirect'] ?? null, 'index.php?action=' . $redirectAction);
                header('Location: ' . $redirectUrl);
                exit();
            }

            // Supprimer le persona
            $deleted = $this->personaModel->deletePersona($id_persona);

            if ($deleted) {
                $_SESSION['success_message'] = "Le persona a été supprimé avec succès.";
            } else {
                $_SESSION['error_message'] = "Erreur lors de la suppression du persona.";
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Erreur lors de la suppression du persona.";
            error_log("Erreur deletePersona: " . $e->getMessage());
        }

        // Rediriger vers la page d'origine ou la liste appropriée
        $redirectUrl = sanitizeRedirect($_GET['redirect'] ?? null, 'index.php?action=' . $redirectAction);
        header('Location: ' . $redirectUrl);
        exit();
    }

    /**
     * Affiche le profil d'un persona
     */
    public function viewPersona()
    {
        // Vérifier que l'utilisateur est connecté
        if (!UsersController::isLoggedIn()) {
            header('Location: index.php?action=login');
            exit();
        }

        $id_persona = (int)($_GET['id'] ?? 0);

        if (!$id_persona) {
            header('Location: index.php?action=list-personas');
            exit();
        }

        // Récupérer le persona
        $persona = $this->personaModel->getPersonaById($id_persona);

        // Vérifier que le persona appartient à l'utilisateur OU que c'est un persona type OU que l'utilisateur est admin
        if (!$persona) {
            header('Location: index.php?action=list-personas');
            exit();
        }
        $canView = (int)$persona['id_user'] === (int)$_SESSION['user_id'] // Le persona appartient à l'utilisateur
            || $persona['is_type'] === 1 // C'est un persona type (public)
            || (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'); // L'utilisateur est admin
        if (!$canView) {
            header('Location: index.php?action=list-personas');
            exit();
        }

        // Récupérer le nom de l'opération si disponible
        $operation_name = '';
        if (!empty($persona['id_operation'])) {
            $operation = $this->operationModel->getoperationById($persona['id_operation']);
            if ($operation) {
                $operation_name = $operation['operation_name'];
            }
        }

        // Récupérer les critères associés au persona
        $personaCriteria = $this->personaModel->getCriteriaByPersona($id_persona);

        // Construire l'URL de l'avatar
        // Pour les personas types, utiliser le seed du persona original
        // Sinon utiliser l'ID du persona actuel pour garantir la stabilité
        if (!empty($persona['id_persona_original'])) {
            $seed = 'persona_' . $persona['id_persona_original'];
        } else {
            $seed = 'persona_' . $id_persona;
        }
        $avatarOptions = json_decode($persona['avatar_options'] ?? '[]', true);
        if (empty($avatarOptions)) {
            // Si pas d'options enregistrées, utiliser les options par défaut
            $avatarRandomizer = new AvatarRandomizer($persona['persona_sexe']);
            $avatarOptions = $avatarRandomizer->generate();
        }
        $avatarBuilder = new AvatarBuilder($seed, $avatarOptions);
        $avatarUrl = $avatarBuilder->buildUrl();

        // Afficher le profil du persona
        $redirect = sanitizeRedirect($_GET['redirect'] ?? null, 'index.php?action=list-personas');
        $content = __DIR__ . '/../Views/profile_persona.php';
        require_once __DIR__ . '/../Views/gabarit.php';
    }

    /**
     * Créer une copie d'un persona en persona type (admin uniquement)
     */
    public function togglePersonaNormalToType()
    {
        // Vérifier que l'utilisateur est connecté
        if (!UsersController::isLoggedIn()) {
            header('Location: index.php?action=login');
            exit();
        }

        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?action=list-personas');
            exit();
        }

        $id_persona = (int)($_GET['id'] ?? 0);

        if (!$id_persona) {
            header('Location: index.php?action=list-personas');
            exit();
        }

        // Récupérer le persona
        $persona = $this->personaModel->getPersonaById($id_persona);

        if (!$persona) {
            $_SESSION['error_message'] = "Persona introuvable.";
            header('Location: index.php?action=list-personas');
            exit();
        }

        try {
            // Bascule du statut typed (indique que le persona a été copié en persona type)
            $newTypedStatus = (int)$persona['typed'] === 1 ? 0 : 1;
            $updated = $this->personaModel->updateTypedStatus($id_persona, $newTypedStatus);

            if (!$updated) {
                $_SESSION['error_message'] = "Erreur lors de la mise à jour du statut.";
                header('Location: index.php?action=list-personas-types');
                exit();
            }

            if ($newTypedStatus === 1) {
                // Si le persona est maintenant marqué comme typé, créer une copie en persona type avec l'admin comme propriétaire
                $created = $this->personaModel->createPersonaTypeFromExisting($id_persona, $_SESSION['user_id']);

                if ($created) {
                    $_SESSION['success_message'] = "Le persona a été transformé en persona type avec succès.";
                } else {
                    $_SESSION['error_message'] = "Erreur lors de la création du persona type.";
                }
            } else {
                $_SESSION['success_message'] = "Le statut du persona a été mis à jour.";
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Erreur lors de la transformation du persona.";
            error_log("Erreur togglePersonaNormalToType: " . $e->getMessage());
        }

        // Rediriger vers la liste des personas
        header('Location: index.php?action=list-personas-types');
        exit();
    }

    /**
     * Créer une copie d'un persona type en persona normal (tous les utilisateurs)
     */
    public function togglePersonaTypeToNormal()
    {
        // Vérifier que l'utilisateur est connecté
        if (!UsersController::isLoggedIn()) {
            header('Location: index.php?action=login');
            exit();
        }

        $id_persona = (int)($_GET['id'] ?? 0);

        if (!$id_persona) {
            header('Location: index.php?action=list-personas-types');
            exit();
        }

        // Récupérer le persona type
        $persona = $this->personaModel->getPersonaById($id_persona);

        if (!$persona || (int)$persona['is_type'] !== 1) {
            // Ce n'est pas un persona type
            $_SESSION['error_message'] = "Persona type introuvable.";
            header('Location: index.php?action=list-personas-types');
            exit();
        }

        try {
            // Créer une copie du persona type en persona normal pour l'utilisateur connecté
            $newPersonaId = $this->personaModel->createNormalPersonaFromType($id_persona, $_SESSION['user_id']);

            if ($newPersonaId) {
                $_SESSION['success_message'] = "Le persona type a été ajouté à vos personas avec succès.";
                header('Location: index.php?action=list-personas');
                exit();
            } else {
                $_SESSION['error_message'] = "Erreur lors de la création du persona.";
                header('Location: index.php?action=list-personas-types');
                exit();
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Erreur lors de la création du persona.";
            error_log("Erreur togglePersonaTypeToNormal: " . $e->getMessage());
            header('Location: index.php?action=list-personas-types');
            exit();
        }
    }
}
