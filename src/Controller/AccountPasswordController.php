<?php

namespace App\Controller;


use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    #[Route('/account/password-change', name: 'app_account_password')]
    public function index(Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);

        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $old_pwd = $form->get('old_password')->getData(); // get la data de l'input password
            if ($encoder->isPasswordValid($user, $old_pwd)){
                $password = $encoder->hashPassword($user, $form->get('new_password')->getData());
                $user->setPassword($password);

                // dans le cas d'une mise a jour / update pas besoin de la method persist (qui sert a préparer la data). On peut flush directement
                $this->entityManager->flush();

            }
        }


        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
