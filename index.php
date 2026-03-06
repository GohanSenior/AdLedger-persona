<?php
session_start();

spl_autoload_register(function ($class) {
    // Chercher dans le dossier app/
    $file = __DIR__ . '/app/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
        return;
    }

    // Chercher dans les sous-dossiers (Model, Controller, View)
    $folders = ['Models', 'Controllers', 'Views'];
    foreach ($folders as $folder) {
        $file = __DIR__ . '/app/' . $folder . '/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }

    // Chercher dans Services/Avatar
    $file = __DIR__ . '/app/Services/Avatar/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
        return;
    }

    // Chercher dans Services/PHPMailer
    $file = __DIR__ . '/app/Services/PHPMailer/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
        return;
    }

    // Chercher dans Services/PHPMailer/src
    $file = __DIR__ . '/app/Services/PHPMailer/src/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
        return;
    }
});

$action = $_GET['action'] ?? 'home';

try {

    switch ($action) {
        case 'register':
            $controller = new UsersController();
            $controller->register();
            exit();
        case 'login':
            $controller = new UsersController();
            $controller->login();
            exit();
        case 'forgot-password':
            $controller = new UsersController();
            $controller->forgotPassword();
            exit();
        case 'reset-password':
            $controller = new UsersController();
            $controller->resetPassword();
            exit();
        case 'dashboard':
            $controller = new UsersController();
            $controller->dashboard();
            exit();
        case 'edit-user':
            $controller = new UsersController();
            $controller->editUser();
            exit();
        case 'edit-company':
            $controller = new CompaniesController();
            $controller->editCompany();
            exit();
        case 'profile-user':
            $controller = new UsersController();
            $controller->profileUser();
            exit();
        case 'list-users':
            $controller = new UsersController();
            $controller->listUsers();
            exit();
        case 'view-user':
            $controller = new UsersController();
            $controller->viewUser();
            exit();
        case 'toggle-user':
            $controller = new UsersController();
            $controller->toggleUserEnabled();
            exit();
        case 'logout':
            $controller = new UsersController();
            $controller->logout();
            exit();
        case 'create-persona':
            $controller = new PersonasController();
            $controller->createPersona();
            exit();
        case 'list-personas':
            $controller = new PersonasController();
            $controller->listPersonas();
            exit();
        case 'list-personas-types':
            $controller = new PersonasController();
            $controller->listPersonasTypes();
            exit();
        case 'list-all-personas':
            $controller = new PersonasController();
            $controller->listAllPersonas();
            exit();
        case 'edit-persona':
            $controller = new PersonasController();
            $controller->editPersona();
            exit();
        case 'view-persona':
            $controller = new PersonasController();
            $controller->viewPersona();
            exit();
        case 'delete-persona':
            $controller = new PersonasController();
            $controller->deletePersona();
            exit();
        case 'toggle-persona':
            $controller = new PersonasController();
            $controller->togglePersonaNormalToType();
            exit();
        case 'toggle-persona-type-to-normal':
            $controller = new PersonasController();
            $controller->togglePersonaTypeToNormal();
            exit();
        case 'list-operations':
            $controller = new OperationsController();
            $controller->listOperations();
            exit();
        case 'edit-operation':
            $controller = new OperationsController();
            $controller->editOperation();
            exit();
        case 'view-operation':
            $controller = new OperationsController();
            $controller->viewOperation();
            exit();
        case 'delete-operation':
            $controller = new OperationsController();
            $controller->deleteOperation();
            exit();
        case 'search-criteria':
            $controller = new PersonasController();
            $controller->searchCriteria();
            exit();
        case 'swot-form':
            $controller = new SwotsController();
            $controller->showSwotForm();
            exit();
        case 'save-swot':
            $controller = new SwotsController();
            $controller->saveSwot();
            exit();
        case 'view-swot':
            $controller = new SwotsController();
            $controller->showSwot();
            exit();
        default:
            // Rediriger vers dashboard si connecté, sinon vers login
            if (isset($_SESSION['user_id'])) {
                header('Location: index.php?action=dashboard');
            } else {
                $content = 'app/Views/login.php';
                // $content = 'app/Views/profile_swot.php';  Rediriger vers profile_swot.php pour les tests
            }
            break;
    }

    require_once 'app/Views/gabarit.php';
} catch (RuntimeException $e) {
    http_response_code(503);
    echo '<p style="font-family:sans-serif;text-align:center;margin-top:4rem;color:#c0392b;">' . htmlspecialchars($e->getMessage()) . '</p>';
}
