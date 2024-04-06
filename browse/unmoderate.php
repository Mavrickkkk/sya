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
?>
<html>
<head>
    <title>unmoderate.</title>
    <meta charset="utf-8"/>
    <meta name="description" content="Partie non modérée de SYA, trouvez vos prochaines illustrations préférés."/>
    <link rel="icon" href="../pics/favicon.png"/>
    <link rel="stylesheet" href="../style/style.css">
</head>
<body>
    <nav>
        <a href="../add/send.php" class="nav-item">ajouter.</a>
        <a href="../" class="nav-item">menu.</a>
        <a href="./verified.php" class="nav-item">verified.</a>
    </nav>
    <div class="marginTop">
        <a class="title left">unmoderate.</a>
        <a class="rightTitle">la liberté avant le jugement</a>
    </div>
    <div class="centered">
        <div class="red"></div>
    </div>
    <div class="centered">
        <div class="red2"></div>
    </div>
    <div class="centered marginBottom">
        <div class="red3"></div>
    </div>

    <div class="centered width">
        <?php
        $nbrelt = 14;
        $page = isset($_GET["page"]) ? $_GET["page"] : 1;
        $gap = ($page - 1) * $nbrelt;

        $requestSearch = "SELECT * FROM illustration WHERE type=1 AND month=$month ORDER BY date DESC LIMIT ? OFFSET ?";
        $stmt = mysqli_prepare($idcom, $requestSearch);
        mysqli_stmt_bind_param($stmt, "ii", $nbrelt, $gap);

        mysqli_stmt_execute($stmt);
        $resultSearch = mysqli_stmt_get_result($stmt);

        while ($rowSearch = mysqli_fetch_assoc($resultSearch)) {
            $picFolder = "../db/illustration/";
            $filePic = $rowSearch["pic"];
            $id = $rowSearch["id"];
            $name = $rowSearch["name"];
            $username = $rowSearch["username"];
            echo "<div class=\"illustrationContainer\">";
            echo "<a href=\"illustration.php?id=$id\"><img src=" . $picFolder . $filePic . " class=\"illustration\"></a>";
            echo "</div>";
        }
        mysqli_stmt_close($stmt);
        ?>
    </div>
    <?php
    $countRequest = "SELECT COUNT(*) as total FROM illustration WHERE type=1";
    $countResult = mysqli_query($idcom, $countRequest);
    $countRow = mysqli_fetch_assoc($countResult);
    $totalElements = $countRow['total'];
    $totalPages = ceil($totalElements / $nbrelt);
    echo "<div class=\"pageChoice centered\">";
    for ($i = 1; $i <= $totalPages; $i++) {
        echo "<a href='unmoderate.php?type=$type&page=$i'>$i</a> ";
    }
    echo "</div>";
    ?>

    <div class="centered">
        <a href="../soutenir/soutiens.php"><img src="../pics/soutenir.jpg" class="soutenir marginTop marginBottom"></a>
    </div>
    <a class="centered subtitle">depuis 2023</a>
</body>
</html>