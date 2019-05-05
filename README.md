# Test Technique

Gestion de contacts pour un utilisateur, basé sur un framework maison, construit avec Docker, Mysql et Php.

## Getting Started

Ces instructions vont vous permettre de recuperer les sources, deployer le projet, et de le tester localement sur votre machine.

### Prerequisites

Pour utiliser ce project, vous avez besoin de :

```
 * Docker https://www.docker.com/
 * Docker Compose https://docs.docker.com/compose/
```

### Installation

Cloner le repository
```
git clone git@github.com:testTech20190504/testTech.git
```

Constuire les containers Docker (php7.2.12 / mysql 8.0.13)
```
docker-compose build
```

Demarrer la Stack Docker
```
docker-compose up
```

Utiliser Composer pour recuperer les dependances
```
testTechnique(master)$ composer install
```

## Running Application

L'application de développement est prévu pour utiliser l'alias http://leboncoin.local, veuillez penser à l'ajouter dans votre fichier `/etc/hosts`

Il y a 2 utilisateurs de tests en BDD, vous pouvez utiliser `test` / `test` pour tester l'application.

Il n'y a que la partie contacts qui est fonctionnelle, le reste est en cours de développement.

## DB

En cas d'erreur lors de l'initialisation du container mysql, vous devrez vous connecter au container mysql et inserer le schéma SQL à la main:

```
docker ps (recuperrez l'id du container mysql)
docker exec -it #mysql_container_id /bin/bash
mysql -u root -p ( testtech )
mysql> use testtech;
```

Le schéma SQL se trouve dans `etc/mysql/upgrade.sql`

## Todo

 * Implementer les tests unitaires pour le controller Contact
 * Implementer le CRUD pour le modele Adresse
 * Optimisation SQL pour la partie recherche
 * Améliorer le routeur pour une gestion plus fine des erreurs
