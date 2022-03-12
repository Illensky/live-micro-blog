<h1>Créer un compte</h1>

<form action="/index.php?c=user&m=register" method="post">
    <div>
        <label for="email">E-mail</label>
        <input type="text" name="email" id="email">
    </div>
    <div>
        <label for="firstname">First name</label>
        <input type="text" name="firstname" id="firstname">
    </div>
    <div>
        <label for="lastname">Last name</label>
        <input type="text" name="lastname" id="lastname">
    </div>
    <div>
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
    </div>
    <div>
        <label for="password-repeat">Password repeat</label>
        <input type="password" name="password-repeat" id="password-repeat">
    </div>
    <div>
        <label for="age">Age</label>
        <input type="number" name="age" id="age">
    </div>

    <input type="submit" value="Créer un compte" name="save">
</form>