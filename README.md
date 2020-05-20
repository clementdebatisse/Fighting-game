# Fighting-game
Basic fighting game 

Résumé de l’apprentissage
1. Vous allez utilisez les connaissances acquises sur PDO pour gérer une base de données de personnages de jeu.
2. Vous allez créer 2 classes PHP qui ont des rôles bien déterminés et séparés.
3. Vous allez en apprendre un peu plus sur l’intérêt des classes.
4. Vous allez découvrir le principe de l’auto loading.
5. Vous allez découvrir le principe de l’hydratation et l’imbrication de la PDO avec les classes.
________________
Instructions

🥊 Finir le TP du mini-**jeu de combat de la partie 1 du cours Openclassroom **🥊

Objectifs partie 1 :

* Chaque visiteur pourra créer un personnage (pas de mot de passe requis pour faire simple).

* L'utilisateur (visiteur connecté) pourra alors choisir un personnage avec lequel se battre.

* Le personnage se bat

Le projet devra comporter 5 fichiers PHP :
* config/autoload.php : permettant le chargement automatique des classes
* config/db.php : permettant la connexion à la bdd (Une instance de PDO devra être créée)
* classes/Personnage.php : définit la classe Personnage possédant :
   * 2 propriétés :
      * son nom (unique).
      * ses dégâts.
   * 2 méthodes :
      * frapper un autre personnage
      * recevoir des dégâts.

* classes/PersonnagesManager.php : définit la classe PersonnagesManager qui stocke les données et comporte ces fonctionnalités :
   * enregistrer un nouveau personnage
   * modifier un personnage
   * supprimer un personnage
   * sélectionner un personnage
   * compter le nombre de personnages
   * récupérer une liste de plusieurs personnages
   * savoir si un personnage existe.

* index.php : affichant l'interface du mini-jeu de combat
   * Le joueur peut créer un personnage
   * Le joueur peut utiliser un personnage existant

* combat.php : utilise les classes instanciées et les méthodes souhaitées sur les objets. (Une instance de PersonnagesManager devra être créée)


L'ensemble du code rendu devra respecter les normes PHP PSR-1 et PSR-2 :
* ​https://www.php-fig.org/psr/psr-1/​
* ​https://www.php-fig.org/psr/psr-2/​
Ressources
* class : https://tutowebdesign.com/declaration-class-php.php​
* visibilité : https://tutowebdesign.com/visibilite-classe-php.php​
* héritage : https://tutowebdesign.com/heritage-objet-php.php

Plus largement tout ce cours : https://tutowebdesign.com/poo-php.php​

Cours officiel Openclassroom (jusqu'au TP mini-jeu de combat) : https://openclassrooms.com/fr/courses/1665806-programmez-en-oriente-objet-en-php

Rendu :

Il vous est demandé de rendre au formateur une URL de dépôt GIT via Google Classroom.