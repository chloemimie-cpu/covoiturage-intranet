<h1 class="h3 mb-4">Tous les trajets</h1>

<?php if (empty($trajets)): ?>
    <div class="alert alert-info">Aucun trajet.</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Départ</th>
                    <th>Date de départ</th>
                    <th>Arrivée</th>
                    <th>Date d'arrivée</th>
                    <th>Places</th>
                    <th>Contact</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($trajets as $trajet): ?>
                    <tr>
                        <td><?= htmlspecialchars($trajet['agence_depart']) ?></td>
                        <td><?= (new DateTime($trajet['date_depart']))->format('d/m/Y H:i') ?></td>
                        <td><?= htmlspecialchars($trajet['agence_arrivee']) ?></td>
                        <td><?= (new DateTime($trajet['date_arrivee']))->format('d/m/Y H:i') ?></td>
                        <td><?= (int) $trajet['places_disponibles'] ?> / <?= (int) $trajet['places_total'] ?></td>
                        <td>
                            <?= htmlspecialchars($trajet['prenom'] . ' ' . $trajet['nom']) ?><br>
                            <small class="text-body-secondary">
                                <?= htmlspecialchars($trajet['telephone']) ?><br>
                                <?= htmlspecialchars($trajet['email']) ?>
                            </small>
                        </td>
                        <td>
                            <form method="post"
                                  action="/admin/trajets/<?= (int) $trajet['id_trajet'] ?>/supprimer"
                                  onsubmit="return confirm('Supprimer ce trajet ?');">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Core\Csrf::token()) ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>