<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\FormLoginAuthenticator;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $em,
        UserAuthenticatorInterface $authenticator,
        FormLoginAuthenticator $formLoginAuthenticator,
    ): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $error = null;

        if ($request->isMethod('POST')) {
            $email    = trim($request->request->get('email', ''));
            $username = trim($request->request->get('username', ''));
            $phone    = trim($request->request->get('phone', ''));
            $pwd      = $request->request->get('password', '');
            $pwd2     = $request->request->get('password2', '');

            if ($pwd !== $pwd2) {
                $error = 'Passwords do not match.';
            } elseif ($em->getRepository(User::class)->find($email)) {
                $error = 'An account with this email already exists.';
            } else {
                $user = new User($email);
                $user->setUsername($username)
                     ->setPhone($phone)
                     ->setPassword($hasher->hashPassword($user, $pwd));

                $em->persist($user);
                $em->flush();

                return $authenticator->authenticateUser($user, $formLoginAuthenticator, $request)
                    ?? $this->redirectToRoute('app_home');
            }
        }

        return $this->render('registration/register.html.twig', ['error' => $error]);
    }
}
