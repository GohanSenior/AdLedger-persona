<div class="container d-flex justify-content-center div-container">
    <div class="text-center form-wrapper">
        <div class="card p-4 rounded-4 form-card">
            <h1 class="text-uppercase mb-4 fw-bold title-custom">Votre espace</h1>
            <?php if (!empty($_SESSION['info_message'])): ?>
                <div class="alert alert-info">
                    <p class="mb-0"><?= htmlspecialchars($_SESSION['info_message'], ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
                <?php unset($_SESSION['info_message']); ?>
            <?php endif; ?>
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <p class="mb-0"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <form action="index.php?action=login" method="post">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="username" class="form-label text-start d-block">Nom d'utilisateur</label>
                        <input type="text"
                            id="username"
                            name="username"
                            class="form-control form-control-custom"
                            autocomplete="username"
                            value="<?= htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            placeholder="Identifiant"
                            required>
                    </div>
                    <div class="col-md-6">
                        <label for="password" class="form-label text-start d-block">Mot de passe</label>
                        <div class="password-input-wrapper">
                            <input type="password"
                                id="password"
                                name="password"
                                class="form-control form-control-custom"
                                autocomplete="current-password"
                                value="<?= htmlspecialchars($_POST['password'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                placeholder="Mot de passe"
                                required>
                            <button type="button" class="toggle-password-btn">
                                <img src="assets/img/eye-inactive.svg" alt="eye-inactive" class="eye-inactive">
                                <img src="assets/img/eye-active.svg" alt="eye-active" class="eye-active">
                            </button>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-lg px-5 py-1 mt-3 btn-connexion">
                        <img src="assets/img/cta-connection.svg" alt="connection" class="btn-img-normal">
                        <img src="assets/img/cta-connection-hover.svg" alt="connection" class="btn-img-hover">
                        <span class="btn-text">Connexion</span>
                    </button>
                </div>
            </form>
            <p class="d-flex flex-column justify-content-center mt-4 mb-0">
                <small class="mb-2">
                    <a href="index.php?action=forgot-password" class="text-decoration-none fw-bold color-link">
                        Mot de passe oublié ?
                    </a>
                </small>
                <small class="text-muted">
                    Pas encore inscrit ?
                    <a href="index.php?action=register" class="text-decoration-none fw-bold color-link">
                        Créer un compte
                    </a>
                </small>
            </p>
        </div>
    </div>
</div>