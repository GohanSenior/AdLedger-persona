<div class="container d-flex justify-content-center div-container">
    <div class="text-center form-wrapper">
        <div class="card p-4 rounded-4 form-card">
            <h1 class="text-uppercase mb-4 fw-bold title-custom">Nouveau mot de passe</h1>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <p class="mb-0"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($success) && $success): ?>
                <div class="alert alert-success">
                    <p class="mb-0"><strong>Votre mot de passe a été réinitialisé avec succès !</strong></p>
                    <p class="mb-0">Vous pouvez maintenant vous connecter avec votre nouveau mot de passe.</p>
                </div>
                <!-- <div class="mt-3">
                    <a href="index.php?action=login" class="btn btn-primary btn-lg">Se connecter</a>
                </div> -->
                <div class="d-flex justify-content-center">
                    <a href="index.php?action=login" class="btn btn-lg px-5 py-1 mt-3 btn-connexion">
                        <img src="assets/img/cta-connection.svg" alt="connection" class="btn-img-normal">
                        <img src="assets/img/cta-connection-hover.svg" alt="connection" class="btn-img-hover">
                        <span class="btn-text">Se connecter</span>
                    </a>
                </div>
            <?php elseif (isset($validToken) && $validToken): ?>
                <form action="index.php?action=reset-password&token=<?= htmlspecialchars($_GET['token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" method="post" class="mx-auto form-wrapper-sm">
                    <div class="row mb-3">
                        <label for="password" class="form-label text-start p-0">Nouveau mot de passe</label>
                        <div class="password-input-wrapper p-0">
                            <input type="password"
                                class="form-control form-control-custom"
                                id="password"
                                name="password"
                                autocomplete="new-password"
                                value="<?= htmlspecialchars($_POST['password'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                placeholder="Minimum 8 caractères"
                                data-validate="password"
                                required
                                minlength="8">
                            <div class="invalid-feedback mt-0"></div>
                            <button type="button" class="toggle-password-btn">
                                <img src="assets/img/eye-inactive.svg" alt="eye-inactive" class="eye-inactive">
                                <img src="assets/img/eye-active.svg" alt="eye-active" class="eye-active">
                            </button>
                        </div>
                        <small class="text-muted text-start">Au moins 8 caractères</small>
                    </div>
                    <div class="row mb-3">
                        <label for="confirm_password" class="form-label text-start p-0">Confirmer le mot de passe</label>
                        <div class="password-input-wrapper p-0">
                            <input type="password"
                                class="form-control form-control-custom"
                                id="confirm_password"
                                name="confirm_password"
                                autocomplete="new-password"
                                value="<?= htmlspecialchars($_POST['confirm_password'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                required
                                minlength="8">
                            <div class="invalid-feedback mt-0"></div>
                            <button type="button" class="toggle-password-btn">
                                <img src="assets/img/eye-inactive.svg" alt="eye-inactive" class="eye-inactive">
                                <img src="assets/img/eye-active.svg" alt="eye-active" class="eye-active">
                            </button>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-lg px-5 py-1 mt-3 btn-connexion">
                            <img src="assets/img/cta-connection.svg" alt="connection" class="btn-img-normal">
                            <img src="assets/img/cta-connection-hover.svg" alt="connection" class="btn-img-hover">
                            <span class="btn-text">Réinitialiser</span>
                        </button>
                    </div>
                </form>
            <?php else: ?>
                <div class="alert alert-warning">
                    <p class="mb-0">Le lien de réinitialisation est invalide ou a expiré.</p>
                </div>
                <small class="mt-3">
                    <a href="index.php?action=forgot-password" class="text-decoration-none fw-bold color-link">Demander un nouveau lien</a>
                </small>
            <?php endif; ?>
        </div>
    </div>
</div>