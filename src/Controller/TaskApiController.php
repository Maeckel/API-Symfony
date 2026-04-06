<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface; // Permet de communiquer avec la base de données.
use Symfony\Component\HttpFoundation\Response; // Pour gérer les réponses
use App\Entity\Task; // Récupération de la classe Tâche 
use Symfony\Component\HttpFoundation\Request; // Permet de lire ce que le client envoie

final class TaskApiController extends AbstractController {

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager) { // Récupération de l'entity manager
        $this->entityManager = $entityManager;
    }

    // Les routes sont configurées dans le fichier config/routes.yaml
    // Les méthodes ci-dessous peuvent être tester à l'aide de POSTMAN

    // Endpoint pour lister une tâche existante
    public function get($task): Response {

        $task = $this->entityManager->getRepository(Task::class)->findOneBy(['id'=>$task]); // Utilisation de findOneBy() pour récuperer la bonne tâche

        if($task === null){ // Si pas de tâches trouvées alors on alerte l'utilisateur
            return $this->json(['message'=>'Aucune tâche trouvée'],Response::HTTP_NOT_FOUND); // Envoi du message en format JSON
        }

        return $this->json($task, Response::HTTP_OK); // Envoi de la tâche en format JSON
    }

    // Endpoint pour lister les tâches existantes
    public function getAll(): Response {

        $tasks = $this->entityManager->getRepository(Task::class)->findAll(); // Utilisation de findAll() pour récuperer tout

        if($tasks === null){ // Si pas de tâches trouvées alors on alerte l'utilisateur
            return $this->json(['message'=>'Aucune tâche trouvée'],Response::HTTP_NOT_FOUND); // Envoi du message en format JSON
        }

        return $this->json($tasks, Response::HTTP_OK); // Envoi des tâches en format JSON
    }

    // Endpoint pour créer une tâche
    public function add(Request $request): Response {

        $data = json_decode($request->getContent(), true); // Récupération de la réponse du client

        if(empty($data['titre']) || empty($data['description']) || empty($data['statut'])){ // On vérifie si tout les champs ont été renseignés
            return $this->json(['message'=>'Tous les champs doivent être renseignés'],Response::HTTP_BAD_REQUEST); // Si ce n'est pas le cas alors on alerte l'utilisateur
        }

        $titre = $data['titre']; // Récuparation du titre
        $description = $data['description']; // Récuparation de la description
        $statut = $data['statut']; // Récuparation du statut

        $newTask = new Task(); // Création d'un nouveau objet Tâche
        $newTask->setTitle($titre)->setDescription($description)->setStatus($statut); // Affectation des données récupérées aux attrbibuts de l'objet Tâche

        $this->entityManager->persist($newTask); // Mise en attente pour persister l'objet en base de données
        $this->entityManager->flush(); // On déclenche l'écriture réelle en base de données

        return $this->json(['message'=>'Tâche créée', 'task' => $newTask],Response::HTTP_CREATED); // Envoi de la confirmation de l'enregistrement
    }

    // Endpoint pour mettre à jour une tâche (statut)
    public function update(Request $request, $task): Response {

        $data = json_decode($request->getContent(), true);  // Récupération de la réponse du client

        $taskObject = $this->entityManager->getRepository(Task::class)->findOneBy(['id'=>$task]);  // Utilisation de findOneBy() pour récuperer la bonne tâche

        if($taskObject === null){ // Si on trouve pas de tâche alors on alerte l'utilisateur
            throw $this->createNotFoundException(sprintf(
                'Pas de tâche trouvée "%s"',
                $task
            ));
        }

        // if($request->getMethod()==='PUT' and (empty($data['titre']) || empty($data['description']) || empty($data['statut']))){
        if(empty($data['statut'])) { // Si le champ statut n'est pas renseigné, on alerte l'utilisateur   
            return $this->json(['message'=>'Tous les champs doivent être renseignés'],Response::HTTP_BAD_REQUEST);
        }

        //if(!empty($data['titre'])) $taskObject->setTitle($data['titre']);
        //if(!empty($data['description'])) $taskObject->setDescription($data['description']);
        if(!empty($data['statut'])) $taskObject->setStatus($data['statut']); // Affectation du nouveau statut à l'objet Tâche si le champ est renseigné

        $this->entityManager->flush(); // On déclenche la modification en base de données

        return $this->json($taskObject,Response::HTTP_OK); //Confirmation de la modification à l'utilisateur
    }

    // Endpoint pour supprimer une tâche
    public function delete(Request $request, $task): Response {
        $taskObject = $this->entityManager->getRepository(Task::class)->findOneBy(['id'=>$task]); // Utilisation de findOneBy() pour récuperer la bonne tâche

        if($taskObject === null){ // Si on trouve pas de tâche alors on alerte l'utilisateur
            throw $this->createNotFoundException(sprintf(
                'Pas de tâche trouvée "%s"',
                $task
            ));
        }

        $this->entityManager->remove($taskObject); // Mise en attente pour supprimer la bonne ligne en base de données
        $this->entityManager->flush(); // On déclenche la suppression en base de données

        return $this->json(['message'=>'Tâche supprimée'],Response::HTTP_OK); // Envoi de la confirmation de la suppression
    }

}
