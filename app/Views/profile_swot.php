<div class="container d-flex justify-content-center div-container">
    <div class="profile-wrapper">
        <div class="card rounded-4 profile-card">

            <a href="index.php" id="closeBtn">
                <img src="assets/img/close-btn.svg" alt="Fermer">
            </a>

            <!-- En-tête -->
            <div class="profile-header">
                <div class="swot-header-icon">
                    <i class="bi bi-grid-1x2-fill"></i>
                </div>
                <h1 class="profile-name">Analyse SWOT</h1>
                <p class="profile-username">
                    <?php if (!empty($company)): ?>
                        <i class="bi bi-building"></i>
                        <?= htmlspecialchars($company['company_name'], ENT_QUOTES, 'UTF-8') ?>
                    <?php else: ?>
                        <i class="bi bi-building"></i> Entreprise
                    <?php endif; ?>
                </p>
            </div>

            <!-- Grille SWOT -->
            <div class="profile-content">

                <div class="swot-grid">

                    <!-- En-têtes colonnes -->
                    <div class="swot-axis-corner"></div>
                    <div class="swot-axis-col swot-col-positive">
                        <i class="bi bi-plus-circle-fill"></i> Positif
                    </div>
                    <div class="swot-axis-col swot-col-negative">
                        <i class="bi bi-dash-circle-fill"></i> Négatif
                    </div>

                    <!-- Ligne Interne -->
                    <div class="swot-axis-row swot-row-internal"><span>Interne</span></div>

                    <!-- Forces -->
                    <div class="swot-quadrant swot-strengths">
                        <div class="swot-quadrant-header">
                            <span class="swot-quadrant-icon"><i class="bi bi-lightning-charge-fill"></i></span>
                            <h3 class="swot-quadrant-title">Forces</h3>
                            <span class="swot-axis-badge swot-badge-internal">Interne</span>
                        </div>
                        <ul class="swot-list">
                            <?php if (!empty($swot['strengths'])): ?>
                                <?php foreach ($swot['strengths'] as $item): ?>
                                    <li>
                                        <i class="bi bi-check-circle-fill swot-check-icon"></i>
                                        <?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li class="swot-empty"><i class="bi bi-dash"></i> Aucune force renseignée</li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <!-- Faiblesses -->
                    <div class="swot-quadrant swot-weaknesses">
                        <div class="swot-quadrant-header">
                            <span class="swot-quadrant-icon"><i class="bi bi-exclamation-triangle-fill"></i></span>
                            <h3 class="swot-quadrant-title">Faiblesses</h3>
                            <span class="swot-axis-badge swot-badge-internal">Interne</span>
                        </div>
                        <ul class="swot-list">
                            <?php if (!empty($swot['weaknesses'])): ?>
                                <?php foreach ($swot['weaknesses'] as $item): ?>
                                    <li>
                                        <i class="bi bi-x-circle-fill swot-cross-icon"></i>
                                        <?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li class="swot-empty"><i class="bi bi-dash"></i> Aucune faiblesse renseignée</li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <!-- Ligne Externe -->
                    <div class="swot-axis-row swot-row-external"><span>Externe</span></div>

                    <!-- Opportunités -->
                    <div class="swot-quadrant swot-opportunities">
                        <div class="swot-quadrant-header">
                            <span class="swot-quadrant-icon"><i class="bi bi-arrow-up-right-circle-fill"></i></span>
                            <h3 class="swot-quadrant-title">Opportunités</h3>
                            <span class="swot-axis-badge swot-badge-external">Externe</span>
                        </div>
                        <ul class="swot-list">
                            <?php if (!empty($swot['opportunities'])): ?>
                                <?php foreach ($swot['opportunities'] as $item): ?>
                                    <li>
                                        <i class="bi bi-check-circle-fill swot-check-icon"></i>
                                        <?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li class="swot-empty"><i class="bi bi-dash"></i> Aucune opportunité renseignée</li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <!-- Menaces -->
                    <div class="swot-quadrant swot-threats">
                        <div class="swot-quadrant-header">
                            <span class="swot-quadrant-icon"><i class="bi bi-shield-exclamation"></i></span>
                            <h3 class="swot-quadrant-title">Menaces</h3>
                            <span class="swot-axis-badge swot-badge-external">Externe</span>
                        </div>
                        <ul class="swot-list">
                            <?php if (!empty($swot['threats'])): ?>
                                <?php foreach ($swot['threats'] as $item): ?>
                                    <li>
                                        <i class="bi bi-x-circle-fill swot-cross-icon"></i>
                                        <?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li class="swot-empty"><i class="bi bi-dash"></i> Aucune menace renseignée</li>
                            <?php endif; ?>
                        </ul>
                    </div>

                </div><!-- /.swot-grid -->
            </div><!-- /.profile-content -->
        </div><!-- /.profile-card -->
    </div><!-- /.profile-wrapper -->
</div>