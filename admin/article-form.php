<?php

require_once '../tools/common.php';

if (!isset($_SESSION['user']) OR $_SESSION['user']['is_admin'] == 0) {
    header('location:../index.php');
    exit;
}

//Si $_POST['save'] existe, cela signifie que c'est un ajout d'article
if (isset($_POST['save'])) {

    $query = $db->prepare('INSERT INTO article (title, content, summary, published_at, category_id, is_published) VALUES (?, ?, ?, ?, ?, ?)');
    $newArticle = $query->execute(
        [
            htmlspecialchars($_POST['title']),
            htmlspecialchars($_POST['content']),
            htmlspecialchars($_POST['summary']),
            htmlspecialchars($_POST['published_at']),
            $_POST['category_id'],
            $_POST['is_published']
        ]
    );

    //redirection après enregistrement
    //si $newArticle alors l'enregistrement a fonctionné
    if ($newArticle) {
        //redirection après enregistrement
        header('location:article-list.php?save=ok');
        exit;
    } else { //si pas $newArticle => enregistrement échoué => générer un message pour l'administrateur à afficher plus bas
        $message = "Impossible d'enregistrer le nouvel article...";
    }
}


//Si $_POST['udpate'] existe, cela signifie que c'est un ajout d'article
if (isset($_POST['UPDATE'])) {

    $query = $db->prepare('UPDATE article SET title=? ,content=?, summary=?, published_at=?, category_id=?, is_published=? WHERE id= ?');
    $newArticle = $query->execute(
        [
            htmlspecialchars($_POST['title']),
            htmlspecialchars($_POST['content']),
            htmlspecialchars($_POST['summary']),
            htmlspecialchars($_POST['published_at']),
            $_POST['category_id'],
            $_POST['is_published'],
            $_POST['id']
        ]
    );

    //redirection après enregistrement
    //si $newArticle alors l'enregistrement a fonctionné
    if ($newArticle) {
        //redirection après enregistrement
        header('location:article-list.php?udpate=ok');
        exit;
    } else { //si pas $newArticle => enregistrement échoué => générer un message pour l'administrateur à afficher plus bas
        $message = "Impossible d'enregistrer le nouvel article...";
    }
}

//selection des catégories pour SELECT list plus bas
$queryCategory = $db->query('SELECT * FROM category');
$categories = $queryCategory->fetchAll();

?>

<!DOCTYPE html>
<html>
<head>

    <title>Administration des articles - Mon premier blog !</title>

    <?php require 'partials/head_assets.php'; ?>

</head>
<body class="index-body">
<div class="container-fluid">

    <?php require 'partials/header.php'; ?>

    <div class="row my-3 index-content">

        <?php require 'partials/nav.php'; ?>
        <?php if (isset($_GET['article_id'])) { ?>
            <?php

            $queryCategory = $db->prepare('SELECT * FROM article WHERE id = ?');
            $queryCategory->execute(array($_GET['article_id']));

            $LISTE = $queryCategory->fetch();

            $codecate = $LISTE['category_id'];


            $queryCategorys = $db->prepare('SELECT * FROM category WHERE id=?');
            $queryCategorys->execute(array($codecate));
            $categorieUdpate = $queryCategorys->fetch();

            $queryCategorie = $db->prepare('SELECT * FROM category WHERE id!=?');
            $queryCategorie->execute(array($codecate));
            $lsistecaegorie = $queryCategorie->fetchAll();


            ?>

            <section class="col-9">
                <header class="pb-3">
                    <h4>Modification article</h4>
                </header>

                <?php if (isset($message)): //si un message a été généré plus haut, l'afficher ?>
                    <div class="bg-danger text-white">
                        <?= $message; ?>
                    </div>
                <?php endif; ?>

                <form action="article-form.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="title">Titre :</label>
                        <input class="form-control" type="hidden" value="<?php echo $LISTE['id']; ?>" name="id" id="id"
                               required/>
                        <input class="form-control" value="<?php echo $LISTE['title']; ?>" type="text"
                               placeholder="Titre" name="title" id="title"/>
                    </div>
                    <div class="form-group">
                        <label for="summary">Résumé :</label>
                        <input class="form-control" value="<?php echo $LISTE['summary']; ?>" type="text"
                               placeholder="Résumé" name="summary" id="summary"/>
                    </div>
                    <div class="form-group">
                        <label for="content">Contenu :</label>
                        <textarea class="form-control" name="content" id="content"
                                  placeholder="Contenu"> <?php echo $LISTE['content']; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="published_at">Date de publication :</label>
                        <input class="form-control" value="<?php echo $LISTE['published_at']; ?>" type="date"
                               placeholder="" name="published_at" id="published_at"/>
                    </div>

                    <div class="form-group">
                        <label for="category_id"> Catégorie :</label>
                        <select class="form-control" name="category_id" id="categories">
                            <option value="<?php echo $categorieUdpate['id']; ?>"> <?php echo $categorieUdpate['name']; ?> </option>
                            <?php foreach ($lsistecaegorie as $key => $category) : ?>
                                <option value="<?= $category['id']; ?>"> <?= $category['name']; ?> </option>
                            <?php endforeach; ?>

                        </select>
                    </div>
                    <div class="form-group">
                        <label for="is_published"> Publié ?</label>
                        <select class="form-control" name="is_published" id="is_published">
                            <?php if ($LISTE['is_published'] = 1) { ?>
                                <option value="1">Oui</option>
                                <option value="0">Non</option>
                            <?php } else { ?>
                                <option value="0">Non</option>
                                <option value="1">Oui</option>

                            <?php } ?>
                        </select>
                    </div>
                    <div class="text-right">
                        <input class="btn btn-success" type="submit" name="UPDATE" value="Modifier"/>
                    </div>
                </form>

            </section>
        <?php } else { ?>

            <section class="col-9">
                <header class="pb-3">
                    <h4>Ajouter un article</h4>
                </header>

                <?php if (isset($message)): //si un message a été généré plus haut, l'afficher ?>
                    <div class="bg-danger text-white">
                        <?= $message; ?>
                    </div>
                <?php endif; ?>

                <form action="article-form.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="title">Titre :</label>
                        <input class="form-control" value="" type="text" placeholder="Titre" name="title" id="title"/>
                    </div>
                    <div class="form-group">
                        <label for="summary">Résumé :</label>
                        <input class="form-control" value="" type="text" placeholder="Résumé" name="summary"
                               id="summary"/>
                    </div>
                    <div class="form-group">
                        <label for="content">Contenu :</label>
                        <textarea class="form-control" name="content" id="content" placeholder="Contenu"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="published_at">Date de publication :</label>
                        <input class="form-control" value="" type="date" placeholder="" name="published_at"
                               id="published_at"/>
                    </div>

                    <div class="form-group">
                        <label for="category_id"> Catégorie :</label>
                        <select class="form-control" name="category_id" id="categories">
                            <?php foreach ($categories as $key => $category) : ?>
                                <option value="<?= $category['id']; ?>"> <?= $category['name']; ?> </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="is_published"> Publié ?</label>
                        <select class="form-control" name="is_published" id="is_published">
                            <option value="0">Non</option>
                            <option value="1">Oui</option>
                        </select>
                    </div>
                    <div class="text-right">
                        <input class="btn btn-success" type="submit" name="save" value="Enregistrer"/>
                    </div>
                </form>

            </section>
        <?php } ?>
    </div>
</div>
</body>
</html>
