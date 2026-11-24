<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title) ?></title>
</head>
<body>
    <header>
        <?php if ($user !== null && $user['role'] === 'admin'): ?>
            <a href="/admin">Covoiturage</a>
            <nav>
                <a href="/admin/utilisateurs">Utilisateurs</a>
                <a href="/admin/agences">Agences</a>
                <a href="/admin/trajets">Trajets</a>
                <form method="post" action="/logout" style="display:inline">
                    <button type="submit">Déconnexion</button>
                </form>
            </nav>
        <?php elseif ($user !== null): ?>
            <a href="/">Covoiturage</a>
            <nav>
                <a href="/trajets/nouveau">Proposer un trajet</a>
                <span><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></span>
                <form method="post" action="/logout" style="display:inline">
                    <button type="submit">Déconnexion</button>
                </form>
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Core\Csrf::token()) ?>">
            </nav>
        <?php else: ?>
            <a href="/">Covoiturage</a>
            <nav>
                <a href="/login">Connexion</a>
            </nav>
        <?php endif; ?>
    </header>

    <main>
        <?php if ($flash !== null): ?>
            <p><strong><?= htmlspecialchars($flash['message']) ?></strong></p>
        <?php endif; ?>

        <?= $content ?>
    </main>

    <footer>
        <p>Covoiturage &copy; <?= date('Y') ?></p>
    </footer>
</body>
</html>