# 🚀 ZwiiCMS 14.1.05
# Français 🇫🇷

Zwii est un CMS sans base de données (flat-file) qui permet de créer et gérer facilement un site web sans aucune connaissance en programmation. Conçu pour la simplicité et la performance, ZwiiCMS offre une solution complète pour la gestion de contenu web moderne.

ZwiiCMS a été créé par un développeur de talent, [Rémi Jean](https://remijean.fr/). Il est désormais maintenu par Frédéric Tempez, qui continue à faire évoluer le projet avec de nouvelles fonctionnalités et améliorations constantes.

[Site](http://zwiicms.fr/) - [Forum](http://forum.zwiicms.com/) - [Version initiale](https://github.com/remijean/ZwiiCMS/) - [GitHub](https://github.com/fredtempez/ZwiiCMS) - [Notes de version](CHANGES.md) - [Release Notes](CHANGELOG.md)

## ⚙️ Configuration recommandée

* PHP 7.2 ou plus (recommandé PHP 8.0+ pour de meilleures performances)
* Support de .htaccess (pour la réécriture d'URL et la sécurité)
* Espace disque : minimum 50 Mo
* Navigateurs modernes : Chrome, Firefox, Safari, Edge

## 📄 Licence

Ce programme est distribué sous licence GNU General Public License version 3 (GPL v3).

Pour plus d'informations sur la GPL v3, consultez le fichier [LICENSE](LICENSE) ou visitez <https://www.gnu.org/licenses/gpl-3.0.html>.

## 📋 Notes de version

* **Français** : [CHANGES.md](CHANGES.md) - Notes de version en français
* **English** : [CHANGELOG.md](CHANGELOG.md) - Release notes in English

## 📥 Téléchargement de ZwiiCMS

Pour télécharger la dernière version publiée, rendez-vous :
* sur [la page des mises à jour](https://forge.chapril.org/ZwiiCMS-Team/ZwiiCMS/releases) pour accéder à toutes les versions
* ou sur [la page de téléchargement du site](https://zwiicms.fr/telechargement) pour la version stable
* Mises à jour automatiques intégrées depuis le panneau d'administration

## 🛠️ Installation

Décompressez l'archive de Zwii et téléversez son contenu à la racine de votre serveur ou dans un sous-répertoire. C'est tout !

Vous trouverez de plus amples explications, en particulier pour une installation chez Free, dans la rubrique "Téléchargements" du forum.

**Points forts de l'installation :**
* Installation en moins de 5 minutes
* Aucune base de données requise
* Configuration de sécurité automatique
* Responsive design dès l'installation

## 🔄 Procédures de mise à jour

A l'occasion de l'installation d'une version majeure, il est recommandé de réaliser une copie de sauvegarde.

**Nouveautés de la version 14 :**
* Interface d'administration modernisée
* Amélioration des performances
* Renforcement de la sécurité
* Statistiques détaillées du site
* Gestion avancée des profils utilisateurs
* Support multilingue étendu

### 🤖 Automatique

* Connectez-vous à votre site en tant qu'administrateur.
* Si une mise à jour est disponible, une notification s'affiche dans la barre d'administration.
* Cliquez sur le bouton "Mettre à jour" et suivez les instructions.
* Le système vérifie automatiquement les mises à jour toutes les 24 heures.

### ✋ Manuelle

* Sauvegardez l'intégralité de votre site, spécialement le répertoire "site" contenant toutes vos données.
* Décompressez la nouvelle version sur votre ordinateur.
* Transférez son contenu sur votre serveur en activant le remplacement des fichiers (sauf le dossier "site").
* Vérifiez que les permissions des fichiers sont correctes (755 pour les dossiers, 644 pour les fichiers).

## 🌳 Arborescence générale

*Légende : [R] Répertoire - [F] Fichier*

**Structure optimisée pour la version 14 :**

```text
[R] core                   Cœur du système
  [R] class                Classes PHP du framework
  [R] layout               Mise en page et templates
  [R] module               Modules du cœur (page, config, etc.)
  [R] vendor               Librairies extérieures (TinyMCE, etc.)
  [F] core.js.php          Cœur javascript (nouveau système de cache)
  [F] core.php             Cœur PHP avec améliorations v14

[R] module                 Modules de page
  [R] blog                 Blog avec flux RSS et commentaires
  [R] form                 Gestionnaire de formulaires avancé
  [R] gallery              Galerie avec miniatures automatiques
  [R] news                 Système de nouvelles avec RSS
  [R] redirection          Redirections d'URL
  [R] download             Espace de téléchargement

[R] site                   Contenu du site
  [R] backup               Sauvegardes automatiques (journalisation)
  [R] i18N                 Langues de l'interface (français, anglais, etc.)
  [R] data                 Répertoire des données
    [R] fr                 Dossier localisé
      [F] page.json        Données des pages (structure hiérarchique)
      [F] module.json      Données des modules de pages
      [F] local.json       Données du site propres à la langue
      [F] .default         Indicateur de la langue de site par défaut
      [R] content          Dossier des contenus de page
        [F] accueil.html   Exemple contenu de la page d'accueil
    [R] font              Dossier contenant les fontes installées
      [F] font.html       Fichier contenant les appels des fontes à charger sur cdnFonts
      [F] font.css        Fichier contenant la feuille de style liée aux polices de caractères locales
      [F] fontes.woff      Fichiers locaux des fontes (woff, etc..)
    [R] modules            Personnalisation des modules ou données propres
    [F] admin.css          Thème des pages d'administration (nouveau design v14)
    [F] admin.json         Données de thème des pages d'administration
    [F] blacklist.json     Journalisation des tentatives de connexion avec des comptes inconnus
    [F] config.json        Configuration du site
    [F] core.json          Configuration du noyau
    [F] custom.css         Feuille de style de la personnalisation avancée
    [F] font.json          Descripteur des fontes personnalisées
    [F] journal.log        Journalisation des activités (rotation automatique)
    [F] language.json      Langues de l'interface
    [F] profil.json        Profils des utilisateurs (système étendu v14)
    [F] theme.css          Thème du site
    [F] theme.json         Données du site
    [F] user.json          Données des utilisateurs
    [F] .backup            Marqueur de la sauvegarde des fichiers si présent
  [R] file                 Répertoire d'upload du gestionnaire de fichiers
    [R] source             Ressources diverses
    [R] thumb              Miniatures des images (optimisation v14)
  [R] tmp                  Répertoire temporaire

[F] index.php              Fichier d'initialisation de ZwiiCMS
[F] robots.txt             Filtrage des répertoires accessibles aux robots des moteurs de recherche
[F] sitemap.xml            Plan du site (généré automatiquement)
[F] sitemap.xml.gz         Version compressée

🔒 Le fichiers .htaccess contribuent à la sécurité en filtrant l'accès aux répertoires sensibles.

```

# English 🇺🇸

Zwii is a database-less (flat-file) CMS that allows you to easily create and manage a web site without any programming knowledge. Designed for simplicity and performance, ZwiiCMS offers a complete solution for modern web content management.

ZwiiCMS was created by a talented developer, [Rémi Jean](https://remijean.fr/). It is now maintained by Frédéric Tempez, who continues to evolve the project with new features and constant improvements.

[Site](http://zwiicms.fr/) - [Forum](http://forum.zwiicms.com/) - [Initial version](https://github.com/remijean/ZwiiCMS/) - [GitHub](https://github.com/fredtempez/ZwiiCMS) - [Release Notes](CHANGELOG.md) - [Notes de version](CHANGES.md)

## ⚙️ Recommended configuration

* PHP 7.2 or higher (PHP 8.0+ recommended for better performance)
* .htaccess support (for URL rewriting and security)
* Disk space: minimum 50 MB
* Modern browsers: Chrome, Firefox, Safari, Edge

## 📄 License

Zwiicms - CMS open source
Copyright (C) 2026 Frederic Tempez

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <https://www.gnu.org/licenses/>.

## 📋 Release Notes

* **English** : [CHANGELOG.md](CHANGELOG.md) - Release notes in English
* **Français** : [CHANGES.md](CHANGES.md) - Notes de version en français

## 📥 Downloading ZwiiCMS

To download the latest released version, go to:
* [the Updates page](https://forge.chapril.org/ZwiiCMS-Team/ZwiiCMS/releases) to access all versions
* or at [the site download page](https://zwiicms.fr/download) for stable version
* Automatic updates integrated in administration panel

## 🛠️ Installation

Unzip the Zwii archive and upload its contents to the root of your server or to a subdirectory. That's all!

You will find more explanations, in particular for an installation at Free, in the "Downloads" section of the forum.

**Installation highlights:**
* Installation in less than 5 minutes
* No database required
* Automatic security configuration
* Responsive design from installation

## 🔄 Update procedures

When installing a major version, it is recommended to make a backup copy.

**Version 14 new features:**
* Modernized administration interface
* Performance improvements
* Enhanced security
* Detailed site statistics
* Advanced user profile management
* Extended multilingual support

### 🤖 Automatic

* Connect to your site as administrator.
* If an update is available, a notification appears in the administration bar.
* Click on "Update" button and follow the instructions.
* The system automatically checks for updates every 24 hours.

### ✋ Manual

* Save your entire site, especially the "site" directory containing all your data.
* Unzip the new version on your computer.
* Transfer its content to your server by activating the file replacement (except the "site" folder).
* Check that file permissions are correct (755 for folders, 644 for files).

## 🌳 General tree structure

*Legend: [D] Directory - [FILE] File*

**Optimized structure for version 14:**

````
text
[DIR] core                   Core of the system
  [DIR] class                PHP framework classes
  [DIR] layout               Layout and templates
  [DIR] module               Core modules (page, config, etc.)
  [DIR] vendor               External libraries (TinyMCE, etc.)
  [FILE] core.js.php         JavaScript core (new cache system)
  [FILE] core.php            PHP core with v14 improvements

[DIR] module                 Page modules
  [DIR] blog                 Blog with RSS feed and comments
  [DIR] form                 Advanced form manager
  [DIR] gallery              Gallery with automatic thumbnails
  [DIR] news                 News system with RSS
  [DIR] redirection          URL redirections
  [DIR] download             Download area

[DIR] site                   Site content
  [DIR] backup               Automatic backups (logging)
  [DIR] i18N                 Interface languages (French, English, etc.)
  [DIR] data                 Data directory
    [DIR] en                  Localized folder
      [FILE] page.json        Page data (hierarchical structure)
      [FILE] module.json      Page module data
      [FILE] local.json       Language-specific site data
      [FILE] .default         Default site language indicator
      [DIR] content           Page content folder
        [FILE] home.html      Sample home page content
    [DIR] fonts               Folder containing installed fonts
      [FILE] fonts.html       File containing font calls to load on cdnFonts
      [FILE] fonts.css        File containing style sheet linked to local fonts
      [FILE] fonts.woff       Local font files (woff, etc..)
    [DIR] modules             Module customization or own data
    [FILE] admin.css          Administration pages theme (new v14 design)
    [FILE] admin.json         Administration pages theme data
    [FILE] blacklist.json     Logging of login attempts with unknown accounts
    [FILE] config.json        Site configuration
    [FILE] core.json          Core configuration
    [FILE] custom.css         Advanced customization stylesheet
    [FILE] font.json          Custom font descriptor
    [FILE] journal.log        Activities logging (automatic rotation)
    [FILE] language.json      Interface languages database
    [FILE] profil.json        User profiles (extended v14 system)
    [FILE] theme.css          Site theme
    [FILE] theme.json         Site database
    [FILE] user.json          User database
    [FILE] .backup            Marker for file backup if present
  [DIR] file                  File manager upload directory
    [DIR] source              Various resources
    [DIR] thumb               Image thumbnails (v14 optimization)
  [DIR] tmp                   Temporary directory

[FILE] index.php              ZwiiCMS initialization file
[FILE] robots.txt             Filtering of directories accessible to search engine robots
[FILE] sitemap.xml            Sitemap (automatically generated)
[FILE] sitemap.xml.gz         Compressed version

🔒 The .htaccess files contribute to security by filtering access to sensitive directories.
