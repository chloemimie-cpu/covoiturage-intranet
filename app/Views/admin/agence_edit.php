<h1 class="h3 mb-4">Modifier l'agence</h1>

<form method="post" action="/admin/agences/<?= (int) $agence['id_agence'] ?>/modifier" class="col-md-6">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Core\Csrf::token()) ?>">

    <div class="mb-3">
        <label for="nom" class="form-label">Nom de la ville</label>
        <input type="text" id="nom" name="nom" required maxlength="50"
               class="form-control<?= !empty($error) ? ' is-invalid' : '' ?>"
               value="<?= htmlspecialchars($nom) ?>">
        <?php if (!empty($error)): ?>
            <div class="invalid-feedback"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-primary">Enregistrer</button>
    <a href="/admin/agences" class="btn btn-outline-secondary">Annuler</a>
</form>