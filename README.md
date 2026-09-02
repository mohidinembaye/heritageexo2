Partie 1:
REPONSE QUESTION 1:
LE DOSSIER VENDOR NE PEUT PAS ETRE VERSIONNE CAR IL CONSERVE LA MEME STRUCTURE;

REPONSE QUESTION 2:
LA DIFFERENCE ENTRE UN COMMIT ET UN TAG UN COMMIT PEUT ETRE UNE CORRECTION UNE PARTIE DU CODE ALORS QUN TAG EST UNE VERSION STABLE ET UTILISABLE DE LAPPLICATION;

REPONSE QUESTION 3:
LA BRANCHE MAIN DOIT RESTER STABLE CAR CEST ELLE QUI DOIT CONTENIR LE CODE DE DEPLOIEMENT FINAL;

Partie 2 :
REPONSE QUESTION 1:
POUR QUON NE PUISSE PAS ACCEDER AU CODE VIA LE NAVIGATEUR SEUL LES FICHIERS CE DOSSIER EST ACCESSIBLE ;

REPONSE QUESTION 2:
POUR QUESTION DE SECURITE INDEX JOUE LE ROLE DE FRONTCONTROLLER POUR LE PROGRAMME TOUT DOIT PASSER PAR LUI;

REPONSE QUESTION 3:
LES ELEMENTS QUI NE DOIVENT PAS ETRE DANS PUBLIC:
CONFIG,DATABASE,SERVICE,REPOSITORY,CONTROLLER,ROUTER;

REPONSE QUESTION 4:
CONFIG:CONFIGURATION 
  database.php:gere les donnees de connexion a la base de donne;
  router.php:

partie 3:

REPONSE QUESTION 1:

  La classe Database  a pour seule responsabilité d'instancier et de fournir l'objet PDO.

  REPONSE QUESTION 2:
Non. On crée une seule connexion par requête HTTP que l'on réutilise pour toutes les requêtes SQL,

  REPONSE QUESTION 3:

Dans un fichier de configuration isolé situé en dehors du dossier public/ et exclu de Git via .gitignore.

REPONSE QUESTION 4:


Sécurité : Il protège contre les injections SQL grâce aux requêtes préparées.



Gestion des erreurs : Il transforme les erreurs SQL en exceptions PHP faciles à attraper.

## Création de la base de données

Le projet utilise PostgreSQL. Les paramètres de connexion sont définis dans un fichier `.env` local, qui est ignoré par Git pour ne pas publier les identifiants.

### Initialiser la base

Créer la base `exo01`, puis exécuter le schéma fourni :

```bash
createdb exo01
psql -d exo01 -f database/schema.sql
```

Si PostgreSQL utilise un hôte, un port ou un utilisateur spécifiques :

```bash
psql -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USER" -d "$DB_NAME" -f database/schema.sql
```

Le fichier [database/schema.sql](database/schema.sql) :

- crée la table `copies_examen` ;
- impose les champs obligatoires `id`, `date_depot` et `date_limite` ;
- limite `note_brute` et `note_finale` à une valeur comprise entre 0 et 20 ;
- ajoute une copie d’exemple et affiche les données avec une requête `SELECT`.

### Connexion depuis PHP

Après installation des dépendances avec Composer, le point d’entrée [public/index.php](public/index.php) charge la configuration PDO située dans [config/Database.php](config/Database.php). La connexion utilise les exceptions PDO et le mode de récupération associatif.

```bash
composer install
php -S localhost:8000 -t public
```

Ne committez jamais le fichier `.env` ; utilisez un fichier d’exemple sans mot de passe pour documenter les variables nécessaires.
