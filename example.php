<?php

/**
 * Kokolitolithe Database mapper
 * Exemple d'utilisation, ceci est à titre informatif et ne regroupe pas toutes les possibilitées qu'offre cette classe.
 * @link http://ref.moufbox.fr/
 */

require "DataBase.php";
require "Model.php";

$kokolitolithe = DataBase::instance(); // methode pour récuperer le Singleton.

$config["mysql"] = array(
			'host' => 'localhost', // l'adresse du serveur MySQL
			'name' => '', // le nom de la base de donnée
			'user' => '', // Votre login MySQL
			'password' => '', // Votre password
			'port' => null // Si null, le port par défaut de la base de donnée soit 3306 sera utilisé.
		);

/**
 * Le mapper permet de gérer le loadbalancing, ainsi nous pouvons mettre en place une architecture serveur de base de donnée redondée pour gérer les montés en charge de votre application.
 * 
 */

$kokolitolithe->configMaster($config["mysql"]["host"],$config["mysql"]["name"],$config["mysql"]["user"],$config["mysql"]["password"]);
$kokolitolithe->configSlave($config["mysql"]["host"],$config["mysql"]["name"],$config["mysql"]["user"],$config["mysql"]["password"]);

/**
Signature de la methode : 
	public function select($table, $params = null, $limit = null, $start = null, $order_by=null, $use_master = false);
**/

$params = array("id" => 1);
$limite = 1; // Optionel

echo "<pre>";
	$resultat = $kokolitolithe->select("nom_de_la_table", $params, $limite);
	var_dump($resultat); // Collection de résultat ou false si il ne trouve pas ou si il y a une érreur
echo "</pre>";

/**
Signature de la methode : 
	public function insert($table, $params = array(), $timestamp_this = null);
**/
$params = array("nom" => "kokolitolithe", "prenom" => "valeur","nom_de_colone" => "valeur_a_entrer");

echo "<pre>";
	$resultat = $kokolitolithe->insert("nom_de_la_table", $params, true);
	var_dump($resultat); // Valeur de la clé primaire ou false si il y a une érreur
echo "</pre>";

/**
Signature de la methode : 
	public function delete($table, $params = array());
**/
$params = array("id" => 1);

echo "<pre>";
	$resultat = $kokolitolithe->delete("nom_de_la_table", $params);
	var_dump($resultat); // renvoie le nombre d'enregistrement supprimé ou false si il y a eu une erreur.
echo "</pre>";

/**
 par defaut, la class log toutes les erreurs dans les fichiers d'erreur d'apache. Pour pouvoir débugger vous pouver récuperer l'errorMessage du try catch
**/

echo $kokolitolithe->getErrorMessage();

/**
* Model_User
* @autors Moufasa
 * Exemple de model dans le framework Kokolitolithe
*/

class Model_User extends Model
{
	protected $table = "user"; //préciser le nom de la table en base.
	protected $timestampable = true; // préciser si le champs created_at et updated_at doivent être remplis.
	protected $primary = array('id'); // préciser la ou les clés primaires.
}

$params = array("nom" => "kokolitolithe", "prenom" => "valeur","nom_de_colone" => "valeur_a_entrer");
$user = new Model_User();
$user->setFromArray($params);
$newId = $user->save(); //Fait un insert et renvoie l'id de l'user courant.

$user->save("prenom" => "Moufasa"); // fait un update de l'user courant avec pour nouveau prenom : "Moufasa"

$user->find(1); //recherche l'id 1 et y fait un select
//OR 
$user->findBy(array("id" => 1)); // biensur cette methode reçoit autant de colone comme filtre que vous le souhaitez.

