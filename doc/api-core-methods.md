# Documentation des méthodes publiques du core ZwiiCMS

Cette documentation détaille les méthodes publiques de la classe `common` du core ZwiiCMS pour les développeurs.

## Table des matières

1. [Méthodes de gestion des données](#méthodes-de-gestion-des-données)
2. [Méthodes de gestion des utilisateurs](#méthodes-de-gestion-des-utilisateurs)
3. [Méthodes de gestion des pages](#méthodes-de-gestion-des-pages)
4. [Méthodes de gestion des entrées/sorties](#méthodes-de-gestion-des-entréessorties)
5. [Méthodes de gestion des fichiers](#méthodes-de-gestion-des-fichiers)
6. [Méthodes de gestion des templates](#méthodes-de-gestion-des-templates)
7. [Méthodes utilitaires](#méthodes-utilitaires)
8. [Constantes utiles](#constantes-utiles)

---

## Méthodes de gestion des données

### `setData($keys = [], $save = null)`

Sauvegarde des données dans la base JSON.

**Paramètres :**
- `$keys` (array) : Clé(s) des données (les premiers éléments sont le chemin, le dernier est la valeur)
- `$save` (bool|null) : Stratégie de sauvegarde
  - `null` (défaut) : Utilise la configuration actuelle de JsonDb
  - `true` : Force la sauvegarde immédiate
  - `false` : Empêche la sauvegarde immédiate (pour les traitements par lots)

**Retour :** bool - Succès de l'opération

**Exemple :**
```php
// Sauvegarder une configuration
$this->setData(['config', 'siteTitle', 'Mon site'], true);

// Sauvegarder des données de module
$this->setData(['module', 'blog', 'posts', $postId, 'title', 'Nouveau titre']);
```

### `getData($keys = [])`

Accède aux données dans la base JSON.

**Paramètres :**
- `$keys` (array) : Clé(s) des données à récupérer

**Retour :** mixed - Données trouvées ou null

**Exemple :**
```php
// Récupérer la configuration du site
$config = $this->getData(['config']);

// Récupérer le titre d'une page
$title = $this->getData(['page', 'accueil', 'title']);

// Récupérer tous les articles d'un blog
$posts = $this->getData(['module', 'blog', 'posts']);
```

### `deleteData($keys)`

Supprime des données de la base JSON.

**Paramètres :**
- `$keys` (array) : Clé(s) des données à supprimer

**Retour :** bool - Succès de l'opération

**Exemple :**
```php
// Supprimer une page
$this->deleteData(['page', 'ancienne-page']);

// Supprimer un article de blog
$this->deleteData(['module', 'blog', 'posts', $articleId]);
```

### `saveDB($module)`

Force la sauvegarde immédiate d'une base de données.

**Paramètres :**
- `$module` (string) : Nom du module/base à sauvegarder

**Retour :** void

**Exemple :**
```php
// Forcer la sauvegarde de la base de configuration
$this->saveDB('config');
```

---

## Méthodes de gestion des utilisateurs

### `getUser($key, $perm1 = null, $perm2 = null)`

Récupère les informations de l'utilisateur connecté.

**Paramètres :**
- `$key` (string) : Clé de la propriété utilisateur
  - `'id'` : Retourne l'ID de l'utilisateur depuis la session
  - Autre clé : Propriété de l'utilisateur (ex: 'role', 'pseudo', 'mail')

**Retour :** mixed
- string|int|array pour les propriétés utilisateur
- null si l'utilisateur n'est pas connecté

**Exemple :**
```php
// Récupérer l'ID de l'utilisateur
$userId = $this->getUser('id');

// Récupérer le rôle de l'utilisateur
$role = $this->getUser('role');

// Récupérer le pseudo
$pseudo = $this->getUser('pseudo');
```

### `isConnected()`

Vérifie si un utilisateur est connecté.

**Paramètres :** Aucun

**Retour :** bool - `true` si l'utilisateur est connecté, sinon `false`

**Exemple :**
```php
if ($this->isConnected()) {
    echo "Utilisateur connecté : " . $this->getUser('pseudo');
} else {
    echo "Visiteur anonyme";
}
```

### `signature($userId)`

Retourne la signature d'un utilisateur selon son format de signature configuré.

**Paramètres :**
- `$userId` (string) : ID de l'utilisateur

**Retour :** string - Signature formatée

**Exemple :**
```php
// Afficher la signature d'un article
echo "Publié par " . $this->signature($authorId);
```

### `getUserPath($userId = null)`

Retourne le chemin du dossier autorisé pour l'utilisateur selon son rôle et profil.

**Paramètres :**
- `$userId` (string|null) : ID de l'utilisateur (utilise l'utilisateur courant si null)

**Retour :** string - Chemin du dossier autorisé ou 'none'

**Exemple :**
```php
// Récupérer le chemin de l'utilisateur courant
$userPath = $this->getUserPath();

// Récupérer le chemin d'un utilisateur spécifique
$userPath = $this->getUserPath('user123');
```

---

## Méthodes de gestion des pages

### `getPage($page, $lang)`

Lit le contenu d'une page.

**Paramètres :**
- `$page` (string) : ID de la page
- `$lang` (string) : Langue de la page

**Retour :** string - Contenu de la page

**Exemple :**
```php
// Lire le contenu de la page d'accueil
$content = $this->getPage('accueil', 'fr_FR');
```

### `setPage($page, $value, $lang)`

Écrit le contenu d'une page.

**Paramètres :**
- `$page` (string) : ID de la page
- `$value` (string) : Contenu de la page
- `$lang` (string) : Langue de la page

**Retour :** int - Nombre d'octets écrits

**Exemple :**
```php
// Mettre à jour le contenu d'une page
$bytesWritten = $this->setPage('accueil', '<h1>Bienvenue</h1>', 'fr_FR');
```

### `deletePage($page, $lang)`

Supprime le fichier de contenu d'une page.

**Paramètres :**
- `$page` (string) : ID de la page
- `$lang` (string) : Langue de la page

**Retour :** bool - Statut de la suppression

**Exemple :**
```php
// Supprimer le contenu d'une page
$success = $this->deletePage('ancienne-page', 'fr_FR');
```

### `getHierarchy($parentId = null, $onlyVisible = true, $onlyBlock = false)`

Accède à la liste des pages parents et de leurs enfants.

**Paramètres :**
- `$parentId` (int|null) : ID de la page parent (null pour toutes les pages)
- `$onlyVisible` (bool) : Afficher seulement les pages visibles
- `$onlyBlock` (bool) : Afficher seulement les pages de type barre

**Retour :** array - Hiérarchie des pages

**Exemple :**
```php
// Récupérer toute la hiérarchie visible
$hierarchy = $this->getHierarchy();

// Récupérer les enfants d'une page spécifique
$children = $this->getHierarchy('page-parent', true, false);

// Récupérer les pages de barre
$barPages = $this->getHierarchy(null, false, true);
```

### `updateSitemap()`

Génère un fichier sitemap.xml pour le référencement.

**Paramètres :** Aucun

**Retour :** bool - Succès de la génération

**Exemple :**
```php
// Mettre à jour le sitemap après modification des pages
if ($this->updateSitemap()) {
    echo "Sitemap mis à jour avec succès";
}
```

---

## Méthodes de gestion des entrées/sorties

### `getInput($key, $filter = helper::FILTER_STRING_SHORT, $required = false)`

Accède à une valeur des variables HTTP (ordre de recherche : _COOKIE, _POST).

**Paramètres :**
- `$key` (string) : Clé de la valeur
- `$filter` (int) : Filtre à appliquer (constantes helper::FILTER_*)
- `$required` (bool) : Champ requis

**Retour :** mixed - Valeur filtrée

**Exemple :**
```php
// Récupérer un champ POST
$title = $this->getInput('title', helper::FILTER_STRING_LONG, true);

// Récupérer un champ d'un tableau
$category = $this->getInput('post[category]', helper::FILTER_STRING_SHORT);

// Récupérer un cookie
$theme = $this->getInput('theme', helper::FILTER_STRING_SHORT);
```

### `addOutput($output)`

Ajoute des valeurs en sortie pour le rendu de la page.

**Paramètres :**
- `$output` (array) : Valeurs en sortie à ajouter

**Retour :** void

**Exemple :**
```php
// Ajouter un titre et une description
$this->addOutput([
    'metaTitle' => 'Ma page',
    'metaDescription' => 'Description de ma page',
    'notification' => 'Page mise à jour'
]);
```

### `addRequiredInputNotices($key)`

Ajoute une notice de champ obligatoire.

**Paramètres :**
- `$key` (string) : Clé du champ

**Retour :** void

**Exemple :**
```php
// Marquer un champ comme obligatoire
$this->addRequiredInputNotices('title');
```

### `checkCSRF()`

Vérifie le token CSRF pour la sécurité des formulaires.

**Paramètres :** Aucun

**Retour :** bool - Validité du token

**Exemple :**
```php
if ($this->checkCSRF()) {
    // Traitement du formulaire sécurisé
} else {
    // Erreur CSRF
}
```

### `isPost()`

Vérifie que des données sont transmises par la méthode POST.

**Paramètres :** Aucun

**Retour :** bool - `true` si POST et CSRF valide

**Exemple :**
```php
if ($this->isPost()) {
    // Traitement du formulaire
    $title = $this->getInput('title');
    // ...
}
```

---

## Méthodes de gestion des fichiers

### `makeThumb($src, $dest = null, $desired_width = 200)`

Crée une miniature d'une image avec gestion de plusieurs formats.

**Paramètres :**
- `$src` (string) : Chemin absolu vers le fichier image source
- `$dest` (string|null) : Chemin de destination (null pour auto-génération)
- `$desired_width` (int) : Largeur souhaitée en pixels

**Retour :** string|bool - Chemin de la miniature ou false en cas d'erreur

**Exemple :**
```php
// Créer une miniature de 300px
$thumbPath = $this->makeThumb('site/file/source/image.jpg', null, 300);

// Créer une miniature avec chemin spécifique
$thumbPath = $this->makeThumb('source.jpg', 'site/file/thumb/mini_source.jpg', 200);
```

### `getThumb($sourcePath, $prefix = self::THUMBS_SEPARATOR)`

Génère le chemin d'une miniature à partir du chemin source.

**Paramètres :**
- `$sourcePath` (string) : Chemin vers le fichier source
- `$prefix` (string) : Préfixe à ajouter au nom du fichier

**Retour :** string - Chemin de la miniature

**Exemple :**
```php
// Obtenir le chemin de la miniature
$thumbPath = $this->getThumb('site/file/source/image.jpg');
```

### `secure_file_put_contents($filename, $data, $flags = 0)`

Écrit des données dans un fichier avec plusieurs tentatives et verrouillage.

**Paramètres :**
- `$filename` (string) : Nom du fichier
- `$data` (string) : Données à écrire
- `$flags` (int) : Drapeaux optionnels

**Retour :** bool - Succès de l'écriture

**Exemple :**
```php
// Écrire un fichier de manière sécurisée
$success = $this->secure_file_put_contents('site/data/config.json', $jsonData);
```

### `deleteDir($path)`

Supprime un dossier non vide récursivement.

**Paramètres :**
- `$path` (string) : Chemin du dossier à supprimer

**Retour :** bool - Succès de la suppression

**Exemple :**
```php
// Supprimer un dossier et son contenu
$success = $this->deleteDir('site/file/temp');
```

### `copyDir($src, $dst)`

Copie récursivement des dossiers.

**Paramètres :**
- `$src` (string) : Dossier source
- `$dst` (string) : Dossier destination

**Retour :** bool - Succès de la copie

**Exemple :**
```php
// Copier un dossier
$success = $this->copyDir('core/module/install/resource', 'site/data');
```

### `makeZip($fileName, $folder, $filter = [])`

Génère une archive ZIP d'un dossier.

**Paramètres :**
- `$fileName` (string) : Chemin et nom de l'archive
- `$folder` (string) : Dossier à zipper
- `$filter` (array) : Dossiers à exclure

**Retour :** void

**Exemple :**
```php
// Créer une archive ZIP
$this->makeZip('backup.zip', 'site/data', ['temp', 'cache']);
```

---

## Méthodes de gestion des templates

### `dataPath($id, $lang)`

Retourne un chemin localisé pour l'enregistrement des données.

**Paramètres :**
- `$id` (string) : Nom du module
- `$lang` (string) : Langue des pages

**Retour :** string - Chemin du dossier

**Exemple :**
```php
// Obtenir le chemin pour les pages françaises
$path = $this->dataPath('page', 'fr_FR'); // site/data/fr_FR/

// Obtenir le chemin pour les données de configuration
$path = $this->dataPath('config', 'fr_FR'); // site/data/
```

### `getUrl($key = null)`

Accède à une partie de l'URL ou à l'URL complète.

**Paramètres :**
- `$key` (int|null) : Clé de l'URL (null pour l'URL complète)

**Retour :** string|null - Partie de l'URL ou URL complète

**Exemple :**
```php
// URL complète
$fullUrl = $this->getUrl();

// Premier segment de l'URL
$firstSegment = $this->getUrl(0);

// Deuxième segment
$secondSegment = $this->getUrl(1);
```

### `getRelativePath($path)`

Retourne un chemin relatif depuis la racine du site sans la baseUrl.

**Paramètres :**
- `$path` (string) : Chemin à convertir

**Retour :** string - Chemin relatif

**Exemple :**
```php
// Convertir un chemin absolu en relatif
$relativePath = $this->getRelativePath('https://monsite.com/site/file/source/image.jpg');
// Résultat : site/file/source/image.jpg
```

### `getNormalizedFilePath($filePath, $userId = null)`

Retourne le chemin normalisé d'un fichier selon le rôle utilisateur.

**Paramètres :**
- `$filePath` (string) : Chemin relatif du fichier
- `$userId` (string|null) : ID utilisateur (optionnel)

**Retour :** string|null - URL complète ou null si accès refusé

**Exemple :**
```php
// Normaliser un chemin de fichier
$fullPath = $this->getNormalizedFilePath('images/photo.jpg');
```

---

## Méthodes utilitaires

### `sendMail($to, $subject, $content, $replyTo = null, $from = 'no-reply@localhost')`

Envoie un email avec configuration SMTP ou mail().

**Paramètres :**
- `$to` (string|array) : Destinataire(s)
- `$subject` (string) : Sujet du mail
- `$content` (string) : Contenu HTML
- `$replyTo` (string|null) : Adresse de réponse
- `$from` (string) : Adresse d'expédition

**Retour :** bool|string - True si succès, message d'erreur sinon

**Exemple :**
```php
// Envoyer un email simple
$success = $this->sendMail('user@example.com', 'Sujet', 'Contenu HTML');

// Envoyer à plusieurs destinataires
$success = $this->sendMail(['user1@example.com', 'user2@example.com'], 'Sujet', 'Contenu');

// Avec adresse de réponse personnalisée
$success = $this->sendMail('user@example.com', 'Sujet', 'Contenu', 'reply@example.com');
```

### `saveLog($message = '')`

Journalise un événement avec gestion de la rotation des logs.

**Paramètres :**
- `$message` (string) : Message à journaliser

**Retour :** void

**Exemple :**
```php
// Journaliser une action
$this->saveLog('Utilisateur connecté');

// Journaliser une erreur
$this->saveLog('Erreur lors de la sauvegarde des données');
```

### `recursive_array_replace($find, $replace, $array, &$count)`

Remplace récursivement des valeurs dans un tableau.

**Paramètres :**
- `$find` (mixed) : Valeur à rechercher
- `$replace` (mixed) : Valeur de remplacement
- `$array` (array) : Tableau à traiter
- `$count` (int) : Nombre de remplacements (référence)

**Retour :** array - Tableau avec les valeurs remplacées

**Exemple :**
```php
// Remplacer une valeur dans tous les niveaux d'un tableau
$count = 0;
$newArray = $this->recursive_array_replace('ancienne_valeur', 'nouvelle_valeur', $dataArray, $count);
echo "Remplacements effectués : " . $count;
```

### `initData($module, $lang, $sampleSite = false)`

Initialise les données d'un module.

**Paramètres :**
- `$module` (string) : Nom du module à générer
- `$lang` (string) : Langue à créer
- `$sampleSite` (bool) : Créer un site exemple en FR

**Retour :** void

**Exemple :**
```php
// Initialiser les données de base
$this->initData('config', 'fr_FR');

// Initialiser avec site exemple
$this->initData('page', 'fr_FR', true);
```

---

## Constantes utiles

### Constantes d'affichage
```php
common::DISPLAY_RAW = 0;          // Affichage brut
common::DISPLAY_JSON = 1;         // Affichage JSON
common::DISPLAY_RSS = 2;          // Affichage RSS
common::DISPLAY_LAYOUT_BLANK = 3;  // Layout vide
common::DISPLAY_LAYOUT_MAIN = 4;   // Layout principal
common::DISPLAY_LAYOUT_LIGHT = 5;  // Layout léger
```

### Rôles utilisateurs
```php
common::ROLE_BANNED = -1;    // Banni
common::ROLE_VISITOR = 0;     // Visiteur
common::ROLE_MEMBER = 1;      // Membre
common::ROLE_EDITOR = 2;      // Éditeur
common::ROLE_ADMIN = 3;       // Administrateur
```

### Constantes de chemins
```php
common::BACKUP_DIR = 'site/backup/';  // Dossier de sauvegarde
common::DATA_DIR = 'site/data/';      // Dossier de données
common::FILE_DIR = 'site/file/';      // Dossier de fichiers
common::TEMP_DIR = 'site/tmp/';       // Dossier temporaire
common::I18N_DIR = 'site/i18n/';     // Dossier i18n
common::MODULE_DIR = 'module/';       // Dossier des modules
```

### Autres constantes utiles
```php
common::ZWII_VERSION = '14.1.04';           // Version actuelle
common::THUMBS_SEPARATOR = 'mini_';          // Préfixe des miniatures
common::THUMBS_WIDTH = 640;                  // Largeur par défaut des miniatures
common::ACCESS_TIMER = 1800;                 // Temps d'inactivité (30 min)
common::MENU_DEPTH = 5;                      // Profondeur maximale des menus
common::MAX_PROFILS = 10;                    // Nombre maximum de profils
```

---

## Exemples d'utilisation courants

### Créer un module simple

```php
class monModule {
    public function index() {
        // Vérifier si l'utilisateur est connecté et a le bon rôle
        if (!$this->isConnected() || $this->getUser('role') < common::ROLE_EDITOR) {
            return 'Accès refusé';
        }
        
        // Récupérer des données
        $data = $this->getData(['module', 'monModule']);
        
        // Traiter un formulaire
        if ($this->isPost()) {
            $title = $this->getInput('title', helper::FILTER_STRING_SHORT, true);
            $this->setData(['module', 'monModule', 'title'], $title);
            $this->addOutput(['notification' => 'Données sauvegardées']);
        }
        
        // Afficher le template
        return $this->output['content'];
    }
}
```

### Gestion des fichiers utilisateur

```php
// Vérifier le chemin autorisé
$userPath = $this->getUserPath();
if ($userPath !== 'none') {
    // Télécharger un fichier
    if (isset($_FILES['upload'])) {
        $fileName = $_FILES['upload']['name'];
        $targetPath = $userPath . $fileName;
        
        if (move_uploaded_file($_FILES['upload']['tmp_name'], $targetPath)) {
            // Créer une miniature si c'est une image
            if (getimagesize($targetPath)) {
                $this->makeThumb($targetPath);
            }
            $this->addOutput(['notification' => 'Fichier uploadé avec succès']);
        }
    }
}
```

### Envoi d'email avec template

```php
// Préparer le contenu HTML
$content = '<h1>Bonjour ' . $this->getUser('pseudo') . '</h1>';
$content .= '<p>Votre compte a été mis à jour.</p>';

// Envoyer l'email
$success = $this->sendMail(
    $this->getUser('mail'),
    'Mise à jour de votre compte',
    $content
);

if ($success) {
    $this->saveLog('Email de confirmation envoyé à ' . $this->getUser('mail'));
}
```

Cette documentation couvre les principales méthodes publiques du core ZwiiCMS. Pour plus de détails sur les méthodes spécifiques ou les constantes, consultez le code source du fichier `core/core.php`.
