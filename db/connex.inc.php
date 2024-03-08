<?php
function connex($param)
{
    include($param.".inc.php");
    $idcom=mysqli_connect(MYHOST,MYUSER,MYPASS,"sya");
    if(!$idcom)
    {
        echo "<script type=text/javascript>";
        echo "alert('Connexion Impossible à la base  syaDB')</script>";
    }
    return $idcom;
}
?>