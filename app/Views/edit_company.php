<div class="container d-flex justify-content-center div-container">
    <div class="text-center form-wrapper">
        <div class="card p-4 mb-5 rounded-4 form-card">
            <a href="index.php?action=dashboard" id="closeBtn">
                <img src="assets/img/close-btn.svg" alt="Fermer">
            </a>
            <h1 class="text-uppercase mb-4 fw-bold title-custom">Votre Entreprise</h1>
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <p class="mb-0"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <form action="index.php?action=edit-company" method="post" enctype="multipart/form-data">
                <div class="row g-3 mb-3">
                    <div class="col-md-12">
                        <label for="name" class="form-label text-start d-block">Raison sociale <span class="required">*</span></label>
                        <input type="text"
                            id="name"
                            name="name"
                            class="form-control form-control-custom"
                            autocomplete="organization"
                            value="<?= htmlspecialchars($company['company_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required>
                    </div>
                    <div class="col-md-12">
                        <label for="address" class="form-label text-start d-block">Adresse <span class="required">*</span></label>
                        <input type="text"
                            id="address"
                            name="address"
                            class="form-control form-control-custom"
                            autocomplete="street-address"
                            value="<?= htmlspecialchars($company['company_address'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required>
                    </div>
                    <div class="col-md-6">
                        <label for="zipcode" class="form-label text-start d-block">Code postal <span class="required">*</span></label>
                        <input type="text"
                            id="zipcode"
                            name="zipcode"
                            class="form-control form-control-custom"
                            autocomplete="postal-code"
                            value="<?= htmlspecialchars($company['company_zipcode'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            data-validate="zipcode"
                            pattern="[0-9]{5}"
                            maxlength="5"
                            required>
                    </div>
                    <div class="col-md-6">
                        <label for="city" class="form-label text-start d-block">Ville <span class="required">*</span></label>
                        <input type="text"
                            id="city"
                            name="city"
                            class="form-control form-control-custom"
                            autocomplete="address-level2"
                            value="<?= htmlspecialchars($company['company_city'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required>
                    </div>
                    <div class="col-md-12">
                        <label for="logo_url" class="form-label text-start d-block">Logo entreprise</label>
                        <?php if (!empty($company['logo_url'])): ?>
                            <div class="mb-2 logo-preview">
                                <img src="<?= htmlspecialchars($company['logo_url'], ENT_QUOTES, 'UTF-8') ?>" alt="Logo actuel">
                                <p class="text-muted small">Logo actuel</p>
                            </div>
                        <?php endif; ?>
                        <input type="file"
                            id="logo_url"
                            name="logo_url"
                            class="form-control form-control-custom"
                            accept="image/png, image/jpeg, image/jpg, image/gif, image/webp">
                        <small class="text-muted">Formats acceptés : JPG, PNG, GIF, WEBP (max 5 Mo)</small>
                    </div>
                </div>
                <p class="text-start form-label mb-0"><span class="required">*</span> Champs obligatoires</p>
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-lg px-5 py-1 btn-connexion mt-3">
                        <img src="assets/img/cta-connection.svg" alt="modify" class="btn-img-normal">
                        <img src="assets/img/cta-connection-hover.svg" alt="modify" class="btn-img-hover">
                        <span class="btn-text">Modifier</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>