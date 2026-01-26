# Analyse et Contexte du Projet ZwiiCMS

## Vue d'ensemble

**ZwiiCMS** est un CMS (Content Management System) léger, sans base de données (flat-file), conçu pour permettre la création et la gestion de sites web sans aucune connaissance en programmation. Le projet privilégie la simplicité, la performance et l'autonomie.

**Version actuelle** : 14.00.00  
**Date d'analyse** : 25 janvier 2026

---

## 📌 Historique et Auteurs

- **Créateur original** : [Rémi Jean](https://remijean.fr/) (2008-2018)
- **Mainteneur actuel** : Frédéric Tempez (2018-2026)
- **Forge officielle** : [Chapril GitLab - ZwiiCMS-Team](https://forge.chapril.org/ZwiiCMS-Team/ZwiiCMS/releases)
- **Site web** : [zwiicms.fr](http://zwiicms.fr/)
- **Forum** : [forum.zwiicms.com](http://forum.zwiicms.com/)

---

## 🏗️ Architecture du Projet

### Structure des Répertoires

```
ZwiiCMS/
├── core/                       # Cœur du système
│   ├── class/                  # Classes fondamentales
│   │   ├── router.class.php       # Routage et contrôle d'accès
│   │   ├── helper.class.php       # Fonctions utilitaires
│   │   ├── template.class.php     # Génération HTML
│   │   ├── autoload.php           # Chargement automatique
│   │   ├── jsondb/                # Base de données JSON améliorée
│   │   │   ├── JsonDb.class.php      # Moteur JsonDb
│   │   │   └── Dot.class.php         # Notation pointée
│   │   ├── phpmailer/             # Envoi d'emails
│   │   └── sitemap/               # Génération de sitemaps
│   ├── core.js.php             # JavaScript principal
│   ├── core.php                # Classe commune (2139 lignes)
│   ├── layout/                 # Templates de mise en page
│   │   ├── main.php               # Layout principal
│   │   ├── light.php              # Layout allégé
│   │   └── blank.php              # Layout vide
│   ├── module/                 # Modules du cœur (non supprimables)
│   │   ├── config/                # Configuration système
│   │   ├── dashboard/             # Tableau de bord
│   │   ├── install/               # Installation initiale
│   │   ├── language/              # Gestion multilingue
│   │   ├── maintenance/           # Mode maintenance
│   │   ├── page/                  # Gestion des pages
│   │   ├── plugin/                # Gestion des plugins
│   │   ├── sitemap/               # Plan du site
│   │   ├── theme/                 # Personnalisation thèmes
│   │   └── user/                  # Gestion utilisateurs
│   ├── include/                # Fichiers d'inclusion
│   │   └── update.inc.php         # Mises à jour automatiques
│   └── vendor/                 # Librairies tierces
│       ├── tinymce/               # Éditeur WYSIWYG
│       ├── jquery/
│       ├── filemanager/
│       ├── datatables/
│       ├── codemirror/
│       ├── lity/                  # Lightbox
│       ├── simplelightbox/
│       ├── tippy/                 # Tooltips
│       └── zwiico/                # Icônes personnalisées
├── module/                     # Modules de page (installables)
│   ├── blog/                      # Blog avec catégories
│   ├── download/                  # Téléchargements
│   ├── form/                      # Formulaires de contact
│   ├── gallery/                   # Galeries d'images
│   ├── news/                      # Actualités
│   ├── redirection/               # Redirections URL
│   ├── search/                    # Moteur de recherche
│   ├── slider/                    # Diaporamas
│   ├── suscribe/                  # Inscriptions/Newsletter
│   └── widget_meteo/              # Widget météo
├── site/                       # Données du site
│   ├── data/                      # Données JSON
│   │   ├── *.json                    # Bases de données (config, user, page, etc.)
│   │   └── [lang]/                   # Données multilingues (fr_FR, en_EN, etc.)
│   ├── backup/                    # Sauvegardes automatiques
│   ├── file/                      # Fichiers uploadés
│   │   ├── source/                   # Fichiers originaux
│   │   └── thumb/                    # Miniatures (640px)
│   ├── tmp/                       # Fichiers temporaires
│   └── i18n/                      # Traductions de l'interface
│       └── *.json                    # Fichiers de langue (fr_FR.json, en_EN.json, etc.)
├── index.php                   # Point d'entrée principal
├── robots.txt
├── sitemap.xml
├── .htaccess                   # Configuration Apache
├── LICENSE                     # Licence GNU GPL v3
├── README.md                   # Documentation anglaise
└── LISEZMOI.md                 # Documentation française
```

---

## 🔑 Concepts Clés

### 1. **Architecture Flat-File (sans base de données)**

- Toutes les données sont stockées en **JSON** dans `site/data/`
- **Bases de données principales** :
  - `config.json` : Configuration globale du site
  - `user.json` : Comptes utilisateurs
  - `page.json` : Structure des pages
  - `module.json` : Données des modules
  - `theme.json` : Paramètres du thème
  - `admin.json` : Configuration de l'administration
  - `locale.json` : Paramètres régionaux
  - `font.json` : Polices personnalisées
  - `blacklist.json` : Tentatives de connexion bloquées
  - `profil.json` : Profils utilisateurs personnalisés

### 2. **Système JsonDb Amélioré** (Nouveauté v14)

Le fichier [`JsonDb.class.php`](core/class/jsondb/JsonDb.class.php) offre :

#### Fonctionnalités avancées :
- **Cache APCu** : Activation automatique si disponible (3-5x plus rapide)
- **Minification JSON** : Réduction de 20-40% de la taille des fichiers
- **Compression gzip** : Économie de 50-70% d'espace disque
- **Chiffrement AES-256-CBC** : Protection des données sensibles
- **Lazy Loading** : Chargement à la demande des bases non essentielles

#### Bases essentielles (chargées au démarrage) :
```php
private $essentialDataFiles = [
    'config',   // Configuration système
    'user',     // Utilisateurs
    'locale',   // Paramètres régionaux
    'core',     // Données core
    'page',     // Pages
    'module'    // Modules
];
```

#### Bases secondaires (chargement différé) :
- `theme`, `font`, `admin`, `blacklist`, `language`, `profil`

### 3. **Système de Rôles et Permissions**

Définis dans [`core.php`](core/core.php#L24-L30) :

```php
const ROLE_BANNED    = -1  // Utilisateur banni
const ROLE_VISITOR   = 0   // Visiteur non connecté
const ROLE_MEMBER    = 1   // Membre (lecture seule)
const ROLE_EDITOR    = 2   // Éditeur (création/modification)
const ROLE_ADMIN     = 3   // Administrateur (tous pouvoirs)
```

**Système de profils** (v13+) :
- Jusqu'à 9 profils personnalisables par rôle
- Calcul : `(role * 10) + profil` (ex: Éditeur profil 2 = 22)
- Défini par `MAX_PROFILS = 10`

### 4. **Routage et Sécurité**

Le fichier [`router.class.php`](core/class/router.class.php) gère :

#### A. Contrôle d'accès concurrent (lignes 603-613)
```php
// Déconnexion automatique des sessions multiples
if (
    $this->isConnected() === true
    and ($this->getUser('role') === common::ROLE_BANNED
        or ($_SESSION['csrf'] !== $this->getData(['user', $this->getUser('id'), 'accessCsrf'])
            and $this->getData(['config', 'connect', 'autoDisconnect']) === true)
    )
) {
    $user = new user;
    $user->logout();
}
```

**Fonctionnement de `autoDisconnect`** :
- ✅ Déconnecte toutes les anciennes sessions lors d'une nouvelle connexion
- Utilise le token CSRF comme identifiant de session unique
- Empêche les connexions simultanées sur plusieurs postes

#### B. Détection d'édition concurrente
```php
const ACCESS_TIMER = 1800; // 30 minutes

// Pages sensibles surveillées
public static $concurrentAccess = [
    'config',
    'edit',
    'language',
    'plugin',
    'theme',
    'user'
];
```

Si deux utilisateurs accèdent simultanément à la même page d'édition :
- Le second utilisateur voit un message de verrouillage
- Affiche le nom de l'utilisateur qui édite
- Expiration automatique après 30 minutes d'inactivité

#### C. Protection CSRF
```php
// Génération token au démarrage de session
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(openssl_random_pseudo_bytes(64));
}

// Vérification sur chaque requête POST
public function checkCSRF() {
    return hash_equals($_POST['csrf'], $_SESSION['csrf']);
}
```

### 5. **Gestion Multilingue**

#### Langues d'interface (UI) supportées :
43 langues disponibles dans [`core.php`](core/core.php#L194-L236) :
- Français (fr_FR), Anglais (en_EN), Allemand (de), Espagnol (es)
- Italien (it), Portugais (pt_PT/pt_BR), Russe (ru), Chinois (zh_CN)
- Japonais (ja), Arabe, Hébreu (he_IL), Grec (gr_GR), etc.

#### Langues de contenu :
- Chaque langue a son propre dossier dans `site/data/[lang]/`
- Fichier `.default` marque la langue par défaut
- Session `$_SESSION['ZWII_SITE_CONTENT']` détermine la langue active

#### Fichiers de traduction :
```
site/i18n/
├── fr_FR.json    # Interface française
├── en_EN.json    # Interface anglaise
└── ...
```

---

## 🎨 Système de Thèmes

### Génération Dynamique de CSS

Méthode [`buildThemeCss()`](core/class/router.class.php#L492-L650) :

#### Variables CSS générées :
```css
:root {
    /* Couleurs */
    --body-bg: #...;
    --site-bg: #...;
    --text-color: #...;
    --link-color: #...;
    
    /* Dimensions */
    --site-width: 1170px;
    --header-height: 150px;
    --menu-height: 15px;
    
    /* Polices */
    --font-text: 'Arial', sans-serif;
    --font-title: 'Georgia', serif;
    
    /* Boutons (variantes automatiques) */
    --btn-bg: #...;
    --btn-hover: #...;  /* Assombri de 10% */
    --btn-active: #...;  /* Assombri de 20% */
}
```

#### Cache intelligent :
- Hash MD5 de la configuration pour détecter les changements
- Regénération uniquement si modification
- Headers de cache busting automatiques

### Polices de caractères

3 types supportés :

1. **Web-safe** : 12 polices système (Arial, Georgia, Verdana, etc.)
2. **Google Fonts** : Importées dynamiquement
3. **Fichiers locaux** : Uploadées dans `site/file/source/`

Résolution via [`resolveFonts()`](core/class/router.class.php#L141-L163)

---

## 📦 Système de Modules

### Structure d'un module

```
module/nom-module/
├── nom-module.php          # Contrôleur principal
├── enum.json               # Métadonnées (version, licence)
├── i18n/                   # Traductions du module
│   ├── fr_FR.json
│   └── en_EN.json
└── view/
    ├── index/
    │   ├── index.php          # Vue
    │   ├── index.css          # Styles
    │   └── index.js.php       # Scripts JS
    └── config/
        ├── config.php
        ├── config.css
        └── config.js.php
```

### Modules du cœur (non supprimables)

Listés dans [`core.php#L87-L97`](core/core.php#L87-L97) :
- `config` : Paramètres système, sauvegardes, logs
- `dashboard` : Tableau de bord
- `install` : Installation et mises à jour
- `language` : Gestion des langues
- `maintenance` : Mode maintenance
- `page` : Création/édition de pages
- `plugin` : Installation de modules tiers
- `sitemap` : Génération du plan de site
- `theme` : Personnalisation visuelle
- `user` : Gestion des utilisateurs

### Modules de page (installables)

- **Blog** : Articles avec catégories, commentaires, RSS
- **Download** : Gestion de téléchargements avec statistiques
- **Form** : Formulaires de contact avec protection CAPTCHA
- **Gallery** : Galeries d'images avec lightbox
- **News** : Fil d'actualités
- **Redirection** : Redirections 301/302
- **Search** : Moteur de recherche interne
- **Slider** : Diaporamas d'images
- **Suscribe** : Formulaires d'inscription/newsletter

---

## 🔧 Fonctionnalités Principales

### 1. Gestion des Pages

- **Éditeur WYSIWYG** : TinyMCE avec configuration personnalisée
- **Barres latérales** : Left/Right bars réutilisables
- **Hiérarchie** : Pages parentes/enfants avec fil d'Ariane
- **Positions de module** : Top, Bottom, Free (avec tag `[MODULE]`)
- **Visibilité** : Publique, privée, cachée du menu
- **CSS/JS inline** : Scripts personnalisés par page

### 2. Sauvegardes et Restauration

Depuis [`config/config.php`](core/module/config/config.php) :

- **Sauvegardes automatiques** : Quotidiennes si activées
- **Rotation** : Conservation des 30 derniers jours
- **Compression** : Archives ZIP avec horodatage
- **Restauration** : En un clic depuis l'interface
- **Export** : Téléchargement des sauvegardes

### 3. Sécurité

#### Journalisation
- Fichier `site/data/journal.log`
- Rotation automatique (max 1 Go, 5 archives)
- Enregistrement : connexions, modifications, erreurs

#### Blacklist
- Blocage temporaire après échecs de connexion
- Stockage dans `site/data/blacklist.json`
- Réinitialisation manuelle possible

#### Protection des fichiers
```apache
# site/data/.htaccess
deny from all
```

### 4. Référencement (SEO)

- **Meta title/description** personnalisables par page
- **Sitemap.xml** généré automatiquement
- **URLs propres** via `.htaccess`
- **Robots.txt** configurable
- **Flux RSS** pour le blog et les actualités

### 5. Performances

#### Optimisations v14 :
- **Cache APCu** : Si disponible sur le serveur
- **Minification** : CSS/JS automatique en production
- **Lazy loading** : Chargement différé des bases secondaires
- **Compression gzip** : Réduction de 50-70% des fichiers JSON

---

## 🔄 Système de Mise à Jour

### Mises à jour automatiques

Constantes dans [`core.php#L57-L70`](core/core.php#L57-L70) :

```php
const ZWII_VERSION = '14.00.00';
const ZWII_UPDATE_URL = 'https://codeberg.org/fredtempez/cms-update/raw/branch/master/';
const ZWII_UPDATE_CHANNEL = 'v14';
```

### Processus de mise à jour :

1. **Vérification** : Télécharge `[channel]/version`
2. **Comparaison** : Compare avec `ZWII_VERSION`
3. **Téléchargement** : Archive `[channel]/update.tar.gz`
4. **Vérification MD5** : Intégrité via `[channel]/update.md5`
5. **Extraction** : Décompression dans un répertoire temporaire
6. **Sauvegarde** : Création automatique avant mise à jour
7. **Application** : Remplacement des fichiers
8. **Scripts** : Exécution de `core/include/update.inc.php`

---

## 🌐 Internationalisation (i18n)

### Architecture des traductions

#### 1. Interface d'administration
```php
// Chargement dans core.php
self::$dialog = json_decode(
    file_get_contents(self::I18N_DIR . self::$i18nUI . '.json'), 
    true
);

// Usage dans le code
helper::translate('Texte à traduire');
```

#### 2. Traductions des modules
```
module/blog/i18n/
├── fr_FR.json    # Traductions françaises
├── en_EN.json    # Traductions anglaises
└── ...
```

Chargées automatiquement si le module est actif.

#### 3. Contenu multilingue
- Chaque langue a son arborescence de pages complète
- Changement de langue via barre d'administration
- Cookie `ZWII_UI` pour mémoriser la préférence

---

## 💾 Stockage des Données

### Bases de données JSON

| Fichier | Description | Critique | Chiffrable |
|---------|-------------|----------|------------|
| `config.json` | Configuration globale | ✅ | ❌ |
| `core.json` | Données système | ✅ | ❌ |
| `user.json` | Comptes utilisateurs | ✅ | ✅ |
| `page.json` | Structure des pages | ✅ | ❌ |
| `module.json` | Données des modules | ✅ | ✅ |
| `theme.json` | Paramètres visuels | ⚠️ | ❌ |
| `admin.json` | Config administration | ⚠️ | ❌ |
| `locale.json` | Paramètres régionaux | ⚠️ | ❌ |
| `font.json` | Polices personnalisées | ⚠️ | ❌ |
| `blacklist.json` | IP bloquées | ⚠️ | ❌ |
| `language.json` | Langues disponibles | ⚠️ | ❌ |
| `profil.json` | Profils utilisateurs | ⚠️ | ✅ |

### Configuration JsonDb

Interface dans [`config/database.php`](core/module/config/) (v14) :

```php
// Exemple de configuration
[
    'minification' => true,      // Réduction de 20-40%
    'compression' => true,       // Réduction de 50-70%
    'encryption' => true,        // AES-256-CBC
    'encryptionKey' => 'clé64caractères...',
    'cache' => true,             // Cache APCu si disponible
    'cacheTTL' => 3600          // Durée de vie du cache
]
```

**Restrictions** :
- `config.json` et `core.json` : Minification uniquement (sécurité)
- Autres bases : Toutes les options disponibles

---

## 🔐 Gestion des Utilisateurs

### Données utilisateur

Structure dans `user.json` :

```json
{
    "userId": {
        "firstname": "Prénom",
        "lastname": "Nom",
        "mail": "email@example.com",
        "password": "$2y$10$hash...",
        "role": 3,
        "profil": 0,
        "language": "fr_FR",
        "accessUrl": "page/edit/accueil",
        "accessTimer": 1737824400,
        "accessCsrf": "token64caractères...",
        "permission": {
            "config": true,
            "page": true,
            "theme": true,
            "user": true
        }
    }
}
```

### Authentification

- **Hachage** : `password_hash()` avec `PASSWORD_DEFAULT` (bcrypt)
- **Sessions** : ID unique par site avec `md5($_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_FILENAME'])`
- **Cookies** : `httponly`, `samesite=Lax`, chemin dynamique
- **Token CSRF** : 128 caractères aléatoires régénérés à chaque session

### Permissions granulaires

Chaque utilisateur peut avoir des permissions spécifiques par module :

```php
// Vérification dans le code
if ($this->getUser('permission', 'config', 'backup')) {
    // Action autorisée
}
```

---

## 🛠️ Technologies Utilisées

| Catégorie | Technologie | Version | Usage |
|-----------|-------------|---------|-------|
| **Backend** | PHP | 7.2+ (8.0+ recommandé) | Cœur applicatif |
| **Stockage** | JSON | - | Base de données flat-file |
| **Frontend** | HTML5/CSS3 | - | Structure et style |
| | JavaScript | ES5+ | Interactivité |
| | jQuery | 3.x | Manipulation DOM |
| **Éditeur** | TinyMCE | 6.x | Éditeur WYSIWYG |
| **Code** | CodeMirror | 5.x | Éditeur de code |
| **Images** | SimpleLightbox | 2.x | Galeries |
| | Lity | 2.x | Lightbox |
| **Tableaux** | DataTables | 1.x | Tableaux dynamiques |
| **Icônes** | Zwiico | Custom | Jeu d'icônes maison |
| **Mail** | PHPMailer | 6.x | Envoi d'emails |
| **Utilitaires** | Tippy.js | 6.x | Tooltips |
| | Strftime | Polyfill | Dates multilingues |

---

## 📊 Processus d'Installation

Fichier : [`install/install.php`](core/module/install/install.php)

### Étapes automatiques :

1. **Détection de langue** : Sélection parmi les langues disponibles
2. **Création des répertoires** :
   - `site/data/`
   - `site/backup/`
   - `site/file/source/`
   - `site/file/thumb/`
   - `site/tmp/`
   - `site/i18n/`
3. **Initialisation des bases** : Création des fichiers JSON vides
4. **Compte administrateur** : Création du premier utilisateur
5. **Configuration par défaut** :
   - Timezone
   - Langue d'interface
   - Page d'accueil (Accueil)
   - Thème par défaut
6. **Protection des répertoires** : Copie des `.htaccess`
7. **Génération CSS** : Compilation du thème

### Prérequis techniques :

- PHP 7.2+ avec extensions : `json`, `mbstring`, `gd`, `zip`, `openssl`
- Apache avec `mod_rewrite` activé
- Droits d'écriture sur `site/`
- 50 Mo d'espace disque minimum

---

## 📂 Gestion des Fichiers

### Structure de stockage

```
site/file/
├── source/           # Fichiers originaux uploadés
│   ├── image.jpg
│   ├── document.pdf
│   └── ...
└── thumb/            # Miniatures (640px max)
    └── mini_image.jpg
```

### Miniatures automatiques

Constantes dans [`core.php#L48-L50`](core/core.php#L48-L50) :

```php
const THUMBS_SEPARATOR = 'mini_';
const THUMBS_WIDTH = 640;  // Pixels
```

Génération à la volée via la librairie GD.

### Gestionnaire de fichiers

Interface intégrée via FileManager :
- Upload par glisser-déposer
- Création de dossiers
- Renommage
- Suppression
- Prévisualisation d'images

---

## 🔍 Moteur de Recherche

Module [`search/search.php`](module/search/search.php) :

### Fonctionnalités :
- Recherche fulltext dans les pages
- Recherche dans les articles de blog
- Recherche dans les actualités
- Exclusion de pages spécifiques
- Mise en évidence des résultats

### Indexation :
- Automatique lors de la sauvegarde
- Suppression des balises HTML
- Découpage en mots-clés
- Stockage dans `module.json`

---

## 📝 Notes Techniques

### 1. Sessions multi-sites

Le système génère un nom de session unique par installation :

```php
$siteId = md5($_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_FILENAME']);
session_name('zwii_session_' . $siteId);
```

Permet d'héberger plusieurs instances Zwii sur le même serveur.

### 2. Chemins dynamiques

Cookie de session avec chemin dynamique :

```php
$scriptPath = dirname($_SERVER['SCRIPT_NAME']);
session_set_cookie_params([
    'path' => $scriptPath,
    'domain' => $_SERVER['SERVER_NAME'],
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax'
]);
```

Permet l'installation en sous-répertoire.

### 3. Proxy support

Configuration proxy dans `config.json` :

```json
{
    "proxyType": "http://",
    "proxyUrl": "proxy.example.com",
    "proxyPort": "8080"
}
```

Utilisé pour les mises à jour automatiques en environnement d'entreprise.

### 4. Mode maintenance

Redirection automatique vers `/maintenance` si :
- `config.maintenance` = `true`
- Utilisateur non connecté OU rôle < `ROLE_ADMIN`
- URL ≠ `/maintenance` et ≠ `/user/login`

---

## 🚀 Recommandations pour la Compression

### Fichiers à exclure de la distribution

```gitignore
site/backup/*              # Sauvegardes
site/tmp/*                 # Fichiers temporaires
site/data/journal.log      # Logs
site/data/blacklist.json   # Liste noire
site/file/source/*         # Fichiers uploadés (démo)
site/file/thumb/*          # Miniatures
.DS_Store                  # Métadonnées macOS
.git/                      # Historique Git
```

### Fichiers critiques à inclure

✅ **Obligatoires** :
- `core/` (intégralité)
- `module/` (modules de base)
- `site/data/.htaccess`
- `site/i18n/*.json` (au moins fr_FR et en_EN)
- `index.php`
- `.htaccess`
- `LICENSE`
- `README.md` / `LISEZMOI.md`

✅ **Recommandés** :
- `site/file/source/` (images de démo)
- `CHANGES.md` (historique des versions)
- `robots.txt`
- `sitemap.xml` (template vide)

### Structure de distribution recommandée

```
ZwiiCMS-14.00.00.zip
├── core/                  # Cœur complet
├── module/                # Modules de base
├── site/
│   ├── data/
│   │   └── .htaccess         # Protection
│   ├── backup/
│   │   └── .htaccess
│   ├── file/
│   │   ├── source/
│   │   │   └── .htaccess
│   │   └── thumb/
│   │       └── .htaccess
│   ├── tmp/
│   │   └── .htaccess
│   └── i18n/
│       ├── fr_FR.json
│       ├── en_EN.json
│       └── ...
├── index.php
├── .htaccess
├── LICENSE
├── README.md
├── LISEZMOI.md
├── CHANGES.md
├── robots.txt
└── sitemap.xml
```

### Optimisations avant compression

1. **Minification** (optionnelle) :
   - CSS : `core/core.css`, `core/vendor/**/*.css`
   - JS : `core/core.js.php`, `core/vendor/**/*.js`

2. **Suppression des commentaires** (déconseillé pour le support) :
   - Conserver les commentaires de licence en en-tête

3. **Compression** :
   - Format : ZIP (compatibilité universelle)
   - Niveau : Maximum (ratio 60-70%)
   - Taille estimée : 8-12 Mo (avec toutes les dépendances)

---

## 📊 Statistiques du Projet

| Métrique | Valeur |
|----------|--------|
| **Version** | 14.00.00 |
| **Fichiers PHP** | ~150 |
| **Lignes de code** | ~50 000 |
| **Modules cœur** | 10 |
| **Modules page** | 11 |
| **Langues UI** | 43 |
| **Librairies tierces** | 13 |
| **Poids installation** | ~10 Mo (compressé) |
| **Poids données** | ~500 Ko (site vide) |

---

## 🆚 Différences ZwiiCMS vs ZwiiCampus

| Caractéristique | ZwiiCMS | ZwiiCampus |
|----------------|---------|------------|
| **Public cible** | Sites web classiques | Formation en ligne (LMS) |
| **Espaces** | Multilingue simple | Multi-espaces pédagogiques |
| **Utilisateurs** | Gestion simple | Gestion avancée (groupes, profils) |
| **Modules** | Blog, Galerie, Download | Cours, Ressources, Ateliers |
| **Progression** | - | Suivi des apprenants |
| **Inscriptions** | - | Inscription libre/clé/groupe |
| **Rapports** | - | Export CSV des participants |
| **Base** | ZwiiCMS 14.x | ZwiiCMS 13.x adapté |

---

## 📚 Ressources Complémentaires

- **Documentation** : [zwiicms.fr/documentation](http://zwiicms.fr/documentation)
- **Forum** : [forum.zwiicms.com](http://forum.zwiicms.com/)
- **GitHub** : [github.com/fredtempez/ZwiiCMS](https://github.com/fredtempez/ZwiiCMS)
- **Mises à jour** : [forge.chapril.org/ZwiiCMS-Team/ZwiiCMS/releases](https://forge.chapril.org/ZwiiCMS-Team/ZwiiCMS/releases)
- **Modules tiers** : Disponibles via le module Plugin

---

## 📄 Licence

**Licence** : GNU General Public License v3.0 ou ultérieure

```
ZwiiCMS - CMS open source
Copyright (C) 2026 Frédéric Tempez

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.
```

**Changement de licence (v14)** : Passage de CC BY-NC-ND 4.0 à GPL v3.0 pour permettre :
- Distribution commerciale
- Modifications et dérivés
- Meilleure compatibilité avec l'écosystème open source

---

## 🔮 Évolutions Futures (Roadmap)

D'après [`CHANGES.md`](CHANGES.md) :

### Version 14.x (en cours)
- ✅ Base de données JsonDb améliorée
- ✅ Cache APCu
- ✅ Compression et chiffrement
- ⏳ Interface de configuration des bases
- ⏳ Optimisation des performances

### Prévisions futures
- Multi-sites natif
- API REST
- Marketplace de thèmes/modules
- Éditeur de blocs (Gutenberg-like)
- PWA (Progressive Web App)

---

**Date de cette analyse** : 25 janvier 2026  
**Version analysée** : ZwiiCMS 14.00.00  
**Analyste** : Documentation automatisée
