<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'auth.register')]
    public function register(Request $request, EntityManagerInterface $entityManager,UserPasswordHasherInterface $userPasswordHasher, ValidatorInterface $validator): Response
    {

        $user = new User(); 
        $form = $this->createForm(RegistrationFormType:: class, $user);
        $form->handleRequest($request);
        $errors = $validator->validate($user);

        if ($form->isSubmitted() && $form->isValid() && $errors) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );


            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app.home');
        }

        // Render the 'hello.html.twig' template
        return $this->render('auth/register.html.twig', [
            'registerForm' => $form,
            'error' => $errors,
        ]);
    }
}
