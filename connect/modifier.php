<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include("../db/connex.inc.php");
$idcom = connex("myparam");
$username = $_GET["username"];

function checkAndRenameFile($targetDir, $fileName)
{
    $originalName = $fileName;
    $i = 0;

    while (file_exists($targetDir . $fileName)) {
        $i++;
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $fileName = pathinfo($originalName, PATHINFO_FILENAME) . $i . "." . $extension;
    }

    return $fileName;
}

if ($username == $_SESSION["username"]) {

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

    if (isset($_POST["submit"])) {
        $newName = $_POST["name"];
        $newInstagram = $_POST["instagram"];
        $newYoutube = $_POST["youtube"];

        $updateQuery = "UPDATE user SET ";
        $updateValues = [];
        $updateTypes = "";
        $shouldRedirect = false;

        // Traitement de l'image
        if ($soutiens >= 5 && $_FILES["profilePic"]["error"] == UPLOAD_ERR_OK) {
            $targetDir = "../db/profilePic/";
            if (!is_dir($targetDir)) {
                echo "<a class='subtitle white centered'>Le dossier de destination n'existe pas.</a>";
                exit();
            }

            $filePicTmp = $_FILES["profilePic"]["name"];
            $filePic = str_replace(' ', '', $filePicTmp);
            $filePic = checkAndRenameFile($targetDir, $filePic);
            $targetFilePath = $targetDir . $filePic;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
            $allowTypes = array('jpg', 'png', 'jpeg');

            if (in_array($fileType, $allowTypes)) {
                if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $targetFilePath)) {
                    // Mise à jour du chemin de l'image dans la base de données
                    $updateValues[] = $filePic;
                    $updateTypes .= "s";
                    $updateQuery .= ($updateQuery == "UPDATE user SET ") ? "profilePicPath=? " : ", profilePicPath=? ";
                    $shouldRedirect = true;
                }
            } else {
                $error_message = "<a class='subtitle white centered'>Veuillez sélectionner un fichier PNG ou JPG</a>";
            }
        }

        // Mise à jour des autres champs
        if (!empty($newName)) {
            if (strlen($newName) <= 25) {
                $updateValues[] = $newName;
                $updateTypes .= "s";
                $updateQuery .= ($updateQuery == "UPDATE user SET ") ? "name=? " : ", name=? ";
                $shouldRedirect = true;
            } else {
                echo "<a class=\"error-message\">name too long (25 max)</a>";
            }
        }

        if (!empty($newInstagram)) {
            $updateValues[] = $newInstagram;
            $updateTypes .= "s";
            $updateQuery .= ($updateQuery == "UPDATE user SET ") ? "instagram=? " : ", instagram=? ";
            $shouldRedirect = true;
        } else {
            $updateQuery .= ($updateQuery == "UPDATE user SET ") ? "instagram=NULL " : ", instagram=NULL ";
        }

        if (!empty($newYoutube)) {
            $updateValues[] = $newYoutube;
            $updateTypes .= "s";
            $updateQuery .= ($updateQuery == "UPDATE user SET ") ? "youtube=? " : ", youtube=? ";
            $shouldRedirect = true;
        } else {
            $updateQuery .= ($updateQuery == "UPDATE user SET ") ? "youtube=NULL " : ", youtube=NULL ";
        }

        if (!empty($updateValues)) {
            $updateQuery .= "WHERE username=?";
            $updateValues[] = $username;
            $updateTypes .= "s";

            $stmt = mysqli_prepare($idcom, $updateQuery);
            mysqli_stmt_bind_param($stmt, $updateTypes, ...$updateValues);
            mysqli_stmt_execute($stmt);

            if ($username == $_SESSION["username"]) {
                $_SESSION["name"] = $newName;
            }

            if ($shouldRedirect) {
                header("Location: profile.php?username=" . htmlspecialchars($username));
                exit();
            }
        }
    }
    ?>
    <html>
    <head>
        <title>sya</title>
        <link rel="icon" href="../pics/favicon.png"/>
        <link rel="stylesheet" href="../style/style.css">
        <link rel="stylesheet" href="../style/styleForm.css">
        <link rel="stylesheet" href="../style/styleMenu.css">
        <script src="../js/hamburger.js"></script>
        <script src="../js/apparition.js"></script>
        <meta charset="utf-8"/>
    </head>
    <body>
    <div id="menuToggle">
        <input type="checkbox"/>
        <span></span>
        <span></span>
    </div>
    <div id="menu">
        <a class="titleSecond" href="../">menu.</a>
        <p class="noMargin"> retourner au menu </p>
        <?php
        if (isset($_SESSION["username"]) && $_SESSION["username"] != "") {
            ?>
            <a class="titleSecond" href="../connect/profile.php?username=<?php echo $_SESSION["username"]; ?>">mon profil.</a>
            <p class="noMargin"> connecté en tant que <?php echo $_SESSION["name"]; ?> </p>
            <?php
        } else {
            ?>
            <a class="titleSecond" href="../connect/login.php">mon profil.</a>
            <p class="noMargin"> me connecter </p>
            <?php
        }
        ?>
        <a class="titleSecond" href="../add/send.php">ajouter.</a>
        <p class="noMargin"> envoyer votre illustration </p>
        <a class="titleSecond" href="../browse/verified.php">parcourir.</a>
        <p class="noMargin"> explorer le meilleur de SYA </p>
        <a class="titleSecond" href="../soutenir/soutiens.php">soutenir.</a>
        <p class="noMargin"> obtenir les dernières fonctionnalités </p>
        <?php
        if (isset($_SESSION["role"]) && $_SESSION["role"]==2 || $_SESSION["role"]==3) {
            ?>
            <a class="titleSecond" href="../soutenir/soutiens.php">unmoderate.</a>
            <p class="noMargin"> les illustrations non modérées </p>
            <a class="titleSecond" href="../moderate/user.php">modération.</a>
            <p class="noMargin">gestion des utilisateurs </p>
            <?php
        }
        ?>
    </div>
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

    <div class="bigMarginLeft">
        <a class="buttonBorderW" href="profile.php?username=<?php echo $username ?>">annuler</a>
    </div>
    <div class="centered marginBottom">
        <div class="formulaire">
            <a class="title left lilMarginTop">Modifier.</a>
            <a class="rightTitle">uniquement les champs que vous souhaitez</a>
            <form action="modifier.php?username=<?php echo htmlspecialchars($username); ?>" method="post"
                  enctype="multipart/form-data">
                <div class="form-group lilMarginTop">
                    <p class="marginV textSection">Nom</p>
                    <input class="input" type="text" name="name" id="name"
                           value="<?php echo htmlspecialchars($name); ?>">
                </div>
                <?php
                if ($soutiens >= 2) {
                    ?>
                    <div class="form-group lilMarginTop">
                        <p class="marginV textSection">Instagram</p>
                        <input class="input" type="text" name="instagram" id="instagram"
                               value="<?php echo htmlspecialchars($insta); ?>">
                    </div>
                    <div class="form-group lilMarginTop">
                        <p class="marginV textSection">YouTube (avec @)</p>
                        <input class="input" type="text" name="youtube" id="youtube"
                               value="<?php echo htmlspecialchars($youtube); ?>">
                    </div>
                    <?php
                }
                if ($soutiens >= 5) {
                    ?>
                    <p class="marginV textSection">Photo de profile</p>
                    <input type="file" name="profilePic" class="subtitle white"></br>
                    <?php
                }
                ?>
                <div class="form-group">
                    <div class="centered lilMarginTop">
                        <button class="submitForm text" type="submit" name="submit">Mettre à jour</button>
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