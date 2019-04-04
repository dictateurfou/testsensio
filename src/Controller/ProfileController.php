<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\UserAvatarType;
use App\Form\UserChangePasswordType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/** @Route("/profile", name="profile_") */
class ProfileController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $form = $this->createForm(UserAvatarType::class, $user, [
            'action' => $this->generateUrl('profile_edit_avatar'),
        ]);

        $formChangePassword = $this->createForm(UserChangePasswordType::class, $user, [
            'action' => $this->generateUrl('profile_edit_pass'),
        ]);

        return $this->render('profile/index.html.twig', ['form' => $form->createView(), 'form_password' => $formChangePassword->createView()]);
    }

    /**
     * @Route("/edit", name="edit_avatar")
     */
    public function editAvatar(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $form = $this->createForm(UserAvatarType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $test = $form->get('uploadedAvatar')->getData();
            $em->persist($user);
            $em->flush();
            $this->addFlash('notif', 'Your changes were saved!');
        }

        return $this->redirectToRoute('profile_index');
    }

    /**
     * @Route("/passwordedit", name="edit_pass")
     */
    public function editPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $form = $this->createForm(UserChangePasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $actualPassword = $passwordEncoder->isPasswordValid($user, $form->get('plainLastPassword')->getData());
            $newPassword = $passwordEncoder->encodePassword(
                $user,
                $form->get('plainNewPassword')->getData()
            );

            if (true === $actualPassword) {
                $user->setPassword($newPassword);
                $em->persist($user);
                $em->flush();
                $this->addFlash("notification", json_encode(["message" => "Your changes were saved!","type" => "info"]));
            } else {
                $this->addFlash("notification", json_encode(["message" => "Your password is incorrect","type" => "error"]));
            }
        }

        return $this->redirectToRoute('profile_index');
    }
}
