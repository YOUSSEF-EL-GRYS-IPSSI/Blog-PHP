<?php require_once 'tools/common.php'; ?>
<?php
//si une catégorie est demandée
if (isset($_GET['category_id'])) {
	//si l'id envoyé n'est pas un nombre entier, je redirige
	if (!ctype_digit($_GET['category_id'])) {
		header('location:index.php');
		exit;
	}
	//selection des articles de la catégorie demandée
	//$queryArticles = $db->prepare('SELECT * FROM article WHERE category_id = ? ORDER BY published_at DESC');
	$queryArticles = $db->prepare('SELECT A.id as  id ,title, summary,published_at,name FROM article A JOIN category G
	WHERE  category_id = ? and published_at <= NOW() AND is_published =1 and A.category_id=G.id
	ORDER BY A.id DESC');
	$queryArticles->execute( array( $_GET['category_id'] ) );

	//selection des informations de la catégorie demandée
	$queryCategory = $db->prepare('SELECT * FROM category WHERE id = ?');
	$queryCategory->execute( array( $_GET['category_id'] ) );
	$selectedCategory = $queryCategory->fetch();

	//si la catégorie n'a pas été trouvé je redirige
	if ($selectedCategory == false) {
		header('location:index.php');
		exit;
	}
}
else {
	//si pas decatégorie demandée j'affiche tous les articles
	//$queryArticles = $db->query('SELECT * FROM article ORDER BY id DESC');
	$queryArticles = $db->query('SELECT A.id as  id ,title, summary,published_at,name FROM article A JOIN category G
	WHERE published_at <= NOW() AND is_published =1 and A.category_id=G.id
	ORDER BY A.id DESC');
//$homeArticles=$query->fetchAll();
}
//puis je récupère les données selon la requête générée avant
$articles = $queryArticles->fetchAll();
?>

<!DOCTYPE html>
<html>
 <head>

	 <title><?php if(isset($_GET['category_id'])): ?><?=  $selectedCategory['name']; ?><?php else: ?>Tous les articles<?php endif; ?> - Mon premier blog !</title>
   <?php require 'partials/head_assets.php'; ?>

 </head>
 <body class="article-list-body">
	<div class="container-fluid">

		<?php require 'partials/header.php'; ?>

		<div class="row my-3 article-list-content">

			<?php require('partials/nav.php'); ?>

			<main class="col-9">
				<section class="all_aricles">
					<header>
						<?php if(isset($_GET['category_id'])): ?>
						<h1 class="mb-4"><?php echo $selectedCategory['name']; ?></h1>
						<?php else: ?>
						<h1 class="mb-4">Tous les articles :</h1>
						<?php endif; ?>
					</header>

					<?php if(isset($_GET['category_id'])): ?>
					<div class="category-description mb-4">
					<?php echo $selectedCategory['description']; ?>
					</div>
					<?php endif; ?>
					
					<!-- s'il y a des articles à afficher -->
					<?php if(count($articles) > 0): ?>
						<?php foreach($articles as $key => $article): ?>
							
							<article class="mb-4">
								<h2><?php echo $article['title']; ?></h2>
								<?php if( !isset($_GET['category_id'])): ?>
								<?php endif; ?>
								<span class="article-date">
									<!-- affichage de la date de l'article selon le format %A %e %B %Y -->
									<strong style="color:red">[ <?php echo $article['name']; ?>] </strong><?php echo strftime("%A %e %B %Y", strtotime($article['published_at'])); ?>
								</span>
								<div class="article-content">
									<?php echo $article['summary']; ?>
								</div>
								<a href="article.php?article_id=<?php echo $article['id']; ?>"> Lire l'article</a>
							</article>
							<?php //endif; ?>
						<?php endforeach; ?>
					<!-- s'il n'y a pas d'articles à afficher -->
					<?php else: ?>
						Aucun article
					<?php endif; ?>
				</section>
			</main>

		</div>

		<?php require 'partials/footer.php'; ?>

	</div>
 </body>
</html>
