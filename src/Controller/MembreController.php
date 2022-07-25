<?php

namespace App\Controller;

use DateTime;
use App\Entity\Membre;
use App\Form\RegisterFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class MembreController extends AbstractController
{
    /**
     * @Route("/register", name="register_membre", methods={"GET|POST"})
     */
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $membre = new Membre();

        $form = $this->createForm(RegisterFormType::class, $membre)
            ->handleRequest($request);

        if($form->isSubmitted()  && $form->isValid()){
            $membre->setRoles(['ROLE_USER']);
            $membre->setCreatedAt(new DateTime());
            $membre->setUpdatedAt(new DateTime());
        }

        // $membre->setPassword($passwordHasher->hashPassword($membre, $membre->getPassword()));

        $entityManager->persist($membre);
        $entityManager->flush();

        $this->addFlash('success', 'Vous vous êtes inscrit avec succès !');
        return $this->redirectToRoute('app_login');

    # 3 - On retourne la vue du formulaire
        return $this->render("register.html.twig", [
            'form' => $form->createView()
        ]);
    }

}