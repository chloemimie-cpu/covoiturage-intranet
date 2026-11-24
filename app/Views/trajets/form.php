<h1><?= htmlspecialchars($heading) ?></h1>

<?php if (isset($errors['general'])): ?>
    <p><strong><?= htmlspecialchars($errors['general']) ?></strong></p>
<?php endif; ?>

<form method="post" action="<?= htmlspecialchars($action) ?>">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Core\Csrf::token()) ?>">

    <h2>Vos coordonnées</h2>
    <p>
        <label>Nom</label><br>
        <input type="text" value="<?= htmlspecialchars($employe['nom']) ?>" readonly>
    </p>
    <p>
        <label>Prénom</label><br>
        <input type="text" value="<?= htmlspecialchars($employe['prenom']) ?>" readonly>
    </p>
    <p>
        <label>Email</label><br>
        <input type="text" value="<?= htmlspecialchars($employe['email']) ?>" readonly>
    </p>
    <p>
        <label>Téléphone</label><br>
        <input type="text" value="<?= htmlspecialchars((string) ($employe['telephone'] ?? '')) ?>" readonly>
    </p>

    <h2>Le trajet</h2>

    <?php foreach (['id_agence_depart' => 'Agence de départ', 'id_agence_arrivee' => "Agence d'arrivée"] as $champ => $libelle): ?>
        <p>
            <label for="<?= $champ ?>"><?= $libelle ?></label><br>
            <select id="<?= $champ ?>" name="<?= $champ ?>" required>
                <option value="">-- Choisir --</option>
                <?php foreach ($agences as $agence): ?>
                    <option value="<?= (int) $agence['id_agence'] ?>"
                        <?= (int) ($old[$champ] ?? 0) === (int) $agence['id_agence'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($agence['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors[$champ])): ?>
                <br><strong><?= htmlspecialchars($errors[$champ]) ?></strong>
            <?php endif; ?>
        </p>
    <?php endforeach; ?>

    <?php
    $champsTexte = [
        'date_depart'        => ['Date et heure de départ', 'datetime-local'],
        'date_arrivee'       => ["Date et heure d'arrivée", 'datetime-local'],
        'places_total'       => ['Nombre total de places', 'number'],
        'places_disponibles' => ['Nombre de places disponibles', 'number'],
    ];
    ?>
    <?php foreach ($champsTexte as $champ => [$libelle, $type]): ?>
        <p>
            <label for="<?= $champ ?>"><?= $libelle ?></label><br>
            <input type="<?= $type ?>" id="<?= $champ ?>" name="<?= $champ ?>" required
                   <?= $type === 'number' ? 'min="0" max="255"' : '' ?>
                   value="<?= htmlspecialchars((string) ($old[$champ] ?? '')) ?>">
            <?php if (isset($errors[$champ])): ?>
                <br><strong><?= htmlspecialchars($errors[$champ]) ?></strong>
            <?php endif; ?>
        </p>
    <?php endforeach; ?>

    <button type="submit"><?= htmlspecialchars($submitLabel) ?></button>
    <a href="/">Annuler</a>
</form>