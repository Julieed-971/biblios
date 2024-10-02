<?php

namespace App\Controller\Admin;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

# Utilisation d'un préfixe pour éviter de le réécrire à chaque création de route
#[Route('/admin/author')]
class AuthorController extends AbstractController
{
    #[Route('', name: 'app_admin_author_index', methods: ['GET'])]
    public function index(Request $request, AuthorRepository $repository): Response
    {
        $authors = $repository->findAll();

        $dates = [];
        if ($request->query->has('start')) {
            $dates['start'] = $request->query->get('start');
        }
        
        if ($request->query->has('end')) {
            $dates['end'] = $request->query->get('end');
        }
        
        $authors = Pagerfanta::createForCurrentPageWithMaxPerPage(
            new QueryAdapter($repository->findByDateOfBirth()),
            $request->query->get('page', 1),
            10
        );
        return $this->render('admin/author/index.html.twig', [
            'controller_name' => 'AuthorController',
            'authors' => $authors,
        ]);
    }

    #[Route('/new', name: 'app_admin_author_new', methods: ['GET', 'POST'])]
    #[Route('/{id}/edit', name: 'app_admin_author_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        # Création d'une instance de Author si  $author  vaut  null  , on lui assignera comme valeur une nouvelle instance de  Author
        $author ??= new Author();
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

            return $this->redirectToRoute('app_admin_author_show', ['id' => $author->getId()]);
        }

        # Le formulaire est passé au template
        return $this->render('admin/author/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_author_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(?Author $author): Response
    {
        return $this->render('admin/author/show.html.twig', [
            'author' => $author,
        ]);
    }
}
