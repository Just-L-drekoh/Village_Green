<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Service\JWTService;
use App\Service\SendEmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    //Inscription
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, 
    EntityManagerInterface $entityManager,
    JWTService $jwt, SendEmailService $mail): Response
    {

        $ref = mt_rand(10000, 99999);
        $userRepository = $entityManager->getRepository(User::class);
        $existingUser = $userRepository->findOneBy(['userRef' => $ref]);
        
        if ($existingUser !== null) {
            
            $ref = mt_rand(10000, 99999);
            
            $this->addFlash('error', 'Un utilisateur avec le même ref existe déjà');
            return $this->redirectToRoute('app_register');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

           
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            $user->setRoles(['ROLE_USER']);
            $user->setUserRef("Cli:{$ref}");
            $user->setCoef('1');
            $user->setVerified(false);
            $user->setUserLastConn(new \DateTimeImmutable());
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email
            
            //Generation du token 

            //Header
            $header = [
                'typ'=> 'JWT',
                'alg'=> 'HS256'
            ];

            //Payload
            $payload = [
                'user_id'=> $user->getId()
            ];

            //On Genere le token
            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

            // Envoyer l'E-mail

            $mail->send(
                'no-reply@Village-green.fr',
                $user->getEmail(),
                'Activation de votre compte sur le Site Village_Green',
                'register',
                compact('user', 'token') //['user => $user, 'token' => $token]

            );

            $this->addFlash('success', 'Vous avez reçu un email pour activer votre compte');
            return $this->redirectToRoute('app_index');
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    //Activation du compte par token 
    public function verifUser($token, JWTService $jwt, 
    UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        //on verifie que le token est correct(correctement formé, pas expiré)

        if($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $this->getParameter('app.jwtsecret'))){

            $payload = $jwt->getPayload($token);
            
            //On cherche le user qui correspond au payload

            $user = $userRepository->find($payload['user_id']);

            if($user && !$user->isVerified()){

                $user->setVerified(true);
                $entityManager->flush();

                $this->addFlash('success', 'Votre compte est maintenant activé');
                return $this->redirectToRoute('app_index');
            }
            $this->addFlash('error', 'Le token est incorrect ou expiré');
            return $this->redirectToRoute('app_register');
        }
}

//Renvoi de l'email pour l'activation du compte
public function resendVerification(
    JWTService $jwt, 
    SendEmailService $mail, 
    EntityManagerInterface $entityManager
): Response {
    $user = $this->getUser();

    if (!$user instanceof User) {
        $this->addFlash('error', "L'utilisateur n'est pas connecté ou introuvable.");
        return $this->redirectToRoute('app_login');
    }

    if ($user->isVerified()) {
        $this->addFlash('info', "Votre compte est déjà activé.");
        return $this->redirectToRoute('app_profile');
    }

    // Génération d'un nouveau token
    $header = ['typ' => 'JWT', 'alg' => 'HS256'];
    $payload = ['user_id' => $user->getId()];
    $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

    // Envoi de l'e-mail
    $mail->send(
        'no-reply@Village-green.fr',
        $user->getEmail(),
        'Réactivation de votre compte sur le site Village_Green',
        'register',
        compact('user', 'token')
    );

    $this->addFlash('success', "Un nouvel e-mail de vérification a été envoyé.");
    return $this->redirectToRoute('app_profil');
}
}



