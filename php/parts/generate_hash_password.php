<?php
$password = "123";
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Votre hash est : " . $hash;
