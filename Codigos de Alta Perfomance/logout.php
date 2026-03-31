<?php
session_start();
session_destroy(); //rasga o cracha de acesso
header("Location: login.php"); //manda de volta pra tela de login
exit;
?>