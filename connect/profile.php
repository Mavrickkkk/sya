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
    } else {
        echo "SQL Error";
        exit();
    }
$random = random_int(1, 7);
$type=$_GET["type"];
if (!$type) $type="illustration";
?>
<html>
    <head>
        <title>sya</title>
        <link rel="icon" href="../pics/favicon.png"/>
        <link rel="stylesheet" href="../style/styleProfile.css">
        <meta charset="utf-8"/>
    </head>
    <body>
    <div class="sticky-bar">
        <div class="bar-content">
            <a class="nameBar"><?php echo $name ;?></a>
            <ul class="bar-links">
                <li><a href="../index.php" class="links">home</a></li>
                <li><a href="../browse/verified.php" class="links">browse</a></li>
                <?php
              	if (isset($_SESSION["name"]))
                	echo "<li><a href=\"../add/send.php\" class=\"links\">add</a></li>";
                ?>
                <?php
                if (!isset($_SESSION["username"])){
                    ?>
                    <li><a href="../connect/login.php" class="links">connect</a></li>
                    <?php
                }else {
                    if ($_SESSION["username"]==$username)
                        echo "<li><a class=\"links\" href=\"../connect/disconnect.php?username=$username\">disconnect</a></li>";
                    else {
                    ?>
                    <li><a class="links" href="../connect/profile.php?username=<?php echo $_SESSION["username"]; ?>"><?php echo $_SESSION["name"]; ?></a></li>
                    <?php
                    }
                }
                ?>
            </ul>
        </div>
    </div>
    <?php
        $requestPresentationPic = "SELECT filePic FROM presentationpic WHERE username='$username' ORDER BY date DESC LIMIT 1";
        $resultPresentationPic = @mysqli_query($idcom, $requestPresentationPic);
        $hasPresentationPic = mysqli_num_rows($resultPresentationPic) > 0;

        if ($hasPresentationPic) {
            $rowPresentationPic = mysqli_fetch_assoc($resultPresentationPic);
            $presentationPic = "../db/presentationPic/" . $rowPresentationPic["filePic"];
            echo "<img src=\"$presentationPic\" class=\"presentationPic\">";
        } else {
                echo "<img src=\"../pics/esquisse2.jpg\" class=\"presentationPic\">";
        }
    ?>
    <?php
        if ($type=="illustration") {
    ?>
    <div class="centered">
        <a class="choiceText">-> ILLUSTRATIONS</a>
        <a class="non-choiceText" href="profile.php?username=<?php echo $username ?>&type=single">SINGLES</a>
    </div>
    <div class="singleContent">
        <?php
        $nbrelt=14;
        $page = isset($_GET["page"]) ? $_GET["page"] : 1;
        $gap = ($page-1) * $nbrelt;
        $request = "SELECT * from illustration WHERE username=\"$username\" LIMIT $gap, $nbrelt";
        $result = @mysqli_query($idcom, $request);
        while ($row = mysqli_fetch_assoc($result)) {
            $picFolder = "../db/illustration/";
            $filePic = $row["pic"];
            $id = $row["id"];
            $namePic = $row["name"];
            $username = $row["username"];
            echo "<div class=\"picContent\">";
            echo "<a href=\"../browse/illustration.php?id=$id\"><img src=" . $picFolder . $filePic . " class=\"pic\"></a>";
            echo "<a class=\"picName\">" . $namePic . "</a>";
            if ((isset($_SESSION["role"]) && $_SESSION["role"] == 3) || $username==$_SESSION["username"]) {
                echo "<div class\"xvtContent\">";
                echo "<a class=\"x\" href=\"../db/removeIllustration.php?id=" . $row["id"] . "\">remove</a>";
                echo "</div>";
            }
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
    echo "<div class=\"pageChoice\">";
    for ($i = 1; $i <= $totalPages; $i++) {
        echo "<a href='profile.php?username=" . htmlspecialchars($username) . "&page=$i'>$i</a> ";
    }
    echo "</div>";
    }
        if ($type=="single") {
    ?>
    <div class="centered">
        <a class="non-choiceText" href="profile.php?username=<?php echo $username ?>">ILLUSTRATIONS</a>
        <a class="choiceText">-> SINGLES</a>
    </div>
    <div class="singleContent">
    <?php
        $nbrelt=14;
        $page = isset($_GET["page"]) ? $_GET["page"] : 1;
        $gap = ($page-1) * $nbrelt;
        $request = "SELECT * from single WHERE username=\"$username\" LIMIT $gap, $nbrelt";
        $result = @mysqli_query($idcom, $request);
        while ($row = mysqli_fetch_assoc($result)) {
            $coverFolder = "../db/cover/";
            $fileCover = $row["fileCover"];
            $trackFolder = "../db/track/";
            $fileTrack = $row["fileTrack"];
            $id = $row["id"];
            $nameSingle = $row["name"];
            $username = $row["username"];
            echo "<div class=\"picContent\">";
            echo "<a href=\"../browse/single.php?id=$id\"><img src=" . $coverFolder . $fileCover . " class=\"pic\"></a>";
            echo "<a class=\"picName\">" . $nameSingle . "</a>";
            if ((isset($_SESSION["role"]) && $_SESSION["role"] == 3) || $username==$_SESSION["username"]) {
                echo "<a class=\"x\" href=\"../db/removeSingle.php?id=" . $row["id"] . "\">remove</a>";
            }
            echo "</div>";
        }
    ?>
    </div>
    <?php
        $countRequest = "SELECT COUNT(*) as total FROM single WHERE username=?";
        $stmt = mysqli_prepare($idcom, $countRequest);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $countResult = mysqli_stmt_get_result($stmt);
        $countRow = mysqli_fetch_assoc($countResult);
        $totalElements = $countRow['total'];
        $totalPages = ceil($totalElements / $nbrelt);
        echo "<div class=\"pageChoice\">";
        for ($i = 1; $i <= $totalPages; $i++) {
            echo "<a href='profile.php?username=" . htmlspecialchars($username) . "&page=$i'>$i</a> ";
        }
        echo "</div>";
        }
    ?>

    <?php
        if (isset($_SESSION["username"])){
            if ($_SESSION["username"]==$username || $_SESSION["role"]==3){
                ?>
        <div class="modify">
            <a class="impactText">custom</a>
        </div>
    <div class="changeInformation">
        <form action="profile.php?username=<?php echo $username; ?>" method="POST" enctype="multipart/form-data">
            <div class="searchBox">
                <a>before <?php echo $name?> now </a>
                <input class="search" type="text" name="name" placeholder="name">
            </div>
            <div class="changePic"><a class="text">pic presentation :</a>
            <input type="file" name="filepic"></div>

            <div class="submitButton"><input class="sendButton" type="submit" name="submit" value="send"></div>
        </form>

    </div>
    <?php
            }
        }
        if(isset($_POST["submit"])) {
            if (!empty($_FILES["filepic"]["name"])) {
                $targetDirCover = "../db/presentationPic/";
                $filePicTmp = $_FILES["filepic"]["name"];
                $filePic = str_replace(' ', '', $filePicTmp);
                $filePic = checkAndRenameFile($targetDirCover, $filePic);
                $targetFilePath = $targetDirCover . $filePic;
                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
                $allowTypes = array('jpg', 'png', 'jpeg');
                if (in_array($fileType, $allowTypes)) {

                    if (move_uploaded_file($_FILES["filepic"]["tmp_name"], $targetFilePath)) {
                        $requestExistingPic = "SELECT filePic FROM presentationpic WHERE username='$username' ORDER BY date DESC LIMIT 1";
                        $resultExistingPic = @mysqli_query($idcom, $requestExistingPic);

                        if (mysqli_num_rows($resultExistingPic) > 0) {
                            $rowExistingPic = mysqli_fetch_assoc($resultExistingPic);
                            $existingPicPath = "../db/presentationPic/" . $rowExistingPic["filePic"];

                            if (file_exists($existingPicPath)) {
                                unlink($existingPicPath);
                            }

                            $requestDeleteExistingPic = "DELETE FROM presentationpic WHERE username='$username'";
                            mysqli_query($idcom, $requestDeleteExistingPic);
                        }
                        $request = "INSERT into presentationpic (filePic, username, date) VALUES ('" . $filePic . "', '" . $username . "', NOW())";
                        if (mysqli_query($idcom, $request)) {
                            header("Location: profile.php?username=$username");
                            exit();
                        }
                    }
                }
            }
            if (!empty($_POST["name"])) {
                $newName = $_POST["name"];
                if (strlen($newName) <= 25) {
                    $request2 = "UPDATE user SET name=? WHERE username=?";
                    $stmt = mysqli_prepare($idcom, $request2);
                    mysqli_stmt_bind_param($stmt, "ss", $newName, $username);
                    mysqli_stmt_execute($stmt);

                    if ($username == $_SESSION["username"]) {
                        $_SESSION["name"] = $newName;
                    }

                    header("Location: profile.php?username=" . htmlspecialchars($username));
                    exit();
                } else {
                    echo "<a class=\"error-message\">name too long (25 max)</a>";
                }
            }
        }
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
    ?>
    <footer>
        <div class="footer-content">
            <a>contact (wip)</a>
            <?php
            if ($random==1)
                echo "<a>aka the best</a>";
            if ($random==2)
                echo "<a>don't forget my name</a>";
            if ($random==3)
                echo "<a>not an AI</a>";
            if ($random==4)
                echo "<a>earth genius</a>";
            if ($random==5)
                echo "<a>listen what I've done</a>";
            if ($random==6)
                echo "<a>what I told you</a>";
            if ($random==7)
                echo "<a>you'll remember me</a>";
            ?>
        </div>
    </footer>
    </body>
</html>