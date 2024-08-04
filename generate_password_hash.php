<?php
$newPassword = 'carlos';
$hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
echo $hashedPassword;
?>
