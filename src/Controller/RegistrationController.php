<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\AccountValidation;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setValid(false);
            $user->setAvatar('default.png');
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $token = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');

            $accountValidation = new AccountValidation();
            $accountValidation->setUser($user);
            $accountValidation->setToken($token);
            $entityManager->persist($accountValidation);
            $entityManager->flush();
            $message = (new \Swift_Message("Comfirmation d'inscription"))
                        ->setFrom('dev@survive-in-hell.fr')
                        ->setTo($user->getEmail())
                        ->setBody(
                            $this->renderView(
                                'email/linkValidation.html.twig',
                                ['token' => $token]
                            ),
                            'text/html'
                        )
                    ;

            $mailer->send($message);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
