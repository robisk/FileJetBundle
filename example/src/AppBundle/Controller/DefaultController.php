<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $files = $entityManager->getRepository('AppBundle:TestFile')->findAll();

        return $this->render('AppBundle::homepage.html.twig', ['files' => $files]);
    }
}
