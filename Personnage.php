<?php

include 'db.php';

class Character
{
    
  private $_id,
          $_damages,
          $_name;

    const SELF_DAMAGE = 1; // const returned by "hit" if the character is hitting himself
    const CHARACTER_KILLED = 2; // const returned by "hit" if the character has been killed
    const CHARACTER_HIT = 3; // const returned by "hit" if we correctly hit the character 

    public function __construct(array $data)
    {
      $this->hydrate($data);
    }

    public function hit(Character $character)
    {
        // if the characted is hitting himself, return const SELF_DAMAGE
        if ($character->id() == $this->_id)
        {
            return self::SELF_DAMAGE;
        }
        else
        // otherwise, the character must receiveDamage : we display the value returned by the method self::CHARACTER_KILLED or self::CHARACTER_HIT
        {
            return $character->receiveDamage();
        }
    }

    public function hydrate(array $data)
    {
        foreach ($data as $key => $value)
        {
            $method = 'set'.ucfirst($key);

            if (method_exists($this, $method))
            {
                $this->$method($value);
            }
        }
    }



    public function receiveDamage()
    {
        $this->_damages += 5;

        // if the character receives 100 dmgs or more : return const CHARACTER_KILLED
        if ($this->_damages >= 100)
        {
            return self::CHARACTER_KILLED;
        }
        else
        // if not killed, return thats the character has received damages : CHARTACTER_HIT
        {
            return self::CHARACTER_HIT;
        }
    }

    // GETTERS //

    public function damages()
    {
        return $this->_damages;
    }



    public function id()
    {
        return $this->_id;
    }



    public function name()
    {
        return $this->_name;
    }



    public function setDamages($damages)
    {

        $damages = (int) $damages;
    
        if ($damages >= 0 && $damages <= 100)
        {
            $this->_damages = $damages;
        }
    }



    public function setId($id)
    {

    $id = (int) $id;
    
        if ($id > 0)
        {
            $this->_id = $id;
        }
    }

    public function nomValide()
    {
      return !empty($this->_nom);
    }
    

    public function setName($name)
    {
        if (is_string($name))
        {
            $this->_name = $name;
        }
    }
}