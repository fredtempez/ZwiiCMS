# Module Formulaire pour ZwiiCMS

Module de formulaire de contact personnalisable pour [ZwiiCMS](http://zwiicms.fr/) : définition libre des champs, envoi des réponses par e-mail, conservation des réponses dans le site, consultation et export CSV.

- **Nom affiché dans ZwiiCMS** : Formulaire
- **Auteurs** : Rémi Jean (2008-2018), Frédéric Tempez (2018-2026)
- **Multi-module** : non (`MULTIMODULE = false`), peut cohabiter avec d'autres modules dans une même page. Un seul form par page est recommandé.
- **Utilisé par** : le module `database`, qui en est un fork et en réutilise la classe d'initialisation

---

## Sommaire

- [Module Formulaire pour ZwiiCMS](#module-formulaire-pour-zwiicms)
  - [Sommaire](#sommaire)
  - [Fonctionnalités](#fonctionnalités)
  - [Prérequis](#prérequis)
  - [Installation](#installation)
  - [Utilisation](#utilisation)
    - [Côté visiteur](#côté-visiteur)
    - [Côté éditeur](#côté-éditeur)
    - [Adresses des actions](#adresses-des-actions)
  - [Définition des champs](#définition-des-champs)
  - [Options du formulaire](#options-du-formulaire)
  - [Réponses enregistrées](#réponses-enregistrées)
  - [Export CSV](#export-csv)
  - [Permissions et profils](#permissions-et-profils)
  - [Structure du module](#structure-du-module)
  - [Données stockées](#données-stockées)
  - [Traductions](#traductions)
  - [Composants tiers](#composants-tiers)
  - [Historique des versions](#historique-des-versions)
  - [Licence](#licence)

---

## Fonctionnalités

- Définition libre des champs, réordonnables par glisser-déposer
- Sept types de champs : étiquette, texte, grand texte, adresse électronique, sélection, case à cocher, date
- Champs obligatoires au choix
- Envoi des réponses par e-mail à des rôles, à un membre et/ou à une adresse libre
- Option de réponse directe à l'expéditeur (adresse du champ e-mail renseigné par le visiteur)
- Captcha facultatif
- Redirection facultative vers une page du site après l'envoi
- Gabarit réglable : alignement, décalage et largeur du formulaire
- Texte du bouton d'envoi et sujet du mail personnalisables
- Conservation des réponses dans le site, avec consultation, suppression et export CSV
- Une réponse n'est enregistrée et envoyée que si le captcha est correct et les champs obligatoires sont renseignés
- Droits par profil sur les options, la consultation, la suppression et l'export
- Interface traduite en 8 langues

## Prérequis

| Besoin | Détail |
|---|---|
| ZwiiCMS | **16.0.00c** ou supérieur à partir de la version 4.9 du module (captcha stocké en session côté serveur) |
| Messagerie | Un SMTP configuré dans ZwiiCMS est nécessaire pour l'envoi des réponses par e-mail |
| Composants du cœur | jQuery, SortableJS (fournis par ZwiiCMS) |

## Installation

1. Copier le dossier `form` dans le répertoire `module/` de votre ZwiiCMS (chemin final : `module/form/form.php`).
2. Créer ou éditer une page et choisir le module **Formulaire** comme type de contenu.
3. Au premier accès, la configuration par défaut est créée automatiquement.
4. Définir les champs (page de configuration) puis régler les options.

> Si vous utilisez aussi le module `database`, laissez le module `form` installé : `database` en dépend.

## Utilisation

### Côté visiteur

La page affiche le formulaire défini par l'éditeur. À l'envoi, les réponses sont enregistrées et, si au moins un destinataire est défini, envoyées par e-mail. Une notification confirme l'envoi, avec redirection éventuelle vers une autre page. Si aucun champ n'est défini, la page affiche « Le formulaire ne contient aucun champ. ».

### Côté éditeur

La page de configuration (`/config`, titre **Configuration du module**) permet de définir les champs. Trois boutons sont disponibles :

| Bouton | Fonction |
|---|---|
| Retour | Retour à l'édition de la page |
| Voir et exporter les données | Ouvre la page des réponses enregistrées (`/data`) |
| Options de configuration | Ouvre les options du formulaire (`/option`) |

### Adresses des actions

Adresses relatives à la page du formulaire (`/ma-page-formulaire`) :

| Adresse | Rôle minimal | Fonction |
|---|---|---|
| `/` | Visiteur | Affichage et envoi du formulaire |
| `/config` | Éditeur | Définition des champs |
| `/option` | Éditeur | Options du formulaire |
| `/data` | Éditeur | Réponses enregistrées |
| `/delete/<id>` | Éditeur | Supprimer une réponse |
| `/deleteall` | Éditeur | Supprimer toutes les réponses |
| `/export2csv` | Éditeur | Exporter les réponses en CSV |

> Chaque action est en plus soumise aux permissions du profil de l'utilisateur (voir [Permissions et profils](#permissions-et-profils)).

## Définition des champs

Page `/config`. Pour chaque champ :

| Élément | Description |
|---|---|
| Intitulé | Nom du champ, utilisé comme libellé dans le formulaire, dans le mail et comme clé de stockage |
| Type | Voir le tableau ci-dessous |
| Liste des valeurs | Pour le type **Sélection** : valeurs séparées par des virgules (`valeur1,valeur2,...`) |
| Champ obligatoire | Rend la saisie obligatoire |
| Déplacer | Réordonne les champs par glisser-déposer |

| Type | Rendu | Valeur enregistrée |
|---|---|---|
| Étiquette | Titre de section, sans option | Aucune saisie |
| Champ texte | Texte sur une ligne | Texte |
| Grand champ texte | Zone de texte multiligne | Texte |
| Adresse électronique | Champ contrôlé comme adresse e-mail | Adresse |
| Sélection | Liste déroulante | Valeur choisie |
| Case à cocher | Case à cocher | `X` si cochée |
| Date | Sélecteur de date | Date au format `jj/mm/aaaa` |

## Options du formulaire

Page `/option`.

**Validation du formulaire**

| Option | Description |
|---|---|
| Captcha | Affiche un captcha (type et force définis dans la configuration de connexion de ZwiiCMS) |
| Étiquette du bouton de soumission | Texte du bouton ; vide : « Envoyer » |
| Redirection après soumission | Redirige vers la page du site choisie |

**Gabarit**

| Option | Description |
|---|---|
| Alignement du formulaire | À gauche, au centre ou à droite |
| Décalage à gauche | Aucun, une colonne ou deux colonnes |
| Largeur | De six à douze colonnes (réduite automatiquement si décalage + largeur dépassent 12) |

**Courriel** : à activer avec la case « Envoyer par mail les données saisies »

| Option | Description |
|---|---|
| Sujet du mail | Vide : « Nouveau message en provenance de votre site » |
| À tous les rôles depuis | Envoie à tous les utilisateurs dont le rôle est supérieur ou égal au rôle choisi |
| À un membre | Envoie à un utilisateur désigné |
| À une adresse électronique | Envoie à une adresse libre |
| Type de signature / Logo / Largeur du logo | Nom du site ou logo, largeur de 40 à 100 % (enregistrés dans la configuration du module) |
| Répondre à l'expéditeur | Utilise l'adresse du premier champ de type e-mail renseigné par le visiteur comme adresse de réponse du mail de notification |

Les trois destinataires se cumulent. Sans rôle, sans membre et sans adresse, aucun mail n'est envoyé mais les réponses restent enregistrées.

## Réponses enregistrées

Page `/data` (titre **Export des données**) : liste paginée (20 par page) des réponses, de la plus récente à la plus ancienne, avec le contenu de chaque champ et un bouton de suppression. Deux boutons permettent de supprimer toutes les réponses (avec confirmation) et d'exporter en CSV.

## Export CSV

Le bouton **Exporter toutes les données** (`/export2csv`) écrit un fichier CSV (séparateur `;`, guillemets doubles) dans le dossier `source/data/` du gestionnaire de fichiers. La première ligne contient les noms des champs. Le dossier est créé s'il n'existe pas. Le nom du fichier est de la forme `data-jjmmaaaa-hhmm-nn.csv` (`nn` : nombre aléatoire) et une notification en indique le nom. S'il n'y a aucune réponse, un message le signale.

## Permissions et profils

Le module ajoute ses permissions aux formulaires de profils (section **Permissions Formulaire**) :

| Permission | Effet |
|---|---|
| Options du formulaire | Accès à `/option` |
| Consulter les réponses | Accès à `/data` |
| Effacer une réponse | Suppression d'une réponse |
| Effacer toutes les réponses | Suppression de toutes les réponses |
| Exporter les réponses | Export CSV (clé `export2csv`) |

L'accès à `/config` (définition des champs) est accordé dès que l'une de ces permissions est cochée, à la création comme à la modification du profil.

## Structure du module

```
form/
├── form.php                  Classe principale du module
├── changes.md                Historique des versions
├── enum.json                 Description du module
├── i18n/                     Fichiers de traduction (JSON)
│   ├── de.json  en_EN.json  es.json  fr_FR.json
│   └── gr_GR.json  it.json  pt_PT.json  tr_TR.json
├── profil/                   Permissions ajoutées aux profils utilisateurs
│   ├── main/                 Traitement des formulaires (add / edit)
│   └── view/                 Formulaires de permissions (add / edit)
├── ressource/
│   └── defaultdata.php       Valeurs par défaut de la configuration
└── view/
    ├── index/                Formulaire affiché au visiteur
    ├── config/               Définition des champs
    ├── option/               Options du formulaire
    └── data/                 Réponses enregistrées
```

## Données stockées

| Clé | Contenu |
|---|---|
| `input` | Liste des champs : `name`, `position`, `required`, `type`, `values` |
| `data` | Réponses, numérotées à partir de 1 ; chaque réponse associe l'intitulé de chaque champ à sa valeur |
| `config` | Options : `button`, `captcha`, `role`, `user`, `mail`, `pageId`, `subject`, `replyto`, `signature`, `logoUrl`, `logoWidth`, `offset`, `width`, `align` |

La configuration est initialisée avec les valeurs de `ressource/defaultdata.php` au premier accès (captcha désactivé, signature `text`, largeur 12 colonnes, alignement à gauche, aucun destinataire).

## Traductions

Langues fournies : allemand (`de`), anglais (`en_EN`), espagnol (`es`), français (`fr_FR`), grec (`gr_GR`), italien (`it`), portugais (`pt_PT`), turc (`tr_TR`).

Les chaînes françaises du code source servent de clés : `fr_FR.json` contient la liste des clés à traduire, ses valeurs sont vides. Pour ajouter une langue, copier ce fichier, le renommer avec le code de la langue et renseigner les valeurs.

## Composants tiers

- SortableJS : glisser-déposer des champs, fourni par ZwiiCMS (depuis la version 4.8)

## Historique des versions

Le détail complet est dans [`changes.md`](changes.md).

## Licence

Voir l'en-tête du fichier `form.php`.

© 2008-2018 Rémi Jean — © 2018-2026 Frédéric Tempez
