<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Participant;
use AppBundle\Entity\Project;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ParticipantController
 *
 * @package AppBundle\Controller
 *
 * @Route("/participate")
 */
class ParticipantController extends Controller
{
    /**
     * @Route("/fund/{id}", name="fund")
     */
    public function fundAction(Request $request, Project $project)
    {
        if (!$this->get('app.participation_service')->isParticipant($project)) {
            $participant = new Participant();
            $participant
                ->setName($this->getUsername())
                ->setProject($project);
            $em = $this->getDoctrine()->getManager();
            $em->persist($participant);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('project_show', [$project]));
    }

    /**
     * @param Request $request
     * @param Project $project
     *
     * @Route("/defund/{id}", name="defund")
     */
    public function defundAction(Request $request, Project $project)
    {
        $participant = $this->get('app.participation_service')->getParticipant($project);
        if (!empty($participant)) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($participant);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('project_show', [$project]));
    }

    /**
     * @return mixed
     */
    protected function getUsername()
    {
        $storage = $this->get('security.token_storage');

        return $storage->getToken()->getUser()->getUsername();
    }
}