<?php
    session_start();
    include("../db/connex.inc.php");
    $idcom = connex("myparam");
    $username = $_GET["username"];

    $requestUser = "SELECT * FROM user WHERE username=?";
    $stmt = mysqli_prepare($idcom, $requestUser);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $resultUser = mysqli_stmt_get_result($stmt);

    if ($resultUser) {
        $rowUser = mysqli_fetch_assoc($resultUser);
        $name = $rowUser["name"];
        $soutiens = $rowUser["soutiens"];
        if ($soutiens>=1) {
            $insta = $rowUser["instagram"];
            $youtube = $rowUser["youtube"];
        }
        else {
            $insta = "";
            $youtube = "";
        }
    } else {
        echo "SQL Error";
        exit();
    }
$type=$_GET["type"];
if (!$type) $type="illustration";
?>
<html>
    <head>
        <title>profil.</title>
        <link rel="icon" href="../pics/favicon.png"/>
        <link rel="stylesheet" href="../style/style.css">
        <meta charset="utf-8"/>
    </head>
    <body>
    <nav>
        <a href="../add/send.php" class="nav-item">ajouter.</a>
        <a href="../" class="nav-item">menu.</a>
        <a href="../browse/verified.php" class="nav-item">parcourir.</a>
    </nav>
    <div class="marginTop">
        <a class="title left"><?php echo $name ?>.</a>
        <a class="rightTitle">mon profil.</a>
    </div>

    <div class="centered">
        <div class="tiffany"></div>
    </div>
    <div class="centered">
        <div class="tiffany2"></div>
    </div>
    <div class="centered">
        <div class="tiffany3"></div>
    </div>
    <div class="left lilMarginTop">
        <?php
        if ($username==$_SESSION["username"]) { ?>
            <a class="buttonRed backTiffany text" href="modifier.php?username=<?php echo $username ?>">modifier.</a>
            <a class="buttonWB text" href="disconnect.php">deconnexion</a>
        <?php
        } if ($youtube!="" || $insta!=""){
        ?>
            <a class="buttonWB text" href="https://www.instagram.com/<?php echo $insta ?>">instagram</a>
            <a class="buttonWB text" href="https://youtube.com/<?php echo $youtube ?>">youtube</a>
        <?php
        }
        ?>
        <?php
        if ($soutiens>=2) {
            ?>
            <a class="buttonWB text">
                soutiens
                <span class="mini"></span>
            </a>
        <?php
        }
        ?>

    </div>
    <div class="centered width">
        <?php
        $nbrelt=14;
        $page = isset($_GET["page"]) ? $_GET["page"] : 1;
        $gap = ($page-1) * $nbrelt;
        $request = "SELECT * from illustration WHERE username=\"$username\" ORDER BY date DESC LIMIT $gap, $nbrelt";
        $result = @mysqli_query($idcom, $request);
        while ($row = mysqli_fetch_assoc($result)) {
            $picFolder = "../db/illustration/";
            $filePic = $row["pic"];
            $id = $row["id"];
            $namePic = $row["name"];
            $username = $row["username"];
            echo "<div class=\"illustrationContainer\">";
            echo "<a href=\"../browse/illustration.php?id=$id\"><img src=" . $picFolder . $filePic . " class=\"illustration\"></a>";
            echo "</div>";
        }
        ?>
    </div>
    <?php
    $countRequest = "SELECT COUNT(*) as total FROM illustration WHERE username=?";
    $stmt = mysqli_prepare($idcom, $countRequest);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $countResult = mysqli_stmt_get_result($stmt);
    $countRow = mysqli_fetch_assoc($countResult);
    $totalElements = $countRow['total'];
    $totalPages = ceil($totalElements / $nbrelt);
    echo "<div class=\"pageChoice centered marginBottom\">";
    for ($i = 1; $i <= $totalPages; $i++) {
        echo "<a href='profile.php?username=" . htmlspecialchars($username) . "&page=$i'>$i</a> ";
    }
    echo "</div>";
    ?>
    </body>
</html>