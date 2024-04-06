<?php
session_start();
include("../db/connex.inc.php");
$idcom = connex("myparam");
$id = $_GET["id"];
?>
<html>
<head>
    <title>illustration.</title>
    <link rel="icon" href="../pics/favicon.png"/>
    <link rel="stylesheet" href="../style/style.css">
    <meta charset="utf-8"/>
</head>
<body>
<div class="centered totalHeight midHeight">
    <?php
    $request = "SELECT * from illustration WHERE id=$id";
    $result = @mysqli_query($idcom, $request);
    while ($row = mysqli_fetch_assoc($result)) {
        $picFolder = "../db/illustration/";
        $filePic = $row["pic"];
        $nameIllustration = $row["name"];
        $username = $row["username"];
        $description = $row["description"];
        $type = $row["type"];
        $request2 = "SELECT * FROM user WHERE username='$username'";
        $result2 = @mysqli_query($idcom, $request2);
        while ($row2 = mysqli_fetch_assoc($result2)) {
            $name = $row2["name"];
            $soutiens = $row2["soutiens"];
            echo "<div class=\"illustrationContainer\">";
            echo "<img src=" . $picFolder . $filePic . " class=\"illustration big\">";
            echo "</div></div>";
            echo "<a class=\"title left\">" . $nameIllustration . "</a>";
        }
    }
    ?>
    <div class="left">
        <div class="margin">
            <a class="buttonWB text" href="../connect/profile.php?username=<?php echo $username; ?>">
                <?php echo $name ?>
                <?php
                    if ($soutiens>=2)
                        echo "<span class=\"mini\"></span>"
                ?>
            </a>
            <?php
                if ($type==2)
                    echo '<a href="verified.php" class="buttonWB text">retour.</a>';
                if ($type==1)
                    echo '<a href="unmoderate.php" class="buttonWB text">retour.</a>';
            ?>
            <a class="buttonBlue text" href="<?php echo $picFolder . $filePic; ?>" download>Télécharger.</a>
            <?php
            if ((isset($_SESSION["username"]) && $username == $_SESSION["username"]) || $_SESSION["role"]==3) {
                echo "<a href=\"modifier.php?id=$id\" class=\"buttonRed text\">Modifier.</a>";
            }
            ?>
        </div>
        <?php if ($type==2) {
            ?>
        <div class="margin lilMarginTop marginBottom">
            <a class="buttonBlue text">verified.</a>
        </div>
        <?php
        } if($type==1){
        ?>
        <div class="margin lilMarginTop marginBottom">
            <a class="buttonRed text">unmoderate.</a>
        </div>
        <?php
        } if ($_SESSION["role"]==3){
            ?>
        <div class="margin lilMarginTop">
            <a class="buttonBlue text" href="../db/verifyIllustration.php?id=<?php echo $id?>" >verified.</a>
            <a class="buttonRed text" href="../db/unmoderateIllustration.php?id=<?php echo $id?>" >unmoderate.</a>
        </div>
        <?php
        }
        ?>

    </div>
    <div class="marginBottom"></div>
    <div class="left">
        <a class="title">Description.</a>
    </div>
    <div class="left lilMarginTop marginBottom">
        <?php
            if ($description!="")
                echo "<a class=\"rightTitle\">$description</a>";
            else
                echo "<a class=\"rightTitle\">Cette illustration ne contient pas de description.</a>";
        ?>
    </div>

</body>
</html>