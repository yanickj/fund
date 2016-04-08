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
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

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
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * Cost of thing we're trying to get.
     *
     * @var float
     *
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    protected $cost;

    /**
     * @var
     *
     * @ORM\Column(type="integer")
     */
    protected $minParticipants;

    /**
     * Image link of thing we're trying to get.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=128)
     */
    protected $imageLink;

    /**
    * Date the thing expires.
    *
     * @var date $expirationDate
     *
     * @ORM\Column(name="expirationDate", type="date")
     */
    protected $expirationDate;

    public function __construct()
    {
        $this->expirationDate = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

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

    /**
     * @return mixed
     */
    public function getMinParticipants()
    {
        return $this->minParticipants;
    }

    /**
     * @param mixed $minParticipants
     */
    public function setMinParticipants($minParticipants)
    {
        $this->minParticipants = $minParticipants;
    }

    /**
     * @return string
     */
    public function getImageLink()
    {
        return $this->imageLink;
    }

    /**
     * @param string $imageLink
     *
     * @return $this
     */
    public function setImageLink($imageLink)
    {
        $this->imageLink = $imageLink;

        return $this;
    }

    /**
     * @return Date
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    /**
     * @param Date $expirationDate
     *
     * @return $this
     */
    public function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }
}
