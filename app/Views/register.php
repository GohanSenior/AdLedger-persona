<div class="container d-flex justify-content-center div-container">
    <div class="text-center form-wrapper">
        <div class="card p-4 rounded-4 form-card">
            <h1 class="text-uppercase mb-4 fw-bold title-custom">Votre Compte</h1>
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <p class="mb-0"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <form action="index.php?action=register" method="post">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="username" class="form-label text-start d-block">Nom d'utilisateur <span class="required">*</span></label>
                        <input type="text"
                            id="username"
                            name="username"
                            class="form-control form-control-custom"
                            autocomplete="username"
                            value="<?= htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            data-validate="username"
                            minlength="5"
                            placeholder="Minimum 5 caractères"
                            required>
                    </div>
                    <div class="col-md-6">
                        <label for="password" class="form-label text-start d-block">Mot de passe <span class="required">*</span></label>
                        <div class="password-input-wrapper">
                            <input type="password"
                                id="password"
                                name="password"
                                class="form-control form-control-custom"
                                placeholder="Minimum 8 caractères"
                                autocomplete="new-password"
                                value="<?= htmlspecialchars($_POST['password'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                data-validate="password"
                                required
                                minlength="8">
                            <div class="invalid-feedback mt-0"></div>
                            <button type="button" class="toggle-password-btn">
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
                            value="<?= htmlspecialchars($_POST['lastname'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required>
                    </div>
                    <div class="col-md-6">
                        <label for="firstname" class="form-label text-start d-block">Prénom <span class="required">*</span></label>
                        <input type="text"
                            id="firstname"
                            name="firstname"
                            class="form-control form-control-custom"
                            autocomplete="given-name"
                            value="<?= htmlspecialchars($_POST['firstname'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label text-start d-block">Email <span class="required">*</span></label>
                        <input type="email"
                            id="email"
                            name="email"
                            class="form-control form-control-custom"
                            autocomplete="email"
                            value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            data-validate="email"
                            required>
                        <div class="invalid-feedback mt-0"></div>
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label text-start d-block">Téléphone</label>
                        <input type="tel"
                            id="phone"
                            name="phone"
                            class="form-control form-control-custom"
                            autocomplete="tel"
                            value="<?= htmlspecialchars($_POST['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            data-validate="phone">
                        <div class="invalid-feedback mt-0"></div>
                    </div>
                </div>
                <h2 class="text-uppercase mt-4 mb-4 fw-bold title-custom">Votre Entreprise</h2>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label text-start d-block">Raison Sociale <span class="required">*</span></label>
                        <input type="text"
                            id="name"
                            name="name"
                            class="form-control form-control-custom"
                            autocomplete="organization"
                            value="<?= htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required>
                    </div>
                    <div class="col-md-6">
                        <label for="address" class="form-label text-start d-block">Adresse <span class="required">*</span></label>
                        <input type="text"
                            id="address"
                            name="address"
                            class="form-control form-control-custom"
                            autocomplete="street-address"
                            value="<?= htmlspecialchars($_POST['address'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required>
                    </div>
                    <div class="col-md-6">
                        <label for="zipcode" class="form-label text-start d-block">Code Postal <span class="required">*</span></label>
                        <input type="text"
                            id="zipcode"
                            name="zipcode"
                            class="form-control form-control-custom"
                            autocomplete="postal-code"
                            value="<?= htmlspecialchars($_POST['zipcode'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            data-validate="zipcode"
                            pattern="[0-9]{5}"
                            maxlength="5"
                            required>
                        <div class="invalid-feedback mt-0"></div>
                    </div>
                    <div class="col-md-6">
                        <label for="city" class="form-label text-start d-block">Ville <span class="required">*</span></label>
                        <input type="text"
                            id="city"
                            name="city"
                            class="form-control form-control-custom"
                            autocomplete="address-level2"
                            value="<?= htmlspecialchars($_POST['city'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required>
                    </div>
                    <p class="text-start form-label mb-0"><span class="required">*</span> Champs obligatoires</p>
                </div>
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-lg px-5 py-1 btn-connexion mt-3">
                        <img src="assets/img/cta-connection.svg" alt="register" class="btn-img-normal">
                        <img src="assets/img/cta-connection-hover.svg" alt="register" class="btn-img-hover">
                        <span class="btn-text">S'inscrire</span>
                    </button>
                </div>
            </form>
            <p class="text-center mt-4 mb-0">
                <small class="text-muted not-register">
                    Déjà inscrit ?
                    <a href="index.php?action=login" class="text-decoration-none fw-bold color-link">
                        Se connecter
                    </a>
                </small>
            </p>
        </div>
    </div>
</div>