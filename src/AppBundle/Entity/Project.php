<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Campaign
 *
 * @author Józef Janik <joe@getsidecar.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="campaign")
 */
class Project
{
    /**
     * Name of campaign thing we're trying to get.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=128)
     */
    protected $name;

    /**
     * Description of the thing.
     *
     * @var string
     *
     * @ORM\Column(type="longtext")
     */
    protected $description;

    /**
     * Cost of thing we're trying to get.
     *
     * @var float
     *
     * @ORM\Column(type="decimal", precision=2, scale=5)
     */
    protected $cost;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return float
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @param float $cost
     *
     * @return $this
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }
}
