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

    if (isset($_POST["submitSingle"])) {
        if ($currentPostCount < 15) {
            if (!isset($_POST["accept_terms"])) {
                $error_message = "<a class='error-message'>sorry but you can't send something without agreeing terms</a>";
            } else {
                if (!empty($_FILES["filepic"]["name"])) {
                    $targetDirCover = "../db/cover/";
                    $fileCoverTmp = $_FILES["filepic"]["name"];
                    $fileCover = str_replace(' ', '', $fileCoverTmp);
                    $fileCover = checkAndRenameFile($targetDirCover, $fileCover);
                    $targetFilePath = $targetDirCover . $fileCover;
                    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
                    $allowTypes = array('jpg', 'png');

                    if (empty($_POST["name"])) {
                        $error_message = "<a class='error-message'>'name' can't be empty</a>";
                    } else {
                        if (in_array($fileType, $allowTypes)) {
                            if (move_uploaded_file($_FILES["filepic"]["tmp_name"], $targetFilePath)) {
                                if (!empty($_FILES["filetrack"]["name"])) {
                                    $targetDirTrack = "../db/track/";
                                    $fileTrackTmp = $_FILES["filetrack"]["name"];
                                    $fileTrack = str_replace(' ', '', $fileTrackTmp);
                                    $fileTrack = checkAndRenameFile($targetDirTrack, $fileTrack);
                                    $targetFilePath2 = $targetDirTrack . $fileTrack;
                                    $fileType2 = pathinfo($targetFilePath2, PATHINFO_EXTENSION);
                                    $allowTypes2 = array('mp3');

                                    if (in_array($fileType2, $allowTypes2)) {
                                        if (move_uploaded_file($_FILES["filetrack"]["tmp_name"], $targetFilePath2)) {
                                            $user = $_SESSION["username"];
                                            $name = $_POST["name"];

                                            $stmt = mysqli_prepare($idcom, "INSERT INTO single (fileCover, fileTrack, name, date, username) VALUES (?, ?, ?, NOW(), ?)");
                                            mysqli_stmt_bind_param($stmt, "ssss", $fileCover, $fileTrack, $name, $user);

                                            if (mysqli_stmt_execute($stmt)) {
                                                $request2 = "UPDATE user SET post=post+1 WHERE username='$user'";
                                                mysqli_query($idcom, $request2);

                                                header('Location: ../index.php');
                                                exit();
                                            } else {
                                                $error_message = "<a class='error-message'>error executing the query</a>";
                                            }
                                            mysqli_stmt_close($stmt);
                                        }
                                    } else {
                                        $error_message = "<a class='error-message'>please select a mp3 file</a>";
                                    }
                                } else {
                                    $error_message = "<a class='error-message'>please select a track file</a>";
                                }
                            }
                        } else {
                            $error_message = "<a class='error-message'>please select a png or jpg file</a>";
                        }
                    }
                } else {
                    $error_message = "<a class='error-message'>please select cover file</a>";
                }
            }
        }else {
            $error_message = "<a class='error-message'>sorry, but you can't post more than 15 arts.</br>
                Please remove something or wait for the next month.</a>";
        }
    }
    if (isset($_POST["submitIllustration"])) {
        if ($currentPostCount < 15) {
            if (!isset($_POST["accept_terms"])) {
                $error_message = "<a class='error-message'>Sorry but you can't send something without agreeing to the terms</a>";
            } else {
                if (!empty($_FILES["fileIllustration"]["name"])) {
                    $targetDirCover = "../db/illustration/";
                    $filePicTmp = $_FILES["fileIllustration"]["name"];
                    $filePic = str_replace(' ', '', $filePicTmp);
                    $filePic = checkAndRenameFile($targetDirCover, $filePic);
                    $targetFilePath = $targetDirCover . $filePic;
                    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
                    $allowTypes = array('jpg', 'png', 'jpeg'); // Modifier ici pour les extensions autorisées pour les illustrations

                    if (empty($_POST["name"])) {
                        $error_message = "<a class='error-message'>Name can't be empty</a>";
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

                                    header('Location: ../index.php');
                                    exit();
                                } else {
                                    $error_message = "<a class='error-message'>Error executing the query</a>";
                                }
                                mysqli_stmt_close($stmt);
                            }
                        } else {
                            $error_message = "<a class='error-message'>Please select a PNG or JPG file</a>";
                        }
                    }
                } else {
                    $error_message = "<a class='error-message'>Please select an illustration file</a>";
                }
            }
        } else {
            $error_message = "<a class='error-message'>Sorry, but you can't post more than 15 arts. Please remove something or wait for the next month.</a>";
        }
    }
    ?>

    <html>
    <head>
        <title>sya</title>
        <link rel="icon" href="../pics/favicon.png"/>
        <link rel="stylesheet" href="../style/styleAdd.css">
        <meta charset="utf-8"/>
    </head>
    <body>
    <div class="sticky-bar">
        <div class="bar-content">
            <a href="../index.php"></a>
            <ul class="bar-links">
                <li><a href="../index.php" class="links">home</a></li>
                <li><a href="../about/about.php" class="links">about</a></li>
                <?php
                if (!isset($_SESSION["username"])) {
                    ?>
                    <li><a href="../connect/login.php" class="links">connect</a></li>
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
    <div class="logoContainer">
        <a href="../index.php"><img src="../pics/logo4.png" class="logo"></a>
    </div>
    <?php
        if ($type=="single") {
            ?>
    <div class="centered-container">
        <form action="send.php?type=single" method="post" enctype="multipart/form-data">
            <a class="titlechoose">->add a single</a></br>
            <a class="title" href="send.php?type=illustration">add an illustration</a></br>
            <a>cover :</a> <input type="file" name="filepic"></br>
            <a>track :</a> <input type="file" name="filetrack"></br>
            <input type="text" name="name" minlength="2" maxlength="25" placeholder="name" class="search"></br>
            <label>
                <input type="checkbox" name="accept_terms"> I accept that it's my single and that everyone can use it for their personal purpose
            </label></br>
            <div class="submitButton"><input class="sendButton" type="submit" name="submitSingle" value="send"></div>
        </form>
        <?php
        if (isset($error_message)) {
            echo $error_message;
        }
        ?>
    </div>
    <?php
        }
        else {
            if($type=="illustration"){
    ?>
    <div class="centered-container">
        <form action="send.php?type=illustration" method="post" enctype="multipart/form-data">
            <a class="title" href="send.php?type=single">add a single</a></br>
            <a class="titlechoose">->add an illustration</a></br>
            <a>illustration :</a> <input type="file" name="fileIllustration"></br>
            <input type="text" name="name" minlength="2" maxlength="25" placeholder="name" class="search"></br>
            <label>
                <input type="checkbox" name="accept_terms"> I accept that it's my illustration and that everyone can use it for their personal purpose
            </label></br>
            <div class="submitButton"><input class="sendButton" type="submit" name="submitIllustration" value="send"></div>
        </form>
        <?php
        if (isset($error_message)) {
            echo $error_message;
        }
        ?>
    </div>
    <?php
            }
            else echo "unknow type";
        }
    ?>
    </body>
    </html>
    <?php
} else {
    ?>
    <html>
    <body>
    <a class="error-message" href="../connect/login.php">you must be connected to add a single</a>
    </body>
    </html>
    <?php
}
?>
