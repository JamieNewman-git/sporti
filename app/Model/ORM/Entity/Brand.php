<?php

namespace App\Model\ORM\Entity;


use _HumbugBox5f65e914a905\Nette\Utils\DateTime;
use App\Models\ORM\Identifier;
use Doctrine\ORM\Mapping as ORM;
use App\Model\ORM\Repository\BrandRepository;

/**
 * @ORM\Entity
 */
class Brand
{

    use Identifier;

    /**
     * @ORM\Column(type="string", length=32, unique=true, nullable=false)
     */
    protected $name;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $deletedAt;

    /**
     * Brand constructor.
     * @param string|null $name
     */
    public function __construct( string $name = NULL ) {
        if ($name !== NULL) {
            $this->name = $name;
        }
        $this->setCreatedAt(new DateTime());
        $this->setUpdatedAt(new DateTime());
    }

    /**
     * @return string
     */
    public function getName() : string {
        return $this->name;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt() : ?\DateTime {
        return $this->createdAt;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt() : ?\DateTime {
        return $this->updatedAt;
    }

    /**
     * @return \DateTime|null
     */
    public function getDeletedAt() : ?\DateTime {
        return $this->deletedAt;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name) : Brand {
        $this->name = $name;
        return $this;
    }

    /**
     * @param DateTime $date
     * @return $this
     */
    public function setCreatedAt(DateTime $date) : Brand {
        $this->createdAt = $date;
        return $this;
    }

    /**
     * @param DateTime $date
     * @return $this
     */
    public function setUpdatedAt(DateTime $date) : Brand {
        $this->updatedAt = $date;
        return $this;
    }

    /**
     * @param DateTime $date
     * @return $this
     */
    public function setDeletedAt(DateTime $date) : Brand
    {
        $this->deletedAt = $date;
        return $this;
    }
}