<?php $currentAction = $_GET['action'] ?? 'home'; ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/datatables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>AdLedger</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid flex-column align-items-stretch">
            <div class="d-flex justify-content-between mb-2 w-100">
                <a class="navbar-brand"
                    href=<?php if (isset($_SESSION['user_id'])) {
                                echo "index.php?action=dashboard";
                            } else {
                                echo "index.php?action=home";
                            } ?>>
                    AdLedger
                </a>
                <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <hr class="w-100 m-0 d-none d-lg-block">
            <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
                <hr class="w-100 d-lg-none">
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <!-- $currentAction: action sur le lien active -->
                            <a class="nav-link fw-medium <?= $currentAction === 'dashboard' ? 'active' : '' ?>"
                                <?= $currentAction === 'dashboard' ? 'aria-current="page"' : '' ?>
                                href="index.php?action=dashboard">
                                <img class="me-1" src="assets/img/dashboard-lnk.svg" alt="Dashboard">
                                Tableau de bord
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link fw-medium dropdown-toggle 
                            <?= in_array($currentAction, ['list-personas', 'list-personas-types', 'create-persona', 'list-operations']) && !isset($_GET['user_id']) ? 'active' : '' ?>"
                                href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img class="me-1" src="assets/img/personas-alt.svg" alt="Personas">
                                Mes personas
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item fw-medium" href="index.php?action=list-personas">
                                        <img class="me-1" src="assets/img/personas.svg" alt="View Personas">
                                        Voir mes personas
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item fw-medium" href="index.php?action=list-personas-types">
                                        <img class="me-1" src="assets/img/persona-type-alt.svg" alt="View Personas Types">
                                        Voir les personas types
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item fw-medium" href="index.php?action=create-persona">
                                        <img class="me-1" src="assets/img/persona-add-alt.svg" alt="Add Persona">
                                        Ajouter un persona
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item fw-medium" href="index.php?action=list-operations">
                                        <img class="me-1" src="assets/img/operation-list.svg" alt="operations">
                                        Gérer mes opérations
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link fw-medium dropdown-toggle <?= in_array($currentAction, ['list-users', 'view-user', 'list-all-personas']) || ($currentAction === 'list-personas' && isset($_GET['user_id'])) ? 'active' : '' ?>"
                                    href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img class="me-1" src="assets/img/user-plus.svg" alt="Administration">
                                    Administration
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item fw-medium" href="index.php?action=list-users">
                                            <img src="assets/img/client-lnk.svg" alt="utilisateurs" class="me-1">
                                            Gestion des utilisateurs
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item fw-medium" href="index.php?action=list-all-personas">
                                            <img src="assets/img/personas.svg" alt="personas" class="me-1">
                                            Gestion de tous les personas
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link fw-medium dropdown-toggle <?= in_array($currentAction, ['edit-user', 'profile-user', 'edit-company']) ? 'active' : '' ?>"
                                href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img class="me-1" src="assets/img/profile-lnk.svg" alt="Profile">
                                Mon profil
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item fw-medium" href="index.php?action=profile-user">
                                        <img class="me-1" src="assets/img/profile-lnk.svg" alt="Profile User">
                                        Ma fiche
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item fw-medium" href="index.php?action=edit-user">
                                        <img class="me-1" src="assets/img/cta-edit-hover.svg" alt="Edit User">
                                        Modifier mon profil
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item fw-medium" href="index.php?action=edit-company">
                                        <img class="me-1" src="assets/img/cta-edit-hover.svg" alt="Edit Company">
                                        Modifier mon entreprise
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item fw-medium" href="index.php?action=logout">
                                        <img class="me-1" src="assets/img/logout-lnk.svg" alt="Logout">
                                        Déconnexion
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link fw-medium <?= $currentAction === 'contact' ? 'active' : '' ?>"
                                href="https://www.toiledecom.fr/contactez-nous/" target="_blank">
                                <img class="me-1" src="assets/img/contact-lnk.svg" alt="Contact">
                                Nous contacter
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <section>
        <?php if (isset($content)) {
            require $content;
        } ?>
        <?php if (!isset($_SESSION['user_id']) && $currentAction !== 'register'): ?>
            <div class="card mx-auto mb-4 p-4 rounded-4 compass-card">
                <img id="compass" src="assets/img/compass-border.svg" alt="Boussole">
                <img id="compass-arrow" src="assets/img/compass-arrow.svg" alt="Fleche de la boussole">
            </div>
        <?php endif; ?>
    </section>
    <footer>
        <div class="container text-center py-3">
            <nav class="mb-2">
                <a href="https://www.toiledecom.fr/politique-de-confidentialite/" target="_blank" class="text-decoration-none me-3 text-footer">Politique de confidentialité</a>
                <span class="text-footer"> | </span>
                <a href="https://www.toiledecom.fr/mentions-legales/" target="_blank" class="text-decoration-none ms-3 text-footer">Mentions légales</a>
            </nav>
            <small class="text-footer">© <?= date('Y'); ?> AdLedger. Tous droits réservés.</small>
        </div>
    </footer>
    <script src="assets/js/datatables.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script>var userIsLoggedIn = <?= isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;</script>  <!-- Variable JavaScript pour indiquer si l'utilisateur est connecté -->
    <script src="assets/js/script.js"></script>
</body>

</html>