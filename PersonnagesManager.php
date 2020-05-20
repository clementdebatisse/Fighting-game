<?php

include 'db.php';

class CharactersManager

{
  private $_db; // PDO instance
  
  public function __construct($db)
  {
    $this->setDb($db);
  }
  
  public function add(Character $character)
  {
    $q = $this->_db->prepare('INSERT INTO characters(name) VALUES(:name)');
    $q->bindValue(':name', $character->name());
    $q->execute();
    
    $character->hydrate([
      'id' => $this->_db->lastInsertId(),
      'damages' => 0,
    ]);
  }
  
  public function count()
  {
    return $this->_db->query('SELECT COUNT(*) FROM characters')->fetchColumn();
  }
  
  public function delete(Character $character)
  {
    $this->_db->exec('DELETE FROM characters WHERE id = '.$character->id());
  }
  
  public function exists($info)
  {
    if (is_int($info)) // Checking if one character having $info for id exists
    {
      return (bool) $this->_db->query('SELECT COUNT(*) FROM characters WHERE id = '.$info)->fetchColumn();
    }
    
    // Otherwise, we want to check if the name already exists
    
    $q = $this->_db->prepare('SELECT COUNT(*) FROM characters WHERE name = :name');
    $q->execute([':name' => $info]);
    
    return (bool) $q->fetchColumn();
  }
  
  public function get($info)
  {
    if (is_int($info))
    {
      $q = $this->_db->query('SELECT id, name, damages FROM characters WHERE id = '.$info);
      $data = $q->fetch(PDO::FETCH_ASSOC);
      
      return new Character($data);
    }
    else
    {
      $q = $this->_db->prepare('SELECT id, name, damages FROM characters WHERE name = :name');
      $q->execute([':name' => $info]);
    
      return new Character($q->fetch(PDO::FETCH_ASSOC));
    }
  }
  
  public function getList($name)
  {
    $characters = [];
    
    $q = $this->_db->prepare('SELECT id, name, damages FROM characters WHERE name <> :name ORDER BY name');
    $q->execute([':name' => $name]);
    
    while ($data = $q->fetch(PDO::FETCH_ASSOC))
    {
      $characters[] = new Character($data);
    }
    
    return $characters;
  }
  
  public function update(Character $character)
  {
    $q = $this->_db->prepare('UPDATE characters SET damages = :damages WHERE id = :id');
    
    $q->bindValue(':damages', $character->damages(), PDO::PARAM_INT);
    $q->bindValue(':id', $character->id(), PDO::PARAM_INT);
    
    $q->execute();
  }
  
  public function setDb(PDO $db)
  {
    $this->_db = $db;
  }
}