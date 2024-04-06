<?php
session_start();

function checkAndRenameFile($targetDir, $fileName) {
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
    $type=$_GET["type"];
    if (!$type) $type="illustration";
    $username=$_SESSION["username"];
    include("../db/connex.inc.php");
    $idcom = connex("myparam");

    $query = "SELECT post FROM user WHERE username='$username'";
    $result = mysqli_query($idcom, $query);
    $row = mysqli_fetch_assoc($result);
    $currentPostCount = $row['post'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ($currentPostCount < 30) {
            if (!isset($_POST["accept_terms"])) {
                $error_message = "<a class='text white centered'>Désolé, mais vous ne pouvez pas envoyer quelque chose sans accepter les conditions</a>";
            } else {
                if (!empty($_FILES["fileIllustration"]["name"])) {
                    $targetDirCover = "../db/illustration/";
                    if (!is_dir($targetDirCover)) {
                        echo "<a class='text white centered'>Le dossier de destination n'existe pas.</a>";
                        exit();
                    }

                    $filePicTmp = $_FILES["fileIllustration"]["name"];
                    $filePic = str_replace(' ', '', $filePicTmp);
                    $filePic = checkAndRenameFile($targetDirCover, $filePic);
                    $targetFilePath = $targetDirCover . $filePic;
                    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
                    $allowTypes = array('jpg', 'png', 'jpeg');

                    if (empty($_POST["name"])) {
                        $error_message = "<a class='text white centered'>Le nom ne peut pas être vide</a>";
                    } else {
                        if (in_array($fileType, $allowTypes)) {
                            if (move_uploaded_file($_FILES["fileIllustration"]["tmp_name"], $targetFilePath)) {
                                $user = $_SESSION["username"];
                                $name = $_POST["name"];

                                $stmt = mysqli_prepare($idcom, "INSERT INTO illustration (pic, name, date, username) VALUES (?, ?, NOW(), ?)");
                                mysqli_stmt_bind_param($stmt, "sss", $filePic, $name, $user);

                                if (mysqli_stmt_execute($stmt)) {
                                    $request2 = "UPDATE user SET post=post+1 WHERE username='$user'";
                                    mysqli_query($idcom, $request2);

                                    header('Location: ../');
                                    exit();
                                } else {
                                    $error_message = "<a class='text white centered'>Erreur lors de l'exécution de la requête</a>";
                                }
                                mysqli_stmt_close($stmt);
                            }
                        } else {
                            $error_message = "<a class='text white centered'>Veuillez sélectionner un fichier PNG ou JPG</a>";
                        }
                    }
                } else {
                    $error_message = "<a class='text white centered'>Veuillez sélectionner un fichier d'illustration</a>";
                }
            }
        } else {
            $error_message = "<a class='text white centered'>Désolé, mais vous ne pouvez pas publier plus de 30 illustrations. Veuillez en supprimer ou nous contacter.</a>";
        }
    }
    ?>
    <html>
    <head>
        <title>ajouter.</title>
        <link rel="icon" href="../pics/favicon.png"/>
        <link rel="stylesheet" href="../style/style.css">
        <meta charset="utf-8"/>
    </head>
    <body>
    <nav>
        <a href="../" class="nav-item">menu.</a>
        <a href="../browse/verified.php" class="nav-item">parcourir.</a>
        <?php
        if (isset($_SESSION["username"]) && $_SESSION["username"]!=""){
            ?>
            <a class="nav-item" href="../connect/profile.php?username=<?php echo $_SESSION["username"]; ?>">mon compte.</a>
            <?php
        }
        ?>
    </nav>
    <div class="marginTop">
        <a class="title left">Ajouter.</a>
        <a class="rightTitle">veuillez remplir tous les champs suivants (modifiable par la suite)</a>
    </div>
    <div class="centered">
        <div class="blue"></div>
    </div>
    <div class="centered">
        <div class="blue2"></div>
    </div>
    <div class="centered">
        <div class="blue3"></div>
    </div>
    <div class="left lilMarginTop">
    </div>
    <div class="centered lilMarginTop">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group lilMarginTop white">
                <label for="fileIllustration">illustration :</label>
                <input type="file" name="fileIllustration" id="fileIllustration">
            </div>
            <div class="form-group lilMarginTop">
                <input type="text" name="name" minlength="2" maxlength="25" placeholder="name" class="search">
            </div>
            <label>
                <div class="lilMarginTop">
                    <input type="checkbox" name="accept_terms"> <a class="rightTitle white">J'accepte que tout le monde puisse utiliser mon illustration à des fins personnelles.</a>
                </div>
            </label>
            <div class="form-group lilMarginTop centered marginBottom">
                <button class="buttonWB text" type="submit" name="submitIllustration">envoyer.</button>
            </div>
        </form>
    </div>
    <?php
    if (isset($error_message)) {
        echo $error_message;
    }
    ?>
    </body>
    </html>
    <?php
} else {
    header('Location: ../connect/login.php');
    exit();
}
?>
