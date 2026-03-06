<div class="container d-flex justify-content-center div-container">
    <div class="text-center form-wrapper">
        <div class="card p-4 mb-5 rounded-4 form-card">

            <a href="index.php" id="closeBtn">
                <img src="assets/img/close-btn.svg" alt="Fermer">
            </a>

            <h1 class="text-uppercase mb-4 fw-bold title-custom">
                <?= isset($swot['id_swot']) ? 'Modifier le SWOT' : 'Votre SWOT' ?>
            </h1>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <p class="mb-0"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="index.php?action=save-swot" method="post" id="swotForm">
                <input type="hidden" name="company_id" value="<?= (int)($company['id_company'] ?? 0) ?>">
                <input type="hidden" name="swot_id"    value="<?= (int)($swot['id_swot']    ?? 0) ?>">

                <!-- Étape 1 — Facteurs internes -->
                <div class="row g-3 mb-3 step">

                    <!-- Titre -->
                    <div class="col-md-12 text-start">
                        <label for="swotTitle" class="form-label">
                            Titre du SWOT <span class="required">*</span>
                        </label>
                        <input type="text"
                               id="swotTitle"
                               name="title"
                               class="form-control form-control-custom"
                               value="<?= htmlspecialchars($swot['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                               placeholder="Ex : Analyse stratégique 2026"
                               required>
                    </div>

                    <!-- Séparateur interne -->
                    <div class="col-md-12 text-start mt-4">
                        <p class="form-label fw-semibold mb-1" style="color: var(--color-violet-1)">
                            <i class="bi bi-circle-half me-1"></i>
                            Facteurs internes
                        </p>
                        <hr class="mt-0 mb-2">
                    </div>

                    <!-- Forces -->
                    <div class="col-md-6 text-start">
                        <label class="form-label d-flex align-items-center gap-2 swot-label swot-label-positive">
                            <span class="swot-quadrant-icon swot-strengths" style="width:28px;height:28px;font-size:.85rem;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;">
                                <i class="bi bi-lightning-charge-fill"></i>
                            </span>
                            Forces
                        </label>
                        <div id="items-strength" class="mb-2">
                            <?php foreach ($itemsByCategory['strength'] ?? [] as $val): ?>
                                <div class="swot-item-row">
                                    <input type="text"
                                           name="items[strength][]"
                                           class="form-control form-control-sm swot-item-input"
                                           value="<?= htmlspecialchars($val, ENT_QUOTES, 'UTF-8') ?>"
                                           placeholder="Saisir une force…">
                                    <button type="button" class="btn-swot-delete" onclick="removeItem(this)" title="Supprimer">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" class="btn btn-sm btn-add-swot-item" onclick="addItem('strength', 'Saisir une force…')">
                            <i class="bi bi-plus-lg me-1"></i> Ajouter
                        </button>
                    </div>

                    <!-- Faiblesses -->
                    <div class="col-md-6 text-start">
                        <label class="form-label d-flex align-items-center gap-2 swot-label swot-label-negative">
                            <span class="swot-quadrant-icon swot-weaknesses" style="width:28px;height:28px;font-size:.85rem;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                            </span>
                            Faiblesses
                        </label>
                        <div id="items-weakness" class="mb-2">
                            <?php foreach ($itemsByCategory['weakness'] ?? [] as $val): ?>
                                <div class="swot-item-row">
                                    <input type="text"
                                           name="items[weakness][]"
                                           class="form-control form-control-sm swot-item-input"
                                           value="<?= htmlspecialchars($val, ENT_QUOTES, 'UTF-8') ?>"
                                           placeholder="Saisir une faiblesse…">
                                    <button type="button" class="btn-swot-delete" onclick="removeItem(this)" title="Supprimer">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" class="btn btn-sm btn-add-swot-item" onclick="addItem('weakness', 'Saisir une faiblesse…')">
                            <i class="bi bi-plus-lg me-1"></i> Ajouter
                        </button>
                    </div>

                </div><!-- /.step 1 -->

                <!-- Étape 2 — Facteurs externes -->
                <div class="row g-3 mb-3 step">

                    <!-- Séparateur externe -->
                    <div class="col-md-12 text-start">
                        <p class="form-label fw-semibold mb-1" style="color: var(--color-magenta)">
                            <i class="bi bi-globe me-1"></i>
                            Facteurs externes
                        </p>
                        <hr class="mt-0 mb-2">
                    </div>

                    <!-- Opportunités -->
                    <div class="col-md-6 text-start">
                        <label class="form-label d-flex align-items-center gap-2 swot-label swot-label-positive">
                            <span class="swot-quadrant-icon swot-opportunities" style="width:28px;height:28px;font-size:.85rem;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;">
                                <i class="bi bi-arrow-up-right-circle-fill"></i>
                            </span>
                            Opportunités
                        </label>
                        <div id="items-opportunity" class="mb-2">
                            <?php foreach ($itemsByCategory['opportunity'] ?? [] as $val): ?>
                                <div class="swot-item-row">
                                    <input type="text"
                                           name="items[opportunity][]"
                                           class="form-control form-control-sm swot-item-input"
                                           value="<?= htmlspecialchars($val, ENT_QUOTES, 'UTF-8') ?>"
                                           placeholder="Saisir une opportunité…">
                                    <button type="button" class="btn-swot-delete" onclick="removeItem(this)" title="Supprimer">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" class="btn btn-sm btn-add-swot-item" onclick="addItem('opportunity', 'Saisir une opportunité…')">
                            <i class="bi bi-plus-lg me-1"></i> Ajouter
                        </button>
                    </div>

                    <!-- Menaces -->
                    <div class="col-md-6 text-start">
                        <label class="form-label d-flex align-items-center gap-2 swot-label swot-label-negative">
                            <span class="swot-quadrant-icon swot-threats" style="width:28px;height:28px;font-size:.85rem;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;">
                                <i class="bi bi-shield-exclamation"></i>
                            </span>
                            Menaces
                        </label>
                        <div id="items-threat" class="mb-2">
                            <?php foreach ($itemsByCategory['threat'] ?? [] as $val): ?>
                                <div class="swot-item-row">
                                    <input type="text"
                                           name="items[threat][]"
                                           class="form-control form-control-sm swot-item-input"
                                           value="<?= htmlspecialchars($val, ENT_QUOTES, 'UTF-8') ?>"
                                           placeholder="Saisir une menace…">
                                    <button type="button" class="btn-swot-delete" onclick="removeItem(this)" title="Supprimer">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" class="btn btn-sm btn-add-swot-item" onclick="addItem('threat', 'Saisir une menace…')">
                            <i class="bi bi-plus-lg me-1"></i> Ajouter
                        </button>
                    </div>

                </div><!-- /.step 2 -->

                <!-- Indication des champs obligatoires -->
                <p class="text-start form-label mb-0"><span class="required">*</span> Champ obligatoire</p>

                <!-- Boutons de navigation -->
                <div class="d-flex justify-content-between mt-3">
                    <button type="button" class="btn btn-back" id="prevBtn">Retour</button>
                    <button type="button" class="btn btn-next" id="nextBtn">Suivant</button>
                </div>

                <!-- Bouton de soumission -->
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-lg px-5 py-1 btn-connexion mt-3" id="submitBtn">
                        <img src="assets/img/cta-connection.svg" alt="enregistrer" class="btn-img-normal">
                        <img src="assets/img/cta-connection-hover.svg" alt="enregistrer" class="btn-img-hover">
                        <span class="btn-text"><?= isset($swot['id_swot']) ? 'Modifier' : 'Créer' ?></span>
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    function addItem(category, placeholder) {
        const container = document.getElementById('items-' + category);
        const row = document.createElement('div');
        row.className = 'swot-item-row';
        row.innerHTML =
            '<input type="text" name="items[' + category + '][]" ' +
                   'class="form-control form-control-sm swot-item-input" ' +
                   'placeholder="' + placeholder + '">' +
            '<button type="button" class="btn-swot-delete" onclick="removeItem(this)" title="Supprimer">' +
                '<i class="bi bi-x-lg"></i>' +
            '</button>';
        container.appendChild(row);
        row.querySelector('input').focus();
    }

    function removeItem(btn) {
        btn.closest('.swot-item-row').remove();
    }
</script>
