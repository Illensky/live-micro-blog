
<h1>Ajouter un article.</h1>

<form action="/index.php?c=article&m=add-article" method="post">
    <div>
        <label for="title">Titre de l'article</label>
        <input type="text" name="title" id="title">
    </div>
    <div>
        <textarea name="content" id="content" cols="30" rows="20"></textarea>
    </div>

    <input type="submit" name="save" value="Enregistrer">
</form>