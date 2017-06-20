<?php

namespace ExampleBundle\Controller;

use ExampleBundle\Entity\TestFile;
use Everlution\FileJetBundle\Api\RequestFormatProvider\UploadRequest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class Api extends Controller
{

    /**
     * @Route("/unique-upload", name="unique-upload")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadToUniquePathAction(Request $request)
    {
        $requestData = json_decode($request->getContent(), true);
        $path = $requestData['path'];
        $contentType = $requestData['contentType'];

        $requestFormatProvider = $this->get('everlution.file.request_format_provider');
        $response = $requestFormatProvider->uniqueUpload(new UploadRequest(
            'default',
            $path,
            $contentType,
            new \DateTime('+ 48 hours')
        ));

        $file = new TestFile();
        $file->setFile($response->getIdentifier(), 'default');

        $this->getDoctrine()->getManager()->persist($file);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse($response->getRequestFormat());
    }

    /**
     * @Route("/delete/{id}", name="delete")
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function deleteAction($id)
    {
        $file = $this->getDoctrine()->getRepository('ExampleBundle:TestFile')->find($id);

        $fileManagement = $this->get('everlution.file.management');
        $fileManagement->deleteFile($file);

        $this->getDoctrine()->getManager()->remove($file);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('homepage');
    }

}
