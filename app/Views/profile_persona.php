<div class="container d-flex justify-content-center div-container">
    <div class="profile-wrapper">
        <div class="card rounded-4 profile-card">
            <a href="<?= htmlspecialchars($redirect, ENT_QUOTES, 'UTF-8') ?>" id="closeBtn">
                <img src="assets/img/close-btn.svg" alt="Fermer">
            </a>

            <!-- En-tête du profil -->
            <div class="profile-header">
                <div class="profile-avatar">
                    <img src="<?= htmlspecialchars($avatarUrl, ENT_QUOTES, 'UTF-8') ?>" alt="Avatar de <?= htmlspecialchars($persona['persona_firstname'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="avatar-persona">
                </div>
                <h1 class="profile-name">
                    <?= htmlspecialchars($persona['persona_firstname'] . ' ' . $persona['persona_lastname'], ENT_QUOTES, 'UTF-8') ?>
                </h1>
                <p class="profile-username">
                    <?php if ($persona['is_type'] == 1): ?>
                        <i class="bi bi-star-fill"></i> Persona type
                    <?php else: ?>
                        Persona personnel
                    <?php endif; ?>
                </p>
            </div>

            <div class="profile-content">
                <!-- Section Informations générales -->
                <div class="profile-section">
                    <h3 class="section-title">Informations générales</h3>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="info-item">
                                <span class="info-icon"><i class="bi bi-cake2-fill"></i></span>
                                <div class="info-content">
                                    <span class="info-label">Âge</span>
                                    <span class="info-value"><?= htmlspecialchars($persona['persona_age'] ?? 'Non renseigné', ENT_QUOTES, 'UTF-8') ?> ans</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-item">
                                <span class="info-icon"><i class="bi bi-gender-ambiguous"></i></span>
                                <div class="info-content">
                                    <span class="info-label">Sexe</span>
                                    <span class="info-value"><?= htmlspecialchars($persona['persona_sexe'] ?? 'Non renseigné', ENT_QUOTES, 'UTF-8') ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-item">
                                <span class="info-icon"><i class="bi bi-geo-alt-fill"></i></span>
                                <div class="info-content">
                                    <span class="info-label">Ville</span>
                                    <span class="info-value"><?= htmlspecialchars($persona['persona_city'] ?? 'Non renseignée', ENT_QUOTES, 'UTF-8') ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Professionnel -->
                <div class="profile-section">
                    <h3 class="section-title">Professionnel</h3>
                    <div class="info-item">
                        <span class="info-icon"><i class="bi bi-briefcase-fill"></i></span>
                        <div class="info-content">
                            <span class="info-label">Poste</span>
                            <span class="info-value"><?= htmlspecialchars($persona['persona_job'] ?? 'Non renseigné', ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                    </div>
                </div>

                <!-- Section Critères associés -->
                <?php if (!empty($personaCriteria)): ?>
                    <div class="profile-section">
                        <h3 class="section-title">Critères associés</h3>
                        <?php
                        $criteriaByType = [];
                        foreach ($personaCriteria as $criterion) {
                            $typeName = $criterion['criteria_type_name'];
                            if (!isset($criteriaByType[$typeName])) {
                                $criteriaByType[$typeName] = [];
                            }
                            $criteriaByType[$typeName][] = $criterion['criterion_description'];
                        }
                        ?>
                        <?php foreach ($criteriaByType as $typeName => $criteria): ?>
                            <div class="criteria-group mb-3">
                                <h4 class="criteria-group-title">
                                    <i class="bi bi-bookmark-fill"></i> <?= htmlspecialchars($typeName, ENT_QUOTES, 'UTF-8'); ?>
                                </h4>
                                <ul class="criteria-list">
                                    <?php foreach ($criteria as $description): ?>
                                        <li>
                                            <i class="bi bi-check-circle-fill criteria-check-icon"></i>
                                            <?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8'); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Section Actions Admin -->
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin' && $persona['is_type'] == 0): ?>
                    <div class="profile-section">
                        <h3 class="section-title">Actions administrateur</h3>
                        <div class="info-item">
                            <span class="info-icon">
                                <?php if ($persona['typed'] == 1): ?>
                                    <img src="assets/img/toggle-on-alt.svg" alt="enabled">
                                <?php else: ?>
                                    <a href="index.php?action=toggle-persona&id=<?= urlencode($persona['id_persona']) ?>"
                                        onclick="return confirm('Êtes-vous sûr de vouloir copier ce persona en persona type ?');"
                                        title="Copier ce persona en persona type">
                                        <img src="assets/img/toggle-off-alt2.svg" alt="disabled">
                                    </a>
                                <?php endif; ?>
                            </span>
                            <div class="info-content">
                                <span class="info-label">Persona type</span>
                                <span class="info-value">
                                    <?php if ($persona['typed'] == 1): ?>
                                        <span class="badge bg-success-custom">Déjà copié en persona type</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary-custom">Copier en persona type</span>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Section Récupération persona type -->
                <?php if ($persona['is_type'] == 1 && $persona['id_user'] != $_SESSION['user_id']): ?>
                    <div class="profile-section">
                        <h3 class="section-title">Récupérer ce persona</h3>
                        <div class="info-item">
                            
                            <a href="index.php?action=toggle-persona-type-to-normal&id=<?= urlencode($persona['id_persona']) ?>"
                                onclick="return confirm('Voulez-vous récupérer ce persona type dans vos personas ?');"
                                class="btn btn-sm btn-custom">
                                <span class="info-icon"><i class="bi bi-download"></i></span>
                            </a>
                            <!-- <span class="info-icon"><i class="bi bi-download"></i></span> -->
                            <div class="info-content">
                                <span class="info-label">Action</span>
                                <span class="info-value">
                                    <span class="badge bg-secondary-custom">Récupérer ce persona type</span>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>