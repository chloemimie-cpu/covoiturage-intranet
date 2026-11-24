<h1>Utilisateurs</h1>

<table>
    <tr>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Téléphone</th>
        <th>Email</th>
        <th>Rôle</th>
    </tr>
    <?php foreach ($employes as $employe): ?>
        <tr>
            <td><?= htmlspecialchars($employe['nom']) ?></td>
            <td><?= htmlspecialchars($employe['prenom']) ?></td>
            <td><?= htmlspecialchars($employe['telephone']) ?></td>
            <td><?= htmlspecialchars($employe['email']) ?></td>
            <td><?= htmlspecialchars($employe['role']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>