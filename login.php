<?php include "connexion.php";?>
<?php
$msg = "";
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
    <title>Login</title>
</head>
<body>
    <?php include 'Menu.php'; ?>
    <form action="" method="post">
        
        <?php
        if($_SERVER["REQUEST_METHOD"]==="POST"){
            $cnx = new PDO("mysql:host=localhost;dbname=annonce","root","123123zzgg");
            $stm = $cnx->prepare("Select count(*) as number from utilisateur where email = :email and password = :pas ;");
            $stm->bindParam(":email",$_POST["emailLog"]);
            $stm->bindParam(":pas",$_POST["passwordLog"]);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if($result['number'] == 0){
                $msg = "Please register first by clicking on this page <a style='color:red' href='inscription.php'>Inscription</a>";
            }else {
                $_SESSION['email']= $_POST["emailLog"];
                $_SESSION['password']= $_POST["passwordLog"];
                header("location:index.php");
            }
        } 
        ?>
        <label for="emailLog">Email:</label>
        <input type="email" name="emailLog" id="emailLog">
        <label for="passwordLog">Password:</label>
        <input type="password" name="passwordLog" id="passwordLog">
        <?php echo "<br>".$msg; ?>
        <input type="submit" value="Sign In">
    </form>
    <?php include 'footer.php'; ?>
</body>
</html>