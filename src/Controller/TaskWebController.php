<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TaskWebController extends AbstractController {

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager) { // Récupération de l'entity manager
        $this->entityManager = $entityManager;
    }

    // Récupération et ajout des tâches
     public function index(Request $request): Response {
        if ($request->isMethod('POST')) { // Si tentative d'ajout
            $task = new Task(); // Création d'un nouveau objet avec les valeurs récupérées
            $task ->setTitle($request->request->get('titre'))
                  ->setDescription($request->request->get('description'))
                  ->setStatus($request->request->get('statut'));

            $this->entityManager->persist($task); // Mise en attente pour persister l'objet en base de données
            $this->entityManager->flush(); // On déclenche l'écriture réelle en base de données

            return $this->redirectToRoute('app_task'); // Redirection vers l'interface web
        }

        $tasks = $this->entityManager->getRepository(Task::class)->findAll(); // Récupération des tâches
        return $this->render('task/index.html.twig', ['tasks' => $tasks]); // Envoi des données sur le twig
    }

    // Modification du statut
    public function update(Request $request, int $id): Response {

        $task = $this->entityManager->getRepository(Task::class)->find($id); // Récupération de la tâche

        if ($task == null) { // Avertissement si non trouvée
            throw $this->createNotFoundException("Tâche introuvable.");
        }

        $statut = $request->request->get('statut'); // Récupération du nouveau statut

        if ($statut != null) { 
            // Enregistrement en BDD
            $task->setStatus($statut);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_task');
    }

    // Suppression de la tâche
    public function delete(int $id): Response {

        $task = $this->entityManager->getRepository(Task::class)->find($id); // Récupération de la tâche

        if ($task != null) {
            // Suppression de la tâche
            $this->entityManager->remove($task); 
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_task');
    }
}
