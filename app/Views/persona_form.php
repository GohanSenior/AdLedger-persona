<div class="container d-flex justify-content-center div-container">
    <div class="text-center form-wrapper">
        <div class="card p-4 mb-5 rounded-4 form-card">
            <a href="<?= $editMode ? 'javascript:history.back()' : 'index.php?action=dashboard' ?>" id="closeBtn">
                <img src="assets/img/close-btn.svg" alt="Fermer">
            </a>
            <h1 class="text-uppercase mb-4 fw-bold title-custom">Votre Persona</h1>
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <p class="mb-0"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <form action="<?= $editMode ? 'index.php?action=edit-persona&id=' . $persona['id_persona'] : 'index.php?action=create-persona' ?>" method="post">

                <!-- Informations de base -->
                <div class="row g-3 mb-3 step">
                    <div class="col-md-6">
                        <label for="lastname" class="form-label text-start d-block">Nom <span class="required">*</span></label>
                        <input type="text"
                            id="lastname"
                            name="lastname"
                            class="form-control form-control-custom"
                            value="<?= htmlspecialchars($persona['persona_lastname'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required>
                    </div>
                    <div class="col-md-6">
                        <label for="firstname" class="form-label text-start d-block">Prénom <span class="required">*</span></label>
                        <input type="text"
                            id="firstname"
                            name="firstname"
                            class="form-control form-control-custom"
                            value="<?= htmlspecialchars($persona['persona_firstname'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required>
                    </div>
                    <div class="col-md-6">
                        <label for="age" class="form-label text-start d-block">Âge <span class="required">*</span></label>
                        <input type="number"
                            id="age"
                            name="age"
                            min="0"
                            class="form-control form-control-custom"
                            value="<?= htmlspecialchars($persona['persona_age'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required>
                    </div>
                    <div class="col-md-6">
                        <label for="job" class="form-label text-start d-block">Poste <span class="required">*</span></label>
                        <input type="text"
                            id="job"
                            name="job"
                            class="form-control form-control-custom"
                            value="<?= htmlspecialchars($persona['persona_job'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required>
                    </div>
                    <div class="col-md-6">
                        <label for="city" class="form-label text-start d-block">Ville <span class="required">*</span></label>
                        <input type="text"
                            id="city"
                            name="city"
                            class="form-control form-control-custom"
                            value="<?= htmlspecialchars($persona['persona_city'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required>
                    </div>
                    <div class="col-md-6">
                        <label for="sexe" class="form-label text-start d-block">Sexe <span class="required">*</span></label>
                        <select id="sexe" name="sexe" class="form-select form-control-custom" required>
                            <option value="" disabled selected>Choisissez le sexe</option>
                            <option value="homme" <?= ($persona['persona_sexe'] ?? '') === 'homme' ? 'selected' : '' ?>>Homme</option>
                            <option value="femme" <?= ($persona['persona_sexe'] ?? '') === 'femme' ? 'selected' : '' ?>>Femme</option>
                            <option value="autre" <?= ($persona['persona_sexe'] ?? '') === 'autre' ? 'selected' : '' ?>>Autre</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="operation" class="form-label text-start d-block">Associer à une opération</label>
                        <input type="text"
                            id="operation"
                            name="operation"
                            class="form-control form-control-custom"
                            value="<?= htmlspecialchars($operation_name ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <?php if ($editMode): ?>
                        <div class="col-md-12 text-center">
                            <label class="form-label d-block">Avatar</label>
                            <img src="<?= htmlspecialchars($avatarUrl, ENT_QUOTES, 'UTF-8') ?>" alt="Avatar" class="mb-3">
                            <div>
                                <label class="form-check-label">
                                    <input type="checkbox" name="regenerate_avatar" class="form-check-input" value="1">
                                    Régénérer un nouvel avatar
                                </label>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Zone dynamique pour les critères par type -->
                <div class="row g-3 mb-3 step">
                    <?php if (!empty($criteriaTypes)): ?>
                        <?php foreach ($criteriaTypes as $type): ?>
                            <div class="col-md-12 text-start">
                                <label class="form-label d-block">
                                    <?= htmlspecialchars($type['criteria_type_name'], ENT_QUOTES, 'UTF-8') ?>
                                    <span class="required">*</span>
                                </label>
                                <div id="criteria-container-<?= $type['id_criteria_type'] ?>" class="mb-2"></div>
                                <button type="button" class="btn btn-sm btn-add-criteria"
                                    onclick="addCriteria(<?= $type['id_criteria_type'] ?>)">
                                    <img src="assets/img/plus.svg" alt="ajouter un critère" class="btn-img-normal me-1">
                                    <img src="assets/img/plus-hover.svg" alt="ajouter un critère" class="btn-img-hover me-1">
                                    Ajouter un critère
                                </button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Indication des champs obligatoires -->
                <p class="text-start form-label mb-0"><span class="required">*</span> Champs obligatoires</p>

                <!-- Boutons de navigation -->
                <div class="d-flex justify-content-between mt-3">
                    <button type="button" class="btn btn-back" id="prevBtn">Retour</button>
                    <button type="button" class="btn btn-next" id="nextBtn">Suivant</button>
                </div>

                <!-- Bouton de soumission, affiché uniquement à la dernière étape -->
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-lg px-5 py-1 btn-connexion mt-3" id="submitBtn">
                        <img src="assets/img/cta-connection.svg" alt="register" class="btn-img-normal">
                        <img src="assets/img/cta-connection-hover.svg" alt="register" class="btn-img-hover">
                        <span class="btn-text"><?= $editMode ? 'Modifier' : 'Créer' ?></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Données pour le script JavaScript -->
<div data-criteria-types='<?= htmlspecialchars(json_encode($criteriaTypes ?? []), ENT_QUOTES, 'UTF-8'); ?>'
    <?php if ($editMode): ?>
    data-persona-criteria='<?= htmlspecialchars(json_encode($personaCriteriaForJs ?? []), ENT_QUOTES, 'UTF-8'); ?>'
    <?php endif; ?>
    data-edit-mode="<?= $editMode ? 'true' : 'false' ?>"
    style="display: none;">
</div>