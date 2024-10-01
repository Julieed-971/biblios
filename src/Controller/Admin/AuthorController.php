<?php

namespace App\Controller\Admin;

use App\Entity\Author;
use App\Form\AuthorType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

# Utilisation d'un préfixe pour éviter de le réécrire à chaque création de route
#[Route('/admin/author')]
class AuthorController extends AbstractController
{
    #[Route('', name: 'app_admin_author_index')]
    public function index(): Response
    {
        return $this->render('admin/author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/new', name: 'app_admin_author_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        # Création d'une instance de Author
        $author = new Author();
        # Création du formulaire grâce au raccourci 'createForm' des controllers,
        # en passant le nom du FormType et l'instance de Author
        $form = $this->createForm(AuthorType::class, $author);

        # Demander au formulaire de traiter la requête
        $form->handleRequest($request);

        # Vérifier que le formulaire a bien été soumis et qu'il est valide
        if ($form->isSubmitted() && $form->isValid()) {
            # Indiquer au manager de se préparer à sauvegarder les infos en bdd
            $manager->persist($author);
            # Indique au manager de terminer les modification et de les effectuer en bdd
            $manager->flush();

            return $this->redirectToRoute('app_admin_author_new');
        }

        # Le formulaire est passé au template
        return $this->render('admin/author/new.html.twig', [
            'form' => $form,
        ]);
    }
}
