<?php
session_start();
include("../db/connex.inc.php");
$idcom = connex("myparam");

    ?>
    <html>
    <head>
        <title>sya</title>
        <link rel="icon" href="../pics/favicon.png"/>
        <link rel="stylesheet" href="../style/styleModerateUser.css">
        <meta charset="utf-8"/>
    </head>
    <body>
    <div class="sticky-bar">
        <div class="bar-content">
            <a href="../" class="links">sya</a>
            <ul class="bar-links">
                <li><a href="../browse/verified.php" class="links">browse</a></li>
                <li><a href="../about/about.php" class="links">about</a></li>
                <li><a href="../add/send.php?type=illustration" class="links">add</a></li>
                <?php
                if (!isset($_SESSION["username"])){
                    ?>
                    <li><a href="../connect/login.php" class="links">connect</a></li>
                    <?php
                }else {
                    ?>
                    <li><a class="links" href="../connect/profile.php?username=<?php echo htmlspecialchars($_SESSION["username"]); ?>"><?php echo htmlspecialchars($_SESSION["name"]); ?></a></li>
                    <?php
                }
                ?>
            </ul>
        </div>
    </div>
    <form action="user.php" method="POST">
        <div class="searchBox">
            <input class="search" type="text" name="name" placeholder="username">
        </div>
        <input class="sendButton" type="submit" value="send">
    </form>
      <?php 
      if (isset($_SESSION["role"]) && $_SESSION["role"]==2 || $_SESSION["role"]==3){
  if (isset($_POST["name"])) {
        $search = $_POST["name"];
        $requestSearch = "SELECT * from user WHERE username LIKE ?";
        $stmt = mysqli_prepare($idcom, $requestSearch);
        mysqli_stmt_bind_param($stmt, "s", $search);
        mysqli_stmt_execute($stmt);
        $resultSearch = mysqli_stmt_get_result($stmt);
    }
    else {
        $requestSearch = "SELECT * from user";
        $resultSearch = @mysqli_query($idcom, $requestSearch);
    }

    if ($resultSearch) {
        while ($rowSearch = mysqli_fetch_assoc($resultSearch)) {
            $username = htmlspecialchars($rowSearch["username"]);
            $role = $rowSearch["role"];
            echo "<a class=\"username\" href=\"../connect/profile.php?username=$username\">" . $username . "</a>";
            if ($role == 1)
                echo "<a class=\"username\"> (user)</a>";
            if ($role == 2)
                echo "<a class=\"username\"> (modo)</a>";
            if ($role == 3)
                echo "<a class=\"username\"> (owner)</a>";
            echo "<a href=\"../db/removeUser.php?username=" . $username . "\"><img src=\"../pics/x.png\" class=\"xv\"></a>";
            echo "<a href=\"../db/modoUser.php?username=" . $username . "\"><img src=\"../pics/v.png\" class=\"xv\"></a>";
            echo "<a href=\"../db/unmodoUser.php?username=" . $username . "\"><img src=\"../pics/unmodo.png\" class=\"xv\"></a></br>";
        }
    } else {
        echo "<p>Error executing the search query.</p>";
    }
      $requestVisitors = "SELECT visitors from month";
      $resultVisitors = @mysqli_query($idcom, $requestVisitors);
      while ($rowVisitor = mysqli_fetch_assoc($resultVisitors)) {
          echo $rowVisitor["visitors"];
      }
} else {
    echo "you can't be there";
}
      ?>
</body>
</html>
