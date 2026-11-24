<h1>Trajets disponibles</h1>

<?php if (empty($trajets)): ?>
    <p>Aucun trajet disponible pour le moment.</p>
<?php else: ?>
    <table>
        <tr>
            <th>Départ</th>
            <th>Date de départ</th>
            <th>Arrivée</th>
            <th>Date d'arrivée</th>
            <th>Places</th>
            <?php if ($user !== null): ?>
                <th>Actions</th>
            <?php endif; ?>
        </tr>
        <?php foreach ($trajets as $trajet): ?>
            <tr>
                <td><?= htmlspecialchars($trajet['agence_depart']) ?></td>
                <td><?= (new DateTime($trajet['date_depart']))->format('d/m/Y H:i') ?></td>
                <td><?= htmlspecialchars($trajet['agence_arrivee']) ?></td>
                <td><?= (new DateTime($trajet['date_arrivee']))->format('d/m/Y H:i') ?></td>
                <td><?= (int) $trajet['places_disponibles'] ?></td>
                <?php if ($user !== null): ?>
                    <td>
                        <?php if ((int) $trajet['id_employe'] === (int) $user['id']): ?>
                            <a href="/trajets/<?= (int) $trajet['id_trajet'] ?>/modifier">Modifier</a>
                            <form method="post" action="/trajets/<?= (int) $trajet['id_trajet'] ?>/supprimer"
                                  style="display:inline"
                                  onsubmit="return confirm('Supprimer ce trajet ?');">
                                <input type="hidden" name="csrf_token"
                                       value="<?= htmlspecialchars(\App\Core\Csrf::token()) ?>">
                                <button type="submit">Supprimer</button>
                            </form>
                        <?php endif; ?>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>