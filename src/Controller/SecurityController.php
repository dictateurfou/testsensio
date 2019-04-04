<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\PasswordRequest;
use App\Entity\AccountValidation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\ForgotPasswordType;
use App\Form\RequestPasswordType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        // controller can be blank: it will never be executed!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    /**
     * @Route("/accountValidation/{token}", name="app_valid_account")
     */
    public function validAccount(AccountValidation $accountValidation, $token)
    {
        if (!$accountValidation) {
            return $this->redirectToRoute('accueil');
        }
        $accountValidation->getUser()->setValid(true);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($accountValidation);
        $entityManager->remove($accountValidation);
        $entityManager->flush();

        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("/forgotPassword", name="app_forgot_password")
     */
    public function forgotPassword(Request $request, \Swift_Mailer $mailer)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
           
            $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findBy(['username' => $data["username"]]);
            //$data["username"]
            if (!$user) {
                $this->addFlash("notification", json_encode(["message" => "this user does not exist","type" => "error"]));
            } else {
                $user = $user[0];

                $checkPasswordRequest = $this->getDoctrine()
                ->getRepository(PasswordRequest::class)
                ->findBy(['user' => $user]);
                if (!$checkPasswordRequest) {
                    $token = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
                    $passwordRequest = new PasswordRequest();
                    $passwordRequest->setToken($token);
                    
                    $passwordRequest->setUser($user);
                    $em->persist($passwordRequest);
                    $em->flush();
                } else {
                    $token = $checkPasswordRequest[0]->getToken();
                }

                $message = (new \Swift_Message("Password request"))
                        ->setFrom('dev@survive-in-hell.fr')
                        ->setTo($user->getEmail())
                        ->setBody(
                            $this->renderView(
                                'email/forgotPassword.html.twig',
                                ['token' => $token]
                            ),
                            'text/html'
                        );
                $mailer->send($message);

                $this->addFlash("notification", json_encode(["message" => "an email has been sent to you","type" => "info"]));
            }
        }
        return $this->render('security/forgotPassword.html.twig', ["form" => $form->createView()]);
    }

    /**
    * @Route("/passwordRequest/{token}", name="app_password_request")
    */
    public function passwordRequest(Request $request, PasswordRequest $passwordRequest, UserPasswordEncoderInterface $passwordEncoder)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $passwordRequest->getUser();
        $form = $this->createForm(RequestPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $passwordEncoder->encodePassword(
                $user,
                $form->get('newPassword')->getData()
            );

            $user->setPassword($newPassword);
            $em->persist($user);
            
            $em->persist($passwordRequest);
            $em->remove($passwordRequest);
            $em->flush();
            $this->addFlash('notification', json_encode(["message" => "Your changes were saved!","type" => "info"]));
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/passwordRequest.html.twig', ["form" => $form->createView()]);
    }
}
