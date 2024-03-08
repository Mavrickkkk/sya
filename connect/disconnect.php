<?php
    session_start();
    unset($_SESSION["username"]);
    unset($_SESSION["name"]);
    unset($_SESSION["role"]);
    header('Location: ../index.php');
    exit();
?>