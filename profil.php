<?php 
session_start();
if (!isset($_SESSION["email"]))
    header("location:login.php");
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
    <?php
        $cnx = new PDO("mysql:host=localhost;dbname=annonce","root","123123zzgg");    ?>
    <div class="container">
        <div class="leftcontainer">
            <img src="" alt="">
            <p class="Name"></p>
            <p>Born in</p>
            <p>Numero</p>
        </div>
        
        <div class="rightcontainer">
            <label for="first">First Name</label><br>
            <input type="text" name="first" id="first">
            <label for="last">Last Name</label><br>
            <input type="text" name="last" id="last">
            <label for="birth">Birth date</label><br>
            <input type="date" name="birth " id="birth">
            <label for="tele">Telephone number</label><br>
            <input type="number" name="tele" id="tele">
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>