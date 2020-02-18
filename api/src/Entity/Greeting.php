<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This is a dummy entity. Remove it!
 *
 * @ORM\Entity
 */
class Greeting
{
    /**
     * @var int The entity Id
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string A nice person
     *
     * @ORM\Column
     */
    public $name = '';

    public function getId(): int
    {
        return $this->id;
    }
}
