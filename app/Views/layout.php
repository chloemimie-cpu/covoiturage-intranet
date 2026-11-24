<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="/css/main.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <?php $csrf = htmlspecialchars(\App\Core\Csrf::token()); ?>

    <nav class="navbar navbar-expand bg-primary" data-bs-theme="dark">
        <div class="container">
            <a class="navbar-brand" href="<?= ($user !== null && $user['role'] === 'admin') ? '/admin' : '/' ?>">Covoiturage</a>

            <div class="navbar-nav ms-auto align-items-center gap-3">
                <?php if ($user !== null && $user['role'] === 'admin'): ?>
                    <a class="nav-link" href="/admin/utilisateurs">Utilisateurs</a>
                    <a class="nav-link" href="/admin/agences">Agences</a>
                    <a class="nav-link" href="/admin/trajets">Trajets</a>
                    <form method="post" action="/logout" class="d-inline">
                        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                        <button type="submit" class="btn btn-outline-light btn-sm">Déconnexion</button>
                    </form>
                <?php elseif ($user !== null): ?>
                    <a class="btn btn-light btn-sm" href="/trajets/nouveau">Proposer un trajet</a>
                    <span class="navbar-text"><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></span>
                    <form method="post" action="/logout" class="d-inline">
                        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                        <button type="submit" class="btn btn-outline-light btn-sm">Déconnexion</button>
                    </form>
                <?php else: ?>
                    <a class="btn btn-light btn-sm" href="/login">Connexion</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main class="container my-4 flex-grow-1">
        <?php if ($flash !== null): ?>
            <div class="alert alert-<?= $flash['type'] === 'error' ? 'danger' : 'success' ?>" role="alert">
                <?= htmlspecialchars($flash['message']) ?>
            </div>
        <?php endif; ?>

        <?= $content ?>
    </main>

    <footer class="py-3 bg-body-tertiary text-center">
        <small>Covoiturage &copy; <?= date('Y') ?></small>
    </footer>

    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="/js/app.js"></script>
</body>
</html>