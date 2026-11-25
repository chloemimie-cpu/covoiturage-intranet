<h1 class="h3 mb-4">Agences</h1>

<h2 class="h5">Ajouter une agence</h2>
<form method="post" action="/admin/agences" class="row g-2 mb-5">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Core\Csrf::token()) ?>">
    <div class="col-sm-6 col-md-4">
        <label for="nom" class="visually-hidden">Nom de la ville</label>
        <input type="text" id="nom" name="nom" required maxlength="50" placeholder="Nom de la ville"
               class="form-control<?= !empty($error) ? ' is-invalid' : '' ?>"
               value="<?= htmlspecialchars($nom) ?>">
        <?php if (!empty($error)): ?>
            <div class="invalid-feedback"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </div>
</form>

<h2 class="h5">Liste des agences</h2>
<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($agences as $agence): ?>
                <tr>
                    <td><?= htmlspecialchars($agence['nom']) ?></td>
                    <td class="text-nowrap">
                        <a class="btn btn-sm btn-outline-secondary"
                           href="/admin/agences/<?= (int) $agence['id_agence'] ?>/modifier">Modifier</a>
                        <form method="post" class="d-inline"
                              action="/admin/agences/<?= (int) $agence['id_agence'] ?>/supprimer"
                              onsubmit="return confirm('Supprimer cette agence ?');">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Core\Csrf::token()) ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>