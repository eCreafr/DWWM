<?php
$password = "ici mettre pass a hacher";
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Votre hash : " . $hash;
