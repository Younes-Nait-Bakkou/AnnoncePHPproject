<?php include "connexion.php";?>
<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="menu.css">
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="script.js"></script>
    <title>Accueil</title>
</head>
<body>
    <?php include 'Menu.php'; ?>
    <div class="slider-container">
        <div class="slider">
            <div class="slide">
                <?php 
                $stm= $cnx->query("select * from annonce
                join utilisateur on annonce.idUtili = utilisateur.ID
                order by annonce.date desc limit 5;");
                $annonces = $stm->fetchAll(PDO::FETCH_ASSOC);
                foreach ($annonces as $annonce) { ?>
                <div class="annonce">
                    <img src="imgs/<?php echo $annonce["photoPrinpale"]?>" class="img-annonce" alt="">
                    <div class="annonce-detail">
                        <div class="title"> <?php echo $annonce["titre"]?> </div>
                        <div class="fullname"> <?php echo $annonce["first_name"].' '.$annonce["last_name"]?></div>
                        <div class="date"> <?php echo $annonce["date"]?> </div>
                        <div class="description"> <?php echo substr($annonce["description"], 0, 20)?> </div>
                    </div>
                </div>
                <?php } ?> 
            </div>
        </div>
    </div>

<!-- take the what he searched, use a loop on titles or description on all announces and use the in, if in is true -->
    <form action="" method="POST">
        <div class="search-section">
            <div class="search-container">
                <?php 
                if($_SERVER["REQUEST_METHOD"]===""){
                    $searchWords = $_POST["search"];
                    $stm= $cnx->prepare("select * from annonce where title like :s% or where description like :s%");
                    $stm->bindParam(":s",$searchWords);
                    $stm->execute();
                    $result = $stm->fetchAll(PDO::FETCH_ASSOC);
                }
                ?>
                <input type="text" class="form-control" name="search" id="search" placeholder="Search...">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
            </div>
        </div>
        <div class="categories">
            <ul>
                <?php 
                $stm3 = $cnx->query("select * from categorie;");    
                $categories= $stm3->fetchAll(PDO::FETCH_ASSOC);
                foreach($categories as $categorie){
                    $stm4 = $cnx->query("select * from souscategorie where idC =".$categorie["idC"].";");
                    $souscategories = $stm4->fetchAll(PDO::FETCH_ASSOC);
                    echo "<li><input type='checkbox' name='C".$categorie["idC"]."' id='cat-".$categorie["idC"]."'><label for='cat-".$categorie["idC"]."'>".$categorie["nom"]."</label>";
                    echo "<ul>";
                    foreach($souscategories as $Scategorie){
                        echo "<li><input type='checkbox' name='S".$Scategorie["idSC"]."' id='subcat-".$Scategorie["idSC"]."'><label for='subcat-".$Scategorie["idSC"]."'>".$Scategorie["nom"]."</label></li>";////
                    }
                    echo "</ul>";   
                    echo "</li>";
                }
                ?>
            </ul>
        </div>
        <div class="pagination-section">
            <div class="pagination-container">
                <div class="recent-annonces">
                    <?php
                    $strcat = "(";
                    $strsub = "(";
                    if($_SERVER["REQUEST_METHOD"]==="POST"){
                        $_GET['page']=1;
                        $_GET['filter']="";
                        $stm = $cnx->query("select count(*) from categorie;");
                        $catNum= $stm->fetchColumn();
                        $stm = $cnx->query("select count(*) from souscategorie;");
                        $subNum= $stm->fetchColumn();
                        $catIDs = array();
                        $subIDs = array();
                        for($i = 1;$i <= $catNum; $i++){
                            if(isset($_POST["C$i"]))
                                $catIDs[] = $i;  
                        }
                        for($i = 1;$i <= $subNum; $i++){
                            if(isset($_POST["S$i"]))
                                $subIDs[] = $i;
                        }
                        foreach($catIDs as $cat){
                            if($cat == $catIDs[count($catIDs)-1])
                                $strcat .= $cat.")"; 
                            else
                                $strcat .= $cat.",";
                        }
    
                        foreach($subIDs as $sub){
                            if($sub == $subIDs[count($subIDs)-1])
                                $strsub .= $sub.")"; 
                            else
                                $strsub .= $sub.",";
                        } 
                    }
                    $categoryQuery="";
                    if($strcat !== "(" && $strsub !== "("){
                        $categoryQuery="join souscategorie on annonce.idSc = souscategorie.idSc
                        join categorie on souscategorie.idC = categorie.idC
                        where souscategorie.idSC in ".$strsub." or categorie.idC in ".$strcat;
                    }elseif($strcat === "(" && $strsub !== "("){
                        $categoryQuery="join souscategorie on annonce.idSc = souscategorie.idSc
                        join categorie on souscategorie.idC = categorie.idC
                        where souscategorie.idSC in ".$strsub;
                    }elseif($strsub ==="(" && $strcat !== "("){
                        $categoryQuery = "join souscategorie on annonce.idSc = souscategorie.idSc
                        join categorie on souscategorie.idC = categorie.idC
                        where categorie.idC in ".$strcat."";
                    }
                    if(isset($_GET['filter']) && $_GET['filter']!=="")
                        $categoryQuery = $_GET['filter'];
                    echo $categoryQuery;
                    $itemsPerPage = 5; 
                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                    $offset = ($page - 1) * $itemsPerPage;
                    $stm = $cnx->prepare("select * from annonce
                    join utilisateur on annonce.idUtili = utilisateur.ID
                    ".$categoryQuery."
                    order by annonce.date desc
                    limit :offset, :itemsPerPage");
                    $stm->bindValue(':offset', $offset, PDO::PARAM_INT);
                    $stm->bindValue(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);
                    $stm->execute();
                    $annonces = $stm->fetchAll(PDO::FETCH_ASSOC);
                    
                    foreach ($annonces as $annonce) {
                        echo
                        '<div class="pagi-annonce">
                            <img src="imgs/'.$annonce["photoPrinpale"].'" class="pagi-annonceImg" alt="">
                            <div class="pagi-annonceDetail">
                                <div class="title> '.$annonce["titre"].'</div>
                                <div class="fullname">'.$annonce["first_name"].' '.$annonce["last_name"].'</div>
                                <div class="date">'.$annonce["date"].'</div>
                                <div class="description">'.$annonce["description"].'</div>
                            </div>
                        </div>';
                    }
                    ?>
                </div>
                <div class="pagination">
                <?php

                    $totalItemsStm = $cnx->query("select count(*) from annonce
                    join utilisateur on annonce.idUtili = utilisateur.ID
                    ".$categoryQuery);
                    $totalItems = $totalItemsStm->fetchColumn();    
                    $totalPages = ceil($totalItems / $itemsPerPage);
                    echo '<div class="pagination">';
                    for ($i = 1; $i <= $totalPages; $i++) {
                        $isActive = ($i == $page) ? 'active' : '';
                        echo '<a href="?page=' . $i . '&filter='.$categoryQuery.'" class="' . $isActive . '">' . $i . '</a>';
                    }
                    echo '</div>';
                    ?>
                </div>
            </div>
        </div>    
    </form>
    <?php include 'footer.php'; ?>
</body>
</html>