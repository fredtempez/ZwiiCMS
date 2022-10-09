# ZwiiCMS 11.5.10

Zwii est un CMS sans base de données (flat-file) qui permet de créer et gérer facilement un site web sans aucune connaissance en programmation.

ZwiiCMS a été créé par un développeur de talent, [Rémi Jean](https://remijean.fr/). Il est désormais maintenu par Frédéric Tempez.

[Site](http://zwiicms.fr/) - [Forum](http://forum.zwiicms.com/) - [Version initiale](https://github.com/remijean/ZwiiCMS/) - [GitHub](https://github.com/fredtempez/ZwiiCMS)

## Configuration recommandée

* PHP 7.2 ou plus
* Support de .htaccess

## Téléchargement de ZwiiCMS

Pour télécharger la dernière version publiée, il faut vous rendre sur la page de téléchargement du [site](https://zwiicms.fr/telechargement)

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
      [F] local.json       Données du site propres à la langue
      [R] content          Dossier des contenus de page
        [F] accueil.html   Exemple contenu de la page d'accueil
    [R] fonts              Dossier contenant les fontes installées
      [F] fonts.html       Fichier contenant les appels des fontes à charger sur cdnFonts
      [F] fonts.css        Fichier contenant la feuille de style liée aux polices de caractères locales
      [F] fontes.woff      Fichiers locaux des fontes (woff, etc..)
    [R] modules            Personnalisation des modules ou données propres
    [F] admin.css          Thème des pages d'administration
    [F] admin.json         Données de thème des pages d'administration
    [F] blacklist.json     Journalisation des tentatives de connexion avec des comptes inconnus
    [F] config.json        Configuration du site
    [F] core.json          Configuration du noyau
    [F] custom.css         Feuille de style de la personnalisation avancée
    [F] fonts.json         Descripteur des fontes personnalisées
    [F] journal.log        Journalisation des actions
    [F] theme.css          Thème du site
    [F] theme.json         Données du site
    [F] user.json          Données des utilisateurs
    [F] .backup            Marqueur de la sauvegarde des fichiers si présent
  [R] file                 Répertoire d'upload du gestionnaire de fichiers
    [R] source             Ressources diverses
    [R] thumb              Miniatures des images
  [R] tmp                  Répertoire temporaire

[F] index.php              Fichier d'initialisation de ZwiiCMS
[F] robots.txt             Filtrage des répertoires accessibles aux robots des moteurs de recherche
[F] sitemap.xml            Plan du site
[F] sitemap.xml.gz         Version compressée

Le fichiers .htaccess contribuent à la sécurité en filtrant l'accès aux répertoires sensibles.

```
