
<h1>Connexion</h1>

<form action="/index.php?c=user&m=login" method="post">
    <div>
        <label for="email">E-mail</label>
        <input type="text" name="email" id="email">
    </div>
    <div>
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
    </div>

    <input type="submit" value="Se connecter" name="save">
</form>