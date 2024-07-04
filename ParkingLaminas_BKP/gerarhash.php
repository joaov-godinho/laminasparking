<?php
$password = 'admin'; // A senha do administrador
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

echo $hashedPassword;
?>