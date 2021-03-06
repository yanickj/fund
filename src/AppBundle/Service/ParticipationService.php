<?php

namespace AppBundle\Service;

use AppBundle\Entity\Participant;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use AppBundle\Entity\Project;

/**
 * Class ParticipationService
 *
 * A service for determining things about a projects participation.
 *
 * @package AppBundle\Service
 */
class ParticipationService
{
    /**
     * @var EntityManager
     */
    protected $participants;

    /**
     * @var TokenStorage
     */
    protected $context;

    /**
     * AppExtension constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, TokenStorage $context)
    {
        $this->participants = $em->getRepository('AppBundle:Participant');
        $this->context = $context;
    }

    /**
     * Returns true if the logged in user is participating in an project/campaign.
     *
     * @param Project $project
     *
     * @return bool
     */
    public function isParticipant(Project $project)
    {
        $participation = $this->getParticipant($project);

        return !is_null($participation);
    }

    /**
     * Get the participant record for the currently logged in user given a project.
     *
     * @param Project $project
     *
     * @return null|object
     */
    public function getParticipant(Project $project)
    {
        return $this->participants->findOneBy(['project' => $project, 'name' => $this->context->getToken()->getUser()->getUsername()]);
    }

    /**
     * Get the cost per participant given a project.
     *
     * @param Project $project
     *
     * @return float
     */
    public function costPerParticipant(Project $project)
    {
        $denominator = $this->getParticipantCount($project);
        $value = ($denominator == 0) ? $project->getCost() : $project->getCost() / $denominator;

        return $this->moneyFormat($value);
    }

    /**
     * Get the maximum cost of the project per person given a project.
     *
     * @param Project $project
     *
     * @return string
     */
    public function maxCostPerParticipant(Project $project)
    {
        $denominator = $project->getMinParticipants();
        $value = ($denominator == 0) ? $project->getCost() : $project->getCost() / $denominator;

        return $this->moneyFormat($value);
    }

    /**
     * Get the number of participants given a project.
     *
     * @param Project $project
     *
     * @return int
     */
    public function getParticipantCount(Project $project)
    {
        $participants = $this->participants->findBy(['project' => $project]);

        return count($participants);
    }

    public function getDaysToRegister(Project $project)
    {
        $now = new \DateTime('now');
        $expire = clone $project->getExpirationDate();
        $expire->modify('+ 1 day');
        $diff = $expire->diff($now);

        return $diff->format('%a');
    }

    /**
     * Return a string in money format.
     *
     * @param $value
     *
     * @return string
     */
    protected function moneyFormat($value)
    {
        setlocale(LC_MONETARY, 'en_US');
        $float = round($value, 2);

        return money_format('%n', $float);
    }
}
