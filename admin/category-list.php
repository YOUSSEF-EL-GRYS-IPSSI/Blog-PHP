<?php

require_once '../tools/common.php';

if(!isset($_SESSION['user']) OR $_SESSION['user']['is_admin'] == 0){
	header('location:../index.php');
	exit;
}

if(isset($_GET['category_id'])){
	
$id=$_GET['category_id'];
$Query="DELETE  FROM category  WHERE  id='$id'";								
$Stmt=$db->prepare($Query);
$Stmt->execute();
$Count=$Stmt->rowCount();
if($Count >0){
						$loginMessage="Suppression Categorie non réussie";
					 							
					    }										
					   Else{
		                      $loginMessage="Suppression Categorie réussie";
}

}

if(isset($_GET['udpate'])){
$loginMessage = "Enregistrement Categorie non réussie";
	
}
if(isset($_GET['save'])){
$loginMessage = "Modification Categorie réussie";
	
}

//séléctionner toutes les catégories pour affichage de la liste
$query = $db->query('SELECT * FROM category ORDER BY id DESC');
$categories = $query->fetchall();
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
				<section class="col-9">
					<header class="pb-4 d-flex justify-content-between">
						<h4>Liste des catégories</h4>
						<a class="btn btn-primary" href="category-form.php">Ajouter une catégorie</a>
					</header>
					<?php if(isset($loginMessage)): ?>
							<div class="text-danger col-sm-8 offset-sm-2 mb-4"><?=$loginMessage; ?></div>
							<?php endif; ?>
					<table class="table table-striped">
						<thead>
							<tr>
								<th>#</th>
								<th>Name</th>
								<th>Description</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php if($categories): ?>
							<?php foreach($categories as $category): ?>
							<tr>
								<th><?= $category['id']; ?></th>
								<td><?= $category['name']; ?></td>
								<td><?= $category['description']; ?></td>
								<td>
										<a href="category-form.php?category_id=<?=$category['id']; ?>&action=edit" class="btn btn-warning">Modifier</a>
										<a onclick="return confirm('Voulez vous vraiment supprimer  <?= $category['name']; ?>?')" href="category-list.php?category_id=<?=$category['id']; ?>&action=delete" class="btn btn-danger">Supprimer</a>
								</td>
							</tr>
							<?php endforeach; ?>
							<?php else: ?>
								Aucune catégorie enregistrée.
							<?php endif; ?>
						</tbody>
					</table>
				</section>
			</div>
		</div>
	</body>
</html>
