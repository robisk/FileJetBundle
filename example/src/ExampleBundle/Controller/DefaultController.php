<?php

namespace ExampleBundle\Controller;

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
        $files = $entityManager->getRepository('ExampleBundle:TestFile')->findAll();

        return $this->render('ExampleBundle::homepage.html.twig', ['files' => $files]);
    }
}
