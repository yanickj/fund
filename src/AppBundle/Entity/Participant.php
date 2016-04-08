<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Participant
 *
 * @package AppBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="participants", indexes={@ORM\Index(name="idx_project_participants", columns={"name", "project_id"})})
 */
class Participant
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
     * @var Project
     *
     * @ORM\OneToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="project_id", onDelete="CASCADE")
     */
    protected $project;

    /**
     * @var string
     *
     * @ORM\Column(name="`name`", type="string", length="32")
     */
    protected $name;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param Project $project
     *
     * @return Participant
     */
    public function setProject(Project $project)
    {
        $this->project = $project;

        return $this;
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
     * @return Participant
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
