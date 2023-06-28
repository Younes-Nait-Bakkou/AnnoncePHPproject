<?php include "connexion.php";?>
<?php
session_start();
if(isset($_SESSION['email'])){
    header("location:index.php");
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="menu.css">
    <title>Document</title>
</head>
<body>
    <?php include 'Menu.php'; ?>
    <form action="inscription.php" method="post">
        <label for="firstName">First Name</label>
        <input type="text" name="firstName" id="firstName"><br>
        <?php

        $flag = TRUE;
        if($_SERVER["REQUEST_METHOD"]==="POST"){
            /*$stm = $cnx->prepare("select count(*) as number from utilisateur where first_name = :frst");
            $stm->bindParam(":frst",$_POST["firstName"]);
            $stm->execute();
            $frst = $stm->fetchAll(PDO::FETCH_ASSOC); 
            foreach($frst as $first){
                if($first['number'] === 0){
                    $insert = $cnx->prepare("insert into utilisateur values(:p1)");
                    $insert->bindParam(":p1",$_POST["firstName"]);
                    $insert->execute();
                }elseif (empty($_POST["firstName"])) {
                    echo "<div style='Please enter a first name'>"
                }
                
            };*/
           
            if(empty($_POST["firstName"])){
                echo "<div style='color:red;'> Please enter a first name </div>";
                $flag = FALSE;
            };
             
        };
        
        
        ?>
        <label for="lastName">Last Name</label>
        <input type="text" name="lastName" id="lastName"><br>
        <?php
        if($_SERVER["REQUEST_METHOD"]==="POST"){
            if(empty($_POST["lastName"])){
                echo "<div style='color:red;'> Please enter a last name </div>";
                $flag = FALSE;
            };
        };
        ?>
        <label for="email">Email</label>
        <input type="email" name="email" id="email"><br>
        <?php 
        if($_SERVER["REQUEST_METHOD"]==="POST"){
            $cnx = new PDO("mysql:host=localhost;dbname=annonce","root","123123zzgg");
            $stm = $cnx->prepare("select count(*) as number from utilisateur where email = :email");
            $stm->bindParam(":email",$_POST["email"]);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if(empty($_POST["email"])){
                    echo "<div style='color:red;'> Please enter an email </div>";
                    $flag = FALSE;
            }elseif($result['number'] > 0){
                    echo "<div style='color:red;'> Email already registered, please use a different email </div>";
                    $flag = FALSE;
                    
            };
           
            
        };
        ?>
        <label for="date">Birth date</label>
        <input type="date" name="date" id="date"><br>
        <?php 
        if($_SERVER["REQUEST_METHOD"]==="POST"){
            if(empty($_POST["date"])){
                echo "<div style='color:red;'> Please enter a date </div>";
                $flag = FALSE;
            };
            
        };
        ?>
        <label for="telephone">Telephone</label>
        <input type="tele" name="tele" id="tele"><br>
        <?php 
        if($_SERVER["REQUEST_METHOD"]==="POST"){
            $cnx = new PDO("mysql:host=localhost;dbname=annonce","root","123123zzgg");
            $stm = $cnx->prepare("select count(*) as number from utilisateur where tele = :tele");
            $stm->bindParam(":tele",$_POST["tele"]);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if(empty($_POST["tele"])){
                echo "<div style='color:red;'> Please enter a tele </div>";
                $flag = FALSE;
            }elseif($result['number'] > 0){
                echo "<div style='color:red;'> Please use a different phone number </div>";
                $flag = FALSE;
            };
            
        };
        ?>
        <label for="password">Password</label>
        <input type="password" name="password" id="password"><br>
        <?php 
        if($_SERVER["REQUEST_METHOD"]==="POST"){
            if(empty($_POST["password"])){
                echo "<div style='color:red;'> Please enter a password </div>";
                $flag = FALSE;
            };
            
        };
        ?>
        <label for="confPassword">Password Confirmation</label>
        <input type="password" name="confPassword" id="confPassword">
        <?php 
        if($_SERVER["REQUEST_METHOD"]==="POST"){
            if($_POST["password"] !== $_POST["confPassword"]){
                echo "<div style='color:red;'> Passwords do not match </div>";
                $flag = FALSE;
            }elseif(empty($_POST["confPassword"])){
                echo "<div style='color:red;'> Please enter the confirmation of the password </div>";
                $flag = FALSE;
            };

            if($flag){
                $_SESSION["email"] = $_POST["email"];
                $_SESSION["password"] = $_POST["password"];
                $cnx = new PDO("mysql:host=localhost;dbname=annonce","root","123123zzgg");
                $stm = $cnx->prepare("insert into utilisateur(first_name,last_name,email,date,tele,password) values(:frst,:last,:email,:date,:tele,:password)");
                $stm->bindParam(":frst",$_POST["firstName"]);
                $stm->bindParam(":last",$_POST["lastName"]);
                $stm->bindParam(":email",$_POST["email"]);
                $stm->bindParam(":date",$_POST["date"]);
                $stm->bindParam(":tele",$_POST["tele"]);
                $stm->bindParam(":password",$_POST["password"]);
                $stm->execute();
                header("location:accueil.php");
                exit();
            };
        };

        ?>
        <input type="submit" value="Register">
    </form>
    <?php include 'footer.php'; ?>
</body>
</html>