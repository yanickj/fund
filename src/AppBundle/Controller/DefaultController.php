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
        $filter = $this->createForm('AppBundle\Form\FilterType');
        $filter->handleRequest($request);
        if ($filter->isSubmitted() && $filter->isValid()) {
            $data = $filter->getData();
            var_dump($data);
        }
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('AppBundle:Project')->createQueryBuilder('p');
        $qb->orderBy('p.expirationDate', 'ASC');
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
