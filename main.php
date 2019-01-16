<?php
/**
 * Created by PhpStorm.
 * User: isaac
 * Date: 06/01/19
 * Time: 15:49
 */


// Appel des sources externes
require_once ('vendor/autoload.php');
require_once "src/query/Query.php";

// Chargement namespace
//use query\Query;
use connection\ConnectionFactory;

// Chargement du ficher de connexion au server
$conf = parse_ini_file('src/config/conf.ini');
ConnectionFactory::makeConnection($conf);

//Liste des articles
echo "<p><b>Tous les articles</b></p>";
$res = \model\Article::all();

foreach ($res as $v){
    echo '<p><b>'.$v->nom.'</b></p>';
}

//Recherche id precis
echo "<p><b>Find avec id 66</b></p>";

$req = \model\Article::find(66);
print_r($req);

// Find avec un champ
echo "<p><b>Find avec sélection des champs</b></p>";

$req = \model\Article::find(66,['id','tarif']);
print_r($req);

// Find avec un where
echo "<p><b>Find avec WHERE</b></p>";

$req = \model\Article::find(['tarif','<=',250],['id','tarif']);
print_r($req);

// find avec plus critères de recherche
echo "<p><b>find avec plus critères de recherche</b></p>";
$req = \model\Article::find([['nom ','like ','%biclou%'],['tarif','<=',220]]);
print_r($req);

// Premier article à moins de 250
echo "<p><b>Premier article à moins de 250 </b></p>";
$req = \model\Article::first(['tarif','<=',250]);
print_r($req);

// Categorie associé à un article
echo "<p><b>Categorie associé à un article</b></p>";

$a= \model\Article::first(66);
$categorie = $a->categorie();
print_r($categorie);

// Articles associés à une categorie
echo "<p><b>Articles associés à une categorie</b></p>";
$c = \model\Categorie::first(1);
$articles = $c->Articles();
print_r($articles);


// Ajout d'un nouvel article
$a = new \model\Article() ;
$a->nom= 'A12609' ;
$a->descr= 'Forza Inter';
$a-> id_categ = '28';
$a->tarif= 59.95;
$a->insert() ;
print_r($a);

