<div class="container d-flex justify-content-center div-container">
    <div class="text-center form-wrapper">
        <div class="card p-4 mb-5 rounded-4 form-card">
            <a href="index.php" id="closeBtn">
                <img src="assets/img/close-btn.svg" alt="Fermer">
            </a>
            <h1 class="text-uppercase mb-4 fw-bold title-custom">Votre Compte</h1>
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <p class="mb-0"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <form action="index.php?action=edit-user" method="post">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="username" class="form-label text-start d-block">Nom d'utilisateur <span class="required">*</span></label>
                        <input type="text"
                            id="username"
                            name="username"
                            class="form-control form-control-custom"
                            autocomplete="username"
                            value="<?= htmlspecialchars($_SESSION['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            data-validate="username"
                            minlength="5"
                            placeholder="Minimum 5 caractères"
                            required>
                    </div>
                    <div class="col-md-6">
                        <label for="password" class="form-label text-start d-block">Nouveau mot de passe</label>
                        <div class="password-input-wrapper">
                            <input type="password"
                                id="password"
                                name="password"
                                class="form-control form-control-custom"
                                autocomplete="new-password"
                                placeholder="Laisser vide pour ne pas modifier"
                                value="<?= htmlspecialchars($_POST['password'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                data-validate="password"
                                minlength="8">
                            <div class="invalid-feedback mt-0"></div>
                            <button type="button" id="togglePassword" class="toggle-password-btn">
                                <img src="assets/img/eye-inactive.svg" alt="eye-inactive" class="eye-inactive">
                                <img src="assets/img/eye-active.svg" alt="eye-active" class="eye-active">
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="lastname" class="form-label text-start d-block">Nom <span class="required">*</span></label>
                        <input type="text"
                            id="lastname"
                            name="lastname"
                            class="form-control form-control-custom"
                            autocomplete="family-name"
                            value="<?= htmlspecialchars($_SESSION['lastname'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required>
                    </div>
                    <div class="col-md-6">
                        <label for="firstname" class="form-label text-start d-block">Prénom <span class="required">*</span></label>
                        <input type="text"
                            id="firstname"
                            name="firstname"
                            class="form-control form-control-custom"
                            autocomplete="given-name"
                            value="<?= htmlspecialchars($_SESSION['firstname'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label text-start d-block">Email <span class="required">*</span></label>
                        <input type="email"
                            id="email"
                            name="email"
                            class="form-control form-control-custom"
                            autocomplete="email"
                            value="<?= htmlspecialchars($_SESSION['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            data-validate="email"
                            required>
                        <div class="invalid-feedback mt-0"></div>
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label text-start d-block">Téléphone</label>
                        <input type="text"
                            id="phone"
                            name="phone"
                            class="form-control form-control-custom"
                            autocomplete="tel"
                            value="<?= htmlspecialchars($_SESSION['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            data-validate="phone">
                        <div class="invalid-feedback mt-0"></div>
                    </div>
                </div>
                <p class="text-start form-label mb-0"><span class="required">*</span> Champs obligatoires</p>
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-lg py-1 btn-connexion mt-3">
                        <img src="assets/img/cta-connection.svg" alt="modify" class="btn-img-normal">
                        <img src="assets/img/cta-connection-hover.svg" alt="modify" class="btn-img-hover">
                        <span class="btn-text">Modifier</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>