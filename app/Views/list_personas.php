<div class="container d-flex justify-content-center div-container">
    <div class="form-wrapper">
        <div class="card mb-5 rounded-4 persona-card">
            <a href="<?= !empty($viewingOtherUser) ? 'index.php?action=list-users' : 'index.php' ?>" id="closeBtn">
                <img src="assets/img/close-btn.svg" alt="Fermer">
            </a>
            <h1 class="text-uppercase mb-4 fw-bold title-custom text-center">
                <?php
                $currentAction = $_GET['action'] ?? '';
                if ($currentAction === 'list-personas-types') {
                    echo 'Personas types';
                } elseif ($currentAction === 'list-all-personas') {
                    echo 'Tous les personas';
                } elseif (!empty($viewingOtherUser) && isset($targetUserName) && $targetUserId != $_SESSION['user_id']) {
                    echo 'Personas de ' . htmlspecialchars($targetUserName, ENT_QUOTES, 'UTF-8');
                } else {
                    echo 'Vos personas';
                }
                ?>
            </h1>
            
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($_SESSION['success_message'], ENT_QUOTES, 'UTF-8'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($_SESSION['error_message'], ENT_QUOTES, 'UTF-8'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>
            
            <?php if ($currentAction === 'list-all-personas'): ?>
                <div class="mx-auto mb-3 col-md-6 d-flex align-items-center gap-2">
                    <label for="personaFilter" class="form-label-custom mb-0 text-nowrap">Filtrer par :</label>
                    <select id="personaFilter" class="form-select form-select-sm">
                        <option value="all">Tous les personas</option>
                        <option value="users">Personas des utilisateurs</option>
                        <option value="mine">Mes personas</option>
                        <option selected value="types">Personas types</option>
                    </select>
                </div>
            <?php endif; ?>
            <div class="table-responsive">
                <table id="personasTable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Nom</th>
                            <th scope="col">Âge</th>
                            <th scope="col">Profession</th>
                            <th id="th-actions" scope="col">Actions</th>
                            <?php if ($currentAction === 'list-all-personas'): ?>
                                <th class="d-none">Type</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($personas as $persona): ?>
                            <tr>
                                <td><?= htmlspecialchars($persona['persona_lastname'] . ' ' . $persona['persona_firstname'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($persona['persona_age'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($persona['persona_job'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="non-wrap">
                                    <a href="index.php?action=view-persona&id=<?= urlencode($persona['id_persona']) ?>&redirect=<?= urlencode($_SERVER['REQUEST_URI']) ?>"
                                        class="icone-list">
                                        <img class="icone-img img-normal" src="assets/img/persona-book.svg" alt="Voir" title="Voir le persona">
                                        <img class="icone-img img-hover" src="assets/img/persona-book-hover.svg" alt="Voir" title="Voir le persona">
                                    </a>
                                    <?php 
                                    // Afficher les boutons de modification/suppression selon le contexte
                                    $canEdit = false;
                                    if ($currentAction === 'list-personas-types') {
                                        // Pour les personas types, seulement les admins peuvent éditer
                                        $canEdit = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
                                    } elseif ($currentAction === 'list-all-personas') {
                                        // Pour la liste complète, seulement si le persona appartient à l'utilisateur
                                        $canEdit = $persona['id_user'] == $_SESSION['user_id'];
                                    } else {
                                        // Pour "mes personas", toujours autoriser, sauf si on voit les personas d'un autre utilisateur
                                        // Un admin consultant sa propre liste via user_id= peut toujours éditer ses propres personas
                                        $canEdit = empty($viewingOtherUser) || $persona['id_user'] == $_SESSION['user_id'];
                                    }
                                    
                                    if ($canEdit): 
                                    ?>
                                        <a href="index.php?action=edit-persona&id=<?= urlencode($persona['id_persona']) ?>&redirect=<?= urlencode($_SERVER['REQUEST_URI']) ?>"
                                            class="icone-list">
                                            <img class="icone-img img-normal" src="assets/img/persona-edit.svg" alt="Modifier" title="Modifier le persona">
                                            <img class="icone-img img-hover" src="assets/img/persona-edit-hover.svg" alt="Modifier" title="Modifier le persona">
                                        </a>
                                        <a href="index.php?action=delete-persona&id=<?= urlencode($persona['id_persona']) ?>&redirect=<?= urlencode($_SERVER['REQUEST_URI']) ?>"
                                            class="icone-list"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce persona ?');">
                                            <img class="icone-img img-normal" src="assets/img/persona-suppr-alt.svg" alt="Supprimer" title="Supprimer le persona">
                                            <img class="icone-img img-hover" src="assets/img/persona-suppr-alt-hover.svg" alt="Supprimer" title="Supprimer le persona">
                                        </a>
                                    <?php endif; ?>
                                </td>
                                <?php if ($currentAction === 'list-all-personas'): ?>
                                    <td class="d-none" data-is-type="<?= htmlspecialchars((string) ($persona['is_type'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" data-user-id="<?= htmlspecialchars((string) ($persona['id_user'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                        <?php 
                                        if ($persona['is_type'] == 1) {
                                            echo 'types';
                                        } elseif ($persona['id_user'] == $_SESSION['user_id']) {
                                            echo 'mine';
                                        } else {
                                            echo 'users';
                                        }
                                        ?>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>