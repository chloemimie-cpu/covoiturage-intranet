<h1>Agences</h1>

<h2>Ajouter une agence</h2>
<?php if (!empty($error)): ?>
    <p><strong><?= htmlspecialchars($error) ?></strong></p>
<?php endif; ?>
<form method="post" action="/admin/agences">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Core\Csrf::token()) ?>">
    <label for="nom">Nom de la ville</label>
    <input type="text" id="nom" name="nom" required maxlength="50" value="<?= htmlspecialchars($nom) ?>">
    <button type="submit">Ajouter</button>
</form>

<h2>Liste des agences</h2>
<table>
    <tr>
        <th>Nom</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($agences as $agence): ?>
        <tr>
            <td><?= htmlspecialchars($agence['nom']) ?></td>
            <td>
                <a href="/admin/agences/<?= (int) $agence['id_agence'] ?>/modifier">Modifier</a>
                <form method="post" action="/admin/agences/<?= (int) $agence['id_agence'] ?>/supprimer"
                      style="display:inline"
                      onsubmit="return confirm('Supprimer cette agence ?');">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Core\Csrf::token()) ?>">
                    <button type="submit">Supprimer</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>