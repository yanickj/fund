<?php
/**
 * Created by PhpStorm.
 * User: alexandramills
 * Date: 4/7/16
 * Time: 10:20 PM
 */

namespace AppBundle\Twig;


use AppBundle\Entity\Project;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class AppExtension extends \Twig_Extension
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

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('isParticipant', [$this, 'isParticipant'])
        ];
    }

    public function getName()
    {
        return 'app_extension';
    }
}