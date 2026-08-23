<?php
/** @var array<string, mixed> $errors */
?>
<div class="row justify-content-center">
    <div class="col-md-5">
        <h1 class="h3 mb-4">Connexion</h1>
        <form method="post" action="/connexion">
            <div class="mb-3">
                <label for="email" class="form-label">Adresse email</label>
                <input type="email" class="form-control" id="email" name="email" required autofocus>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </form>
    </div>
</div>
