<?php
session_start();

// Ici connexion à la BDD et FETCH-ASSOC classique
$connexion = new PDO('mysql:host=localhost;dbname=panier;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$query = $connexion->prepare("SELECT*FROM produits");
$query->execute();
$result = $query->fetchAll(PDO::FETCH_ASSOC);
// ================================================

/**
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

/**
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
function check_id($id, $count)
{
    for($i=0; $i<$count;$i++)
    {   
        echo "$i";
            if($_SESSION["panier"][$i]["id"] == $id)
            {
                return $i;
            }               
    }   
}
//==================================================

//ICI ON VAR_DUMP TOUT \\////\/\\/\/\\/\\//\//\\//\\\\/
 if(isset($_POST))
 {
     echo "<pre>";
     var_dump($_POST);
     echo "<pre>";
 }
//On regarde combien d'articles on dispose dans le panier                                 
if(isset($_SESSION["panier"]))
{
    echo "<pre>";
    var_dump(count($_SESSION["panier"]));
    echo "</pre>";
}
if(isset($_SESSION["panier"]))
{
    echo "<pre>";
    var_dump($_SESSION["panier"]);
    echo "</pre>";
}
if(isset($verif))
{
    echo "<pre>";
    var_dump($verif);
    echo "</pre>";
}
///\\\//\\//\/\/\\////\/\\/\/\\/\\//\//\\//\\\\/\///\\\/




//=====================================================
//Lorsqu'on clique sur le boutton ajouter
if(isset($_POST["submit"]))
{
    //==========================
    //Si le panier n'existe pas 
    if(!isset($_SESSION["panier"]))
    {
        //on rajoutera une condition pour verifier le stock

        //on recupère l'ID grace à la valeur incluse dans $_POST["submit"]
        $id_article = $_POST["submit"];
        //On appele la fonction qui recupère les info de l'article en question
        $recup = selectOne($id_article);

        //on crée le panier en intégrant le premier article (donc à l'emplacement[0] )
        $_SESSION["panier"][0]["id"] = $recup["id"];
        $_SESSION["panier"][0]["nom"] = $recup["nom"];
        $_SESSION["panier"][0]["prix"] = $recup["prix"];
        $_SESSION["panier"][0]["categorie"] = $recup["categorie"];
        $_SESSION["panier"][0]["image"] = $recup["image"];
        $_SESSION["panier"][0]["stock"] = $recup["stock"];
        $_SESSION["panier"][0]["quantite"] = 1;

        //on refresh et on die sinon le code continue
        header("Refresh:0.3");
        die();
    }

    //==========================
    //Si le panier existe deja 
    if(isset($_SESSION["panier"]))
    {
        //on recupère l'ID grace à la valeur incluse dans $_POST["submit"]
        $id_article = $_POST["submit"];
        //On compte combien il y a d'element dans le panier
        $count = count($_SESSION["panier"]);
        //on appele la fonction qui permet de verifier si l'article à deja été ajouté
        $verif = check_id($id_article, $count);
        // var_dump($verif);
        // die();

        //========================== 
        // On verifie la présence de l'article
        //Si verif n'est pas NULL c'est que l'article est deja dans le panier
        if($verif == TRUE || $verif === 0)
        {
            //on metra une verif de stock ici

            //du coup on pourra incremeter la quantité 
            // $verif contient l'index du panier ou se trouve l'article
            $_SESSION["panier"][$verif]["quantite"]++;

            //on refresh et on die sinon le code continue
            header("Refresh:0.3");
            die();
        }
        //Le resultat de check_id peut etre TRUE mais aussi FALSE quand l'emplacement est à l'index [0]
        if($verif === NULL){
            //========================== 
            //l'article n'est pas dans le panier donc on le crée

            //on recupère l'ID grace à la valeur incluse dans $_POST["submit"]
            $id_article = $_POST["submit"];

            //on defini le nouvel emplacement grace a count 
            $place = $count;

            //  var_dump($place);
            //  die();

            //On appele la fonction qui recupère les info de l'article en question
            $recup = selectOne($id_article);

            //On rajoute l'article a la fin du panier (grace a count)
            $_SESSION["panier"][$place]["id"] = $recup["id"];
            $_SESSION["panier"][$place]["nom"] = $recup["nom"];
            $_SESSION["panier"][$place]["prix"] = $recup["prix"];
            $_SESSION["panier"][$place]["categorie"] = $recup["categorie"];
            $_SESSION["panier"][$place]["image"] = $recup["image"];
            $_SESSION["panier"][$place]["stock"] = $recup["stock"];
            $_SESSION["panier"][$place]["quantite"] = 1;
    
            //on refresh et on die sinon le code continue
            header("Refresh:0.3");
            die();
        }
    }
}
//=====================================================

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boutique</title>
</head>
    <body>

        <div class="header">
            <a href="index.php">ACCUEIL</a>
            <a href="boutique.php">BOUTIQUE</a>
            <a href="panier.php">PANIER</a>
        </div>

        <div class="main">
            <div class="display_cart">
                <h1>LA BOUTIQUE</h1>
                <p>Ici afichage des articles</p>
            </div>
            <div class="produits_display">
                <?php 
                for($i=0; isset($result[$i]); $i++ )
                {
                    echo "<div class='card'>";
                        echo "<p>".$result[$i]["nom"]."</p>";
                        echo "<p>".$result[$i]["prix"]."</p>";
                        echo "<p>".$result[$i]["categorie"]."</p>";
                        echo "<p>".$result[$i]["image"]."</p>";
                            echo "<form action='' method='POST'>";
                                echo "<button name='submit' value='".$result[$i]["id"]."' type='submit'>Ajouter au panier</button>";
                                // echo "<input type='submit' name='submit' value='".$result[$i]["id"]."'>";
                                echo "<a href=produitdescription.php?id=".$result[$i]["id"].">Voir details</a>"; 
                            echo "</form>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>      
    </body>
</html>

<style>
.produits_display {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
}
.card {
    border: 2px solid black;
    margin: 10px;
    padding: 5px;
    text-align: center;
}
</style>