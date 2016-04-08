<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use AppBundle\Entity\Project;

class ParticipationService
{
    /**
     * @var EntityManager
     */
    protected $participants;

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
     * @param Project $project
     */
    public function isParticipant(Project $project)
    {
        $participation = $this->participants->findOneBy(['project' => $project, 'name' => $this->context->getToken()->getUser()->getUsername()]);

        return !is_null($participation);
    }
}
