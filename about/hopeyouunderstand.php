<?php
session_start();
include("../db/connex.inc.php");
$idcom=connex("myparam");
?>
<html>
<head>
    <title>sya</title>
    <link rel="icon" href="../pics/favicon.png"/>
    <link rel="stylesheet" href="../style/styleAbout.css">
    <meta charset="utf-8"/>
</head>
<body>
<div class="sticky-bar">
    <div class="bar-content">
        <a class="nameBar">sya</a>
        <ul class="bar-links">
            <li><a href="../index.php" class="links">home</a></li>
            <li><a href="../browse/verified.php" class="links">browse</a></li>
            <?php
            if (isset($_SESSION["name"]))
                echo "<li><a href=\"../add/send.php?type=illustration\" class=\"links\">add</a></li>";
            ?>
            <?php
            if (!isset($_SESSION["username"])){
                ?>
                <li><a href="../connect/login.php" class="links">connect</a></li>
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
<img src="../pics/about1.jpg" class="pic">
<img src="../pics/about2.jpg" class="pic">
<img src="../pics/about3.jpg" class="pic">
<div class="signatureContainer"><img src="../pics/signature.png" class="signature"></div>
</body>
</html>