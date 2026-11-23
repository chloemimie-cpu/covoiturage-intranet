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
        </tr>
        <?php foreach ($trajets as $trajet): ?>
            <tr>
                <td><?= htmlspecialchars($trajet['agence_depart']) ?></td>
                <td><?= (new DateTime($trajet['date_depart']))->format('d/m/Y H:i') ?></td>
                <td><?= htmlspecialchars($trajet['agence_arrivee']) ?></td>
                <td><?= (new DateTime($trajet['date_arrivee']))->format('d/m/Y H:i') ?></td>
                <td><?= (int) $trajet['places_disponibles'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>