<?php
session_start();

if(isset($_POST))
{
    echo "<pre>";
    var_dump($_POST);
    echo "<pre>";
}

if(isset($_SESSION["panier"]))
{
    $nbre_articles = count($_SESSION["panier"]);

    //A supprimer
    echo "<pre>";
    echo "yo";
    var_dump($nbre_articles);
    echo "</pre>";
}

if(isset($_POST["passage_achat"]))
{
    $nbre_articles = count($_POST)-1;

    for($i=0; $i<$nbre_articles; $i++)
    {
        // echo "<pre>";
        // echo $_POST["index$i"];
        // echo "<pre>";
        // echo $i;
        $update = $_POST["index$i"];

        $_SESSION["panier"][$i]["quantite"] = $_POST["index$i"];

    }

     //on refresh et on die sinon le code continue
     header("Refresh:0.1");
     die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recapitulatif</title>
</head>
    <body>

        <div class="header">
            <a href="index.php">ACCUEIL</a>
            <a href="boutique.php">BOUTIQUE</a>
            <a href="panier.php">PANIER</a>
        </div>

        <div class="main">
            <div class="display_cart">
                <h1>RECAPITULATIF</h1>
                <p>Ici recapitulatif avant passage à l'achat</p>
            </div>
        </div>

        <div class="container_final">
            <?php
                for($i=0; $i<$nbre_articles;$i++)
                {
                    $id_each = $_SESSION["panier"][$i]["id"];
                    $nom_each = $_SESSION["panier"][$i]["nom"];
                    $prix_each = $_SESSION["panier"][$i]["prix"];
                    $categorie_each = $_SESSION["panier"][$i]["categorie"];
                    $image_each = $_SESSION["panier"][$i]["image"];
                    $stock_each = $_SESSION["panier"][$i]["stock"];
                    (int)$quantite_each = $_SESSION["panier"][$i]["quantite"];

                    echo "<div class='card_each_final'>";
                            echo "<p> Id : $id_each</p>";
                            echo "<p> Nom : $nom_each</p>";
                            echo "<p> Prix : $prix_each</p>";
                            echo "<p> Categorie : $categorie_each</p>";
                            echo "<p>$image_each</p>";
                            echo "<p> Stock : $stock_each</p>";
                            echo "<p> Quantité : $quantite_each</p>";
                    echo "</div>";

                    //RAJOUTER CALCUL DU PRIX
                    //RAJOUTER CALCUL DU STOCK
                }
            ?>
        </div>
        
        
    </body>

    <form id="final" action="" method="POST">
          <button name="acheter" type="submit">ACHETER</button>
    </form>
</html>

<style>
.card_each_final {
    border: 2px solid black;
    margin:5px;
    padding:5px;
}

</style>