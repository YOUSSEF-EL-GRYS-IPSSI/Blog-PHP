<?php

require_once '../tools/common.php';

if(!isset($_SESSION['user']) OR $_SESSION['user']['is_admin'] == 0){
	header('location:../index.php');
	exit;
}

if(isset($_GET['id_delete_article'])){
	
$id=$_GET['id_delete_article'];
$Query="DELETE  FROM article WHERE  id='$id'";								
$Stmt=$db->prepare($Query);
$Stmt->execute();
$Count=$Stmt->rowCount();
IF($Count >0){
						$loginMessage="Suppression Article réussie";
					 							
					    }										
					   Else{
		                      $loginMessage="Suppression Article non réussie";							
}

}

//séléctionner tous les articles pour affichage de la liste
$query = $db->query('SELECT * FROM article ORDER BY id DESC');
$articles = $query->fetchall();

if(isset($_GET['udpate'])){
$loginMessage = "Modification Article réussie";	
	
}
if(isset($_GET['save'])){
$loginMessage = "Enregistrement Article  réussie";	
	
}
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

            <section class="col-9">
                <header class="pb-4 d-flex justify-content-between">
                    <h4>Liste des articles</h4>
                    <a class="btn btn-primary" href="article-form.php">Ajouter un article</a>
                </header>
                <?php if (isset($loginMessage)): ?>
                    <div class="text-danger col-sm-8 offset-sm-2 mb-4"><?= $loginMessage; ?></div>
                <?php endif; ?>

                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Titre</th>
                        <th>Publié</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($articles): ?>
                        <?php foreach ($articles as $article): ?>
                            <tr>
                                <th><?= $article['id']; ?></th>
                                <td><?= $article['title']; ?></td>
                                <td>
                                    <?php echo ($article['is_published'] == 1) ? 'Oui' : 'Non'; ?>
                                </td>
                                <td>
                                    <a href="article-form.php?article_id=<?= $article['id']; ?>&action=edit"
                                       class="btn btn-warning">Modifier</a>
                                    <a onclick="return confirm('Voulez vous vraiment supprimer  <?= $article['title']; ?>?')"
                                       href="article-list.php?id_delete_article=<?= $article['id']; ?>&action=delete"
                                       class="btn btn-ms btn-danger">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        Aucun article enregistré.
                    <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </div>
    </div>
    </body>
</html>
