<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>PHP 002 | commenter, afficher le code</title>

	<link rel="stylesheet" href="../html/css/froggie.css" />
	<style>
		.cadre {
			border: #000 solid 1px;
			margin: 10px;
			padding: 10px;
		}

		table td {
			background-color: #CCC;
			padding: 4px;
		}
	</style>
</head>

<body>

	<br><br>

	<div class="cadre">

		<h1>commenter le code : </h1>

		<?php
		//Déclaration de la variable $jcvd
		$jcvd;

		//Affectation de $jcvd
		$jcvd = "Jean Claude Van Damme";

		/* Affichage du contenu de $jcvd 
	******************************* */
		echo "Thomas a apprécié le jeu d'acteur de $jcvd dans streetfighter le film";

		?>

	</div>

	<div class="froggiesplaining">
		<span> Froggiesplaining :</span>
		<br>
		<img src="img/002-2.png" alt="">

		<img src="../html/img/froggie-300.png" alt="Froggie" class="overfrog" />
	</div>
	<br><br>
	<div class="cadre">

		<h1>Afficher le code avec echo</h1>
		<?php
		//Un simple echo
		echo "Bonjour le monde <br>";

		//Un echo sur plusieurs lignes
		echo "Cet echo se

	répartit sur plusieurs lignes.
	
	C'est au
	choix de chacun d'utiliser une ou
	plusiers lignes. <br>";

		//Le caractère d'échappement backslash utilisé avec echo
		echo "L'échappement de caractères se fait : \"comme ceci\"." . $jcvd . "<br>";

		// Afficher les contenus des variables avec echo()
		$Mission = "Ranges ta chambre";
		$name = "Sebastien";

		echo "La variable \$Mission contient \"$Mission\", hein $name." . "<br>";

		// Vous pouvez aussi utiliser des tableaux
		$quoi = array("avis" => "ballec", "name" => "Paul");
		echo "{$quoi['name']} a dit un jour : Le php c'est {$quoi['avis']} !" . "<br>";

		// Les guillemets simples annulent le déférencement des variables
		echo 'variable1 vaut $variable1' . '<br>';

		// Avec des paramètres
		echo 'Cette ', 'chaîne ', 'a été ', 'faite ', 'avec plusieurs paramètres.' . '<br>';

		// Avec la concaténation
		echo 'Cette ' . $name . ' chaîne ' . 'a été ' . 'faite ' . 'avec la concaténation.<br>';

		// On exécute une méthode dans le echo

		echo "Cette chaîne est en " . strtoupper('majuscules') . ".";
		?>
	</div>
	<div class="froggiesplaining">
		<span> Froggiesplaining :</span> <br>
		<img src="img/002-1.png" alt="">

		<img src="../html/img/froggie-300.png" alt="Froggie" class="overfrog" />
	</div>

	<br><br>


	<div class="cadre">
		<h1>Fractionner le code php c'est possible </h1>

		<?php
		$f1 = "Mohamed";
		$f2 = "Manon";
		$f3 = "Sebastien";
		?>

		<table>
			<tr>
				<td> <?php echo $f1; ?> </td>
				<td> <?php echo $f2; ?> </td>
				<td> <?php echo $f3; ?> </td>
			</tr>
			<tr>
				<td> 20 </td>
				<td> 20 </td>
				<td> 20 </td>
			</tr>
		</table>

	</div>

	<div class="froggiesplaining">
		<span> Froggiesplaining :</span>
		<br>
		<img src="img/002-3.png" alt="">

		<img src="../html/img/froggie-300.png" alt="Froggie" class="overfrog" />
	</div>




</body>

</html>