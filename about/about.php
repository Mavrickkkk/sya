<?php
    session_start();
    include("../db/connex.inc.php");
    $idcom=connex("myparam");
?>
<html>
<head>
    <title>sya - about</title>
    <meta charset="utf-8"/>
    <meta name="description" content="tout savoir à propos de sya"/>
    <link rel="icon" href="../pics/favicon.png"/>
    <link rel="stylesheet" href="../style/styleAbout.css">
</head>
<body>
    <div class="sticky-bar">
        <div class="bar-content">
            <a class="nameBar" href="../">SYA</a>
            <ul class="bar-links">
                <li><a href="https://www.paypal.com/donate/?hosted_button_id=8XFJ7GKL2Y53W" class="links">don</a></li>
                <li><a href="../browse/verified.php" class="links">parcourir</a></li>
                <?php
              	if (isset($_SESSION["name"]))
                	echo "<li><a href=\"../add/send.php?type=illustration\" class=\"links\">ajouter</a></li>";
              ?>
                <?php
                if (!isset($_SESSION["username"])){
                    ?>
                    <li><a href="../connect/login.php" class="links">se connecter</a></li>
                    <?php
                }else {
                    ?>
                    <li><a class="links" href="../connect/profile.php?username=<?php echo $_SESSION["username"]; ?>"><?php echo $_SESSION["name"]; ?></a></li>
                    <?php
                }
                ?>
            </ul>
        </div>
    </div>
    <div class="main-container">
        <div class="centered-container">
            <a class="title">QUEL EST LE BUT</a></br>
        </div>
        <div class="centered-container">
            <a class="text">C'est une galerie d'art virtuelle</br>
                dans le but de promouvoir et de mettre en valeur votre talent. </br>
                Tout ce que vous publiez peut être utilisé par n'importe qui,</br>
                ce qui signifie que tout le monde peut promouvoir vos créations tout en vous créditant.</br>
                Je vous encourage à imprimer les illustrations et à les afficher dans votre ville</br>
                pour vous aider mutuellement et promouvoir ce que vous aimez.</br>
                Je le ferai aussi, et je le posterai sur Instagram @spreadyourarts.</br>
                Il en va de même pour les singles.</br>
                Pour me soutenir financièrement,</br>
                il y a une option "don" dans la barre de navigation ici et sur la page d'accueil.
            </a>
        </div>
        <div class="centered-container">
            <a class="title">COMMENT UTILISER SYA</a></br>
        </div>
        <div class="centered-container">
            <a class="text">Déposez l'art que vous avez créé</br>
                vous aurez juste besoin d'un compte (sans email)</br>
                votre art doit être votre travail original, et n'importe qui peut l'utiliser à des fins personnelles</br>
                en gros, lorsque vous le publiez, il apparaît dans la section "unmoderate"</br>
                puis peut-être qu'il sera pris dans la section "verified" par un modérateur</br>
                vous pouvez également tout voir sans avoir de compte.</br>
                Il est fait de la manière la plus simple pour la liberté de chacun.</br>
                Ça fait du bien la simplicité.
            </a>
        </div>
        <div class="centered-container">
            <a class="text">
                <?php
                if (isset($_SESSION["name"]))
                    echo "<a class=\"text\" href='../add/send.php'>allons-y</a>";
                else
                    echo "<a class=\"text\" href='../connect/login.php'>allons-y</a>";
                ?>
            </a>
        </div>
        <div class="centered-container">
            <a class="text2" href="hopeyouunderstand.php">anciennes explications</a>
        </div>
    </div>
    <footer>
        <div class="footer-content">
            <a href="contact.php">me contacter</a>
        </div>
    </footer>

</body>
</html>