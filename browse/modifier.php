<?php
session_start();
include("../db/connex.inc.php");
$idcom = connex("myparam");
$id = $_GET["id"];
if(isset($_POST["delete"])) {
    $request5 = "SELECT * FROM illustration WHERE id=$id";
    $result = @mysqli_query($idcom, $request5);
    while ($row = mysqli_fetch_assoc($result)) {
        $fileCoverTmp = $row["pic"];
        $fileCover = "../db/illustration/" . $fileCoverTmp;
        if (file_exists($fileCover)) {
            unlink($fileCover);
        }
    }
    $request3 = "DELETE FROM illustration WHERE id=?";
    $stmt = mysqli_prepare($idcom, $request3);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    header("Location: ../");
    exit();
}

if(isset($_POST["submit"])) {
    if (!empty($_POST["name"])) {
        $newName = $_POST["name"];
        if (strlen($newName) <= 25) {
            $request2 = "UPDATE illustration SET name=? WHERE id=?";
            $stmt = mysqli_prepare($idcom, $request2);
            mysqli_stmt_bind_param($stmt, "ss", $newName, $id);
            mysqli_stmt_execute($stmt);
        } else {
            echo "<a class=\"error-message\">le nom est trop long (25 max)</a>";
        }
    }
    if (!empty($_POST["description"])) {
        $newDescription = $_POST["description"];
        echo $newDescription;
        if (strlen($newDescription) <= 255) {
            $request3 = "UPDATE illustration SET description=? WHERE id=?";
            $stmt = mysqli_prepare($idcom, $request3);
            mysqli_stmt_bind_param($stmt, "ss", $newDescription, $id);
            mysqli_stmt_execute($stmt);

        }
        else {
            echo "<a class=\"error-message\">la description est trop longue (255 max)</a>";
        }
    }
    header("Location: illustration.php?id=$id");
    exit();
}
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
        }
    }
    ?>
    <div class="centered marginBottom">
        <div class="backW">
            <a class="title black left lilMarginTop">Modifier.</a>
            <a class="rightTitle black">uniquement les champs nécessaires</a>
            <form action="modifier.php?id=<?php echo $id; ?>" method="post">
                <div class="form-group lilMarginTop">
                    <label for="name">Nom de l'illustration :</label>
                    <input type="text" name="name" id="name" value="<?php echo $nameIllustration; ?>">
                </div>
                <div class="form-group lilMarginTop marginBottom">
                    <label for="description">Description :</label>
                    <input type="text" name="description" id="description" value="<?php echo $description; ?>">
                </div>
                <div class="form-group">
                    <div class="centered">
                        <button class="buttonBW text" type="submit" name="submit">Mettre à jour</button>
                    </div>
                    <div class="centered lilMarginTop EL">
                        <a class="rightTitle black" href="illustration.php?id=<?php echo $id ?>">retour</a>
                    </div>
                    <div class="centered lilMarginTop EL">
                        <button class="rightTitle redText" type="submit" name="delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette illustration ?')">Supprimer l'illustration</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</body>
</html>