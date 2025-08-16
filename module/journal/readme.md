# Module Journal des Éditions pour Zwiicms

Ce module permet de consulter un journal des éditions de pages sur un site utilisant Zwiicms.
Il vous permet de consulter, par défaut, les vingt dernières modifications apportées à différentes pages, un nombre qui peut être ajusté si nécessaire. 
Il affiche également l'auteur de chaque modification, qu'il s'agisse d'un visiteur ou d'un administrateur. 
Ainsi, si une page est modifiée et réenregistrée plusieurs fois au cours d'une même journée, elle n'apparaîtra qu'une seule fois dans la liste, correspondant à sa dernière version. Cependant, le nombre total de pages affichées reste constant.

Il offre la possibilité de filtrer les entrées par utilisateur et de configurer certains paramètres.

## Fonctionnalités

- Affichage des dernières pages éditées.
- Filtres par utilisateur (admin, visiteur, tous).
- Configuration de la limite de pages affichées.
- Interface de configuration pour les administrateurs.

## Prérequis

- Zwiicms installé et configuré.
- PHP 7.4 ou supérieur.

# Préalable

Ce module utilise le fichier `journal.log` de ZwiiCMS situé dans `site/data`.  
Pour qu'il soit généré, vous devez activer la journalisation dans les paramètres de ZwiiCMS :

**Configuration > Connexion > Activer la journalisation**

## Installation

1. Copiez le dossier du module dans le répertoire `modules` de votre installation Zwiicms.
2. Connectez-vous à l'interface d'administration de Zwiicms.
3. Activez le module "Journal des Éditions".

## Configuration

Pour configurer le module, accédez à la page de configuration via l'administration :

1. Rendez-vous dans la section **Modules**.
2. Cliquez sur **Journal des Éditions**.
3. Configurez les paramètres selon vos besoins :
   - **Identifiant** : Identifiant utilisé pour le filtrage (optionnel).
   - **Limite**      : Nombre maximum de pages à afficher.
   - **Filtres**     : Cochez les types d’utilisateurs à inclure (Admin, Visiteur, Tous).
4. Cliquez sur **Enregistrer** pour sauvegarder vos réglages.

5. Vous pouvez également utiliser le bouton **Vider journal.log** pour effacer tout le contenu du fichier journal, sans le supprimer.

## Utilisation

Une fois installé et configuré, le module est accessible via l'URL dédiée au journal des éditions. 
Les utilisateurs peuvent y consulter l'historique des pages modifiées et utiliser un menu déroulant pour filtrer les entrées par type d'utilisateur.

## Personnalisation

Vous pouvez personnaliser l'apparence du journal en modifiant les styles CSS directement dans le fichier `index.css`.

## Auteur

[Lemimi] — sur une idée de @henry  
Grand merci à @fred pour le développement de ZwiiCMS.

## Version

2.03

