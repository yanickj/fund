<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('AppBundle:Project')->createQueryBuilder('p');
        $qb->orderBy('expirationDate', 'ASC');
        $projects = $this->get('knp_paginator')->paginate($qb, $request->query->getInt('page', 1), 12);

        return $this->render('project/index.html.twig', array(
            'projects' => $projects,
        ));
    }

    /**
     * @param Project $project
     *
     * @return bool
     */
    protected function isFunded(Project $project)
    {
        $em = $this->getDoctrine()->getManager();
        $finder = $em->getRepository('AppBundle:Participant');
        $usr = $this->get('security.context')->getToken()->getUser();
        $participant = $finder->findOneBy(['name' => $usr->getUsername(), 'project' => $project]);

        return is_null($participant);
    }
}
