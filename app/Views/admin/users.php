<h1 class="h3 mb-4">Utilisateurs</h1>

<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Téléphone</th>
                <th>Email</th>
                <th>Rôle</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employes as $employe): ?>
                <tr>
                    <td><?= htmlspecialchars($employe['nom']) ?></td>
                    <td><?= htmlspecialchars($employe['prenom']) ?></td>
                    <td><?= htmlspecialchars($employe['telephone']) ?></td>
                    <td><?= htmlspecialchars($employe['email']) ?></td>
                    <td>
                        <span class="badge text-bg-<?= $employe['role'] === 'admin' ? 'primary' : 'secondary' ?>">
                            <?= htmlspecialchars($employe['role']) ?>
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>