<?php
$password = "12345";
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Votre hash est : " . $hash;
