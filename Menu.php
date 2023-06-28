<?php include "connexion.php";?>
<ul class="menu">
    <li class="logo">
        <a href="#" id="logoLink"><img src="imgs/LogoF.png" alt="Logo" width="50"></a>
    </li>
    <li><a href="index.php">Home</a></li>
    <?php 
    if(isset($_SESSION['email'])){
        $stm2 = $cnx->prepare("select profileImg from utilisateur where email = :e and password = :p ");
        $stm2->bindParam(":e",$_SESSION['email']);
        $stm2->bindParam(":p",$_SESSION['password']);
        $stm2->execute();
        $result2 = $stm2->fetch(PDO::FETCH_ASSOC);
        echo "<li><a href='annonce_create.php'>New Announcement</a></li>";
        echo 
        "<li class='profil'>
            <a href='profil.php'><img src='imgs/".$result2['profileImg']."' alt=''></a>
            <ul class='dropdown'>
                <li><a href='login.php'>New</a></li>
                <li><a href='inscription.php'>Edit</a></li>
            </ul>
        </li>";
        echo "<li class='historique'><a href='historique.php'>Historique</a></li>"; 
        
    }else{
        echo 
        "<li class='profil'>
            <a href=''><img src='imgs/anonym.png' alt=''></a>
            <ul class='dropdown'>
                <li><a href='login.php'>Sign In</a></li>
                <li><a href='inscription.php'>Sign Up</a></li>
            </ul>
        </li>";
    }
    ?>
</ul>
