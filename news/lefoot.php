<?php
    session_start();
    include("../db/connex.inc.php");
    $idcom=connex("myparam");
    $random = random_int(1, 17);
?>
<html>
<head>
    <title>sya</title>
    <link rel="icon" href="../pics/favicon.png"/>
    <link rel="stylesheet" href="../style/stylelefoot.css">
    <meta charset="utf-8"/>
</head>
<body>
<div class="sticky-bar">
    <div class="bar-content">
        <div class="bar-title"><a href="../">sya</a></div>
        <ul class="bar-links">
            <li><a href="../browse/verified.php">browse</a></li>
            <?php
            if (isset($_SESSION["name"]))
                echo "<li><a href=\"../add/send.php?type=illustration\">add</a></li>";
            ?>
            <?php
            if (!isset($_SESSION["username"])){
                ?>
                <li><a href="../connect/login.php">connect</a></li>
                <?php
            }else {
                ?>
                <li><a href="../connect/profile.php?username=<?php echo $_SESSION["username"]; ?>"><?php echo $_SESSION["name"]; ?></a></li>
                <?php
            }
            ?>
        </ul>
    </div>
</div>
<a href="https://youtu.be/d2gw37IqXqE"><img src="../pics/thumbnail.png" class="pic1"></a>
<div class="container">
    <div class="centered">
        <a class="subtitle">25th February 02:00 PM (UTC+1)</a>
    </div>
    <div class="centered-top">
        <a class="subtitle">Fr</a>
    </div>
    <div class="centered">
        <a class="subtitle">Moi (Mavrick) et mon co-host Valentin sommes fiers</br> de vous présenter</a>
    </div>
    <div class="centered">
        <a class="subtitle">"le foot"</a>
    </div>
    <div class="centered">
        <a class="subtitle">Cela va se passer sur le jeu HaxBall, donc en équipe</br> et </br>
            tout le monde est invité à participer,</br>les places seront limités
            </br>mais vous pourrez toujours regarder la partie commentée
            </br>par nous mêmes</br>Merci.</a>
    </div>
    <div class="centered-top">
        <a class="subtitle" href="https://youtu.be/d2gw37IqXqE">Le trailer</a>
    </div>
    <div class="centered">
        <a href="https://twitch.tv/mavricckkk" class="subtitle">Le lien du twitch</a>
    </div>
    <div class="centered">
        <a href="https://discord.gg/BZB735aECR" class="subtitle">Discord</a>
    </div>

    <div class="centered-top">
        <a class="subtitle">Eng</a>
    </div>
    <div class="centered">
        <a class="subtitle">My co-host Valentin and I (Mavrick) are proud</br> to present to you</a>
    </div>
    <div class="centered">
        <a class="subtitle">"le foot"</a>
    </div>
    <div class="centered">
        <a class="subtitle">This will take place on the HaxBall game, so in teams</br> and </br>
            everyone is invited to participate,</br> places will be limited
            </br>but you can always watch the game commented
            </br>by us</br>Thank you.</a>
    </div>
    <div class="centered-top">
        <a class="subtitle" href="https://youtu.be/d2gw37IqXqE">The trailer</a>
    </div>
    <div class="centered">
        <a href="https://twitch.tv/mavricckkk" class="subtitle">The twitch link</a>
    </div>
    <div class="centered">
        <a href="https://discord.gg/BZB735aECR" class="subtitle">Discord</a>
    </div>

</div>
<footer>
    <div class="footer-content">
        <a href="../about/contact.php">contact me</a>
        <?php
        if ($random==1)
            echo "<a>le foot</a>";
        if ($random==2)
            echo "<a href=\"https://bandcamp.com/\">bandcamp is better</a>";
        if ($random==3)
            echo "<a href\"https://www.youtube.com/watch?v=TGgcC5xg9YI\">see you again</a>";
        if ($random==4)
            echo "<a>you're loved</a>";
        if ($random==5)
            echo "<a>call your momma</a>";
        if ($random==6)
            echo "<a>wish you were here before</a>";
        if ($random==7)
            echo "<a>well, missed you</a>";
        if ($random==8)
            echo "<a>straight from france</a>";
        if ($random==9)
            echo "<a>@2023</a>";
        if ($random==10)
            echo "<a>thanks for bring the sunshine today</a>";
        if ($random==11)
            echo "<a>no longer confused but don't tell anybody</a>";
        if ($random==12)
            echo "<a href=\"https://www.youtube.com/watch?v=IVzzw7Vkiyg\">crack rock, crack rock</a>";
        if ($random==13)
            echo "<a href=\"https://www.youtube.com/watch?v=0gvEfIbGJxQ\">you make my earthquake</a>";
        if ($random==14)
            echo "<a>still the kids we used to</a>";
        if ($random==15)
            echo "<a>simple way to express yourself</a>";
        if ($random==16)
            echo "<a>please refresh, this message isn't important</a>";
        if ($random==17)
            echo "<a>sotw = single of the week</a>";
        ?>
    </div>
</footer>
</body>
</html>