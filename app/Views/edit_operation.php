<div class="container d-flex justify-content-center div-container">
    <div class="text-center form-wrapper">
        <div class="card p-4 mb-5 rounded-4 form-card">
            <a href="javascript:history.back()" id="closeBtn">
                <img src="assets/img/close-btn.svg" alt="Fermer">
            </a>
            <h1 class="text-uppercase mb-4 fw-bold title-custom">Votre operation</h1>
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <p class="mb-0"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <form action="index.php?action=edit-operation&id=<?= htmlspecialchars($operation['id_operation'] ?? '', ENT_QUOTES, 'UTF-8') ?>" method="post">
                <div class="row g-3 mb-3">
                    <div class="col-md-12">
                        <label for="name" class="form-label text-start d-block">Nom <span class="required">*</span></label>
                        <input type="text"
                            id="name"
                            name="name"
                            class="form-control form-control-custom"
                            value="<?= htmlspecialchars($operation['operation_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required>
                    </div>
                </div>
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