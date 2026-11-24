<h1>Modifier l'agence</h1>

<?php if (!empty($error)): ?>
    <p><strong><?= htmlspecialchars($error) ?></strong></p>
<?php endif; ?>

<form method="post" action="/admin/agences/<?= (int) $agence['id_agence'] ?>/modifier">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Core\Csrf::token()) ?>">
    <label for="nom">Nom de la ville</label>
    <input type="text" id="nom" name="nom" required maxlength="50" value="<?= htmlspecialchars($nom) ?>">
    <button type="submit">Enregistrer</button>
    <a href="/admin/agences">Annuler</a>
</form>