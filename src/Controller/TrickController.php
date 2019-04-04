<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Trick;
use App\Entity\Image;
use App\Entity\Discussion;
use App\Form\TrickType;
use App\Form\DiscussionType;
use App\Service\VideoLinkValidator;

/** @Route("/trick", name="trick_") */
class TrickController extends AbstractController
{
    /**
     * Page d'info d'un trick.
     *
     * @Route("/view/{trickId}", name="view")
     */
    public function showTrick(Request $request, $trickId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $trick = $this->getDoctrine()
        ->getRepository(Trick::class)
        ->find($trickId);

        $comment = new Discussion();
        if (!$trick) {
            return $this->redirectToRoute('accueil');
        }

        $form = $this->createForm(DiscussionType::class, $comment);
        $form->handleRequest($request);
        $user = $this->getUser();

        $repositoryComment = $this->getDoctrine()->getRepository(Discussion::class);

        if ($form->isSubmitted() && $form->isValid() && null !== $user) {
            $comment->setAuthor($user);
            $comment->setTrick($trick);

            $entityManager->persist($comment);
            $entityManager->flush();
        }

        return $this->render('trick.html.twig', ['trick' => $trick, 'form' => $form->createView()]);
    }

    /**
     * Page d'info d'un trick.
     *
     * @Route("/remove/{trick}", name="remove")
     */
    public function removeTrick(Request $request, Trick $trick)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($trick);
        $em->flush();

        return $this->redirectToRoute('accueil');
    }

    /**
     * Page d'ajout d'un trick.
     *
     * @Route("/add", name="add")
     */
    public function addTrick(Request $request, VideoLinkValidator $videoLinkvalidator)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $this->getDoctrine()->getManager();
        $trick = new Trick();
        $date = new \DateTime();
        $date->format('Y-m-d H:i:s');
        $image = new Image();
        $trick->addImageList($image);
        $trick->setEditedAt($date);
        $trick->setCreatedAt($date);
        $trick->setVideoList(array(''));
        $trick->setEdited('no');
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $trick->getImageList();
            $trick->setVideoList($videoLinkvalidator->checkUrl($trick->getVideoList()));

            //check upload
            $i = 0;
            while ($i < count($trick->getImageList())) {
                if (null === $trick->getImageList()[$i]->getName()) {
                    $trick->removeImageList($trick->getImageList()[$i]);
                    $this->addFlash("notification", json_encode(["message" => "an error occuring when upload file , format accepted png and jpeg","type" => "error"]));
                    return $this->redirectToRoute('accueil');
                }
                $i++;
            }

            $entityManager->persist($trick);
            $entityManager->flush();
            $form = $this->createForm(TrickType::class, $trick);
            $this->addFlash("notification", json_encode(["message" => "your trick has been added","type" => "info"]));
            return $this->redirectToRoute('accueil');
        }

        return $this->render('trick/add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Page d'ajout d'un trick.
     *
     * @Route("/edit/{trick}", name="edit")
     */
    public function editTrick(Request $request, VideoLinkValidator $videoLinkvalidator, Trick $trick)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setVideoList($videoLinkvalidator->checkUrl($trick->getVideoList()));
            $entityManager->persist($trick);
            $trick->setEdited('yes');
            $date = new \DateTime();
            $date->format('Y-m-d H:i:s');
            $trick->setEditedAt($date);
            $entityManager->flush();
            $form = $this->createForm(TrickType::class, $trick);
        }

        return $this->render('trick/edit.html.twig', ['form' => $form->createView(), 'trick' => $trick]);
    }
}
