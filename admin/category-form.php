<?php
require_once '../tools/common.php';

if (!isset($_SESSION['user']) OR $_SESSION['user']['is_admin'] == 0) {
    header('location:../index.php');
    exit;
}

//Si $_POST['save'] existe, cela signifie que c'est un ajout d'une catégorie
if (isset($_POST['save'])) {
    $query = $db->prepare('INSERT INTO category (name, description) VALUES (?, ?)');
    $newCategory = $query->execute([
        htmlspecialchars($_POST['name']),
        htmlspecialchars($_POST['description'])
    ]);

    if ($newCategory) {
        header('location:category-list.php?save=ok');
        exit;
    } else {
        $message = "Impossible d'enregistrer la nouvelle categorie...";
    }
}

if (isset($_POST['Update'])) {

    $query = $db->prepare('UPDATE  category SET name=? , description=? WHERE id= ?');
    $newUser = $query->execute([
        htmlspecialchars($_POST['name']),
        htmlspecialchars($_POST['description']),
        $_POST['id'],
    ]);


    //redirection après enregistrement
    //si $newUser alors l'enregistrement a fonctionné
    if ($newUser) {
        header('location:category-list.php?udpate=ok');
        exit;
    } else { //si pas $newUser => enregistrement échoué => générer un message pour l'administrateur à afficher plus bas
        $message = "Impossible d'enregistrer le nouvel utilisateur...";
    }
}
?>

<!DOCTYPE html>
<html>
<head>

    <title>Administration des catégories - Mon premier blog !</title>

    <?php require 'partials/head_assets.php'; ?>

</head>
<body class="index-body">
<div class="container-fluid">

    <?php require 'partials/header.php'; ?>

    <div class="row my-3 index-content">

        <?php require 'partials/nav.php'; ?>

        <?php if (isset($_GET['category_id'])) { ?>
            <?php

            $queryCategory = $db->prepare('SELECT * FROM category WHERE id = ?');
            $queryCategory->execute(array($_GET['category_id']));
            $LISTE = $queryCategory->fetch();


            ?>

            <section class="col-9">
                <header class="pb-3">
                    <h4>Modification d'une catégorie</h4>
                </header>

                <?php if (isset($message)): //si un message a été généré plus haut, l'afficher ?>
                    <div class="bg-danger text-white">
                        <?= $message; ?>
                    </div>
                <?php endif; ?>

                <form action="category-form.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Nom :</label>
                        <input class="form-control" type="hidden" value="<?php echo $LISTE['id']; ?>" name="id" id="id"
                               required/>
                        <input class="form-control" value="<?php echo $LISTE['name']; ?>" type="text" placeholder="Nom"
                               name="name" id="name"/>
                    </div>
                    <div class="form-group">
                        <label for="description">Description : </label>
                        <input class="form-control" value="<?php echo $LISTE['description']; ?>" type="text"
                               placeholder="Description" name="description" id="description"/>
                    </div>
                    <div class="text-right">
                        <input class="btn btn-success" type="submit" name="Update" value="Enregistrer"/>
                    </div>
                </form>
            </section>
        <?php } else { ?>

            <section class="col-9">
                <header class="pb-3">
                    <h4>Ajouter une catégorie</h4>
                </header>

                <?php if (isset($message)): //si un message a été généré plus haut, l'afficher ?>
                    <div class="bg-danger text-white">
                        <?= $message; ?>
                    </div>
                <?php endif; ?>

                <form action="category-form.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Nom :</label>
                        <input class="form-control" value="" type="text" placeholder="Nom" name="name" id="name"/>
                    </div>
                    <div class="form-group">
                        <label for="description">Description : </label>
                        <input class="form-control" value="" type="text" placeholder="Description" name="description"
                               id="description"/>
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
