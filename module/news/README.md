# Module News pour ZwiiCMS

Module d'actualités pour [ZwiiCMS](http://zwiicms.fr/) : articles datés avec publication programmée et date de fin facultative, affichage en colonnes, résumés, cadre personnalisable et flux RSS.

- **Nom affiché dans ZwiiCMS** : News
- **Auteurs** : Rémi Jean (2008-2018), Frédéric Tempez (2018-2026)
- **Multi-module** : non (`MULTIMODULE = false`), une seule instance par page

---

## Sommaire

- [Module News pour ZwiiCMS](#module-news-pour-zwiicms)
  - [Sommaire](#sommaire)
  - [Fonctionnalités](#fonctionnalités)
  - [Prérequis](#prérequis)
  - [Installation](#installation)
  - [Utilisation](#utilisation)
    - [Côté visiteur](#côté-visiteur)
    - [Côté éditeur](#côté-éditeur)
    - [Adresses des actions](#adresses-des-actions)
  - [Options d'un article](#options-dun-article)
  - [Options du module](#options-du-module)
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

- Articles rédigés avec l'éditeur TinyMCE
- États **Brouillon** / **Publié**
- Date de publication programmable et date de fin facultative (article « Permanent » si elle est vide)
- Liste des articles sur une à quatre colonnes, avec pagination
- Résumés des articles dans la liste (200 à 1000 caractères) avec lien « lire la suite », ou articles complets
- Affichage au choix de la signature, de la date et de l'heure, avec formats personnalisables
- Bouton de retour dans les articles
- Cadre des articles personnalisable : bordure, épaisseur, couleurs de bordure et de fond
- Flux RSS 2.0 avec lien d'accès
- Droits par profil : ajout, édition, suppression, options
- Interface traduite en 8 langues

## Prérequis

| Besoin | Détail |
|---|---|
| ZwiiCMS | Cœur prenant en charge les modules à données localisées (`site/data/news/`) |
| Composants du cœur | jQuery, TinyMCE, TinyColorPicker (fournis par ZwiiCMS) |

## Installation

1. Copier le dossier `news` dans le répertoire `module/` de votre ZwiiCMS (chemin final : `module/news/news.php`).
2. Créer ou éditer une page et choisir le module **News** comme type de contenu.
3. Au premier accès, le module crée sa configuration, son thème et la feuille de style de l'instance.

Ne placer qu'une seule instance du module par page.

## Utilisation

### Côté visiteur

- La page affiche la liste des articles publiés, du plus récent au plus ancien, en colonnes et par pages. Chaque article présente son titre, sa signature, sa date et son contenu (complet ou abrégé).
- Un clic sur le titre ou sur « lire la suite » ouvre l'article seul, avec un bouton de retour à la liste.
- Un article apparaît dans la liste lorsqu'il est **publié**, que sa **date de publication est passée** et que sa **date de fin**, si elle existe, n'est pas dépassée.
- Les administrateurs connectés voient un lien **Éditer** dans la liste et dans l'article.

### Côté éditeur

La page de configuration (`/config`, titre **Configuration du module**) liste les articles (titre, date de publication, date de fin ou « Permanent », état) avec les boutons d'édition et de suppression, 8 articles par page.

### Adresses des actions

Adresses relatives à la page du module (`/ma-page-news`) :

| Adresse | Rôle minimal | Fonction |
|---|---|---|
| `/` | Visiteur | Liste des articles |
| `/<id-article>` | Visiteur | Affichage d'un article |
| `/rss` | Visiteur | Flux RSS |
| `/config` | Éditeur | Liste de gestion des articles |
| `/add` | Éditeur | Rédiger un article |
| `/edit/<id>` | Éditeur | Modifier un article |
| `/delete/<id>` | Éditeur | Supprimer un article |
| `/option` | Éditeur | Options du module et cadre des articles |

> Chaque action est en plus soumise aux permissions du profil de l'utilisateur (voir [Permissions et profils](#permissions-et-profils)).

## Options d'un article

| Option | Description |
|---|---|
| Titre | Obligatoire ; l'identifiant de l'article (et son adresse) en est dérivé et rendu unique automatiquement |
| Contenu | Éditeur TinyMCE |
| Auteur | Utilisateur désigné comme signataire |
| Date de publication | Obligatoire ; l'article n'est visible qu'à partir de cette date |
| Date de fin | Facultative ; l'article disparaît de la liste après cette date |
| État | Brouillon ou Publié |

À l'édition, une date de fin antérieure à la date de publication est supprimée.

## Options du module

Page `/option` (titre **Options de configuration**) :

| Option | Description |
|---|---|
| Bouton de retour | Affiche un lien de retour dans les articles |
| Signature | Affiche le nom de l'auteur |
| Afficher la date | Affiche la date de publication |
| Format des dates | `DD MMMM YYYY`, `DD/MM/YYYY`, `MM/DD/YYYY`, `DD/MM/YY`, `MM/DD/YY`, `DD-MM-YYYY`, `MM-DD-YYYY`, `DD-MM-YY`, `MM-DD-YY` |
| Afficher l'heure | Affiche l'heure de publication |
| Format des heures | `HH:MM` (24 h) ou `HH:MM tt` (12 h avec AM/PM) |
| Nombre de colonnes | 1, 2, 3 ou 4 colonnes |
| Articles par page | 4, 8, 12, 16 ou 22 |
| Abrégé de l'article | Article complet, ou abrégé à 1000, 800, 600, 400 ou 200 caractères |
| Lien du flux RSS | Affiche l'icône et le lien vers le flux |
| Texte de l'étiquette RSS | Libellé associé au lien |
| Bordure | Aucune ou bordure pleine |
| Épaisseur | Aucune, très fine (0,1 em), fine (0,15 em), très petite (0,2 em) ou petite (0,25 em) |
| Couleur de la bordure | Sélecteur de couleur |
| Couleur du fond | Sélecteur de couleur |

Les réglages du cadre sont enregistrés dans la feuille de style `theme.css` de l'instance (classe `.newsFrame`), dans le dossier de données du module (`site/data/news/<page>/`).

## Flux RSS

- Adresse : `https://votre-site/ma-page-news/rss`
- RSS 2.0 (bibliothèque FeedWriter), UTF-8, avec lien `atom:self`
- Seuls les articles publiés dont la date de publication est passée sont inclus
- Chaque entrée contient le titre, le lien vers l'article, un résumé en texte brut (300 caractères maximum), le contenu HTML complet (`content:encoded`), l'auteur et la date
- Le titre et la description du flux viennent du titre et de la méta-description de la page ; à défaut, le titre de la page ou l'URL du site
- La langue déclarée est `fr-FR`

## Permissions et profils

Le module ajoute ses permissions aux formulaires de profils :

| Permission | Effet |
|---|---|
| Ajouter un article | Accès à `/add` |
| Éditer un article | Accès à `/edit` |
| Effacer un article | Accès à `/delete` |
| Options des articles | Accès à `/option` |

L'accès à `/config` est accordé dès que l'une de ces permissions est cochée.

## Structure du module

```
news/
├── news.php                  Classe principale du module
├── changes.md                Historique des versions
├── enum.json                 Description du module
├── i18n/                     Fichiers de traduction (JSON)
│   ├── de.json  en_EN.json  es.json  fr_FR.json
│   └── gr_GR.json  it.json  pt_PT.json  tr_TR.json
├── profil/                   Permissions ajoutées aux profils utilisateurs
│   ├── main/                 Traitement des formulaires (add / edit)
│   └── view/                 Formulaires de permissions (add / edit)
├── ressource/
│   ├── defaultdata.php       Configuration et thème par défaut
│   └── feed-icon-16.gif      Icône RSS
├── vendor/
│   └── FeedWriter/           Génération du flux RSS
└── view/
    ├── index/                Liste des articles
    ├── article/              Affichage d'un article
    ├── add/  edit/           Rédaction et modification
    ├── config/               Liste de gestion
    ├── option/               Options du module et cadre
    └── rss/                  Sortie RSS
```

## Données stockées

| Clé | Contenu |
|---|---|
| `config` | `feeds`, `feedsLabel`, `itemsperPage`, `itemsperCol`, `height`, `dateFormat`, `timeFormat`, `buttonBack`, `showDate`, `showTime`, `showPseudo`, `versionData` |
| `theme` | `style` (chemin de la feuille de style), `borderStyle`, `borderColor`, `borderWidth`, `backgroundColor` |
| `posts` | Un enregistrement par article, identifié par son id : `title`, `content`, `publishedOn`, `publishedOff`, `state`, `userId` |

Valeurs par défaut : flux RSS affiché avec l'étiquette « RSS », 8 articles par page, une colonne, articles complets, date `DD MMMM YYYY`, heure `HH:MM`, bouton de retour actif, aucune bordure.

La clé `versionData` permet les migrations automatiques lors des mises à jour (étapes 3.2 à 6.0).

## Traductions

Langues fournies : allemand (`de`), anglais (`en_EN`), espagnol (`es`), français (`fr_FR`), grec (`gr_GR`), italien (`it`), portugais (`pt_PT`), turc (`tr_TR`).

Les chaînes françaises du code source servent de clés : `fr_FR.json` contient la liste des clés à traduire, ses valeurs sont vides. Pour ajouter une langue, copier ce fichier, le renommer avec le code de la langue et renseigner les valeurs.

## Composants tiers

- [FeedWriter](https://github.com/mibe/FeedWriter) : génération des flux RSS 1.0, RSS 2.0 et ATOM
- TinyMCE, TinyColorPicker et jQuery : fournis par ZwiiCMS

## Historique des versions

Le détail complet est dans [`changes.md`](changes.md). Dernières versions :

- **6.5** : constante `MULTIMODULE`
- **6.4** : nettoyage des liens dans la description du flux RSS
- **6.3** : flux RSS en UTF-8
- **6.2** : normalisation des chemins vers les ressources
- **6.0** : options d'affichage de la signature, de la date et de l'heure ; correction du format de `buttonBack`
- **5.3** : option d'un lien de retour dans la signature
- **5.0** : gestion des permissions intégrée au module

## Licence

Voir l'en-tête du fichier `news.php`.

© 2008-2018 Rémi Jean — © 2018-2026 Frédéric Tempez
