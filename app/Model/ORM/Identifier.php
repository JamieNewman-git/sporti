<?php

namespace App\Models\ORM;


use Doctrine\ORM\Mapping as ORM;

trait Identifier
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var integer|null
     */
    protected $id;

    /**
     * @return integer
     */
    final public function getId()
    {
        return $this->id;
    }



    public function __clone()
    {
        $this->id = NULL;
    }

}