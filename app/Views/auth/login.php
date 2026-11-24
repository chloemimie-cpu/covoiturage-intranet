<h1>Connexion</h1>

<?php if (!empty($error)): ?>
    <p><strong><?= htmlspecialchars($error) ?></strong></p>
<?php endif; ?>

<form method="post" action="/login">
    <p>
        <label for="email">Adresse email</label><br>
        <input type="email" id="email" name="email" required
               value="<?= htmlspecialchars($email ?? '') ?>">
    </p>
    <p>
        <label for="password">Mot de passe</label><br>
        <input type="password" id="password" name="password" required>
    </p>
    <button type="submit">Se connecter</button>
</form>