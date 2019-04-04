<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use App\Entity\Trick;
use App\Entity\Discussion;

/**
 * @Route("/api", name="api")
 */
class ApiController extends AbstractController
{
    /**
     * @Route("/message/{trickid}/{page}", name="api_conversation")
     */
    public function getConversation($trickid, $page = null)
    {
        $repositoryComment = $this->getDoctrine()->getRepository(Discussion::class);
        if (null === $page) {
            $commentsList = $repositoryComment->findWithPagination($trickid, 1);
        } else {
            $commentsList = $repositoryComment->findWithPagination($trickid, $page);
        }

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        // Serialize your object in Json
        $jsonObject = $serializer->serialize($commentsList, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            },
        ]);

        return $this->json($jsonObject);
    }

    /**
     * @Route("/trick/{page}", name="api_trick")
     */
    public function getTricks($page = null)
    {
        $repositoryTrick = $this->getDoctrine()->getRepository(Trick::class);
        if (null === $page) {
            $trickList = $repositoryTrick->findWithPagination(1);
        } else {
            $trickList = $repositoryTrick->findWithPagination($page);
        }

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        // Serialize your object in Json
        $jsonObject = $serializer->serialize($trickList, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            },
        ]);

        return $this->json($jsonObject);
    }
}
