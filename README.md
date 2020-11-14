![](https://img.shields.io/github/last-commit/fredtempez/ZwiiCMS/master) ![](https://img.shields.io/github/release-date/fredtempez/ZwiiCMS)

# ZwiiCMS 10.4.00

Zwii est un CMS sans base de données (flat-file) qui permet de créer et gérer facilement un site web sans aucune connaissance en programmation.

ZwiiCMS a été créé par un développeur de talent, [Rémi Jean](https://remijean.fr/). Il est désormais maintenu par Frédéric Tempez.

[Site](http://zwiicms.fr/) - [Forum](http://forum.zwiicms.com/) - [Version initiale](https://github.com/remijean/ZwiiCMS/) - [GitHub](https://github.com/fredtempez/ZwiiCMS)

## Configuration recommandée

* PHP 5.6 ou plus
* Support de .htaccess

## Téléchargement de ZwiICMS

Pour télécharger la dernière version publiée, il faut vous rendre sur la page de téléchargemet du [site](https://zwiicms.fr/telechargements)

La version github est une **version de développement** qui peut encore contenir des bugs mais elle vous permet de tester les dernières nouveautés. Cette version n'est pas recommandée en production.

## Installation

Décompressez l'archive de Zwii et téléversez son contenu à la racine de votre serveur ou dans un sous-répertoire. C'est tout !

Vous trouverez de plus amples explications, en particulier pour une installation chez Free, dans la rubrique "Téléchargements" du forum.


## Procédures de mise à jour

### Automatique

* Connectez-vous à votre site.
* Si une mise à jour est disponible, elle vous est proposée dans la barre d'administration.
* Cliquez sur le bouton "Mettre à jour".

### Manuelle

* Sauvegardez l'intégralité de votre site, spécialement le répertoire "site".
* Décompressez la nouvelle version sur votre ordinateur.
* Transférez son contenu sur votre serveur en activant le remplacement des fichiers.

En cas de difficulté avec la nouvelle version, il suffira de téléverser la sauvegarde pour remettre votre site dans son état initial.

**Remarques :**

* La mise à jour manuelle désactive la réécriture d'URL. À vous de la réactiver depuis la page de configuration du site.
* La mise à niveau de la version 8 vers la version 9 crée deux fichiers de données distincts ("core.json" et "theme.json") à partir du fichier "data.json" de la version 8, puis le renomme "data_imported.json".

Pour revenir à la version 8, renommez ce fichier "data.json".

## Arborescence générale

*Légende : [R] Répertoire - [F] Fichier*

```text
[R] core                   Cœur du système
  [R] class                Classes
  [R] layout               Mise en page
  [R] module               Modules du cœur
  [R] vendor               Librairies extérieures
  [F] core.js.php          Cœur javascript
  [F] core.php             Cœur PHP
  
[R] module                 Modules de page
  [R] blog                 Blog
  [R] form                 Gestionnaire de formulaires
  [R] gallery              Galerie
  [R] news                 Nouvelles
  [R] redirection          Redirection
       
[R] site                   Contenu du site
  [R] backup               Sauvegardes automatiques
  [R] data                 Répertoire des données
    [R] fr                 Dossier localisé
      [F] page.json        Données des pages
      [F] module.json      Données des modules de pages
    [F] admin.css          Thème de la partie administration
    [F] admin.json         Données de la partie administration
    [F] blacklist.json     Données de connexion des comptes inconnus
    [F] config.json        Configuration du site
    [F] core.json          Configuration du noyau
    [F] custom.css         Feuille de style de la personnalisation avancée
    [F] journal.log        Journalisation des actions
    [F] theme.css          Thème du site
    [F] theme.json         Données du site
    [F] user.json          Données des utilisateurs
  [R] file                 Répertoire d'upload du gestionnaire de fichiers
    [R] source             Ressources diverses
    [R] thumb              Miniatures des images
  [R] tmp                  Répertoire temporaire
  
[F] index.php              Fichier d'initialisation de ZwiiCMS
[F] robots.txt             Filtrage des répertoires accessibles aux robots des moteurs de recherche
[F] sitemap.xml            Plan du site
[F] sitemap.xml.gz         Version compressée

Le fichiers .htaccess contribuent à la sécurité en filtant l'accès aux répertoires sensibles.

```
