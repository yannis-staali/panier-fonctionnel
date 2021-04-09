<?php
session_start();

  //RAJOUTER CALCUL DU PRIX
                    //RAJOUTER CALCUL DU STOCK

if(!isset($_GET["id"]))
{
    header('location: index.php');
}

if(isset($_GET["id"]))
{
    $id = $_GET["id"];

    $connexion = new PDO('mysql:host=localhost;dbname=panier;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    $query_one = $connexion->prepare("SELECT*FROM produits WHERE id= ?");
    $query_one->execute(array($id));
    $result = $query_one->fetch(PDO::FETCH_ASSOC);
    // return $resultat;
}
echo "<pre>";
var_dump($resultat);
echo "<pre>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
</head>
    <body>

        <div class="header">
            <a href="index.php">ACCUEIL</a>
            <a href="boutique.php">BOUTIQUE</a>
            <a href="panier.php">PANIER</a>
        </div>

        <div class="main">
            <div class="display_cart">
                <h1>Produit</h1>
                <p>DETAILS DU PRODUIT</p>
            </div>
        </div>

        <div class="display_one">
            <?php
               //pas besoin de foreach puisque le resultat de la requete est un tableau 
               //Si on fait un FetchALl a la place on pourra utiliser foreach puisque le resultat sera un tableau de tableau
                    echo "<div class='card'>";
                        echo "<p>".$result["nom"]."</p>";
                        echo "<p>".$result["prix"]."</p>";
                        echo "<p>".$result["categorie"]."</p>";
                        echo "<p>".$result["image"]."</p>";
                    echo "</div>";
                


            ?>

        </div>
        
    </body>
</html>
