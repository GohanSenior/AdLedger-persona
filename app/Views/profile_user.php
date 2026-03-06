<div class="container d-flex justify-content-center div-container">
    <div class="profile-wrapper">
        <div class="card rounded-4 profile-card">
            <?php if (isset($_GET['id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="index.php?action=list-users" id="closeBtn">
                    <img src="assets/img/close-btn.svg" alt="Fermer">
                </a>
            <?php else: ?>
                <a href="index.php" id="closeBtn">
                    <img src="assets/img/close-btn.svg" alt="Fermer">
                </a>
            <?php endif; ?>

            <div class="profile-header">
                <div class="profile-avatar">
                    <?php
                    $initials = strtoupper(substr($user['firstname'], 0, 1) . substr($user['lastname'], 0, 1));
                    ?>
                    <div class="avatar-circle"><?= htmlspecialchars($initials, ENT_QUOTES, 'UTF-8') ?></div>
                </div>
                <h1 class="profile-name">
                    <?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname'], ENT_QUOTES, 'UTF-8') ?>
                </h1>
                <p class="profile-username">@<?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?></p>
            </div>

            <div class="profile-content">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <p class="mb-0"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

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

                <div class="profile-section">
                    <h3 class="section-title">Coordonnées</h3>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="info-item">
                                <span class="info-icon"><i class="bi bi-envelope-fill"></i></span>
                                <div class="info-content">
                                    <span class="info-label">Email</span>
                                    <a href="mailto:<?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?>" itemprop="email" class="info-value">
                                        <?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?>
                                    </a>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <span class="info-icon"><i class="bi bi-telephone-fill"></i></span>
                                <div class="info-content">
                                    <span class="info-label">Téléphone</span>
                                    <?php if (!empty($user['phone'])): ?>
                                        <a href="tel:<?= htmlspecialchars($user['phone'], ENT_QUOTES, 'UTF-8') ?>" itemprop="telephone" class="info-value">
                                            <?= htmlspecialchars($user['phone'], ENT_QUOTES, 'UTF-8') ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="info-value">Non renseigné</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="profile-section">
                    <h3 class="section-title">Entreprise</h3>
                    <div class="info-item">
                        <span class="info-icon"><i class="bi bi-building-fill"></i></span>
                        <div class="info-content">
                            <span class="info-label">Raison sociale</span>
                            <span class="info-value"><?= htmlspecialchars($user['company_name'] ?? 'Non renseigné', ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                    </div>
                </div>

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <div class="profile-section">
                        <h3 class="section-title">Informations du compte</h3>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-icon"><i class="bi bi-key-fill"></i></span>
                                    <div class="info-content">
                                        <span class="info-label">Rôle</span>
                                        <span class="info-value">
                                            <?php if ($user['role'] === 'admin'): ?>
                                                Admin <img src="assets/img/crown.svg" alt="admin" class="crown">
                                            <?php else: ?>
                                                Utilisateur
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-icon">
                                        <?php if ($user['id_user'] != $_SESSION['user_id']): ?>
                                            <a href="index.php?action=toggle-user&id=<?= urlencode($user['id_user']) ?>&enabled=<?= urlencode($user['enabled']) ?>&redirect=<?= urlencode($_SERVER['REQUEST_URI']) ?>"
                                                onclick="return confirm('Êtes-vous sûr de vouloir <?= $user['enabled'] == 1 ? 'désactiver' : 'activer' ?> cet utilisateur ?');"
                                                title="<?= $user['enabled'] == 1 ? 'Désactiver' : 'Activer' ?> l'utilisateur">
                                                <?php if ($user['enabled'] == 1): ?>
                                                    <img src="assets/img/toggle-on-alt.svg" alt="enabled">
                                                <?php else: ?>
                                                    <img src="assets/img/toggle-off-alt2.svg" alt="disabled">
                                                <?php endif; ?>
                                            </a>
                                        <?php else: ?>
                                            <i class="bi bi-person-fill"></i>
                                        <?php endif; ?>
                                    </span>
                                    <div class="info-content">
                                        <span class="info-label">Statut</span>
                                        <span class="info-value">
                                            <?php if ($user['enabled'] == 1): ?>
                                                <span class="badge bg-success-custom">Actif</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary-custom">Inactif</span>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>