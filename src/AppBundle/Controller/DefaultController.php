<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use Doctrine\ORM\Query\Expr\Join;
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
        $qb->orderBy('p.expirationDate', 'ASC');

        $filter = $this->createForm('AppBundle\Form\FilterType');
        $filter->handleRequest($request);
        if ($filter->isSubmitted() && $filter->isValid()) {
            $data = $filter->getData();
            if ($data['filter'] == 'me') {
                $qb->innerJoin(
                    'AppBundle:Participant',
                    'participant',
                    Join::WITH,
                    'participant.project = p and participant.name = :name'
                )->setParameter('name', $this->get('security.token_storage')->getToken()->getUsername());
            }
        }
        $projects = $this->get('knp_paginator')->paginate($qb, $request->query->getInt('page', 1), 2);

        return $this->render('project/index.html.twig', array(
            'projects' => $projects,
            'filter' => $filter->createView(),
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

    private function createFilterForm(Request $request)
    {
        return $this->createForm('AppBundle\Form\FilterType');
    }
}
