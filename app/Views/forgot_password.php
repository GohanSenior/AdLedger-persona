<div class="container d-flex justify-content-center div-container">
    <div class="text-center form-wrapper">
        <div class="card p-4 rounded-4 form-card">
            <h1 class="text-uppercase mb-4 fw-bold title-custom">Réinitialiser le mot de passe</h1>
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <p class="mb-0"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if (isset($success) && $success): ?>
                <div class="alert alert-success">
                    <p class="mb-0">Un email contenant les instructions de réinitialisation a été envoyé à votre adresse email.</p>
                    <p class="mb-0">Veuillez vérifier votre boîte de réception (et vos spams).</p>
                </div>
                <div class="mt-3">
                    <a href="index.php?action=login" class="btn btn-connexion btn-lg">
                        <img src="assets/img/cta-connection.svg" alt="connection" class="btn-img-normal">
                        <img src="assets/img/cta-connection-hover.svg" alt="connection" class="btn-img-hover">
                        <span class="btn-text">Retour</span>
                    </a>
                </div>
            <?php else: ?>
                <form action="index.php?action=forgot-password" method="post" class="mx-auto form-wrapper-sm">
                    <div class="row mb-3">
                        <label for="user_login" class="form-label text-start p-0">Identifiant ou adresse Email</label>
                        <input type="text" class="form-control form-control-custom" id="user_login" name="user_login" required>
                    </div>
                    <div class="d-flex justify-content-center mb-3">
                        <button type="submit" class="btn btn-lg px-5 py-1 mt-3 btn-connexion">
                            <img src="assets/img/cta-connection.svg" alt="connection" class="btn-img-normal">
                            <img src="assets/img/cta-connection-hover.svg" alt="connection" class="btn-img-hover">
                            <span class="btn-text">Envoyer</span>
                        </button>
                    </div>
                    <small>
                        <a href="index.php?action=login" class="text-decoration-none fw-bold color-link">Retour à la connexion</a>
                    </small>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>