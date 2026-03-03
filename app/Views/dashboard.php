<div class="container d-flex flex-column align-items-center div-container">
    <div class="welcome-wrapper mb-5">
        <?php if (isset($company) && !empty($company['logo_url'])): ?>
            <img src="<?= htmlspecialchars($company['logo_url'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($company['company_name'] ?? 'Logo entreprise', ENT_QUOTES, 'UTF-8') ?>" class="logo-dashboard">
        <?php else: ?>
            <img src="assets/logo/default1.svg" alt="Logo par défaut" class="logo-dashboard">
        <?php endif; ?>
        <div class="text-center">
            <h2 class="text-uppercase fw-bold title-custom">Bonjour <?= isset($_SESSION['firstname']) ? htmlspecialchars($_SESSION['firstname'], ENT_QUOTES, 'UTF-8') : ''; ?></h2>
            <?php if (isset($company) && !empty($company['company_name'])): ?>
                <h3 class="text-uppercase fw-bold title-custom"><?= htmlspecialchars($company['company_name'], ENT_QUOTES, 'UTF-8') ?></h3>
            <?php endif; ?>
        </div>
    </div>
    
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show form-wrapper" role="alert">
            <?= htmlspecialchars($_SESSION['success_message'], ENT_QUOTES, 'UTF-8'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show form-wrapper" role="alert">
            <?= htmlspecialchars($_SESSION['error_message'], ENT_QUOTES, 'UTF-8'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
    
    <div>
        <h1 class="text-uppercase fw-bold title-custom">Tableau de bord</h1>
    </div>
</div>