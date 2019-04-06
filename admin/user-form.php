<?php
require_once '../tools/common.php';

if (!isset($_SESSION['user']) OR $_SESSION['user']['is_admin'] == 0) {
    header('location:../index.php');
    exit;
}

//Si $_POST['save'] existe, cela signifie que c'est un ajout d'utilisateur
if (isset($_POST['save'])) {

    $query = $db->prepare('INSERT INTO user (firstname, lastname, password, email, is_admin, bio) VALUES (?, ?, ?, ?, ?, ?)');
    $newUser = $query->execute([
        htmlspecialchars($_POST['firstname']),
        htmlspecialchars($_POST['lastname']),
        hash('md5', $_POST['password']),
        htmlspecialchars($_POST['email']),
        $_POST['is_admin'],
        htmlspecialchars($_POST['bio']),
    ]);

    //redirection après enregistrement
    //si $newUser alors l'enregistrement a fonctionné
    if ($newUser) {
        header('location:user-list.php?save=ok');
        exit;
    } else { //si pas $newUser => enregistrement échoué => générer un message pour l'administrateur à afficher plus bas
        $message = "Impossible d'enregistrer le nouvel utilisateur...";
    }
}
if (isset($_POST['Update'])) {

    $query = $db->prepare('UPDATE  user SET firstname=? , lastname=?, email=?, password=?, is_admin=?, bio=? WHERE id= ?');
    $newUser = $query->execute([
        htmlspecialchars($_POST['firstname']),
        htmlspecialchars($_POST['lastname']),
        htmlspecialchars($_POST['email']),
        hash('md5', $_POST['password']),
        $_POST['is_admin'],
        htmlspecialchars($_POST['bio']),
        $_POST['id'],
    ]);

    print_r($newUser);

    //redirection après enregistrement
    //si $newUser alors l'enregistrement a fonctionné
    if ($newUser) {
        header('location:user-list.php?udpate=ok');
        exit;
    } else { //si pas $newUser => enregistrement échoué => générer un message pour l'administrateur à afficher plus bas
        $message = "Impossible d'enregistrer le nouvel utilisateur...";
    }
}

?>

<!DOCTYPE html>
<html>
<head>

    <title>Administration des utilisateurs - Mon premier blog !</title>

    <?php require 'partials/head_assets.php'; ?>

</head>
<body class="index-body">
<div class="container-fluid">

    <?php require 'partials/header.php'; ?>

    <div class="row my-3 index-content">

        <?php require 'partials/nav.php'; ?>

        <?php if (isset($_GET['user_id'])) { ?>
            <?php

            $queryCategory = $db->prepare('SELECT * FROM user WHERE id = ?');
            $queryCategory->execute(array($_GET['user_id']));
            $LISTE = $queryCategory->fetch();


            ?>

            <section class="col-9">
                <header class="pb-3">
                    <h4>Modification de l'utilisateur</h4>
                </header>

                <?php if (isset($message)): //si un message a été généré plus haut, l'afficher ?>
                    <div class="bg-danger text-white">
                        <?= $message; ?>
                    </div>
                <?php endif; ?>

                <form action="user-form.php" method="post">
                    <div class="form-group">
                        <label for="firstname">Prénom :</label>
                        <input class="form-control" type="hidden" value="<?php echo $LISTE['id']; ?>" name="id" id="id"
                               required/>
                        <input class="form-control" type="text" value="<?php echo $LISTE['firstname']; ?>"
                               placeholder="Prénom" name="firstname" id="firstname"/>
                    </div>
                    <div class="form-group">
                        <label for="lastname">Nom de famille : </label>
                        <input class="form-control" type="text" value="<?php echo $LISTE['lastname']; ?>"
                               placeholder="Nom de famille" name="lastname" id="lastname"/>
                    </div>
                    <div class="form-group">
                        <label for="email">Email :</label>
                        <input class="form-control" type="email" value="<?php echo $LISTE['email']; ?>"
                               placeholder="Email" name="email" id="email"/>
                    </div>
                    <div class="form-group">
                        <label for="password">Password : </label>
                        <input class="form-control" type="password" placeholder="Mot de passe"
                               value="<?php echo $LISTE['password']; ?>" name="password" id="password"/>
                    </div>
                    <div class="form-group">
                        <label for="bio">Biographie :</label>
                        <textarea class="form-control" name="bio" id="bio"
                                  placeholder="Sa vie son oeuvre...">  <?php echo $LISTE['bio']; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="is_admin"> Admin ?</label>
                        <select class="form-control" name="is_admin" id="is_admin" required>
                            <?php if ($LISTE['is_admin'] = 1) { ?>
                                <option value="1">Oui</option>
                                <option value="0">Non</option>
                            <?php } else { ?>
                                <option value="0">Non</option>
                                <option value="1">Oui</option>

                            <?php } ?>
                        </select>
                    </div>
                    <div class="text-right">
                        <input class="btn btn-success" type="submit" name="Update" value="Modifier"/>
                    </div>
                </form>
            </section>


        <?php } else { ?>


            <section class="col-9">
                <header class="pb-3">
                    <h4>Ajouter un utilisateur</h4>
                </header>

                <?php if (isset($message)): //si un message a été généré plus haut, l'afficher ?>
                    <div class="bg-danger text-white">
                        <?= $message; ?>
                    </div>
                <?php endif; ?>

                <form action="user-form.php" method="post">
                    <div class="form-group">
                        <label for="firstname">Prénom :</label>
                        <input class="form-control" value="" type="text" placeholder="Prénom" name="firstname"
                               id="firstname"/>
                    </div>
                    <div class="form-group">
                        <label for="lastname">Nom de famille : </label>
                        <input class="form-control" value="" type="text" placeholder="Nom de famille" name="lastname"
                               id="lastname"/>
                    </div>
                    <div class="form-group">
                        <label for="email">Email :</label>
                        <input class="form-control" value="" type="email" placeholder="Email" name="email" id="email"/>
                    </div>
                    <div class="form-group">
                        <label for="password">Password : </label>
                        <input class="form-control" type="password" placeholder="Mot de passe" name="password"
                               id="password"/>
                    </div>
                    <div class="form-group">
                        <label for="bio">Biographie :</label>
                        <textarea class="form-control" name="bio" id="bio"
                                  placeholder="Sa vie son oeuvre..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="is_admin"> Admin ?</label>
                        <select class="form-control" name="is_admin" id="is_admin">
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
