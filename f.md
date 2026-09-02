# Les fichiers `.env`

Documentation : à quoi sert un fichier `.env`, comment il fonctionne, comment le charger en PHP, et comment l'appliquer concrètement au projet `systeme-notation`.

---

## Sommaire

1. [Qu'est-ce qu'un fichier .env ?](#1-quest-ce-quun-fichier-env)
2. [Pourquoi l'utiliser : le problème qu'il résout](#2-pourquoi-lutiliser--le-problème-quil-résout)
3. [Syntaxe d'un fichier .env](#3-syntaxe-dun-fichier-env)
4. [Sécurité : la règle absolue](#4-sécurité--la-règle-absolue)
5. [Charger un .env en PHP — deux méthodes](#5-charger-un-env-en-php--deux-méthodes)
6. [Application concrète au projet `systeme-notation`](#6-application-concrète-au-projet-systeme-notation)
7. [Le fichier `.env.example`](#7-le-fichier-envexample)
8. [Distinguer les environnements (dev / test / prod)](#8-distinguer-les-environnements-dev--test--prod)
9. [Erreurs fréquentes](#9-erreurs-fréquentes)
10. [Récapitulatif](#10-récapitulatif)

---

## 1. Qu'est-ce qu'un fichier .env ?

Un fichier `.env` ("environment", environnement) est un simple fichier texte, placé à la racine d'un projet, qui contient des **paires clé=valeur** représentant la configuration de l'application :

```env
DB_HOST=localhost
DB_PORT=5432
DB_NAME=systeme_notation
DB_USER=postgres
DB_PASSWORD=motdepasse
```

Il ne contient **aucun code exécutable** — juste des valeurs. Ces valeurs sont ensuite lues par l'application au démarrage, et rendues disponibles au code PHP via des variables d'environnement.

**Terre-à-terre** : c'est la fiche de réglages de la machine sur laquelle tourne l'application, séparée du code lui-même. Le code dit *"je vais chercher DB_HOST"*, sans jamais savoir à l'avance si ce sera `localhost` (chez toi), `db` (dans un conteneur Docker), ou l'adresse d'un vrai serveur (en production).

---

## 2. Pourquoi l'utiliser : le problème qu'il résout

### Le problème : configuration codée en dur

Reprenons ton fichier `Database.php` actuel :

```php
class Database
{
    public static function getConnexion(): PDO
    {
        self::$connexion = new PDO(
            "pgsql:host=localhost;port=5432;dbname=boutique",
            "postgres",
            "motdepasse"
        );
        // ...
    }
}
```

Trois problèmes concrets :

1. **Sécurité** : le mot de passe de la base de données est écrit en clair dans un fichier PHP. Si ce fichier est versionné avec Git (ce qui arrive presque toujours par erreur), le mot de passe se retrouve **dans l'historique Git pour toujours** — même si on le supprime plus tard, il reste consultable dans les anciens commits.
2. **Portabilité** : le même fichier `Database.php` ne peut pas fonctionner tel quel chez toi, chez ton camarade de binôme, et sur le serveur de production — chacun a des identifiants différents. Il faudrait modifier le code à chaque changement de machine.
3. **Violation directe du cahier des charges du TP** : *"les informations de connexion ne doivent pas être écrites directement dans les classes"* — c'est une règle explicite du TP `systeme-notation`, et un fichier `.env` est **la solution standard** à cette exigence.

### La solution : séparer configuration et code

Avec un `.env`, `Database.php` ne contient plus aucune valeur sensible — juste la **logique** de connexion :

```php
class Database
{
    public static function getConnexion(): PDO
    {
        self::$connexion = new PDO(
            "pgsql:host=" . $_ENV['DB_HOST'] . ";port=" . $_ENV['DB_PORT'] . ";dbname=" . $_ENV['DB_NAME'],
            $_ENV['DB_USER'],
            $_ENV['DB_PASSWORD']
        );
        // ...
    }
}
```

Le code devient **identique sur toutes les machines** ; seul le contenu du `.env` (jamais versionné) change d'un environnement à l'autre.

Ce principe fait partie des [**12 facteurs**](https://12factor.net/fr/config) qu'une application moderne bien conçue est censée respecter : *"Store config in the environment"* (stocker la configuration dans l'environnement, pas dans le code).

---

## 3. Syntaxe d'un fichier .env

```env
# Ceci est un commentaire

# Base de données
DB_HOST=localhost
DB_PORT=5432
DB_NAME=systeme_notation
DB_USER=postgres
DB_PASSWORD=motdepasse

# Une valeur contenant des espaces doit être entre guillemets
APP_NAME="Système de notation universitaire"

# Booléens : convention courante (pas de vrai type booléen en .env)
APP_DEBUG=true

# URL
APP_URL=http://localhost:8000
```

Règles à respecter :

| Règle | Exemple |
|---|---|
| Une variable par ligne | `CLE=valeur` |
| Pas d'espace autour du `=` | `DB_HOST=localhost` ✅ / `DB_HOST = localhost` ❌ (souvent mal interprété) |
| Convention de nommage : MAJUSCULES + underscores | `DB_PASSWORD`, pas `dbPassword` |
| Guillemets si la valeur contient des espaces | `APP_NAME="Mon Application"` |
| `#` pour les commentaires | `# ceci est ignoré` |
| Aucune valeur = chaîne vide, pas `null` | `DB_PASSWORD=` |

---

## 4. Sécurité : la règle absolue

> **Un fichier `.env` ne doit JAMAIS être versionné avec Git.**

C'est pour ça que ton `.gitignore` (créé à l'étape d'initialisation du projet) contient déjà cette ligne :

```gitignore
/vendor/
.env
.DS_Store
```

**Pourquoi c'est aussi strict ?** Un `.env` contient typiquement des mots de passe, des clés d'API, des jetons secrets. Le versionner revient à publier ces secrets — et si le dépôt est un jour rendu public (ou simplement partagé avec plus de personnes que prévu), c'est une fuite de sécurité immédiate. Une fois un secret poussé sur Git, même en le supprimant ensuite, il reste **récupérable dans l'historique** des commits précédents, sauf réécriture complète de l'historique (`git filter-repo` / `BFG`) — une opération lourde à éviter.

**Vérifier que Git ignore bien le fichier** avant de committer quoi que ce soit :

```bash
git status
# .env ne doit JAMAIS apparaître dans la liste des fichiers trackés
```

Si `.env` a été committé par erreur avant l'ajout au `.gitignore`, `.gitignore` seul ne suffit pas (Git continue de suivre un fichier déjà tracké) :

```bash
git rm --cached .env
git commit -m "fix: retirer .env du suivi Git"
```

---

## 5. Charger un .env en PHP — deux méthodes

### Méthode 1 : sans dépendance (parsing manuel)

Utile pour un petit projet sans Composer, ou pour comprendre ce qui se passe réellement sous le capot :

```php
<?php

function chargerEnv(string $cheminFichier): void
{
    if (!file_exists($cheminFichier)) {
        throw new RuntimeException("Fichier .env introuvable : {$cheminFichier}");
    }

    $lignes = file($cheminFichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lignes as $ligne) {
        $ligne = trim($ligne);

        // ignore les commentaires
        if (str_starts_with($ligne, '#')) {
            continue;
        }

        // sépare CLE=valeur (seulement au premier "=")
        [$cle, $valeur] = array_pad(explode('=', $ligne, 2), 2, '');
        $cle = trim($cle);
        $valeur = trim($valeur, " \t\n\r\0\x0B\"'"); // retire espaces + guillemets

        if ($cle !== '') {
            $_ENV[$cle] = $valeur;
            putenv("{$cle}={$valeur}");
        }
    }
}

// Utilisation, tout en haut du point d'entrée (public/index.php) :
chargerEnv(dirname(__DIR__) . '/.env');

echo $_ENV['DB_HOST']; // "localhost"
```

### Méthode 2 : avec la bibliothèque `vlucas/phpdotenv` (recommandée pour un vrai projet)

C'est la bibliothèque standard de l'écosystème PHP pour cette tâche (utilisée par Laravel, Symfony et la plupart des frameworks modernes) :

```bash
composer require vlucas/phpdotenv
```

```php
<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

echo $_ENV['DB_HOST']; // "localhost"
```

**Avantages par rapport au parsing manuel** : gestion des erreurs plus robuste, validation des variables requises (`$dotenv->required(['DB_HOST', 'DB_PASSWORD'])->notEmpty()`), meilleure gestion des cas particuliers (valeurs multi-lignes, variables imbriquées, etc.).

---

## 6. Application concrète au projet `systeme-notation`

### Étape 1 — Créer le fichier `.env` à la racine (jamais committé)

```env
DB_HOST=localhost
DB_PORT=5432
DB_NAME=systeme_notation
DB_USER=postgres
DB_PASSWORD=motdepasse
```

### Étape 2 — Modifier `Database.php` pour lire les variables d'environnement

```php
<?php

class Database
{
    private static ?PDO $connexion = null;

    private function __construct()
    {
    }

    public static function getConnexion(): PDO
    {
        if (self::$connexion === null) {
            $dsn = sprintf(
                'pgsql:host=%s;port=%s;dbname=%s',
                $_ENV['DB_HOST'],
                $_ENV['DB_PORT'],
                $_ENV['DB_NAME']
            );

            self::$connexion = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);

            self::$connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$connexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }

        return self::$connexion;
    }
}
```

### Étape 3 — Charger le `.env` une seule fois, dans le point d'entrée unique (`public/index.php`)

C'est cohérent avec une autre règle du TP : *"toutes les requêtes HTTP doivent passer par un point d'entrée unique"*. C'est donc **le seul endroit** où le `.env` a besoin d'être chargé — tout le reste de l'application (Repository, Service, Controller) accède ensuite à `$_ENV[...]` normalement, sans jamais recharger le fichier.

```php
<?php
// public/index.php

require_once dirname(__DIR__) . '/src/core/EnvLoader.php';
require_once dirname(__DIR__) . '/src/core/Database.php';

EnvLoader::charger(dirname(__DIR__) . '/.env');

// ... reste de l'application
```

---

## 7. Le fichier `.env.example`

Puisque `.env` n'est jamais versionné, un camarade qui clone le dépôt ne sait pas quelles variables il doit définir. La convention est de committer un fichier **`.env.example`** (celui-ci, sans secrets réels) qui sert de modèle :

```env
# .env.example — à copier en .env et à compléter avec de vraies valeurs
DB_HOST=localhost
DB_PORT=5432
DB_NAME=
DB_USER=
DB_PASSWORD=
```

```bash
cp .env.example .env
# puis éditer .env avec les vraies valeurs
```

`.env.example` **doit** être versionné (il ne contient aucun secret) — seul `.env` reste ignoré.

---

## 8. Distinguer les environnements (dev / test / prod)

Un même projet tourne souvent sur plusieurs environnements, chacun avec sa propre base de données. La convention la plus courante :

| Fichier | Rôle | Versionné ? |
|---|---|---|
| `.env` | Config réelle utilisée localement | ❌ Jamais |
| `.env.example` | Modèle documentant les variables attendues | ✅ Oui |
| `.env.testing` | Config utilisée pendant les tests automatisés (souvent une base de données séparée, pour ne jamais toucher aux vraies données) | Dépend du projet |

Dans le contexte du TP (*"le traitement métier doit pouvoir être testé sans serveur web"*), avoir un `.env.testing` pointant vers une base de test permet de lancer les tests du `Service` sans jamais risquer d'altérer les données réelles.

---

## 9. Erreurs fréquentes

| Erreur | Conséquence | Correction |
|---|---|---|
| Committer `.env` par erreur avant de l'ajouter au `.gitignore` | Secret exposé dans l'historique Git | `git rm --cached .env` + rotation du mot de passe exposé |
| Espaces autour du `=` (`DB_HOST = localhost`) | Certains parseurs lisent la clé comme `"DB_HOST "` (avec espace) | Toujours `CLE=valeur`, sans espace |
| Oublier de charger le `.env` avant d'utiliser `$_ENV[...]` | `Undefined array key "DB_HOST"` | Charger le `.env` tout en haut du point d'entrée, avant tout autre traitement |
| Mettre des valeurs sensibles dans `.env.example` | Même problème que committer `.env` directement | `.env.example` ne doit contenir que des clés, pas de vraies valeurs |
| Utiliser `.env` en production tel quel | Risque si le fichier est accessible publiquement via une mauvaise configuration du serveur web | S'assurer que le `DocumentRoot` du serveur pointe vers `public/`, jamais vers la racine du projet — c'est justement pour ça que `.env` doit rester **au-dessus** de `public/`, jamais dedans |

---

## 10. Récapitulatif

- Un `.env` sépare la **configuration** (identifiants, URLs, clés) du **code** (logique de l'application).
- Il répond directement à l'exigence du TP : *"les informations de connexion ne doivent pas être écrites directement dans les classes"*.
- Il ne doit **jamais** être versionné avec Git — seul `.env.example` (sans secrets) l'est.
- Il se charge **une seule fois**, au point d'entrée unique de l'application, puis ses valeurs sont accessibles partout via `$_ENV[...]`.
- Il permet au même code de fonctionner sur toutes les machines (dev, binôme, production) sans aucune modification.