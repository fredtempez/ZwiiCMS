# 🚀 ZwiiCMS 14.00.00

Zwii est un CMS sans base de données (flat-file) qui permet de créer et gérer facilement un site web sans aucune connaissance en programmation. Conçu pour la simplicité et la performance, ZwiiCMS offre une solution complète pour la gestion de contenu web moderne.

ZwiiCMS a été créé par un développeur de talent, [Rémi Jean](https://remijean.fr/). Il est désormais maintenu par Frédéric Tempez, qui continue à faire évoluer le projet avec de nouvelles fonctionnalités et améliorations constantes.

[Site](http://zwiicms.fr/) - [Forum](http://forum.zwiicms.com/) - [Version initiale](https://github.com/remijean/ZwiiCMS/) - [GitHub](https://github.com/fredtempez/ZwiiCMS)

## ⚙️ Configuration recommandée

* PHP 7.2 ou plus (recommandé PHP 8.0+ pour de meilleures performances)
* Support de .htaccess (pour la réécriture d'URL et la sécurité)
* Espace disque : minimum 50 Mo
* Navigateurs modernes : Chrome, Firefox, Safari, Edge

## 📄 Licence

Cette œuvre est mise à disposition sous licence Attribution - Pas d'utilisation Commerciale - Pas de Modification 4.0 International.

Pour voir une copie de cette licence, visitez <http://creativecommons.org/licenses/by-nc-nd/4.0/> ou écrivez à Creative Commons, PO Box 1866, Mountain View, CA 94042, USA.

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
