# API Symfony — Gestionnaire de tâches

API REST développée avec Symfony permettant de gérer une liste de tâches. Inclut une interface web et une documentation interactive via API Platform.

---

## Technologies

- PHP 8.2
- Symfony 7
- Doctrine ORM
- MariaDB / MySQL
- API Platform
- Twig (interface web)

---

## Installation

### Prérequis

- PHP 8.2+
- Composer
- MariaDB ou MySQL

### Étapes

```bash
# Cloner le projet
git clone https://github.com/Maeckel/API-Symfony.git
cd API-Symfony

# Installer les dépendances
composer install
composer require orm
composer require --dev maker-bundle
composer req symfony/serializer
composer req symfony/property-access

# Configurer la base de données dans .env
DATABASE_URL="mysql://user:password@127.0.0.1:3306/edreams_db?serverVersion=10.11.2-MariaDB&charset=utf8mb4"

# Créer la base de données
bin/console doctrine:database:create
bin/console make:migration
bin/console doctrine:migrations:migrate

# Démarrer le serveur
symfony server:start

# Stopper le serveur
symfony server:stop
```

---
## Exercice 1
### Endpoints API

> Pour les endpoints nous utilisons **l'entité Task**, son **repository** et le controller **TaskController.php**.
> Les routes sont configurées dans le fichier **config/routes.yaml**.
> Elles sont testables via **POSTMAN**.

| Méthode | Route | Description |
|--------|-------|-------------|
| `GET` | `/api/tasks` | Lister toutes les tâches |
| `POST` | `/api/task` | Créer une tâche |
| `PUT` | `/api/task/{id}` | Modifier le statut (tous les champs requis) |
| `DELETE` | `/api/task/{id}` | Supprimer une tâche |

### Corps de la requête (POST)

```json
{
    "titre": "Mise en place du login",
    "description": "Implémenter l'authentification",
    "statut": "En attente"
}
```

---

### Corps de la requête (PUT)

```json
{
    "statut": "En Cours"
}
```

---

## Exercice 2
### Question de réflexion — Gestion des permissions

Pour gérer les permissions dans une application de gestion, j'utiliserais un système de rôles :

- Chaque utilisateur se voit attribuer un **rôle** (`ADMIN`, `USER`, `SUPER_ADMIN`)
- Chaque rôle définit un ensemble de **permissions** sur les ressources (lire, créer, modifier, supprimer)
- Par exemple : lire, créer, modifier, supprimer une tâche pour `ADMIN`, `SUPER_ADMIN` et lire, modifier une tâche pour `USER` (seulement celles qui lui s'on assignées)

## Exercice 3
### Déploiement

L'API est déployée en ligne sur Railway :

**URL** : ``

---

## Exercice 4
### Interface web

Une interface web est disponible sur `http://127.0.0.1:8000/tasks` permettant de :

- Lister les tâches existantes
- Ajouter une nouvelle tâche
- Modifier le statut d'une tâche
- Supprimer une tâche

> Le twig utilisé est **templates/task/index.html.twig**.

---

## API Platform

API Platform génère automatiquement une interface web accessible sur `/api`.

---
