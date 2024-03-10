<?php
session_start();
include("../db/connex.inc.php");
$idcom = connex("myparam");
$username = $_GET["username"];

if ($username=$_SESSION["username"]){

$requestUser = "SELECT * FROM user WHERE username=?";
$stmt = mysqli_prepare($idcom, $requestUser);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$resultUser = mysqli_stmt_get_result($stmt);

if ($resultUser) {
    $rowUser = mysqli_fetch_assoc($resultUser);
    $name = $rowUser["name"];
    $soutiens = $rowUser["soutiens"];
    $insta = $rowUser["instagram"];
    $youtube = $rowUser["youtube"];
} else {
    echo "SQL Error";
    exit();
}

if(isset($_POST["submit"])) {
    $newName = $_POST["name"];
    $newInstagram = $_POST["instagram"];
    $newYoutube = $_POST["youtube"];

    $updateQuery = "UPDATE user SET ";
    $updateValues = [];
    $updateTypes = "";

    if (!empty($newName)) {
        if (strlen($newName) <= 25) {
            $updateValues[] = $newName;
            $updateTypes .= "s";
            $updateQuery .= "name=? ";
        } else {
            echo "<a class=\"error-message\">name too long (25 max)</a>";
        }
    }

    if (!empty($newInstagram)) {
        $updateValues[] = $newInstagram;
        $updateTypes .= "s";
        $updateQuery .= ($updateQuery == "UPDATE user SET ") ? "instagram=? " : ", instagram=? ";
    } else {
        $updateQuery .= ($updateQuery == "UPDATE user SET ") ? "instagram=NULL " : ", instagram=NULL ";
    }

    if (!empty($newYoutube)) {
        $updateValues[] = $newYoutube;
        $updateTypes .= "s";
        $updateQuery .= ($updateQuery == "UPDATE user SET ") ? "youtube=? " : ", youtube=? ";
    } else {
        $updateQuery .= ($updateQuery == "UPDATE user SET ") ? "youtube=NULL " : ", youtube=NULL ";
    }

    $updateQuery .= "WHERE username=?";
    $updateValues[] = $username;
    $updateTypes .= "s";

    $stmt = mysqli_prepare($idcom, $updateQuery);
    mysqli_stmt_bind_param($stmt, $updateTypes, ...$updateValues);
    mysqli_stmt_execute($stmt);

    if ($username == $_SESSION["username"]) {
        $_SESSION["name"] = $newName;
    }

    header("Location: profile.php?username=" . htmlspecialchars($username));
    exit();
}


?>
<html>
    <head>
        <title>sya</title>
        <link rel="icon" href="../pics/favicon.png"/>
        <link rel="stylesheet" href="../style/style.css">
        <meta charset="utf-8"/>
    </head>
    <body>
        <nav>
            <a href="../add/send.php" class="nav-item">ajouter.</a>
            <a href="../" class="nav-item">menu.</a>
            <a href="../browse/verified.php" class="nav-item">parcourir.</a>
            <?php
            if (isset($_SESSION["username"]) && $_SESSION["username"]!=""){
                ?>
                <a class="nav-item" href="../connect/profile.php?username=<?php echo $_SESSION["username"]; ?>">mon compte.</a>
                <?php
            } else {
                ?>
                <a class="nav-item" href="../connect/login.php">mon compte.</a>
                <?php
            }
            ?>
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
        </div>
        <div class="centered marginBottom">
            <div class="backW">
                <a class="title black left lilMarginTop">Modifier.</a>
                <a class="rightTitle black">uniquement les champs que vous souhaitez</a>
                <form action="modifier.php?username=<?php echo htmlspecialchars($username); ?>" method="post">
                    <div class="form-group lilMarginTop">
                        <label for="name">Nom :</label>
                        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name); ?>">
                    </div>
                    <?php
                        if ($soutiens>=2){
                    ?>
                    <div class="form-group lilMarginTop">
                        <label for="instagram">Instagram :</label>
                        <input type="text" name="instagram" id="instagram" value="<?php echo htmlspecialchars($insta); ?>">
                    </div>
                    <div class="form-group lilMarginTop">
                        <label for="youtube">YouTube (avec @) :</label>
                        <input type="text" name="youtube" id="youtube" value="<?php echo htmlspecialchars($youtube); ?>">
                    </div>
                    <?php
                        }
                    ?>
                    <div class="form-group">
                        <div class="centered lilMarginTop">
                            <button class="buttonBW text" type="submit" name="submit">Mettre à jour</button>
                        </div>
                        <div class="centered EL lilMarginTop">
                            <a class="rightTitle black" href="profile.php?username=<?php echo $username ?>">annuler</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
<?php
} else {
?>
    <div class="centered">
        <a class="text white">vous ne pouvez pas être ici</a>
    </div>
<?php }
?>