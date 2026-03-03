<?php

class UsersController
{
    private $userModel;
    private $companyModel;

    public function __construct()
    {
        $this->userModel = new Users();
        $this->companyModel = new Companies();
    }

    /**
     * Affiche le formulaire et traite la création d'un nouveau compte utilisateur et de son entreprise
     */
    public function register()
    {
        $errors = [];
        $success = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validation des données utilisateur
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $lastname = trim($_POST['lastname'] ?? '');
            $firstname = trim($_POST['firstname'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            // Convertir le téléphone vide en NULL pour la base de données
            $phone = !empty($phone) ? $phone : null;

            // Validation des données entreprise
            $companyName = trim($_POST['name'] ?? '');
            $companyAddress = trim($_POST['address'] ?? '');
            $companyZipcode = trim($_POST['zipcode'] ?? '');
            $companyCity = trim($_POST['city'] ?? '');

            // Validation des champs obligatoires utilisateur
            if (empty($username)) {
                $errors[] = "Le nom d'utilisateur est requis.";
            } elseif (strlen($username) < 5) {
                $errors[] = "Le nom d'utilisateur doit contenir au moins 5 caractères.";
            }
            if (empty($password)) {
                $errors[] = "Le mot de passe est requis.";
            } elseif (strlen($password) < 8) {
                $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
            }
            if (empty($lastname)) {
                $errors[] = "Le nom est requis.";
            }
            if (empty($firstname)) {
                $errors[] = "Le prénom est requis.";
            }
            if (empty($email)) {
                $errors[] = "L'email est requis.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "L'email n'est pas valide.";
            }

            // Validation du téléphone (optionnel mais doit être valide si renseigné)
            if (!empty($phone) && !preg_match('/^(\d{2}[\s.]?){4}\d{2}$/', $phone)) {
                $errors[] = "Le numéro de téléphone doit contenir 10 chiffres (format accepté : 0612345678, 06 12 34 56 78 ou 06.12.34.56.78).";
            }

            // Validation des champs obligatoires entreprise
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

            // Vérifier si l'email existe déjà
            if (empty($errors)) {
                $existingUser = $this->userModel->getUserByEmail($email);
                if ($existingUser) {
                    $errors[] = "Un compte avec cet email existe déjà.";
                }
            }

            // Vérifier si le nom d'utilisateur existe déjà
            if (empty($errors)) {
                $existingUsername = $this->userModel->getUserByUsername($username);
                if ($existingUsername) {
                    $errors[] = "Ce nom d'utilisateur est déjà pris.";
                }
            }

            // Si pas d'erreurs, créer l'entreprise et l'utilisateur
            if (empty($errors)) {
                try {
                    // Démarrer une transaction
                    $pdo = Database::getConnection();
                    $pdo->beginTransaction();

                    // Créer l'entreprise d'abord via CompaniesController
                    $companiesController = new CompaniesController();
                    $companyId = $companiesController->createCompany(
                        $companyName,
                        $companyAddress,
                        $companyZipcode,
                        $companyCity
                    );

                    if (!$companyId) {
                        throw new Exception("Erreur lors de la création de l'entreprise.");
                    }

                    // Hacher le mot de passe
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    // Créer l'utilisateur avec l'ID de l'entreprise
                    $userCreated = $this->userModel->createUser(
                        $username,
                        $hashedPassword,
                        $lastname,
                        $firstname,
                        $email,
                        $phone,
                        $companyId
                    );

                    if (!$userCreated) {
                        throw new Exception("Erreur lors de la création du compte utilisateur.");
                    }

                    // Récupérer l'ID de l'utilisateur créé
                    $userId = $pdo->lastInsertId();

                    // Valider la transaction
                    $pdo->commit();

                    // Stocker les informations de l'utilisateur dans la session
                    $_SESSION['user_id'] = $userId;
                    $_SESSION['username'] = $username;
                    $_SESSION['lastname'] = $lastname;
                    $_SESSION['firstname'] = $firstname;
                    $_SESSION['email'] = $email;
                    $_SESSION['phone'] = $phone;
                    $_SESSION['role'] = 'user'; // Par défaut
                    $_SESSION['id_company'] = $companyId;

                    // Rediriger vers le tableau de bord
                    header('Location: index.php?action=dashboard');
                    exit();
                } catch (Exception $e) {
                    // Annuler la transaction en cas d'erreur
                    if ($pdo->inTransaction()) {
                        $pdo->rollBack();
                    }
                    $errors[] = "Une erreur est survenue lors de l'inscription : " . $e->getMessage();
                }
            }
        }

        // Afficher le formulaire avec les erreurs éventuelles
        $content = __DIR__ . '/../Views/register.php';
        require_once __DIR__ . '/../Views/gabarit.php';
    }

    /**
     * Connecte un utilisateur
     */
    public function login()
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            // Validation
            if (empty($username)) {
                $errors[] = "Le nom d'utilisateur est requis.";
            }
            if (empty($password)) {
                $errors[] = "Le mot de passe est requis.";
            }

            // Vérifier les identifiants
            if (empty($errors)) {
                $user = $this->userModel->getUserByUsername($username);

                if ($user && password_verify($password, $user['password'])) {
                    // Vérifier si l'utilisateur est actif
                    if ($user['enabled'] == 1) {
                        // Stocker les informations de l'utilisateur dans la session
                        $_SESSION['user_id'] = $user['id_user'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['lastname'] = $user['lastname'];
                        $_SESSION['firstname'] = $user['firstname'];
                        $_SESSION['email'] = $user['email'];
                        $_SESSION['phone'] = $user['phone'];
                        $_SESSION['role'] = $user['role'];
                        $_SESSION['id_company'] = $user['id_company'];

                        // Rediriger vers le tableau de bord
                        header('Location: index.php?action=dashboard');
                        exit();
                    } else {
                        $errors[] = "Votre compte a été désactivé. Contactez un administrateur.";
                    }
                } else {
                    $errors[] = "Nom d'utilisateur ou mot de passe incorrect.";
                }
            }
        }

        // Afficher le formulaire de connexion
        $content = __DIR__ . '/../Views/login.php';
        require_once __DIR__ . '/../Views/gabarit.php';
    }

    /**
     * Déconnecte l'utilisateur
     */
    public function logout()
    {
        // Détruire toutes les variables de session
        $_SESSION = [];

        // Détruire la session
        session_destroy();

        // Rediriger vers la page de connexion
        header('Location: index.php?action=login');
        exit();
    }

    public function forgotPassword()
    {
        $errors = [];
        $success = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userLogin = trim($_POST['user_login'] ?? '');

            // Validation
            if (empty($userLogin)) {
                $errors[] = "Veuillez saisir votre nom d'utilisateur ou votre adresse email.";
            }

            if (empty($errors)) {
                // Rechercher l'utilisateur par username ou email
                $user = $this->userModel->getUserByUsernameOrEmail($userLogin);

                if ($user) {
                    // Générer un token unique et sécurisé
                    $token = bin2hex(random_bytes(32));

                    // Enregistrer le token dans la base de données (expiration calculée en SQL)
                    $tokenSaved = $this->userModel->setResetToken($user['id_user'], $token);

                    if ($tokenSaved) {
                        // Créer le lien de réinitialisation
                        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
                        $host = $_SERVER['HTTP_HOST'];
                        $resetLink = $protocol . '://' . $host . dirname($_SERVER['PHP_SELF']) . '/index.php?action=reset-password&token=' . $token;

                        // Envoyer l'email avec le template
                        try {
                            $mailer = new MailerService();

                            // Préparer les variables pour le template
                            $templatePath = __DIR__ . '/../templates/reset_password_email.html';
                            $variables = [
                                'firstname' => $user['firstname'],
                                'lastname' => $user['lastname'],
                                'reset_link' => $resetLink,
                                'year' => date('Y')
                            ];

                            // Envoyer l'email avec le template
                            $emailSent = $mailer->sendWithTemplate(
                                $user['email'],
                                $user['firstname'] . ' ' . $user['lastname'],
                                'Réinitialisation de votre mot de passe',
                                $templatePath,
                                $variables
                            );

                            if ($emailSent) {
                                $success = true;
                            } else {
                                $errors[] = "Erreur lors de l'envoi de l'email. Veuillez réessayer.";
                            }
                        } catch (Exception $e) {
                            $errors[] = "Erreur lors de l'envoi de l'email : " . $e->getMessage();
                        }
                    } else {
                        $errors[] = "Erreur lors de la création du token. Veuillez réessayer.";
                    }
                } else {
                    // Pour des raisons de sécurité, ne pas indiquer si l'utilisateur existe ou non
                    // On affiche quand même un message de succès
                    $success = true;
                }
            }
        }

        // Afficher le formulaire
        $content = __DIR__ . '/../Views/forgot_password.php';
        require_once __DIR__ . '/../Views/gabarit.php';
    }

    /**
     * Réinitialise le mot de passe avec le token
     */
    public function resetPassword()
    {
        $errors = [];
        $success = false;
        $validToken = false;
        $user = null;

        // Récupérer le token depuis l'URL
        $token = $_GET['token'] ?? '';

        if (!empty($token)) {
            // Vérifier la validité du token
            $user = $this->userModel->getUserByResetToken($token);
            
            if ($user) {
                $validToken = true;
            }
        }

        // Traiter le formulaire de réinitialisation
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $validToken) {
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            // Validation
            if (empty($password)) {
                $errors[] = "Le mot de passe est requis.";
            } elseif (strlen($password) < 8) {
                $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
            }

            if (empty($confirmPassword)) {
                $errors[] = "Veuillez confirmer votre mot de passe.";
            }

            if ($password !== $confirmPassword) {
                $errors[] = "Les mots de passe ne correspondent pas.";
            }

            // Si pas d'erreurs, réinitialiser le mot de passe
            if (empty($errors)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $resetSuccess = $this->userModel->resetPassword($user['id_user'], $hashedPassword);

                if ($resetSuccess) {
                    $success = true;
                } else {
                    $errors[] = "Erreur lors de la réinitialisation du mot de passe.";
                }
            }
        }

        // Afficher le formulaire de réinitialisation
        $content = __DIR__ . '/../Views/reset_password.php';
        require_once __DIR__ . '/../Views/gabarit.php';
    }

    /**
     * Vérifie si un utilisateur est connecté
     */
    public static function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Vérifie si l'utilisateur est un administrateur
     */
    public static function isAdmin()
    {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    /**
     * Affiche le formulaire de modification du profil utilisateur
     */
    public function editUser()
    {
        // Vérifier que l'utilisateur est connecté
        if (!self::isLoggedIn()) {
            header('Location: index.php?action=login');
            exit();
        }

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer et valider les données du formulaire
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $lastname = trim($_POST['lastname'] ?? '');
            $firstname = trim($_POST['firstname'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $phone = !empty($phone) ? $phone : null;

            // Validation des champs obligatoires
            if (empty($username)) {
                $errors[] = "Le nom d'utilisateur est requis.";
            }
            // Validation du mot de passe uniquement s'il est rempli (optionnel en édition)
            if (!empty($password) && strlen($password) < 8) {
                $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
            }
            if (empty($lastname)) {
                $errors[] = "Le nom est requis.";
            }
            if (empty($firstname)) {
                $errors[] = "Le prénom est requis.";
            }
            if (empty($email)) {
                $errors[] = "L'email est requis.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "L'email n'est pas valide.";
            }

            // Validation du téléphone (optionnel mais doit être valide si renseigné)
            if (!empty($phone) && !preg_match('/^(\d{2}[\s.]?){4}\d{2}$/', $phone)) {
                $errors[] = "Le numéro de téléphone doit contenir 10 chiffres (peut contenir des points ou des espaces).";
            }

            // Vérifier si le username existe déjà (sauf pour l'utilisateur actuel)
            if (empty($errors) && $username !== $_SESSION['username']) {
                $existingUsername = $this->userModel->getUserByUsername($username);
                if ($existingUsername && $existingUsername['id_user'] != $_SESSION['user_id']) {
                    $errors[] = "Ce nom d'utilisateur est déjà pris.";
                }
            }

            // Vérifier si l'email existe déjà (sauf pour l'utilisateur actuel)
            if (empty($errors) && $email !== $_SESSION['email']) {
                $existingUser = $this->userModel->getUserByEmail($email);
                if ($existingUser && $existingUser['id_user'] != $_SESSION['user_id']) {
                    $errors[] = "Un compte avec cet email existe déjà.";
                }
            }

            // Si pas d'erreurs, mettre à jour l'utilisateur
            if (empty($errors)) {
                try {
                    // Si un nouveau mot de passe est fourni, le hacher
                    // Sinon, récupérer le mot de passe actuel de la base de données
                    if (!empty($password)) {
                        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    } else {
                        // Récupérer l'utilisateur actuel pour garder son mot de passe
                        $currentUser = $this->userModel->getUserById($_SESSION['user_id']);
                        $hashedPassword = $currentUser['password'];
                    }

                    $updated = $this->userModel->updateUser(
                        $_SESSION['user_id'],
                        $username,
                        $hashedPassword,
                        $lastname,
                        $firstname,
                        $email,
                        $phone,
                        $_SESSION['id_company']
                    );

                    if ($updated) {
                        // Mettre à jour les informations dans la session
                        $_SESSION['username'] = $username;
                        $_SESSION['lastname'] = $lastname;
                        $_SESSION['firstname'] = $firstname;
                        $_SESSION['email'] = $email;
                        $_SESSION['phone'] = $phone;

                        $_SESSION['success_message'] = "Votre profil a été mis à jour avec succès.";
                        header('Location: index.php?action=dashboard');
                        exit();
                    } else {
                        $errors[] = "Erreur lors de la mise à jour du profil.";
                    }
                } catch (PDOException $e) {
                    $errors[] = "Erreur lors de la mise à jour du profil.";
                    error_log("Erreur updateUser: " . $e->getMessage());
                }
            }
        }

        // Afficher le formulaire de modification avec les erreurs éventuelles
        $content = __DIR__ . '/../Views/edit_user.php';
        require_once __DIR__ . '/../Views/gabarit.php';
    }

    /**
     * Affiche le tableau de bord
     */
    public function dashboard()
    {
        // Vérifier que l'utilisateur est connecté
        if (!self::isLoggedIn()) {
            header('Location: index.php?action=login');
            exit();
        }

        // Récupérer les données de l'entreprise
        $company = null;
        if (isset($_SESSION['id_company'])) {
            $company = $this->companyModel->getCompanyById($_SESSION['id_company']);
        }

        // Afficher le dashboard
        $content = __DIR__ . '/../Views/dashboard.php';
        require_once __DIR__ . '/../Views/gabarit.php';
    }

    /**
     * Affiche sa fiche utilisateur
     */
    public function profileUser()
    {
        $errors = [];

        // Vérifier que l'utilisateur est connecté
        if (!self::isLoggedIn()) {
            header('Location: index.php?action=login');
            exit();
        }

        // Récupérer les informations de l'utilisateur
        $user = $this->userModel->getUserById($_SESSION['user_id']);

        if (!$user) {
            $errors[] = "Utilisateur introuvable.";
        } else {
            // Récupérer les informations de l'entreprise si disponible
            if (isset($_SESSION['id_company'])) {
                $company = $this->companyModel->getCompanyById($_SESSION['id_company']);
                if ($company && isset($company['company_name'])) {
                    $user['company_name'] = $company['company_name'];
                }
            }
        }

        // Afficher la page de profil
        $content = __DIR__ . '/../Views/profile_user.php';
        require_once __DIR__ . '/../Views/gabarit.php';
    }

    /**
     * Affiche la liste de tous les utilisateurs (admin uniquement)
     */
    public function listUsers()
    {
        // Vérifier que l'utilisateur est admin
        if (!self::isAdmin()) {
            header('Location: index.php?action=dashboard');
            exit();
        }

        // Récupérer tous les utilisateurs
        $users = $this->userModel->getUsers();

        // Ajouter le nom de l'entreprise pour chaque utilisateur
        foreach ($users as &$user) {
            if (!empty($user['id_company'])) {
                $company = $this->companyModel->getCompanyById($user['id_company']);
                $user['company_name'] = $company['company_name'] ?? 'N/A';
            } else {
                $user['company_name'] = 'N/A';
            }
        }
        unset($user); // Détruire la référence pour éviter les bugs

        // Afficher la liste
        $content = __DIR__ . '/../Views/list_users.php';
        require_once __DIR__ . '/../Views/gabarit.php';
    }

    /**
     * Affiche le profil d'un utilisateur spécifique (admin uniquement)
     */
    public function viewUser()
    {
        // Vérifier que l'utilisateur est admin
        if (!self::isAdmin()) {
            header('Location: index.php?action=dashboard');
            exit();
        }

        // Récupérer l'ID de l'utilisateur à afficher
        $userId = $_GET['id'] ?? null;

        if (!$userId) {
            header('Location: index.php?action=list-users');
            exit();
        }

        // Récupérer les informations de l'utilisateur
        $user = $this->userModel->getUserById($userId);

        if (!$user) {
            $_SESSION['error_message'] = "Utilisateur introuvable.";
            header('Location: index.php?action=list-users');
            exit();
        }

        // Récupérer les informations de l'entreprise si disponible
        if (!empty($user['id_company'])) {
            $company = $this->companyModel->getCompanyById($user['id_company']);
            if ($company && isset($company['company_name'])) {
                $user['company_name'] = $company['company_name'];
            }
        }

        // Afficher la page de profil
        $content = __DIR__ . '/../Views/profile_user.php';
        require_once __DIR__ . '/../Views/gabarit.php';
    }

    /**
     * Active ou désactive un utilisateur (admin uniquement)
     */
    public function toggleUserEnabled()
    {
        // Vérifier que l'utilisateur est admin
        if (!self::isAdmin()) {
            header('Location: index.php?action=dashboard');
            exit();
        }

        $id = $_GET['id'] ?? null;
        $enabled = $_GET['enabled'] ?? null;
        $redirect = $_GET['redirect'] ?? 'index.php?action=list-users';

        if ($id && $enabled !== null) {
            // Vérifier que l'utilisateur n'essaie pas de se désactiver lui-même
            if ($id == $_SESSION['user_id']) {
                $_SESSION['error_message'] = "Vous ne pouvez pas désactiver votre propre compte.";
            } else {
                try {
                    $newStatus = ($enabled == 1) ? 0 : 1;
                    $success = $this->userModel->updateUserEnabled($id, $newStatus);
                    
                    if ($success) {
                        $_SESSION['success_message'] = "Le statut de l'utilisateur a été mis à jour.";
                    } else {
                        $_SESSION['error_message'] = "Erreur lors de la mise à jour du statut.";
                    }
                } catch (PDOException $e) {
                    $_SESSION['error_message'] = "Erreur lors de la mise à jour du statut.";
                    error_log("Erreur updateUserEnabled: " . $e->getMessage());
                }
            }
        }

        header('Location: ' . $redirect);
        exit();
    }
}
