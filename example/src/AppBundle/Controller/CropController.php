<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TestFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CropController extends Controller
{
    /**
     * @Route("/crop/{id}", name="single-crop")
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cropAction($id)
    {
        $file = $this->findFile($id);
        $urlPatterns = $this->get('everlution.file.url_provider')->downloadUrlPatterns($file->getFileStorageName());

        return $this->render('AppBundle::single-crop.html.twig', [
            'file' => $file,
            'urlPatterns' => $urlPatterns
        ]);
    }

    /**
     * @Route("/crop-confirm/{id}", name="single-crop-confirm")
     *
     * @param int $id
     * @param Request $request
     * @return RedirectResponse
     */
    public function cropConfirmAction($id, Request $request)
    {
        $mutation = $request->getContent();

        $file = $this->findFile($id);

        $newFile = new TestFile();
        $newFile->setFile($file->getFileIdentifier(), $file->getFileStorageName(), $mutation);

        $this->getDoctrine()->getManager()->persist($newFile);
        $this->getDoctrine()->getManager()->flush();

        return new Response($mutation);
    }

    /**
     * @param int $id
     * @return TestFile
     */
    protected function findFile($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        return $entityManager->getRepository('AppBundle:TestFile')->find($id);
    }
}
