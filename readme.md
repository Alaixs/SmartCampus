# Stack de développement Symfony de la SAE3

--- 
Contenu : 
- [Prérequis](#prérequis)
  - [1. Extensions Visual Studio Code](#1-extensions-visual-studio-code)
- [Démarrage](#démarrage)
  - [1. Cloner la stack du projet](#1-cloner-la-stack-du-projet)
  - [2. Démarrer la stack du projet](#2-démarrer-la-stack-du-projet)
  - [3. Les commandes utiles](#3-les-commandes-utiles)
  - [4. Connexion à la base de données](#4-connexion-à-la-base-de-données)
    - [Sous linux](#sous-linux)
    - [Sous Mac OS](#sous-mac-os)
  - [5. Récupération des tables de la base de données](#5-récupération-des-tables-de-la-base-de-données)
  - [6. Accéder au site en local](#6-accéder-au-site-en-local)
- [Partager le projet](#partager-le-projet)

--- 

## Prérequis

Sur votre machine Linux ou Mac :

- Docker 24 ou +
- Docker Engine sous Linux
- Docker Desktop sous Mac
- PHPStorm ou [Visual Studio Code](#extensions-visual-studio-code)  

De manière optionnelle, mais fortement recommandée :

- Une [clef SSH](https://forge.iut-larochelle.fr/help/ssh/index#generate-an-ssh-key-pair) active sur votre machine
  (perso) et [ajoutée dans votre compte gitlab](https://forge.iut-larochelle.fr/help/ssh/index#add-an-ssh-key-to-your-gitlab-account) :  
  elle vous permettra de ne pas taper votre mot de passe en permanence.

### 1. Extensions visual studio code
- MySQL de Weijan Chen
- Symfony code snippets

## Démarrage

### 1. Cloner la stack du projet 

Cloner le projet git :
```
git clone 'lien SSH'
```

### 2. Démarrer la stack du projet 

Dans un terminal positionné dans le dossier de la stack du projet : 

- démarrer la stack    
```
docker compose up --build -d
```

- inspecter l'état des services 
```
docker compose ps
```

### 3. Les commandes utiles

Dans un terminal positionné dans le dossier de la stack :

j'ai touché ton readme je t'attends daddy 🫦

- se positionner dans le conteneur `sfapp` :
```
docker compose exec sfapp bash
```

- se positionner dans le dossier `/app/sfapp` :
```
cd sfapp
```

- installer tous les composants nécessaires au fonctionnement du projet symfony :
```
composer install
```

### 4. Connexion à la base de données

#### Sous linux

Modifier le fichier `.env` et y rentrer vos identifiants.

Par exemple :
```
USER_NAME=mdesch01
USER_ID=1000
GROUP_NAME=mdesch01
GROUP_ID=1000
```


#### Sous Mac OS

Ne touchez pas au `.env`, tout fonctionnera avec les identifiants déjà rentrés.

### 5. Récupération des tables de la base de données

Dans un terminal positionné dans le dossier `/app/sfapp` dans le conteneur `sfapp` :

- Migrer les tables de la base de données :
```
php bin/console doctrine:migrations:migrate
```

### 6. Accéder au site en local

Ouvrir un navigateur web quelconque et rechercher `localhost:8000` dans la barre de recherche.