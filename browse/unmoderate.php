<?php
    session_start();
    include("../db/connex.inc.php");
    $idcom = connex("myparam");
    $requestmonth = "SELECT * from month WHERE month=0";
    $resultmonth = @mysqli_query($idcom, $requestmonth);
    while ($rowmonth = mysqli_fetch_assoc($resultmonth)) {
        $month=$rowmonth["month"];
    }
    $type=$_GET["type"];
    if (!$type) $type="illustration";
?>
<html>
<head>
    <title>sya - unmoderate</title>
    <meta charset="utf-8"/>
    <meta name="description" content="Partie non modérée de SYA, trouvez vos prochaines illustrations ou single préférés."/>
    <link rel="icon" href="../pics/favicon.png"/>
    <link rel="stylesheet" href="../style/styleUnmoderate.css">
</head>
<body>
<div class="sticky-bar">
    <div class="bar-content">
        <a href="../"><img src="../pics/logo2.png" class="logoBar"></a>
        <ul class="bar-links">
            <li><a href="verified.php" class="links">verified</a></li>
            <li><a href="../about/about.php" class="links">à propos</a></li>
          <?php
              	if (isset($_SESSION["name"]))
                	echo "<li><a href=\"../add/send.php?type=illustration\" class=\"links\">ajouter</a></li>";
              ?>
            <?php
            if (!isset($_SESSION["username"])) {
                ?>
                <li><a href="../connect/login.php" class="links">se connecter</a></li>
                <?php
            } else {
                ?>
                <li><a class="links" href="../connect/profile.php?username=<?php echo $_SESSION["username"]; ?>"><?php echo $_SESSION["name"]; ?></a></li>
                <?php
            }
            ?>
        </ul>
    </div>
</div>
<img src="../pics/unmoderateHome.jpg" class="unmoderateHome">

<?php
if ($type=="illustration"){
    ?>
    <form action="unmoderate.php?type=illustration" method="POST">
        <div class="searchBox">
            <input class="search" type="text" name="name" placeholder="nom">
        </div>
        <input class="submit" type="submit" value="send">
    </form>
    <div class="searchBox">
        <a class="choiceText">-> ILLUSTRATIONS</a>
        <a class="non-choiceText" href="unmoderate.php?type=single">SINGLES</a>
    </div>

    <div class="singleContent">
        <?php
        $nbrelt = 14;
        $page = isset($_GET["page"]) ? $_GET["page"] : 1;
        $gap = ($page - 1) * $nbrelt;
        if (isset($_POST["name"])) {
            $search = "%" . $_POST["name"] . "%";
            $requestSearch = "SELECT * FROM illustration WHERE type=1 AND month=$month AND name LIKE ?";
            $stmt = mysqli_prepare($idcom, $requestSearch);
            mysqli_stmt_bind_param($stmt, "s", $search);
        } else {
            $requestSearch = "SELECT * FROM illustration WHERE type=1 AND month=$month LIMIT ? OFFSET ?";
            $stmt = mysqli_prepare($idcom, $requestSearch);
            mysqli_stmt_bind_param($stmt, "ii", $nbrelt, $gap);
        }

        mysqli_stmt_execute($stmt);
        $resultSearch = mysqli_stmt_get_result($stmt);

        while ($rowSearch = mysqli_fetch_assoc($resultSearch)) {
            $picFolder = "../db/illustration/";
            $filePic = $rowSearch["pic"];
            $id = $rowSearch["id"];
            $name = $rowSearch["name"];
            $username = $rowSearch["username"];
            echo "<div class=\"picContent\">";
            echo "<a href=\"illustration.php?id=$id\"><img src=" . $picFolder . $filePic . " class=\"pic\"></a>";
            echo "<div class=\"infoContent\">";
            echo "<a class=\"picName\">" . $name . "</a>";
                $requestName = "SELECT name from user WHERE username='$username'";
                $resultName = @mysqli_query($idcom, $requestName);
                while ($rowName = mysqli_fetch_assoc($resultName)) {
                    $name=$rowName["name"];
                    echo "<a href=\"../connect/profile.php?username=$username\" class=\"picName\"> by " . $name . "</a>";
                }
            echo "</div>";
            if (isset($_SESSION["role"]) && $_SESSION["role"] == 3) {
                echo "<div class\"xvtContent\">";
                echo "<a class=\"x\" href=\"../db/removeIllustration.php?id=" . $rowSearch["id"] . "\">remove</a>";
                echo "</div>";
                echo "<div class\"xvtContent\">";
                echo "<a class=\"v\" href=\"../db/verifyIllustration.php?id=" . $rowSearch["id"] . "\">verified</a>";
                echo "</div>";
                echo "<div class\"xvtContent\">";
                echo "<a class=\"top\" href=\"../db/iotw.php?id=" . $rowSearch["id"] . "\">top</a>";
                echo "</div>";
            }
            echo "</div>";
        }
        mysqli_stmt_close($stmt);
        ?>
    </div>
    <?php
    $countRequest = "SELECT COUNT(*) as total FROM illustration WHERE type=2";
    $countResult = mysqli_query($idcom, $countRequest);
    $countRow = mysqli_fetch_assoc($countResult);
    $totalElements = $countRow['total'];
    $totalPages = ceil($totalElements / $nbrelt);
    echo "<div class=\"pageChoice\">";
    for ($i = 1; $i <= $totalPages; $i++) {
        echo "<a href='verified.php?type=$type&page=$i'>$i</a> ";
    }
    echo "</div>";
}
?>

<?php
    if ($type=="single") {
?>
    }
    <form action="unmoderate.php" method="POST">
        <div class="searchBox">
            <input class="search" type="text" name="name" placeholder="nom">
        </div>
        <input class="submit" type="submit" value="send">
    </form>
    <div class="searchBox">
        <a class="non-choiceText" href="unmoderate.php">ILLUSTRATIONS</a>
        <a class="choiceText">-> SINGLES</a>
    </div>
<div class="singleContent">
    <?php
    $nbrelt = 14;
    $page = isset($_GET["page"]) ? $_GET["page"] : 1;
    $gap = ($page - 1) * $nbrelt;
    if (isset($_POST["name"])) {
        $search = $_POST["name"];
        $requestSearch = "SELECT * FROM single WHERE type=1 AND month=$month AND name LIKE ?";
        $stmt = mysqli_prepare($idcom, $requestSearch);
        mysqli_stmt_bind_param($stmt, "s", $search);
    } else {
        $requestSearch = "SELECT * FROM single WHERE type=1 AND month=$month LIMIT $nbrelt OFFSET $gap";
        $stmt = mysqli_prepare($idcom, $requestSearch);
    }

    mysqli_stmt_execute($stmt);
    $resultSearch = mysqli_stmt_get_result($stmt);

    while ($rowSearch = mysqli_fetch_assoc($resultSearch)) {
        $coverFolder = "../db/cover/";
        $fileCover = $rowSearch["fileCover"];
        $trackFolder = "../db/track/";
        $fileTrack = $rowSearch["fileTrack"];
        $id=$rowSearch["id"];
        $nameSingle = $rowSearch["name"];
        $username = $rowSearch["username"];
        echo "<div class=\"picContent\">";
        echo "<a href=\"single.php?id=$id\"><img src=" . $coverFolder . $fileCover . " class=\"pic\"></a>";
        if (isset($_SESSION["role"]) && $_SESSION["role"]==3 || $_SESSION["role"]==2)
            echo "<audio src=" . $trackFolder . $fileTrack . " controls></audio>";
        echo "<div class=\"infoContent\">";
            echo "<a class=\"picName\">" . $nameSingle . "</a>";
            $requestName = "SELECT name from user WHERE username='$username'";
            $resultName = @mysqli_query($idcom, $requestName);
            while ($rowName = mysqli_fetch_assoc($resultName)) {
                $name=$rowName["name"];
                echo "<a href=\"../connect/profile.php?username=$username\" class=\"picName\"> by " . $name . "</a>";
            }
        echo "</div>";
        if (isset($_SESSION["role"]) && $_SESSION["role"]==3|| $_SESSION["role"]==2) {
            echo "<div class\"xvtContent\">";
            echo "<a class=\"x\" href=\"../db/removeSingle.php?id=" . $rowSearch["id"] . "\">remove</a>";
            echo "</div>";
            echo "<div class\"xvtContent\">";
            echo "<a class=\"v\" href=\"../db/verifySingle.php?id=" . $rowSearch["id"] . "\">verified</a>";
            echo "</div>";

        }
        if (isset($_SESSION["role"]) && $_SESSION["role"]==3) {
            echo "<div class\"xvtContent\">";
            echo "<a class=\"top\" href=\"../db/sotw.php?id=" . $rowSearch["id"] . "\">top</a>";
            echo "</div>";
        }
        echo "</div>";
    }
    mysqli_stmt_close($stmt);
    ?>
</div>
<?php
    $countRequest = "SELECT COUNT(*) as total FROM single WHERE type=1";
    $countResult = mysqli_query($idcom, $countRequest);
    $countRow = mysqli_fetch_assoc($countResult);
    $totalElements = $countRow['total'];
    $totalPages = ceil($totalElements / $nbrelt);
    echo "<div class=\"pageChoice\">";
    for ($i = 1; $i <= $totalPages; $i++) {
        echo "<a href='unmoderate.php?page=$i'>$i</a> ";
    }
    echo "</div>";
    }
?>
<footer>
    <div class="footer-content">
        <a href="../about/contact.php">me contacter</a>
        <a>attention</a>
        <a href="verified.php">retourner dans un endroit plus sûr</a>
    </div>
</footer>
</body>
</html>