# ZwiiCMS - Liste des méthodes par trait

## 📋 Table des matières
- [Data](#data)
- [InputOutput](#inputoutput)
- [PageContent](#pagecontent)
- [Security](#security)
- [Tools](#tools)
- [UserPermission](#userpermission)

---

## 🗄️ Data

**Gestion des données et bases de données JsonDb**

### Méthodes publiques
- `deleteData($keys)` - Supprime des données
- `setData($keys = [], $save = null)` - Sauvegarde des données
- `getData($keys = [])` - Accède aux données
- `jsonDB($lang, $dataFilesToLoad = null)` - Instancie les bases JsonDb
- `fetchDataFile($dataFile, $lang = null, $query = null)` - Lit une base JsonDb isolée
- `saveDataFile($dataFile, $data, $lang = null)` - Écrit des données dans une base
- `isDataFileLoaded($dataFile)` - Vérifie si une base est chargée
- `loadModuleDataFiles($moduleId, $lang = null)` - Précharge les bases d'un module
- `initData($module, $lang, $sampleSite = false)` - Initialisation des données
- `saveDB($module)` - Forçage de l'enregistrement
- `dataPath($id, $lang)` - Retourne un chemin localisé

### Méthodes protégées
- `ensureDataFileLoaded($dataFile, $lang = null)` - Assure qu'une base est chargée

### Méthodes privées
- `loadDataFile($dataFile, $lang = null)` - Charge une base JsonDb
- `getDataFilesConfig()` - Construit la configuration par base

---

## 📥 InputOutput

**Gestion des entrées/sorties, emails et fichiers**

### Méthodes publiques
- `addRequiredInputNotices($key)` - Ajoute une notice de champ obligatoire
- `addOutput($output)` - Ajoute les valeurs en sortie
- `secure_file_put_contents($filename, $data, $flags = 0)` - Écriture sécurisée dans un fichier
- `getInput($key, $filter = helper::FILTER_STRING_SHORT, $required = false)` - Accède aux variables HTTP
- `isPost()` - Vérifie si une valeur est transmise par POST
- `sendMail($to, $subject, $content, $replyTo = null, $serverEmail = null, $file_name = null)` - Envoi d'email
- `saveLog($message = '', $force = false)` - Journalisation

### Méthodes protégées
- `getCorrectedSmtpFrom($smtpFrom)` - Corrige l'adresse email SMTP

### Méthodes privées
- `rotateLogFile()` - Gère la rotation des fichiers journaux

---

## 📄 PageContent

**Gestion des pages, hiérarchie et SEO**

### Méthodes publiques
- `getPage($page, $lang)` - Lit les données de la page
- `setPage($page, $value, $lang)` - Écrit les données de la page
- `deletePage($page, $lang)` - Efface les données de la page
- `getHierarchy($parentId = null, $onlyVisible = true, $onlyBlock = false)` - Accède à la liste des pages parents/enfants
- `updateSitemap()` - Génère le fichier sitemap.xml

### Méthodes privées
- `buildHierarchy()` - Construit le tableau des pages
- `tinyMcePages()` - Génère le fichier JSON pour TinyMCE
- `addTinyMceChildren(&$menu, $childrenPageIds, $rewrite, $depth = 1)` - Ajoute récursivement les enfants pour TinyMCE

---

## 🔒 Security

**Gestion de la sécurité et tokens CSRF**

### Méthodes publiques
- `checkCSRF()` - Vérification du token CSRF

---

## 🛠️ Tools

**Utilitaires divers: images, fichiers, archives**

### Méthodes publiques
- `getUrl($key = null)` - Accède à une partie de l'URL
- `makeThumb($src, $dest = null, $desired_width = 200)` - Crée des miniatures d'images
- `getThumb($sourcePath, $prefix = self::THUMBS_SEPARATOR)` - Génère le chemin d'une miniature
- `deleteDir($path)` - Efface un dossier non vide
- `copyDir($src, $dst)` - Copie récursive de dossiers
- `recursive_array_replace($find, $replace, $array, &$count)` - Parcours et remplace dans les données
- `makeZip($fileName, $folder, $filter = [])` - Génère une archive ZIP
- `signature($userId)` - Retourne la signature d'un utilisateur
- `getRelativePath($path)` - Retourne un chemin relatif

---

## 👤 UserPermission

**Gestion des utilisateurs, permissions et authentification**

### Méthodes publiques
- `getUser($key, $perm1 = null, $perm2 = null)` - Récupère les informations de l'utilisateur
- `isConnected()` - Vérifie si l'utilisateur est connecté
- `getUserPath($userId = null)` - Retourne le chemin du dossier autorisé
- `getNormalizedFilePath($filePath, $userId = null)` - Retourne le chemin normalisé d'un fichier

### Méthodes privées
- `getPermission($key1, $key2 = null)` - Vérifie les permissions d'un utilisateur

---

## 📊 Résumé

| Trait | Méthodes publiques | Méthodes protégées | Méthodes privées | Total |
|-------|-------------------|-------------------|------------------|-------|
| Data | 11 | 1 | 2 | 14 |
| InputOutput | 7 | 1 | 1 | 9 |
| PageContent | 5 | 0 | 3 | 8 |
| Security | 1 | 0 | 0 | 1 |
| Tools | 9 | 0 | 0 | 9 |
| UserPermission | 4 | 0 | 1 | 5 |
| **Total** | **37** | **2** | **7** | **46** |

---

*Document généré le 17 mars 2026 - Projet ZwiiCMS*
