<?php

include 'Personnage.php';
include 'PersonnagesManager.php';

// On enregistre notre autoload.
function chargerClasse($classname)
{
  require $classname.'.php';
}

spl_autoload_register('chargerClasse');

session_start(); // On appelle session_start() APRÈS avoir enregistré l'autoload.

if (isset($_GET['deconnexion']))
{
  session_destroy();
  header('Location: .');
  exit();
}

$db = new PDO('mysql:host=localhost;dbname=tp_jeu_de_combat', 'root', '');

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); // On émet une alerte à chaque fois qu'une requête a échoué.

$manager = new CharactersManager($db);

if (isset($_SESSION['perso'])) // Si la session perso existe, on restaure l'objet.
{
  $perso = $_SESSION['perso'];
}

if (isset($_POST['creer']) && isset($_POST['name'])) // Si on a voulu créer un personnage.
{
  $perso = new Character(['name' => $_POST['name']]); // On crée un nouveau personnage.
  
  if (!$perso->nameValide())
  {
    $message = 'Le nom choisi est invalide.';
    unset($perso);
  }
  elseif ($manager->exists($perso->name()))
  {
    $message = 'Le name du personnage est déjà pris.';
    unset($perso);
  }
  else
  {
    $manager->add($perso);
  }
}

elseif (isset($_POST['utiliser']) && isset($_POST['name'])) // Si on a voulu utiliser un personnage.
{
  if ($manager->exists($_POST['name'])) // Si celui-ci existe.
  {
    $perso = $manager->get($_POST['name']);
  }
  else
  {
    $message = 'Ce personnage n\'existe pas !'; // S'il n'existe pas, on affichera ce message.
  }
}

elseif (isset($_GET['hit'])) // Si on a cliqué sur un personnage pour le hit.
{
  if (!isset($perso))
  {
    $message = 'Merci de créer un personnage ou de vous identifier.';
  }
  
  else
  {
    if (!$manager->exists((int) $_GET['hit']))
    {
      $message = 'Le personnage que vous voulez hit n\'existe pas !';
    }
    
    else
    {
      $persoAFrapper = $manager->get((int) $_GET['hit']);
      
      $retour = $perso->hit($persoAFrapper); // On stocke dans $retour les éventuelles erreurs ou messages que renvoie la méthode hit.
      
      switch ($retour)
      {
        case Character::SELF_DAMAGE :
          $message = 'Mais... pourquoi voulez-vous vous hit ???';
          break;
        
        case Character::CHARACTER_HIT :
          $message = 'Le personnage a bien été frappé !';
          
          $manager->update($perso);
          $manager->update($persoAFrapper);
          
          break;
        
        case Character::CHARACTER_KILLED :
          $message = 'Vous avez tué ce personnage !';
          
          $manager->update($perso);
          $manager->delete($persoAFrapper);
          
          break;
      }
    }
  }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>TP : Mini jeu de combat</title>
    
    <meta charset="utf-8" />
  </head>
  <body>
    <p>Nombre de personnages créés : <?= $manager->count() ?></p>
<?php
if (isset($message)) // On a un message à afficher ?
{
  echo '<p>', $message, '</p>'; // Si oui, on l'affiche.
}

if (isset($perso)) // Si on utilise un personnage (nouveau ou pas).
{
?>
    <p><a href="?deconnexion=1">Déconnexion</a></p>
    
    <fieldset>
      <legend>Mes informations</legend>
      <p>
        Nom : <?= htmlspecialchars($perso->name()) ?><br />
        Dégâts : <?= $perso->damages() ?>
      </p>
    </fieldset>
    
    <fieldset>
      <legend>Qui hit ?</legend>
      <p>
<?php
$persos = $manager->getList($perso->name());

if (empty($persos))
{
  echo 'Personne à hit !';
}

else
{
  foreach ($persos as $unPerso)
  {
    echo '<a href="?hit=', $unPerso->id(), '">', htmlspecialchars($unPerso->name()), '</a> (dégâts : ', $unPerso->damages(), ')<br />';
  }
}
?>
      </p>
    </fieldset>
<?php
}
else
{
?>
    <form action="" method="post">
      <p>
        Nom : <input type="text" name="name" maxlength="50" />
        <input type="submit" value="Créer ce personnage" name="creer" />
        <input type="submit" value="Utiliser ce personnage" name="utiliser" />
      </p>
    </form>
<?php
}
?>
  </body>
</html>
<?php
if (isset($perso)) // Si on a créé un personnage, on le stocke dans une variable session afin d'économiser une requête SQL.
{
  $_SESSION['perso'] = $perso;
}