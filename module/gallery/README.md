# Module Galerie pour ZwiiCMS

Module de galeries d'images pour [ZwiiCMS](http://zwiicms.fr/) : chaque galerie est construite à partir d'un dossier d'images du gestionnaire de fichiers, avec légendes, image de couverture, tri manuel, affichage en lightbox et thème visuel réglable.

- **Nom affiché dans ZwiiCMS** : Galerie
- **Auteurs** : Rémi Jean (2008-2018), Frédéric Tempez (2018-2026)
- **Multi-module** : oui (`MULTIMODULE = true`), plusieurs modules Galerie peuvent cohabiter sur une même page

---

## Sommaire

- [Module Galerie pour ZwiiCMS](#module-galerie-pour-zwiicms)
  - [Sommaire](#sommaire)
  - [Fonctionnalités](#fonctionnalités)
  - [Prérequis](#prérequis)
  - [Installation](#installation)
  - [Utilisation](#utilisation)
    - [Côté visiteur](#côté-visiteur)
    - [Côté éditeur](#côté-éditeur)
    - [Adresses des actions](#adresses-des-actions)
  - [Créer et configurer une galerie](#créer-et-configurer-une-galerie)
    - [Création (`/add`)](#création-add)
    - [Édition (`/edit/<id>`)](#édition-editid)
    - [Options de la galerie (`/option/gallery/<id>`)](#options-de-la-galerie-optiongalleryid)
  - [Options du module](#options-du-module)
  - [Thème](#thème)
  - [Permissions et profils](#permissions-et-profils)
  - [Structure du module](#structure-du-module)
  - [Données stockées](#données-stockées)
  - [Plusieurs galeries sur une même page](#plusieurs-galeries-sur-une-même-page)
  - [Traductions](#traductions)
  - [Composants tiers](#composants-tiers)
  - [Historique des versions](#historique-des-versions)
  - [Licence](#licence)

---

## Fonctionnalités

- Une galerie par dossier d'images ; plusieurs galeries par module, ordonnables par glisser-déposer
- Page d'accueil listant les galeries avec leur image de couverture et leur nom
- Affichage des images en vignettes avec visionneuse (lightbox) et légendes
- Image de couverture au choix pour chaque galerie
- Tri des images : alphabétique, alphabétique inverse ou **manuel** (glisser-déposer, enregistré automatiquement)
- Mode plein écran automatique à l'ouverture d'une galerie
- Affichage du contenu de la page avec la galerie
- Accès direct à la galerie lorsque le module n'en contient qu'une seule
- Boutons de retour à l'index des galeries (position et alignement réglables)
- Thème par module : taille, marges, bordures, ombres, angles arrondis, opacité au survol, style des légendes
- Création automatique des miniatures manquantes
- Plusieurs modules Galerie sur une même page : chaque instance a son thème, ses galeries, sa visionneuse et ses identifiants propres
- Dossiers proposés limités à ceux autorisés par le profil de l'utilisateur
- Droits par profil : ajout, édition, suppression, options, thème
- Interface traduite en 8 langues

## Prérequis

| Besoin | Détail |
|---|---|
| ZwiiCMS | Cœur prenant en charge le **multi-module** |
| Images | Dossiers d'images du gestionnaire de fichiers du site |
| Composants du cœur | jQuery, SimpleLightbox, TinyColorPicker (fournis par ZwiiCMS) |

## Installation

1. Copier le dossier `gallery` dans le répertoire `module/` de votre ZwiiCMS (chemin final : `module/gallery/gallery.php`).
2. Créer ou éditer une page et choisir le module **Galerie** comme type de contenu.
3. Au premier accès, le module crée ses données, son thème par défaut et la feuille de style de l'instance.

## Utilisation

### Côté visiteur

- La page affiche la liste des galeries sous forme de vignettes de couverture portant le nom de chaque galerie.
- Un clic sur une galerie affiche ses images ; un clic sur une image l'ouvre dans la visionneuse avec sa légende.
- Les images sont lues dans le dossier au moment de l'affichage : une image ajoutée au dossier apparaît dans la galerie.
- Une galerie sans image n'est pas affichée ; une galerie dont le dossier a été supprimé disparaît de la liste.

### Côté éditeur

La page de configuration (`/config`, titre **Configuration des galeries**) liste les galeries (nom, dossier cible) avec les boutons d'édition et de suppression. Les galeries se réordonnent par glisser-déposer. Les boutons du haut ouvrent l'édition de la page, les **Options**, le **Thème** et la création d'une galerie. Un dossier vide ou introuvable est signalé en rouge dans la liste.

### Adresses des actions

Adresses relatives à la page du module (`/ma-page-galerie`) :

| Adresse | Rôle minimal | Fonction |
|---|---|---|
| `/` | Visiteur | Liste des galeries |
| `/<id-galerie>` | Visiteur | Affichage d'une galerie |
| `/config` | Éditeur | Liste et gestion des galeries |
| `/add` | Éditeur | Créer une galerie |
| `/edit/<id>` | Éditeur | Légendes, couverture et ordre des images |
| `/delete/<id>` | Éditeur | Supprimer une galerie |
| `/option/galleries` | Éditeur | Options communes à toutes les galeries du module |
| `/option/gallery/<id>` | Éditeur | Options d'une galerie |
| `/theme` | Éditeur | Thème du module |
| `/sortGalleries` | Éditeur | Enregistrement de l'ordre des galeries (appel automatique) |
| `/sortPictures` | Éditeur | Enregistrement de l'ordre des images (appel automatique) |

> Chaque action est en plus soumise aux permissions du profil de l'utilisateur (voir [Permissions et profils](#permissions-et-profils)).

## Créer et configurer une galerie

### Création (`/add`)

| Option | Description |
|---|---|
| Nom | Nom de la galerie, utilisé pour son identifiant (rendu unique automatiquement) |
| Dossier cible | Dossier d'images à choisir parmi ceux autorisés par le profil |
| Tri des images | Alphabétique, Alphabétique inverse ou Manuel |
| Mode plein écran automatique | À l'ouverture, la première image s'affiche en plein écran |
| Afficher le contenu de la page avec la galerie | Le contenu de la page est toujours affiché dans la liste des galeries ; cette option l'affiche aussi avec une galerie, utile quand le module n'en contient qu'une seule |

À la création, le module génère les miniatures, retient la première image comme couverture et initialise les légendes (vides) et l'ordre. Le dossier doit exister, être lisible et contenir au moins une image.

### Édition (`/edit/<id>`)

Tableau des images du dossier, avec pour chaque image :

| Colonne | Description |
|---|---|
| Numéro d'ordre | Position de l'image |
| Nom du fichier | Nom du fichier image |
| Couverture | Case à cocher : image utilisée comme couverture de la galerie |
| Légende | Texte affiché sous la vignette et dans la visionneuse |
| Miniature | Aperçu cliquable |

Avec le tri **Manuel**, les images se déplacent par glisser-déposer et l'ordre est enregistré automatiquement.

### Options de la galerie (`/option/gallery/<id>`)

Permet de modifier le nom, le dossier cible, le tri, le plein écran automatique et l'affichage du contenu de la page. Renommer la galerie change son identifiant, donc son adresse.

> Supprimer une galerie supprime sa configuration, ses légendes et son ordre, mais pas les images du dossier.

## Options du module

Page `/option/galleries` :

| Option | Description |
|---|---|
| Masquer l'index des galeries lorsque le module ne contient qu'une seule galerie | Affiche directement la galerie unique (les boutons de retour sont alors masqués) |
| Position du bouton de retour à l'index des galeries | Masqué, au-dessus, en dessous, ou au-dessus et en dessous |
| Alignement du bouton de retour | À gauche, au centre ou à droite |

## Thème

Page `/theme`. Les réglages s'appliquent à toutes les galeries du module. À l'enregistrement, le module génère la feuille de style `theme.css` de l'instance (dans le dossier de données du module, sous `gallery/<page>/`). Toutes les règles de cette feuille sont limitées au conteneur de l'instance (classe `galleryScope-<identifiant de l'instance>`) : deux galeries de thèmes différents sur la même page ne se mélangent pas. Les modèles `ressource/vartheme.css` et `ressource/theme.css` portent le jeton `#scope#`, remplacé à la génération.

| Groupe | Réglages |
|---|---|
| Vignettes | Alignement (à gauche, au centre, à droite, distribué avec ou sans marges), largeur (très petite à très grande, ou proportionnelle), hauteur, marge, bordure et sa couleur, arrondi des angles, ombre et sa couleur, opacité au survol |
| Légendes | Couleur du texte, couleur d'arrière-plan, hauteur, alignement |

Les vignettes passent à 90 % de la largeur sur les écrans de 432 px et moins.

## Permissions et profils

Le module ajoute ses permissions aux formulaires de profils :

| Permission | Effet |
|---|---|
| Ajouter une galerie | Accès à `/add` |
| Éditer une galerie | Accès à `/edit` |
| Effacer une galerie | Accès à `/delete` |
| Options des galeries | Accès à `/option` |
| Thème des galeries | Accès à `/theme` |

L'accès à `/config` est accordé dès que l'une de ces permissions est cochée.

## Structure du module

```
gallery/
├── gallery.php               Classe principale du module
├── changes.md                Historique des versions
├── enum.json                 Description du module
├── i18n/                     Fichiers de traduction (JSON)
│   ├── de.json  en_EN.json  es.json  fr_FR.json
│   └── gr_GR.json  it.json  pt_PT.json  tr_TR.json
├── profil/                   Permissions ajoutées aux profils utilisateurs
│   ├── main/                 Traitement des formulaires (add / edit)
│   └── view/                 Formulaires de permissions (add / edit)
├── ressource/
│   ├── defaultdata.php       Thème et options par défaut (classe `galleryDefaultData`)
│   ├── vartheme.css          Variables CSS du thème (gabarit, jeton `#scope#`)
│   └── theme.css             Règles CSS des vignettes et légendes (gabarit, jeton `#scope#`)
├── vendor/
│   └── tablednd/             Glisser-déposer des lignes de tableau
└── view/
    ├── index/                Liste des galeries
    ├── gallery/              Affichage d'une galerie
    ├── config/               Liste de gestion
    ├── add/  edit/           Création et édition
    ├── option/               Options du module et d'une galerie
    └── theme/                Thème
```

## Données stockées

Les données sont enregistrées par instance du module :

| Clé | Contenu |
|---|---|
| `content` | Une entrée par galerie, identifiée par son id (voir ci-dessous) |
| `config` | `showUniqueGallery`, `backPosition`, `backAlign`, `versionData` |
| `theme` | Réglages du thème (`thumbAlign`, `thumbWidth`, `thumbHeight`, `thumbMargin`, `thumbBorder`, `thumbBorderColor`, `thumbOpacity`, `thumbShadows`, `thumbShadowsColor`, `thumbRadius`, `legendHeight`, `legendAlign`, `legendTextColor`, `legendBgColor`) et chemin de la feuille de style `style` |

Une galerie (`content`) contient :

- `config` : `name`, `directory`, `homePicture`, `sort`, `position`, `fullScreen`, `showPageContent`
- `legend` : légende de chaque image, la clé étant le nom du fichier sans les points
- `position` : ordre manuel de chaque image, avec la même clé

Le dossier de données du module (`site/data/gallery/`) contient en plus, pour chaque instance, le fichier `theme.css` généré.

Les identifiants d'images sont obtenus en retirant les points du nom de fichier : deux fichiers dont les noms ne diffèrent que par leurs points (`a.b.jpg` et `ab.jpg`) partagent la même légende et la même position. Ce schéma est conservé pour ne pas perdre les légendes déjà saisies.

## Plusieurs galeries sur une même page

Chaque instance du module (identifiant `gallery`, `gallery-2`, etc.) possède ses propres données, sa feuille de style et son conteneur HTML. Les identifiants HTML, les sélecteurs CSS et le script de la visionneuse portent l'identifiant de l'instance : la visionneuse d'une galerie ne contient que ses images, et le mode plein écran automatique ne concerne que sa galerie.

### Duplication de page

La duplication d'une page copie le dossier de données de chaque instance, donc sa feuille de style. Comme celle-ci est limitée à l'ancienne instance, le module la vérifie à chaque affichage et la régénère pour la nouvelle instance si besoin.

### Miniatures

Le dossier des miniatures se déduit du dossier d'images en remplaçant le seul segment `source` de la racine du gestionnaire de fichiers par `thumb`. Un dossier dont le nom contient « source » (par exemple `ressources`) n'est pas modifié.

Les anciennes données (versions antérieures à 3.1) sont migrées automatiquement au premier accès.

## Traductions

Langues fournies : allemand (`de`), anglais (`en_EN`), espagnol (`es`), français (`fr_FR`), grec (`gr_GR`), italien (`it`), portugais (`pt_PT`), turc (`tr_TR`).

Les chaînes françaises du code source servent de clés : `fr_FR.json` contient la liste des clés à traduire, ses valeurs sont vides. Pour ajouter une langue, copier ce fichier, le renommer avec le code de la langue et renseigner les valeurs.

## Composants tiers

- [TableDnD](https://github.com/isocra/TableDnD) 1.0.5 : glisser-déposer des lignes de tableau (licence MIT incluse dans `vendor/tablednd`)
- SimpleLightbox, TinyColorPicker et jQuery : fournis par ZwiiCMS

## Historique des versions

Le détail complet est dans [`changes.md`](changes.md).

## Licence

Voir l'en-tête du fichier `gallery.php`.

© 2008-2018 Rémi Jean — © 2018-2026 Frédéric Tempez
