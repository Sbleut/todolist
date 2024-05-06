ToDoList
========

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/8421aa982ab14367af6699c767a78de1)](https://app.codacy.com/gh/Sbleut/todolist/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)

Base du projet #8 : Améliorez un projet existant

Améliorations :
 - Maître Mot Simplicité.
 - Navbar Correct avec multi accès.
 - Status des tâches (clarification terminée, non-terminée, Anonyme).
 - Dashboard des tâches plus claires.
 - Date de création de la tâches.
 - Date de fin sur une tâche.
 - UX Design de meilleure Facture.
 - Dictionnaire du site fr.

https://openclassrooms.com/projects/ameliorer-un-projet-existant-1

### Installation

1. Téléchargez directement le projet à partir du lien suivant ou utilisez la commande git clone :

git clone https://github.com/Sbleut/todolist.git

2. À la racine du projet, exécutez la commande suivante pour installer les dépendances via Composer :

composer install

### Configuration de la base de données

3. Modifiez le fichier .env situé à la racine du projet avec vos informations spécifiques à votre base de données. Voici un exemple de configuration :

DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7

4. Si vous avez le CLI Symfony, exécutez les commandes suivantes :

 - Créez la base de données avec la commande :
 ```
 symfony console doctrine:database:create
  ```
 - Lancez la migration avec la commande :
  ```
 symfony console doctrine:migrations:migrate
  ```
 - Ajoutez des données factices à la base de données avec la commande :
  ```
 symfony console doctrine:fixtures:load
  ```

Remarque : Si vous n'avez pas le client Symfony, remplacez symfony console par php bin/console (par exemple : php bin/console doctrine:database:create).

5. Lancez le serveur avec la commande suivante

symfony serve

6. Accédez à l'adresse de votre serveur web (par exemple : localhost:8000) pour accéder à l'application.