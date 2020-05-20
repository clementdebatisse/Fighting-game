<?php

include 'db.php';
include 'autoload.php';


$manager = new CharactersManager($db);

if (isset($_SESSION['character'])) // Si la session character existe, on restaure l'objet.
{
  $character = $_SESSION['character'];
}

if (isset($_POST['creer']) && isset($_POST['name'])) // Si on a voulu créer un personnage.
{
  $character = new Character(['name' => $_POST['name']]); // On crée un nouveau personnage.
  
  if (!$character->nameValide())
  {
    $message = 'Le name choisi est invalide.';
    unset($character);
  }
  elseif ($manager->exists($character->name()))
  {
    $message = 'Le name du personnage est déjà pris.';
    unset($character);
  }
  else
  {
    $manager->add($character);
  }
}

elseif (isset($_POST['utiliser']) && isset($_POST['name'])) // Si on a voulu utiliser un personnage.
{
  if ($manager->exists($_POST['name'])) // Si celui-ci existe.
  {
    $character = $manager->get($_POST['name']);
  }
  else
  {
    $message = 'Ce personnage n\'existe pas !'; // S'il n'existe pas, on affichera ce message.
  }
}

elseif (isset($_GET['frapper'])) // Si on a cliqué sur un personnage pour le frapper.
{
  if (!isset($character))
  {
    $message = 'Merci de créer un personnage ou de vous identifier.';
  }
  
  else
  {
    if (!$manager->exists((int) $_GET['frapper']))
    {
      $message = 'Le personnage que vous voulez frapper n\'existe pas !';
    }
    
    else
    {
      $persoAFrapper = $manager->get((int) $_GET['frapper']);
      
      $retour = $character->frapper($persoAFrapper); // On stocke dans $retour les éventuelles erreurs ou messages que renvoie la méthode frapper.
      
      switch ($retour)
      {
        case Personnage::SELF_DAMAGE :
          $message = 'Mais... pourquoi voulez-vous vous frapper ???';
          break;
        
        case Personnage::CHARACTER_HIT :
          $message = 'Le personnage a bien été frappé !';
          
          $manager->update($character);
          $manager->update($persoAFrapper);
          
          break;
        
        case Personnage::CHARACTER_KILLED :
          $message = 'Vous avez tué ce personnage !';
          
          $manager->update($character);
          $manager->delete($persoAFrapper);
          
          break;
      }
    }
  }
}
?>