<?php
session_start();
include("../db/connex.inc.php");
$idcom = connex("myparam");
$requestmonth = "SELECT * from month WHERE month=0";
$resultmonth = @mysqli_query($idcom, $requestmonth);
while ($rowmonth = mysqli_fetch_assoc($resultmonth)) {
    $month=$rowmonth["month"];
}
if(isset($_POST["submit"])) {
    $username = $_POST["username"];
    $soutiens = $_POST["soutiens"];

    $stmt = mysqli_prepare($idcom, "UPDATE user SET soutiens=? WHERE username=?");
    mysqli_stmt_bind_param($stmt, "is", $soutiens, $username);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: ./soutiens.php");
        exit();
    } else {
        echo "Erreur lors de la mise à jour de l'utilisateur.";
    }
}
?>
<html>
<head>
    <title>soutiens.</title>
    <meta charset="utf-8"/>
    <meta name="description" content="Partie vérifiée de SYA, trouvez vos prochaines illustrations préférés."/>
    <link rel="icon" href="../pics/favicon.png"/>
    <link rel="stylesheet" href="../style/style.css">
</head>
<body>

<nav>
    <a href="../add/send.php" class="nav-item">ajouter.</a>
    <a href="../" class="nav-item">menu.</a>
    <a href="./unmoderate.php" class="nav-item">unmoderate.</a>
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
<div class="centered lilMarginTop marginBottom">
    <a class="donateButton" href="https://www.paypal.com/donate/?hosted_button_id=8XFJ7GKL2Y53W">faire un don</a>
</div>
<div class="left lilMarginTop">
    <a class="title">nos contributeurs.</a>
</div>
<div class="left lilMarginTop">
    <?php
    $request = "SELECT * from user WHERE soutiens>=1";
    $result = @mysqli_query($idcom, $request);
    while ($rowSearch = mysqli_fetch_assoc($result)) {
        $username = $rowSearch["username"];
        $name = $rowSearch["name"];
        echo "<a class=\"buttonWB text\" href=\"../connect/profile.php?username=$username\">$name</a>";
    }
    ?>
</div>
<?php
    if ($_SESSION["role"]==3){
        ?>
            <div class="left lilMarginTop">
                <a class="white title">ajouter.</a>
            </div>
        <div class="left lilMarginTop">
            <form action="soutiens.php" method="post">
                <div class="form-group lilMarginTop white">
                    <label for="username">Nom d'utilisateur :</label>
                    <input type="text" name="username" id="username" required>
                </div>
                <div class="form-group lilMarginTop white">
                    <label for="soutiens">soutiens :</label>
                    <input type="number" name="soutiens" id="soutiens" required>
                </div>
                <div class="form-group lilMarginTop">
                    <button class="buttonWB text" type="submit" name="submit">Mettre à jour</button>
                </div>
            </form>
        </div>
<?php
    }
?>
<div class="left marginTop">
    <a class="title">objectifs.</a>
</div>
<div class="left lilMarginTop">
    <a class="rightTitle">afficher vos illustrations dans la rue</a>
</div>
<div class="left lilMarginTop">
    <a class="rightTitle">financer une application mobile</a>
</div>
<div class="left lilMarginTop">
    <a class="rightTitle">financer un serveur dédié à sya pour être indépendant et pouvois améliorer les technologies du site</a>
</div>
<div class="left lilMarginTop">
    <a class="rightTitle">création de concepts sous forme de vidéos</a>
</div>
<div class="left marginTop">
    <a class="title">récompenses.</a>
    <a class="rightTitle">à partir de 2€</a>
</div>
<div class="left lilMarginTop">
    <a class="rightTitle">la possibilité d'ajouter vos réseaux sur votre profil</a>
</div>
<div class="left lilMarginTop">
    <a class="rightTitle">ce joli petit coeur qui accompagnera votre nom comme ceci :</a>
</div>
<div class="centered lilMarginTop marginBottom">
    <a class="buttonWB text">
        Mavrick
        <span class="mini"></span>
    </a>
</div>
<div class="lilMarginTop">
    <a class="centered subtitle">merci. je vous aimes</a>
</div>
</body>
</html>