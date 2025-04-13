<?php
// définition du titre de la page
$title = "Liste des articles";
?>

<h2>Articles</h2>

<?php foreach ($articles as $article) { ?>

	<div class="article">
		<h3>
			<?= htmlspecialchars($article->getTitre()); ?>
		</h3>
		<p>
			<?= nl2br(htmlspecialchars($article->getTexte())); ?>
		</p>
	</div>

<?php
} // fin du foreach 
?>