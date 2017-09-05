<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Neo
 *
 * @ORM\Table(name="neo")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NeoRepository")
 */
class Neo
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=255)
     */
    private $reference;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var float
     *
     * @ORM\Column(name="speed", type="float")
     */
    private $speed;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_hazardous", type="boolean")
     */
    private $isHazardous;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Neo
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return Neo
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Neo
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set speed
     *
     * @param float $speed
     *
     * @return Neo
     */
    public function setSpeed($speed)
    {
        $this->speed = $speed;

        return $this;
    }

    /**
     * Get speed
     *
     * @return float
     */
    public function getSpeed()
    {
        return $this->speed;
    }

    /**
     * Set isHazardous
     *
     * @param boolean $isHazardous
     *
     * @return Neo
     */
    public function setIsHazardous($isHazardous)
    {
        $this->isHazardous = $isHazardous;

        return $this;
    }

    /**
     * Get isHazardous
     *
     * @return bool
     */
    public function getIsHazardous()
    {
        return $this->isHazardous;
    }
}

