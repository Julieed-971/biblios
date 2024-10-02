<?php

namespace App\Controller\Admin;

use App\Entity\Editor;
use App\Form\EditorType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/editor')]
class EditorController extends AbstractController
{
    #[Route('', name: 'app_admin_editor_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin/editor/index.html.twig', [
            'controller_name' => 'EditorController',
        ]);
    }

    #[Route('/new', name: 'app_admin_editor_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        # Création d'une instance de Editor
        $editor = new Editor();
        # Création du formulaire avec le raccourci 'createForm' des controllers,
        # en passant le nom du FormType et l'instance de Editor
        $form = $this->createForm(EditorType::class, $editor);

        # Demander au formulaire de traiter la requête
        $form->handleRequest($request);

        # Vérifier que le formulaire est bien soumis et qu'il est valide
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($editor);
            $manager->flush();

            return $this->redirectToRoute('app_admin_editor_new');
        }

        # Le formulaire est passé au template
        return $this->render('admin/editor/new.html.twig', [
            'form' => $form,
        ]);
    }
}
