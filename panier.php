<?php
session_start();
  //RAJOUTER CALCUL DU PRIX
    //RAJOUTER CALCUL DU STOCK

// Ici connexion à la BDD et FETCH-ASSOC classique
$connexion = new PDO('mysql:host=localhost;dbname=panier;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$query = $connexion->prepare("SELECT*FROM produits");
$query->execute();
$result = $query->fetchAll(PDO::FETCH_ASSOC);
// ================================================
/**FONCTION
 * 
 * Ici la fonction qui permet de lancer une 
 * requete quand le gars clique sur ajouter au panier
 */
function selectOne($id)
{
    $connexion = new PDO('mysql:host=localhost;dbname=panier;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    $query_one = $connexion->prepare("SELECT*FROM produits WHERE id= ?");
    $query_one->execute(array($id));
    $resultat = $query_one->fetch(PDO::FETCH_ASSOC);
    return $resultat;
}
//=========================================================
/**FONCTION
 * 
 * Ici la fonction qui permet de verifier si l'article existe deja ou pas
 * dans le panier (grace à l'ID et le count elle va parcourir les clé du panier
 * et verifier la correspondance des ID)
 * 
 * @param [int] $id
 * @param [int] $count
 * 
 * Si l'article existe dans le panier :
 * @return $i (c'est l'index, l'emplacement dans le panier ou l'article se trouve)
 * Sinon :
 * @return NULL
 */
function get_index($id, $count)
{
    for($i=0; $i<$count;$i++)
    {   
        // echo "$i";
            if($_SESSION["panier"][$i]["id"] == $id)
            {
                return $i;
            }               
    }   
}

//==================================================

//ICI ON VAR_DUMP TOUT \\////\/\\/\/\\/\\//\//\\//\\\\/
if(isset($_GET["ajoutone"]))
{
    echo "<pre>";
    var_dump((int)$_GET["ajoutone"]);
    echo "<pre>";
}

 if(isset($_POST))
 {
     echo "<pre>";
     var_dump($_POST);
     echo "<pre>";
 }


if(isset($_SESSION["panier"]))
{
    echo "<pre>";
    var_dump($_SESSION["panier"]);
    echo "</pre>";
}
///\\\//\\//\/\/\\////\/\\/\/\\/\\//\//\\//\\\\/\///\\\/


//On regarde combien d'articles on dispose dans le panier                                 
if(isset($_SESSION["panier"]))
{
    $nbre_articles = count($_SESSION["panier"]);

    //A supprimer
    echo "<pre>";
    echo "yo";
    var_dump($nbre_articles);
    echo "</pre>";
}
if(!isset($_SESSION["panier"]))
{
    $nbre_articles = 0;
}


//=====================================================

//AJOUT 1
if(isset($_GET["ajoutone"]))
{
    //on recup l'id de l'article qui est stocké dans le boutton
    $id_add = $_GET["ajoutone"];
    //On recup le nombre d'article dans le panier pour utiliser la fonction get_index
    $count = count($_SESSION["panier"]);
    //get_index va nous recuperer l'index en question pour pouvoir ajouter au panier
    $index = get_index($id_add, $count);

    //On incremente la quantité 
    $_SESSION["panier"][$index]["quantite"]++;

    //on refresh et on die sinon le code continue
    //la redirection mene a l'ancre du formulaire mais on pourra rediriger à l'article en question grace à lID nottament
    header("Location:panier.php#basket");
    die();

}

//REMOVE 1
if(isset($_GET["deleteone"]))
{
    

    //on recup l'id de l'article qui est stocké dans le boutton
    (int)$id_add = (int)$_GET["deleteone"];
    //On recup le nombre d'article dans le panier pour utiliser la fonction get_index
    $count = count($_SESSION["panier"]);
    //get_index va nous recuperer l'index en question pour pouvoir ajouter au panier
    $index = get_index($id_add, $count);

    $quantité =  (int)$_SESSION["panier"][$index]["quantite"];

    //S'il y a au moins deux articles
    if($quantité>=2)
    {
         //On décremente la quantité 
        $_SESSION["panier"][$index]["quantite"]--;

        //on refresh et on die sinon le code continue
        //la redirection mene a l'ancre du formulaire mais on pourra rediriger à l'article en question grace à lID nottament
        header("Location:panier.php#basket");
        die();
    }
    
    //S'il ne reste qu'un article
    if($quantité<=1)
    {
        //On enleve l'element du panier en le ciblant avec l'index
        unset($_SESSION["panier"][$index]);
        //on utilise la fonction array_values pour remettre les numéro des clé dans l'ordre
        $_SESSION["panier"] = array_values($_SESSION["panier"]);

        //on refresh et on die sinon le code continue
        //la redirection mene a l'ancre du formulaire mais on pourra rediriger à l'article en question grace à lID nottament
        header("Location:panier.php#basket");
        die();
    }


}
if(isset($_GET["deleteitem"]))
{
    //on recup l'id de l'article qui est stocké dans le boutton
    (int)$id_add = (int)$_GET["deleteitem"];
    //On recup le nombre d'article dans le panier pour utiliser la fonction get_index
    $count = count($_SESSION["panier"]);
    //get_index va nous recuperer l'index en question pour pouvoir ajouter au panier
    $index = get_index($id_add, $count);
    //On enleve l'element du panier en le ciblant avec l'index
    unset($_SESSION["panier"][$index]);
    //on utilise la fonction array_values pour remettre les numéro des clé dans l'ordre
    $_SESSION["panier"] = array_values($_SESSION["panier"]);

    //on refresh et on die sinon le code continue
    //la redirection mene a l'ancre du formulaire mais on pourra rediriger à l'article en question grace à lID nottament
    header("Location:panier.php#basket");
    die();

}
if(isset($_POST["finalcart"]))
{
    unset($_SESSION["panier"]);
    header("Refresh:0.2");
    die();
}
//=====================================================

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>
</head>
    <body>

        <div class="header">
            <a href="index.php">ACCUEIL</a>
            <a href="boutique.php">BOUTIQUE</a>
            <a href="panier.php">PANIER</a>
        </div>

        <div class="main">
            <div class="display_cart">
                <h1>PANIER</h1>
            </div>
        </div>
        <div id='basket' class="card_container">
            <?php
                if($nbre_articles>0)
                {
                    echo "<form action='recapitulatif.php' method='POST'>";
                    //Ici on affiche tout ce qu'il y a dans le panier
                    for($i=0; $i<$nbre_articles; $i++)
                    {
                        //variables intermédiaires
                        $id_each = $_SESSION["panier"][$i]["id"];
                        $nom_each = $_SESSION["panier"][$i]["nom"];
                        $prix_each = $_SESSION["panier"][$i]["prix"];
                        $categorie_each = $_SESSION["panier"][$i]["categorie"];
                        $image_each = $_SESSION["panier"][$i]["image"];
                        $stock_each = $_SESSION["panier"][$i]["stock"];
                        (int)$quantite_each = $_SESSION["panier"][$i]["quantite"];

                        echo "<div class='card_each'>";
                            echo "<p> Id : $id_each</p>";
                            echo "<p> Nom : $nom_each</p>";
                            echo "<p> Prix : $prix_each</p>";
                            echo "<p> Categorie : $categorie_each</p>";
                            echo "<p>$image_each</p>";
                            echo "<p> Stock : $stock_each</p>";
                            echo "<p> Quantité : $quantite_each</p>";

                                echo "<select name='index$i'>";
                                        // echo "<option value=\"$quantite_each\">$quantite_each</option>";
                                        // for($y=$quantite_each+1; $y<=100; $y++){
                                        //     echo "<option value='$y'>$y</option>";
                                        // }
                                        for($y=1;$y<=100;$y++)
                                        {
                                            if($y == $quantite_each)
                                            {
                                                echo "<option value=\"$quantite_each\" selected>$quantite_each</option>";

                                            }
                                            else echo "<option value='$y'>$y</option>";
                                           
                                        }
                                echo "</select>";        

                                echo "<a href='panier.php?ajoutone=$id_each'>ADD</a>";
                                echo "<a href='panier.php?deleteone=$id_each'> REMOVE</a>";
                                echo "<a href='panier.php?deleteitem=$id_each'> SUPPRIMER</a>";

                        echo "</div>";
                    }
                        echo "<button name='passage_achat' type='submit'>Passer à l'achat</button>";
                    echo "</form>";
                }

                if($nbre_articles<=0)
                {
                    echo "<div class='card_empty'>";
                        echo "<p>Le panier est vide</p>";
                    echo "</div>";
                }
                
            ?>

        </div>
    <form action="" method="POST">
      <button name="finalcart" type="submit">Vider le panier</button>
    </form>
        
    </body>
</html>

<style>
.card_each {
   border: 2px solid black;
   margin: 5px;
   width: fit-content;
   min-width: 500px;
   text-align: center;
}

</style>