<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    #[Route('/register', name: 'app_register')]
    public function index(Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $user = $form->getData(); // recupere les data du form

            $password = $encoder->hashPassword($user, $user->getPassword()) ; 
            // on utilise la method hashPassword de UserPasswordHasherInterface pour crypter le mot de passe
            $user->setPassword($password); // on set le password du nouveau user

            $this->entityManager->persist($user); // creation de la requete pour persister le new user
            $this->entityManager->flush(); // flush les requetes
        }

        return $this->render('register/index.html.twig',[
            'user' => $user,
            'form' => $form->createView()
        ]);
    }
}
