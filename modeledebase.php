<?php
session_start();

/*===================================================================
ICI LA FONCTION QUI PERMET DE CHECKER UN ID EXISTE DEJA OU PAS
DANS LE TABLEAU SESSION A L'EMPLACEMENT ID

SI L'ID EN QUESTION EXISTE ALORS LA FONCTION RETURN TRUE SINON ELLE
DEVIENT NULL

NE PAS HESITER A METTRE LES VARIABLES SESSION EN COMMENTAIRE POUR
TESTER LE CODE SINON ELLES SE REMETTRON A LA MEME VALEUR A CHAQUE FOIS
===================================================================*/

//On créer quelques element dans SESSION
//   $_SESSION["panier"][0]["nom"] = "Hache";
//   $_SESSION["panier"][0]["prix"] = 78;
//   $_SESSION["panier"][0]["id"] = 22;
//   $_SESSION["panier"][0]["quantite"] = 100;


//   $_SESSION["panier"][1]["nom"] = "Table";
//   $_SESSION["panier"][1]["prix"] = 35;
//   $_SESSION["panier"][1]["id"] = 55;
//   $_SESSION["panier"][1]["quantite"] = 50;

//   $_SESSION["panier"][2]["nom"] = 'Vitre';
//   $_SESSION["panier"][2]["prix"] = 90;
//   $_SESSION["panier"][2]["id"] = 12;
//   $_SESSION["panier"][2]["quantite"] = 70;

//   $_SESSION["panier"][3] = array('nom' => 'Chaise',
//                                    'prix' => 28,
//                                    'id' => 33,
//                                    'quantite' => 90);

//On regarde combien d'articles on dispose dans le panier                                 
if(isset($_SESSION["panier"]))
{
    echo "<pre>";
    var_dump(count($_SESSION["panier"]));
    echo "</pre>";
}

/**
 * Cette fonction va répéter une boucle autant de fois qu'il y a 
 * d'articles dans $_SESSION["panier"] , du coup incrémenter l'index [$i]
 * qui va servir à cibler chaque id de chaque article
 * Si l'ID en question est retrouvé elle return TRUE sinon elle devient NULL
 * 
 * Cette fonction a été crée dans l'optique de checker si un article a deja été ajouté au panier
 * lorsque l'utilisateur va cliquer sur le boutton "ajouter au panier" que cela soit dans la page
 * article général ou dans la page article unique en question 
 * 
 * Le cas echeant on décidera soit de créer une variable SESSION avec le nouvel article (grace a l'ID)
 * Soit d'incrémenter la valeur QUANTITE (car ajout en plus au panier)
 */
function check_id($idproduit, $count)
{
    for($i=0; $i<$count;$i++)
    {   
            if($_SESSION["panier"][$i]["id"] == $idproduit)
            {
                return $i;
            }               
    }   
}

//on compte combien d'articles dans le panier
if(isset($_SESSION["panier"]))
{
    $count = count($_SESSION["panier"]);
}


//On utilise la variable $idEnQuestion pour tester des valeur d'ID 
//Lorsqu'on implementera la fonction cette variable sera relié au GET ou POST de l'article en questiob
$idEnQuestion = 2222 ;

//on stocke le resultat de la fonction dans une variable intermédiaire
//on fait un test avec $idEnQuestion et la variable $count

// $resultat = check_id($idEnQuestion, $count);

/*Si le resultat n'est pas null c'est qu'il est true donc c'est qu'il y a 
un article deja présent sinon elle return NULL
*/
// if($resultat !== NULL)
// {
//     // echo "LE PRODUIT EXISTE DEJA";
//     //On pourra incrémenter la quantité
//     $_SESSION["panier"][$resultat]["quantite"]++;
// }
// else echo "LE PRODUIT NEXISTE PAS ENCORE";


// Ici on affiche simplement le contenu de la variable SESSION
if(isset($_SESSION))
{
    echo "<pre>";
    var_dump($_SESSION);
    echo "</pre>";
}


//ici on décremente lorsqu'on clique sur submit ()
if(isset($_POST["submit"]))
{

    $count = count($_SESSION["panier"]);
    (int)$idactuel = (int)$_POST["idarticle"];
    
    for($i=0; $i<$count;$i++)
    {   
            if($_SESSION["panier"][$i]["id"] == $idactuel)
            {
                $_SESSION["panier"][$i]["quantite"]--;
                header("Refresh:1");
            }               
    }   
}

if(isset($_POST["submit2"]))
{

    $count = count($_SESSION["panier"]);
    if($count == 1)
    {
        unset($_SESSION["panier"]);
        header("Refresh:0.1");
        die();
    }
    (int)$idactuel = (int)$_POST["idarticle"];
    
    for($i=0; $i<=$count;$i++)
    {   
            if($_SESSION["panier"][$i]["id"] == $idactuel)
            {
                unset($_SESSION["panier"][$i]);
                $_SESSION["panier"] = array_values($_SESSION["panier"]);
                header("Refresh:0.1");
                die();
            }               
    }   
}

// if(isset($count) && $count == 0)
// {
//     unset($_SESSION["panier"]);
// }

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
                <h1>ACCUEIL</h1>
                <p>Ici afichage du panier dans l'accueil</p>
            </div>
        </div>

        
        
    </body>

    <form action="" method="POST">
        <input type="text" name="idarticle"> 
        <input type="submit" name="submit2">
    </form>
</html>
