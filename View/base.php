<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body> <?php
// Handling error messages.
use App\Model\Entity\Role;

if (isset($_SESSION['errors']) && count($_SESSION['errors']) > 0) {
    $errors = $_SESSION['errors'];
    unset($_SESSION['errors']);

    foreach ($errors as $error) { ?>
        <div class="alert alert-error"><?= $error ?></div> <?php
    }
}

// Handling sucecss messages.
if (isset($_SESSION['success'])) {
    $message = $_SESSION['success'];
    unset($_SESSION['success']);
    ?>
    <div class="alert alert-success"><?= $message ?></div> <?php
}
?>
<header>
    <nav>
        <ul>
            <li><a href="/index.php" title="Acceuil">Acceuil</a></li>
            <?php
            if (UserController::isUserConnected()) {
                ?>
                <li><a href="/index.php?c=user&m=logout">Se déconnecter</a></li>
                <li><a href="/index.php?c=user&m=user-space">Votre espace</a></li>
                <?php
                $roles = UserController::getUserRolesNames($_SESSION['user']);
                if (in_array("admin", $roles)) {
                    ?>
                    <li><a href="/index.php?c=user" title="Administration">Administration</a></li>
                    <?php
                }
                if (in_array("editor", $roles)) {
                    ?>
                    <li><a href="/index.php?c=article&m=add-article">Ajouter un article</a></li>
                    <?php
                }
            } else { ?>
                <li><a href="/index.php?c=user&m=register">S'enregistrer</a></li>
                <li><a href="/index.php?c=user&m=login">Se Connecter</a></li>
                <?php
            }
            ?>

        </ul>
    </nav>
</header>

<main>
    <?= $html ?>
</main>

<footer>
    <div>Infos de contact</div>
    <div>Horaires</div>
</footer>
<script src="/assets/js/app.js"></script>
</body>
</html>