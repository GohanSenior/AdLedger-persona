<div class="container d-flex justify-content-center div-container">
    <div class="form-wrapper">
        <div class="card mb-5 rounded-4 persona-card">
            <a href="index.php" id="closeBtn">
                <img src="assets/img/close-btn.svg" alt="Fermer">
            </a>
            <h1 class="text-uppercase mb-4 fw-bold title-custom text-center">Gestion des utilisateurs</h1>

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

            <div class="table-responsive">
                <table id="usersTable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Nom</th>
                            <th scope="col">Email</th>
                            <th scope="col">Entreprise</th>
                            <th scope="col">Rôle</th>
                            <th scope="col">Statut</th>
                            <th id="th-actions" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['lastname'] . ' ' . $user['firstname'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($user['company_name'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <?php if ($user['role'] === 'admin'): ?>
                                        <span class="non-wrap">Admin <img src="assets/img/crown.svg" alt="admin" class="crown"></span>
                                    <?php else: ?>
                                        <span>User</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($user['enabled'] == 1): ?>
                                        <span class="badge bg-success-custom">Actif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary-custom">Inactif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="non-wrap">
                                    <a href="index.php?action=view-user&id=<?= urlencode($user['id_user']) ?>"
                                        class="icone-list"
                                        title="Voir le profil">
                                        <img class="icone-img img-normal" src="assets/img/persona-book.svg" alt="Voir">
                                        <img class="icone-img img-hover" src="assets/img/persona-book-hover.svg" alt="Voir">
                                    </a>
                                    <a href="index.php?action=list-personas&user_id=<?= urlencode($user['id_user']) ?>"
                                        class="icone-list"
                                        title="Gérer les personas de l'utilisateur">
                                        <img class="icone-img img-normal" src="assets/img/personas.svg" alt="liste personas">
                                        <img class="icone-img img-hover" src="assets/img/personas-hover.svg" alt="liste-personas">
                                    </a>
                                    <?php if ($user['id_user'] != $_SESSION['user_id']): ?>
                                        <a href="index.php?action=toggle-user&id=<?= urlencode($user['id_user']) ?>&enabled=<?= urlencode($user['enabled']) ?>&redirect=<?= urlencode($_SERVER['REQUEST_URI']) ?>"
                                            class="icone-list"
                                            onclick="return confirm('Êtes-vous sûr de vouloir <?= $user['enabled'] == 1 ? 'désactiver' : 'activer' ?> cet utilisateur ?');"
                                            title="<?= $user['enabled'] == 1 ? 'Désactiver' : 'Activer' ?> l'utilisateur">
                                            <?php if ($user['enabled'] == 1): ?>
                                                <img class="icone-img img-normal" src="assets/img/toggle-on-alt.svg" alt="enabled">
                                                <img class="icone-img img-hover" src="assets/img/toggle-on-alt2.svg" alt="enabled">
                                            <?php else: ?>
                                                <img class="icone-img img-normal" src="assets/img/toggle-off-alt2.svg" alt="disabled">
                                                <img class="icone-img img-hover" src="assets/img/toggle-off-alt.svg" alt="disabled">
                                            <?php endif; ?>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>