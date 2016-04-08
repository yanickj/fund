<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use AppBundle\Entity\Project;

/**
 * Class ParticipationService
 *
 * A service for determining if the logged in user is a participant.
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
        $participation = $this->participants->findOneBy(['project' => $project, 'name' => $this->context->getToken()->getUser()->getUsername()]);

        return !is_null($participation);
    }

    /**
     * @param Project $project
     *
     * @return float
     */
    public function costPerParticipant(Project $project)
    {
        setlocale(LC_MONETARY, 'en_US');
        $participants = $this->participants->findBy(['project' => $project]);
        $denominator = count($participants);
        $value = ($denominator == 0) ? $project->getCost() : $project->getCost() / $denominator;

        return $this->moneyFormat($value);
    }

    protected function moneyFormat($value)
    {
        $float = round($value, 2);

        return money_format('%n', $float);
    }
}
