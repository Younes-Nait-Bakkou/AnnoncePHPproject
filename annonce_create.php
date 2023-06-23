<?php 
    session_start();
    if($_SERVER["REQUEST_METHOD"]==="POST"){
        
    }

    if(!isset($_SESSION["email"])){
        header("location:login.php");
        exit();
    }
    $cnx = new PDO("mysql:host=localhost;dbname=annonce","root","123123zzgg");
    $stm = $cnx->prepare("select id from utilisateur where email = :email and password = :pass");
    $stm->bindParam(":email",$_SESSION["email"]);
    $stm->bindParam(":pass",$_SESSION["password"]);
    $stm->execute();
    $result= $stm->fetch(PDO::FETCH_ASSOC);
    $id=$result["id"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="menu.css">
    <title>Ajouter</title>
</head>
<body>
    <?php include "Menu.php";?>
    <div class="container">
        <h1>Create Announcement</h1>
  
        <form action="submit_announcement.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="principalFile">Principal File (PNG, JPEG, JPG):</label>
                <input type="file" class="form-control-file" id="principalFile" name="principalFile" accept=".png,.jpeg,.jpg" required>
            </div>
    
            <div class="form-group">
                <label for="resources">Resources (PNG, JPEG, JPG):</label>
                <input type="file" class="form-control-file" id="resources" name="resources[]" multiple accept=".png,.jpeg,.jpg"/ required>
            </div>
            
            <button type="submit" class="btn btn-primary">Create Announcement</button>
        </form>
    </div>
    <?php include "footer.php";?>
</body>
</html>