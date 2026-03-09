<div class="container d-flex justify-content-center div-container">
    <div class="form-wrapper">
        <div class="card p-4 rounded-4 form-card">
            <a href="<?= htmlspecialchars($redirect, ENT_QUOTES, 'UTF-8') ?>" id="closeBtn">
                <img src="assets/img/close-btn.svg" alt="Fermer">
            </a>
            <h1 class="text-uppercase mb-2 fw-bold title-custom text-center">Votre opération</h1>
            <h3 class="text-uppercase mb-4 fw-bold title-custom text-center"><?= htmlspecialchars($operation['operation_name'], ENT_QUOTES, 'UTF-8') ?></h3>
            <div class="table-responsive">
                <table id="personasTable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Nom</th>
                            <th scope="col">Âge</th>
                            <th scope="col">Profession</th>
                            <th id="th-actions" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($personas as $persona): ?>
                            <tr>
                                <td><?= htmlspecialchars($persona['persona_lastname'] . ' ' . $persona['persona_firstname'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($persona['persona_age'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($persona['persona_job'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <a href="index.php?action=view-persona&id=<?= urlencode($persona['id_persona']) ?>&redirect=<?= urlencode($_SERVER['REQUEST_URI']) ?>"
                                        class="icone-list">
                                        <img class="icone-img img-normal" src="assets/img/persona-book.svg" alt="Voir" title="Voir la fiche">
                                        <img class="icone-img img-hover" src="assets/img/persona-book-hover.svg" alt="Voir" title="Voir la fiche">
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>