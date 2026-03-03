<div class="container d-flex justify-content-center div-container">
    <div class="operations-wrapper">
        <div class="card mb-5 rounded-4 persona-card">
            <a href="index.php?action=dashboard" id="closeBtn">
                <img src="assets/img/close-btn.svg" alt="Fermer">
            </a>
            <h1 class="text-uppercase mb-4 fw-bold title-custom text-center">Vos operations</h1>
            
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($_SESSION['success_message'], ENT_QUOTES, 'UTF-8'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($_SESSION['error_message'], ENT_QUOTES, 'UTF-8'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>
            
            <?php if (empty($operations)): ?>
                <p class="text-center text-muted">Aucune opération enregistrée</p>
            <?php else: ?>
            <div class="table-responsive">
                <table id="operationsTable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Nom</th>
                            <th id="th-actions" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($operations as $operation): ?>
                            <tr>
                                <td><?= htmlspecialchars($operation['operation_name'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <a href="index.php?action=view-operation&id=<?= urlencode($operation['id_operation']) ?>"
                                        class="icone-list">
                                        <img class="icone-img img-normal" src="assets/img/operation.svg" alt="Voir" title="Voir l'operation">
                                        <img class="icone-img img-hover" src="assets/img/operation-hover.svg" alt="Voir" title="Voir l'operation">
                                    </a>
                                    <a href="index.php?action=edit-operation&id=<?= urlencode($operation['id_operation']) ?>"
                                        class="icone-list">
                                        <img class="icone-img img-normal" src="assets/img/operation-edit.svg" alt="Modifier" title="Modifier l'operation">
                                        <img class="icone-img img-hover" src="assets/img/operation-edit-hover.svg" alt="Modifier" title="Modifier l'operation">
                                    </a>
                                    <a href="index.php?action=delete-operation&id=<?= urlencode($operation['id_operation']) ?>"
                                        class="icone-list"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette operation ?');">
                                        <img class="icone-img img-normal" src="assets/img/operation-delete.svg" alt="Supprimer" title="Supprimer l'operation">
                                        <img class="icone-img img-hover" src="assets/img/operation-delete-hover.svg" alt="Supprimer" title="Supprimer l'operation">
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>