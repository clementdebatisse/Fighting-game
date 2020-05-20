<?php

include 'db.php';

// Getting autoload to work
function loadClasse($classname)
{
  require $classname.'.php';
}

spl_autoload_register('loadClasse');

?>