<?php
session_start();
include("../db/connex.inc.php");

$idcom = connex("myparam");
$id = $_GET["id"];

if (isset($_POST["submit"])) {
    if (isset($_POST["comment"]) && !empty($_POST["comment"])) {
        $comment = $_POST["comment"];
        $username = $_SESSION["username"];
        $idIllustration = $id;

        $request = "INSERT INTO comment (comment, username, idIllustration, date) VALUES (?, ?, ?, NOW())";
        $stmt = mysqli_prepare($idcom, $request);

        // Liaison des valeurs aux placeholders et exécution de la requête
        mysqli_stmt_bind_param($stmt, "sss", $comment, $username, $idIllustration);
        if (mysqli_stmt_execute($stmt)) {
            header('Location: illustration.php?id=' . $id);
            exit();
        } else {
            echo "database error";
        }
    } else {
        echo "submit error";
    }
}
?>
<html>
<head>
    <title>sya</title>
    <link rel="icon" href="../pics/favicon.png"/>
    <link rel="stylesheet" href="../style/styleSingle.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta charset="utf-8"/>
</head>
<body>
<script>
    $(document).ready(function() {
        setTimeout(function() {
            $('.picv').css({
                width: '50px',
                top: '0',
                left: '50%',
                transform: 'translateX(-50%)',
                opacity: 1
            });
        }, 1000);
    });
</script>
<div class="singleContent">
    <?php
    $request = "SELECT * from illustration WHERE id=$id";
    $result = @mysqli_query($idcom, $request);
    while ($row = mysqli_fetch_assoc($result)) {
        $picFolder = "../db/illustration/";
        $filePic = $row["pic"];
        $nameIllustration = $row["name"];
        $username = $row["username"];
        $request2 = "SELECT name FROM user WHERE username='$username'";
        $result2 = @mysqli_query($idcom, $request2);
        while ($row2 = mysqli_fetch_assoc($result2)) {
            $name = $row2["name"];
            echo "<div class=\"picContent\">";
            echo "<a class=\"presents\" href=\"../connect/profile.php?username=$username\">$name presents</a>";
            echo "<img src=" . $picFolder . $filePic . " class=\"pic\">";
            echo "<a class=\"picName\">" . $nameIllustration . "</a>";
            echo "</div>";
        }
    }
    ?>
</div>
<div class="commentAdd">
    <a class="commentTitle" href="../">return home</a>
</div>
<div class="commentAdd">
    <?php if (isset($_SESSION["name"]) && $_SESSION["name"]!=""){?>
        <form action="illustration.php?id=<?php echo $id ?>" method="post">
            <a class="commentTitle">add a comment</a></br>
            <textarea class="commentarea" name="comment" placeholder="here"></textarea>
            <div class="submitButton"><input type="submit" name="submit" value="send" class="sendButton" minlength="3" maxlength="255"></div>
        </form>
    <?php } else echo "<a class=\"commentTitle\" href=\"../connect/login.php\">connect you to post a comment</a>" ?>
</div>
<div class="commentList">
    <a class="commentTitle">last comments (50 max)</a>
    <?php
    $request = "SELECT * from comment WHERE idIllustration=$id ORDER BY date DESC LIMIT 50";
    $result = @mysqli_query($idcom, $request);
    while ($row = mysqli_fetch_assoc($result)) {
        $username = $row["username"];
        $comment = $row["comment"];
        echo "<div class=\"fullComment\">";
        echo "<a class=\"commentSubtitle\" href=\"../connect/profile.php?username=$username\">from $username</a>";
        echo "<a class='comment'>$comment</a>";
        if ((isset($_SESSION["role"]) && $_SESSION["role"] == 3) || $_SESSION["username"]==$username) {
            echo "<a href=\"../db/removeComment2.php?id=" . $row["id"] . "\"><img src=\"../pics/x.png\" class=\"xv\"></a></br>";
        }
        echo "</div>";
    }
    ?>
</div>
</div>
</body>
</html>