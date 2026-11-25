<h1 class="h3 mb-4"><?= htmlspecialchars($heading) ?></h1>

<?php if (isset($errors['general'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($errors['general']) ?></div>
<?php endif; ?>

<form method="post" action="<?= htmlspecialchars($action) ?>">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Core\Csrf::token()) ?>">

    <h2 class="h5">Vos coordonnées</h2>
    <div class="row g-3 mb-4">
        <?php foreach (['nom' => 'Nom', 'prenom' => 'Prénom', 'email' => 'Email', 'telephone' => 'Téléphone'] as $champ => $libelle): ?>
            <div class="col-md-6">
                <label class="form-label"><?= $libelle ?></label>
                <input type="text" class="form-control" readonly
                       value="<?= htmlspecialchars($employe[$champ]) ?>">
            </div>
        <?php endforeach; ?>
    </div>

    <h2 class="h5">Le trajet</h2>
    <div class="row g-3 mb-4">
        <?php foreach (['id_agence_depart' => 'Agence de départ', 'id_agence_arrivee' => "Agence d'arrivée"] as $champ => $libelle): ?>
            <div class="col-md-6">
                <label for="<?= $champ ?>" class="form-label"><?= $libelle ?></label>
                <select id="<?= $champ ?>" name="<?= $champ ?>" required
                        class="form-select<?= isset($errors[$champ]) ? ' is-invalid' : '' ?>">
                    <option value="">-- Choisir --</option>
                    <?php foreach ($agences as $agence): ?>
                        <option value="<?= (int) $agence['id_agence'] ?>"
                            <?= (int) ($old[$champ] ?? 0) === (int) $agence['id_agence'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($agence['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors[$champ])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors[$champ]) ?></div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <?php
        $champsSaisie = [
            'date_depart'        => ['Date et heure de départ', 'datetime-local'],
            'date_arrivee'       => ["Date et heure d'arrivée", 'datetime-local'],
            'places_total'       => ['Nombre total de places', 'number'],
            'places_disponibles' => ['Nombre de places disponibles', 'number'],
        ];
        ?>
        <?php foreach ($champsSaisie as $champ => [$libelle, $type]): ?>
            <div class="col-md-6">
                <label for="<?= $champ ?>" class="form-label"><?= $libelle ?></label>
                <input type="<?= $type ?>" id="<?= $champ ?>" name="<?= $champ ?>" required
                       class="form-control<?= isset($errors[$champ]) ? ' is-invalid' : '' ?>"
                       value="<?= htmlspecialchars((string) ($old[$champ] ?? '')) ?>">
                <?php if (isset($errors[$champ])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors[$champ]) ?></div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <button type="submit" class="btn btn-primary"><?= htmlspecialchars($submitLabel) ?></button>
    <a href="/" class="btn btn-outline-secondary">Annuler</a>
</form>