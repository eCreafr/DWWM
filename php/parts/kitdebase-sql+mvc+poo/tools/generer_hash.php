<?php

/**
 * Script temporaire pour générer un hash de mot de passe
 * À exécuter une fois puis à supprimer
 * pouf info MD5 pour les mots de passe : c'est obsolète et non sécurisé. ici password_hash() utilise bcrypt qui est bien plus sécurisé.
 */

$motDePasse = 'admin123';
$hash = password_hash($motDePasse, PASSWORD_DEFAULT);

echo "Mot de passe : $motDePasse\n";
echo "Hash généré : $hash\n";
echo "\n";
echo "Requête SQL à exécuter :\n";
echo "UPDATE admins SET motDePasse = '$hash' WHERE login = 'admin';\n";
