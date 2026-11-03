# Module Blog pour ZwiiCMS

Module de blog pour [ZwiiCMS](http://zwiicms.fr/) : rédaction d'articles avec image de couverture, publication différée, commentaires modérés, notification par e-mail et flux RSS.

- **Auteurs** : Rémi Jean (2008-2018), Frédéric Tempez (2018-2026)
- **Multi-module** : non (`MULTIMODULE = false`), peut cohabiter avec d'autres modules dans une même page. Un seul blog par page est recommandé.
- **Compatibilité** : ZwiiCMS 16.0.00c ou supérieur recommandé (voir [Prérequis](#prérequis))

---

## Sommaire

- [Module Blog pour ZwiiCMS](#module-blog-pour-zwiicms)
  - [Sommaire](#sommaire)
  - [Fonctionnalités](#fonctionnalités)
  - [Prérequis](#prérequis)
  - [Installation](#installation)
  - [Utilisation](#utilisation)
    - [Adresses des actions](#adresses-des-actions)
  - [Options du module](#options-du-module)
  - [Options d'un article](#options-dun-article)
  - [Commentaires](#commentaires)
  - [Flux RSS](#flux-rss)
  - [Permissions et profils](#permissions-et-profils)
  - [Structure du module](#structure-du-module)
  - [Données stockées](#données-stockées)
  - [Traductions](#traductions)
  - [Composants tiers](#composants-tiers)
  - [Historique des versions](#historique-des-versions)
  - [Licence](#licence)

---

## Fonctionnalités

- Rédaction d'articles avec l'éditeur TinyMCE
- Image de couverture facultative (taille et position réglables, masquable dans l'article)
- Permalien généré automatiquement à partir du titre (plugin jQuery *furl*), modifiable
- États **Brouillon** / **Publié** et date de publication programmable
- Deux dispositions de la liste : **Classique** (avec aperçu tronqué) ou **Moderne**
- Pagination de la liste des articles et des commentaires
- Commentaires avec limite de longueur, modération, fermeture par article
- Captcha pour les visiteurs non connectés
- Notification par e-mail à chaque nouveau commentaire
- Flux RSS 2.0 avec miniature et contenu HTML complet
- Gestion fine des droits par profil (ajout, édition, suppression, options, commentaires)
- Droits d'édition par article : tous les rôles, rôle du propriétaire ou propriétaire seul
- Formats de date et d'heure configurables, affichage de la signature, de la date et de l'heure au choix
- Bouton de retour dans les articles
- Interface traduite en 8 langues

## Prérequis

| Besoin | Détail |
|---|---|
| ZwiiCMS | **16.0.00c** ou supérieur à partir de la version 8.7 du module (captcha stocké en session côté serveur) |
| ZwiiCMS | 13.5 ou supérieur pour le tronquage correct des articles (version 8.0) |
| Messagerie | Un SMTP configuré dans ZwiiCMS est nécessaire pour les notifications de commentaires |

## Installation

1. Copier le dossier `blog` dans le répertoire `module/` de votre ZwiiCMS (le chemin final doit être `module/blog/blog.php`).
2. Dans ZwiiCMS, créer ou éditer une page et choisir le module **Blog** comme type de contenu.
3. Enregistrer la page : le module s'initialise avec ses valeurs par défaut au premier affichage.

## Utilisation

Une fois la page créée, un utilisateur disposant des droits nécessaires accède à la configuration du blog depuis la page, puis :

- **Rédiger un article** (bouton `+`) : titre, permalien, image, contenu, date de publication, état, options de commentaires.
- **Éditer / supprimer** un article depuis la liste de configuration (icônes crayon et corbeille).
- **Gérer les commentaires** d'un article via le bouton de compteur de la liste (format `à approuver / approuvés` quand une modération est active).
- **Régler les options** générales du blog (icône curseurs).

### Adresses des actions

Toutes les adresses sont relatives à la page du blog (`/ma-page-blog`) :

| Adresse | Rôle minimal | Fonction |
|---|---|---|
| `/` | Visiteur | Liste des articles publiés |
| `/<permalien>` | Visiteur | Affichage d'un article et dépôt de commentaire |
| `/rss` | Visiteur | Flux RSS |
| `/config` | Éditeur | Liste des articles à gérer |
| `/add` | Éditeur | Rédiger un article |
| `/edit/<id>` | Éditeur | Modifier un article |
| `/delete/<id>` | Éditeur | Supprimer un article |
| `/option` | Éditeur | Options générales du blog |
| `/comment/<id>` | Éditeur | Liste des commentaires d'un article |
| `/commentApprove/<id>/<idCommentaire>` | Éditeur | Approuver / rejeter un commentaire |
| `/commentDelete/<id>/<idCommentaire>` | Éditeur | Supprimer un commentaire |
| `/commentDeleteAll/<id>` | Éditeur | Supprimer tous les commentaires d'un article |

> Chaque action est en plus soumise aux permissions du profil de l'utilisateur (voir [Permissions et profils](#permissions-et-profils)).

## Options du module

Menu **Options de configuration** (`/option`) :

| Option | Description |
|---|---|
| Bouton de retour | Affiche un bouton de retour à la liste dans les articles |
| Signature | Affiche le pseudo de l'auteur |
| Afficher la date | Affiche la date de publication |
| Format des dates | `DD MMMM YYYY`, `DD/MM/YYYY`, `MM/DD/YYYY`, `DD/MM/YY`, `MM/DD/YY`, `DD-MM-YYYY`, `MM-DD-YYYY`, `DD-MM-YY`, `MM-DD-YY` |
| Afficher l'heure | Affiche l'heure de publication |
| Format des heures | `HH:MM` (24 h) ou `HH:MM tt` (12 h avec AM/PM) |
| Disposition | **Classique** ou **Moderne** |
| Aperçus | Longueur des extraits dans la liste : articles complets, ou 600 à 1800 signes par pas de 200. Disponible uniquement avec la disposition Classique |
| Articles par page | 1, 2, 4, 6, 8, 10 ou 12 (s'applique aussi à la pagination des commentaires) |
| Lien du flux RSS | Affiche l'icône et le lien vers le flux RSS |
| Texte de l'étiquette RSS | Libellé associé au lien RSS |

## Options d'un article

| Option | Description |
|---|---|
| Titre | Titre de l'article |
| Permalien | Fin de l'adresse de l'article ; incrémenté automatiquement en cas de doublon avec une page, un article ou une action du module |
| Image de couverture | Facultative. Taille : très petite (20 %), petite (30 %), grande (40 %), très grande (50 %) ou pleine largeur (100 %). Position : à gauche ou à droite |
| Masquer l'image dans l'article | L'image n'apparaît que dans la liste |
| Contenu | Éditeur TinyMCE |
| Auteur | Choix de l'auteur (réservé aux administrateurs ; sinon l'utilisateur connecté) |
| Date de publication | Date et heure ; l'article n'est visible qu'une fois cette date atteinte |
| État | Brouillon ou Publié |
| Droits d'édition | **Tous les rôles**, **Rôle du propriétaire** ou **Propriétaire** |
| Fermer les commentaires | Interdit les nouveaux commentaires |
| Approbation par un modérateur | Les commentaires doivent être approuvés avant d'être affichés |
| Caractères par commentaire | 100, 250, 500 ou 750 signes |
| Notification par e-mail | Prévient par e-mail lors d'un nouveau commentaire |
| Destinataires de la notification | Tous les utilisateurs dont le rôle est supérieur ou égal au rôle choisi |

Un article n'apparaît dans la liste publique que s'il est **publié** et que sa **date de publication est passée**.

## Commentaires

- Les visiteurs non connectés doivent résoudre un **captcha** (type et force définis dans la configuration de connexion de ZwiiCMS). Le résultat attendu est conservé en session côté serveur, comparé avec `hash_equals` et à usage unique.
- Un commentaire vide est refusé.
- Avec l'approbation activée, le commentaire reste en attente jusqu'à validation par un modérateur ; le bouton **A** (approuvé) / **R** (rejeté) permet de basculer son état.
- La notification par e-mail n'est envoyée qu'après validation du captcha et dépôt du commentaire. Elle contient le titre de l'article (avec lien), le nom de l'auteur du commentaire et le texte du commentaire.
- Les commentaires des anciennes versions du module sont considérés comme approuvés.

## Flux RSS

- Adresse : `https://votre-site/ma-page-blog/rss`
- Format RSS 2.0 (bibliothèque FeedWriter), encodage UTF-8
- Seuls les articles publiés et dont la date est passée sont inclus
- Chaque entrée contient : titre, lien, résumé en texte brut (300 caractères maximum), contenu HTML complet (`content:encoded`), auteur, date, et la miniature de l'image de couverture en pièce jointe
- Les chemins d'images sont préfixés par l'URL de base du site
- Le titre et la description du flux proviennent du titre et de la méta-description de la page ; à défaut, le titre de la page ou l'URL du site sont utilisés
- La langue déclarée du flux est celle de l'interface du site (code à deux lettres : `fr`, `en`, `de`, `el`...)

## Permissions et profils

Le module déclare ses propres permissions, ajoutées aux formulaires de création et d'édition des profils (section **Permissions Blog**) :

| Permission | Effet |
|---|---|
| Ajouter un article | Accès à `/add` |
| Éditer un article | Accès à `/edit` |
| Effacer un article | Accès à `/delete` |
| Options des articles | Accès à `/option` |
| Gérer les commentaires | Accès à `/comment` |
| Approuver un commentaire | Accès à `/commentApprove` (requiert la gestion des commentaires) |
| Effacer un commentaire | Accès à `/commentDelete` (requiert la gestion des commentaires) |
| Effacer tous les commentaires | Accès à `/commentDeleteAll` (requiert la gestion des commentaires) |

L'accès à `/config` est accordé dès que l'une de ces permissions est cochée.

## Structure du module

```
blog/
├── blog.php                  Classe principale du module
├── changes.md                Historique des versions
├── i18n/                     Fichiers de traduction (JSON)
│   ├── de.json  en_EN.json  es.json  fr_FR.json
│   └── gr_GR.json  it.json  pt_PT.json  tr_TR.json
├── profil/                   Permissions ajoutées aux profils utilisateurs
│   ├── main/                 Profils principaux (add / edit)
│   └── view/                 Formulaires de permissions (add / edit)
├── ressource/
│   └── feed-icon-16.gif      Icône RSS
├── vendor/
│   ├── FeedWriter/           Génération du flux RSS
│   └── furl/                 Génération du permalien (jQuery)
└── view/                     Vues (php + css + js.php)
    ├── index/                Liste des articles
    ├── article/              Article et formulaire de commentaire
    ├── add/  edit/           Rédaction et modification
    ├── config/               Liste de gestion
    ├── option/               Options générales
    ├── comment/              Modération des commentaires
    └── rss/                  Message d'erreur du flux
```

## Données stockées

Les données sont enregistrées dans les données du module, pour chaque page de blog :

- `config` : réglages généraux (`feeds`, `feedsLabel`, `layout`, `articlesLenght`, `itemsperPage`, `dateFormat`, `timeFormat`, `buttonBack`, `showDate`, `showTime`, `showPseudo`, `versionData`)
- `posts` : un enregistrement par article, identifié par son permalien : `title`, `content`, `picture`, `hidePicture`, `pictureSize`, `picturePosition`, `publishedOn`, `state`, `userId`, `editConsent`, `commentMaxlength`, `commentApproved`, `commentClose`, `commentNotification`, `commentGroupNotification`, `comment`
- `comment` (dans un article) : `author`, `content`, `createdOn`, `userId`, `approval`

La clé `versionData` permet au module de migrer automatiquement ses données lors d'une mise à jour (étapes 5.0, 6.0, 6.5 et 8.0).

## Traductions

Langues fournies : allemand (`de`), anglais (`en_EN`), espagnol (`es`), français (`fr_FR`), grec (`gr_GR`), italien (`it`), portugais (`pt_PT`), turc (`tr_TR`).

Les chaînes françaises du code source servent de clés : le fichier `fr_FR.json` contient la liste des clés à traduire, ses valeurs sont vides. Pour ajouter une langue, copier ce fichier, le renommer avec le code de la langue et renseigner les valeurs.

## Composants tiers

- [FeedWriter](https://github.com/mibe/FeedWriter) : génération des flux RSS 1.0, RSS 2.0 et ATOM
- jQuery Furl 1.0.0 : conversion d'un titre en adresse conviviale
- TinyMCE : éditeur de contenu fourni par ZwiiCMS

## Historique des versions

Le détail complet est dans [`changes.md`](changes.md). 

## Licence

Voir l'en-tête du fichier `blog.php`.

© 2008-2018 Rémi Jean — © 2018-2026 Frédéric Tempez
