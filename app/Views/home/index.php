<h1 class="h3 mb-4">Trajets disponibles</h1>

<?php if (empty($trajets)): ?>
    <div class="alert alert-info">Aucun trajet disponible pour le moment.</div>
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
                    <?php if ($user !== null): ?>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($trajets as $trajet): ?>
                    <tr>
                        <td><?= htmlspecialchars($trajet['agence_depart']) ?></td>
                        <td><?= (new DateTime($trajet['date_depart']))->format('d/m/Y H:i') ?></td>
                        <td><?= htmlspecialchars($trajet['agence_arrivee']) ?></td>
                        <td><?= (new DateTime($trajet['date_arrivee']))->format('d/m/Y H:i') ?></td>
                        <td><?= (int) $trajet['places_disponibles'] ?></td>
                        <?php if ($user !== null): ?>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-sm btn-outline-primary"
                                        data-bs-toggle="modal" data-bs-target="#detailsModal"
                                        data-identite="<?= htmlspecialchars($trajet['prenom'] . ' ' . $trajet['nom']) ?>"
                                        data-telephone="<?= htmlspecialchars($trajet['telephone']) ?>"
                                        data-email="<?= htmlspecialchars($trajet['email']) ?>"
                                        data-places-total="<?= (int) $trajet['places_total'] ?>">
                                    Détails
                                </button>

                                <?php if ((int) $trajet['id_employe'] === (int) $user['id']): ?>
                                    <a class="btn btn-sm btn-outline-secondary"
                                       href="/trajets/<?= (int) $trajet['id_trajet'] ?>/modifier">Modifier</a>
                                    <form method="post" class="d-inline"
                                          action="/trajets/<?= (int) $trajet['id_trajet'] ?>/supprimer"
                                          onsubmit="return confirm('Supprimer ce trajet ?');">
                                        <input type="hidden" name="csrf_token"
                                               value="<?= htmlspecialchars(\App\Core\Csrf::token()) ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php if ($user !== null): ?>
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="detailsModalLabel">Détails du trajet</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Proposé par</dt>
                        <dd class="col-sm-7" data-field="identite"></dd>
                        <dt class="col-sm-5">Téléphone</dt>
                        <dd class="col-sm-7" data-field="telephone"></dd>
                        <dt class="col-sm-5">Email</dt>
                        <dd class="col-sm-7" data-field="email"></dd>
                        <dt class="col-sm-5">Places au total</dt>
                        <dd class="col-sm-7" data-field="places-total"></dd>
                    </dl>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>