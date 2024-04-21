<?php
session_start();

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

if (isset($_SESSION["username"])) {
    $type = $_GET["type"];
    if (!$type) $type = "illustration";
    $username = $_SESSION["username"];
    include("../db/connex.inc.php");
    $idcom = connex("myparam");

    $query = "SELECT post FROM user WHERE username='$username'";
    $result = mysqli_query($idcom, $query);
    $row = mysqli_fetch_assoc($result);
    $currentPostCount = $row['post'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ($currentPostCount < 30) {
            if (!isset($_POST["accept_terms"])) {
                $error_message = "<a class='subtitle white centered'>Vous ne pouvez pas envoyer d'illustration sans accepter les conditions</a>";
            } else {
                if (!empty($_FILES["fileIllustration"]["name"])) {
                    $targetDirCover = "../db/illustration/";
                    if (!is_dir($targetDirCover)) {
                        echo "<a class='subtitle white centered'>Le dossier de destination n'existe pas.</a>";
                        exit();
                    }

                    $filePicTmp = $_FILES["fileIllustration"]["name"];
                    $filePic = str_replace(' ', '', $filePicTmp);
                    $filePic = checkAndRenameFile($targetDirCover, $filePic);
                    $targetFilePath = $targetDirCover . $filePic;
                    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
                    $allowTypes = array('jpg', 'png', 'jpeg');

                    if (empty($_POST["name"])) {
                        $error_message = "<a class='subtitle white centered'>Le nom ne peut pas être vide</a>";
                    } else {
                        if (empty($_POST["description"])) {
                            $error_message = "<a class='subtitle white centered'>La description ne peut pas être vide</a>";
                        } else {
                            if (in_array($fileType, $allowTypes)) {
                                if (move_uploaded_file($_FILES["fileIllustration"]["tmp_name"], $targetFilePath)) {
                                    $user = $_SESSION["username"];
                                    $name = $_POST["name"];
                                    $description = $_POST["description"];

                                    $stmt = mysqli_prepare($idcom, "INSERT INTO illustration (pic, name, description, date, username) VALUES (?, ?, ?, NOW(), ?)");
                                    mysqli_stmt_bind_param($stmt, "ssss", $filePic, $name, $description, $user);

                                    if (mysqli_stmt_execute($stmt)) {
                                        $request2 = "UPDATE user SET post=post+1 WHERE username='$user'";
                                        mysqli_query($idcom, $request2);

                                        header('Location: ../');
                                        exit();
                                    } else {
                                        $error_message = "<a class='subtitle white centered'>Erreur lors de l'exécution de la requête</a>";
                                    }
                                    mysqli_stmt_close($stmt);
                                }
                            } else {
                                $error_message = "<a class='subtitle white centered'>Veuillez sélectionner un fichier PNG ou JPG</a>";
                            }
                        }
                    }
                } else {
                    $error_message = "<a class='subtitle white centered'>Veuillez sélectionner un fichier d'illustration</a>";
                }
            }
        } else {
            $error_message = "<a class='subtitle white centered'>Désolé, mais vous ne pouvez pas publier plus de 30 illustrations. Veuillez en supprimer ou nous contacter.</a>";
        }
    }
    ?>
    <html>
    <head>
        <title>ajouter.</title>
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
            <a class="titleSecond" href="../moderate/user.php">modération.</a>
            <p class="noMargin">gestion des utilisateurs </p>
            <?php
        }
        ?>
    </div>
    <div class="centered marginBottom">
        <div class="formulaire">
            <p class="title">Ajouter.</p></br>
            <p class="subtitle white">* tous les champs sont nécessaires</p>
            <form action="send.php" method="post" enctype="multipart/form-data">
                <p class="marginV textSection">Nom</p>
                <input type="text" name="name" minlength="2" maxlength="25" class="input">
                <p class="marginV textSection">Description</p>
                <textarea name="description" minlength="2" maxlength="250" class="input textArea"></textarea>
                <p class="marginV textSection">illustration :</p>
                <input type="file" name="fileIllustration" class="subtitle white"></br>
                <div class="marginV"><input type="checkbox" name="accept_terms"> <a class="subtitle white">J'accepte que
                        tout le
                        monde puisse utiliser mon illustration à des fins personnelles.</a></div>
                <div class="form-group lilMarginTop centered marginBottom">
                    <button class="submitForm text" type="submit" name="submitIllustration">envoyer.</button>
                </div>
            </form>
            <?php
            if (isset($error_message)) {
                echo $error_message;
            }
            ?>
        </div>
    </div>

    </body>
    </html>
    <?php
} else {
    header('Location: ../connect/login.php');
    exit();
}
?>
