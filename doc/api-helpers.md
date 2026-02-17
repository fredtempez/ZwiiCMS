# Documentation des assistants (Helpers) ZwiiCMS

Cette documentation détaille les assistants et fonctions utilitaires disponibles dans ZwiiCMS pour les développeurs.

## Table des matières

1. [Classe helper](#classe-helper)
2. [Assistants de template](#assistants-de-template)
3. [Assistants de chaîne](#assistants-de-chaîne)
4. [Assistants de tableau](#assistants-de-tableau)
5. [Assistants de fichier](#assistants-de-fichier)
6. [Assistants de date/heure](#assistants-de-dateheure)
7. [Assistants de sécurité](#assistants-de-sécurité)
8. [Assistants d'URL](#assistants-durl)
9. [Assistants de validation](#assistants-de-validation)

---

## Classe helper

La classe `helper` contient des méthodes statiques utilitaires utilisées dans tout le CMS.

### baseUrl($rewrite = true, $host = true)

Retourne l'URL de base du site.

**Paramètres :**
- `$rewrite` (bool) : Inclure la réécriture d'URL
- `$host` (bool) : Inclure le nom d'hôte

**Retour :** string - URL de base

**Exemple :**
```php
$url = helper::baseUrl(); // https://monsite.com/
$url = helper::baseUrl(false); // /zwii/
$url = helper::baseUrl(true, false); // //monsite.com/zwii/
```

### getIp($anonymize = false)

Retourne l'adresse IP du client.

**Paramètres :**
- `$anonymize` (bool) : Anonymiser l'IP (pour le RGPD)

**Retour :** string - Adresse IP

**Exemple :**
```php
$ip = helper::getIp(); // 192.168.1.1
$ip = helper::getIp(true); // 192.168.1.0
```

### dateUTF8($format, $timestamp, $lang = 'fr_FR')

Formate une date selon la locale spécifiée.

**Paramètres :**
- `$format` (string) : Format de date (syntaxe strftime)
- `$timestamp` (int) : Timestamp Unix
- `$lang` (string) : Code de langue

**Retour :** string - Date formatée

**Exemple :**
```php
$date = helper::dateUTF8('%d/%m/%Y', time(), 'fr_FR'); // 17/02/2026
$date = helper::dateUTF8('%A %d %B %Y', time(), 'fr_FR'); // mardi 17 février 2026
```

### translate($text, $lang = null)

Traduit un texte dans la langue de l'interface.

**Paramètres :**
- `$text` (string) : Texte à traduire
- `$lang` (string|null) : Langue cible (null = langue courante)

**Retour :** string - Texte traduit

**Exemple :**
```php
$text = helper::translate('Save'); // "Sauvegarder" en français
$text = helper::translate('Save', 'en_EN'); // "Save" en anglais
```

### filter($value, $filter)

Filtre et nettoie une valeur selon un type.

**Paramètres :**
- `$value` (mixed) : Valeur à filtrer
- `$filter` (int) : Type de filtre (constantes helper::FILTER_*)

**Retour :** mixed - Valeur filtrée

**Exemple :**
```php
$clean = helper::filter($input, helper::FILTER_STRING_SHORT);
$email = helper::filter($email, helper::FILTER_EMAIL);
$int = helper::filter($number, helper::FILTER_INT);
```

---

## Assistants de template

### template::text($name, $options = [])

Génère un champ de texte.

**Paramètres :**
- `$name` (string) : Nom du champ
- `$options` (array) : Options du champ

**Options disponibles :**
```php
[
    'label' => 'Libellé',           // Texte du label
    'value' => 'valeur',            // Valeur par défaut
    'placeholder' => 'Indication',   // Texte indicatif
    'required' => true,             // Champ obligatoire
    'disabled' => false,            // Désactivé
    'readonly' => false,            // Lecture seule
    'maxlength' => 255,             // Longueur maximale
    'help' => 'Texte d\'aide',      // Texte d'aide
    'class' => 'custom-class',      // Classes CSS additionnelles
    'id' => 'custom-id',            // ID personnalisé
    'autocomplete' => 'on'           // Autocomplétion
]
```

**Exemple :**
```php
<?php echo template::text('title', [
    'label' => 'Titre',
    'value' => $pageTitle,
    'placeholder' => 'Entrez un titre',
    'required' => true,
    'maxlength' => 100,
    'help' => 'Le titre sera affiché dans le menu'
]); ?>
```

### template::password($name, $options = [])

Génère un champ mot de passe.

**Paramètres :**
- `$name` (string) : Nom du champ
- `$options` (array) : Options du champ

**Exemple :**
```php
<?php echo template::password('password', [
    'label' => 'Mot de passe',
    'required' => true,
    'help' => 'Minimum 8 caractères, majuscule, chiffre et symbole'
]); ?>
```

### template::email($name, $options = [])

Génère un champ email.

**Exemple :**
```php
<?php echo template::email('email', [
    'label' => 'Adresse email',
    'value' => $userEmail,
    'required' => true,
    'help' => 'Exemple : nom@domaine.com'
]); ?>
```

### template::textarea($name, $options = [])

Génère une zone de texte.

**Options spécifiques :**
```php
[
    'rows' => 10,                   // Nombre de lignes
    'cols' => 50,                   // Nombre de colonnes
    'editor' => false,               // Activer l'éditeur riche
    'class' => 'editor'             // Classe pour l'éditeur
]
```

**Exemple :**
```php
<?php echo template::textarea('content', [
    'label' => 'Contenu',
    'value' => $content,
    'rows' => 15,
    'class' => 'editor',
    'help' => 'Utilisez l\'éditeur pour formater le texte'
]); ?>
```

### template::select($name, $options, $attributes = [])

Génère un sélecteur (dropdown).

**Paramètres :**
- `$name` (string) : Nom du champ
- `$options` (array) : Options du sélecteur (valeur => libellé)
- `$attributes` (array) : Attributs du champ

**Exemple :**
```php
<?php echo template::select('category', [
    'news' => 'Actualités',
    'blog' => 'Blog',
    'page' => 'Page',
    'download' => 'Téléchargement'
], [
    'label' => 'Catégorie',
    'selected' => $selectedCategory,
    'required' => true,
    'help' => 'Choisissez la catégorie appropriée'
]); ?>
```

### template::checkbox($name, $checked, $options = [])

Génère une case à cocher.

**Paramètres :**
- `$name` (string) : Nom du champ
- `$checked` (bool) : État coché par défaut
- `$options` (array) : Options du champ

**Exemple :**
```php
<?php echo template::checkbox('enabled', true, [
    'label' => 'Activer',
    'checked' => $isEnabled,
    'help' => 'Cochez pour activer cette fonctionnalité'
]); ?>
```

### template::radio($name, $options, $attributes = [])

Génère des boutons radio.

**Exemple :**
```php
<?php echo template::radio('type', [
    'type1' => 'Type 1',
    'type2' => 'Type 2',
    'type3' => 'Type 3'
], [
    'label' => 'Type',
    'selected' => $selectedType,
    'required' => true
]); ?>
```

### template::file($name, $options = [])

Génère un sélecteur de fichier.

**Options spécifiques :**
```php
[
    'filter' => 'image/*',           // Types de fichiers acceptés
    'multiple' => false,             // Sélection multiple
    'help' => 'Formats : jpg, png'   // Texte d'aide
]
```

**Exemple :**
```php
<?php echo template::file('image', [
    'label' => 'Image',
    'value' => $imagePath,
    'filter' => 'image/*',
    'help' => 'Formats acceptés : jpg, png, gif, webp'
]); ?>
```

### template::date($name, $options = [])

Génère un sélecteur de date.

**Exemple :**
```php
<?php echo template::date('date', [
    'label' => 'Date',
    'value' => $date,
    'required' => true,
    'help' => 'Format : JJ/MM/AAAA'
]); ?>
```

### template::submit($name, $options = [])

Génère un bouton de soumission.

**Exemple :**
```php
<?php echo template::submit('submit', [
    'value' => 'Sauvegarder',
    'class' => 'buttonGreen',
    'disabled' => false,
    'ico' => 'save'                  // Icône (optionnel)
]); ?>
```

### template::button($name, $options = [])

Génère un bouton standard.

**Options spécifiques :**
```php
[
    'href' => '/url',                // URL de destination
    'target' => '_blank',           // Cible du lien
    'onclick' => 'function()',       // JavaScript onclick
    'ico' => 'cancel'               // Icône
]
```

**Exemple :**
```php
<?php echo template::button('cancel', [
    'value' => 'Annuler',
    'href' => helper::baseUrl() . $this->getUrl(0),
    'class' => 'buttonGrey',
    'ico' => 'cancel'
]); ?>
```

### template::hidden($name, $options = [])

Génère un champ caché.

**Exemple :**
```php
<?php echo template::hidden('csrf', [
    'value' => $_SESSION['csrf']
]); ?>

<?php echo template::hidden('action', [
    'value' => 'save'
]); ?>
```

---

## Assistants de chaîne

### truncate($text, $length = 100, $suffix = '...')

Tronque une chaîne de caractères.

**Paramètres :**
- `$text` (string) : Texte à tronquer
- `$length` (int) : Longueur maximale
- `$suffix` (string) : Suffixe à ajouter

**Retour :** string - Texte tronqué

**Exemple :**
```php
$text = helper::truncate('Long texte qui sera coupé', 20);
// Résultat : "Long texte qui ser..."
```

### slugify($text)

Convertit une chaîne en slug URL-friendly.

**Paramètres :**
- `$text` (string) : Texte à convertir

**Retour :** string - Slug

**Exemple :**
```php
$slug = helper::slugify('Titre de mon article');
// Résultat : "titre-de-mon-article"
```

### capitalize($text)

Met en majuscule la première lettre de chaque mot.

**Paramètres :**
- `$text` (string) : Texte à formater

**Retour :** string - Texte formaté

**Exemple :**
```php
$text = helper::capitalize('titre de l\'article');
// Résultat : "Titre De L'Article"
```

### randomString($length = 10)

Génère une chaîne aléatoire.

**Paramètres :**
- `$length` (int) : Longueur de la chaîne

**Retour :** string - Chaîne aléatoire

**Exemple :**
```php
$token = helper::randomString(32);
// Résultat : "a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5"
```

---

## Assistants de tableau

### arrayColumn($array, $column, $index = null)

Extrait une colonne d'un tableau multidimensionnel.

**Paramètres :**
- `$array` (array) : Tableau source
- `$column` (string) : Nom de la colonne
- `$index` (string|null) : Colonne d'index (optionnel)

**Retour :** array - Tableau avec la colonne extraite

**Exemple :**
```php
$users = [
    ['id' => 1, 'name' => 'Alice', 'email' => 'alice@example.com'],
    ['id' => 2, 'name' => 'Bob', 'email' => 'bob@example.com']
];

$names = helper::arrayColumn($users, 'name');
// Résultat : ['Alice', 'Bob']

$indexed = helper::arrayColumn($users, 'email', 'id');
// Résultat : [1 => 'alice@example.com', 2 => 'bob@example.com']
```

### arraySort($array, $column, $direction = SORT_ASC)

Trie un tableau multidimensionnel selon une colonne.

**Paramètres :**
- `$array` (array) : Tableau à trier
- `$column` (string) : Colonne de tri
- `$direction` (int) : Direction de tri (SORT_ASC ou SORT_DESC)

**Retour :** array - Tableau trié

**Exemple :**
```php
$users = [
    ['name' => 'Charlie', 'age' => 25],
    ['name' => 'Alice', 'age' => 30],
    ['name' => 'Bob', 'age' => 20]
];

$sorted = helper::arraySort($users, 'age');
// Résultat : Bob (20), Charlie (25), Alice (30)
```

### arrayFilter($array, $callback)

Filtre un tableau avec une fonction de rappel.

**Paramètres :**
- `$array` (array) : Tableau à filtrer
- `$callback` (callable) : Fonction de filtrage

**Retour :** array - Tableau filtré

**Exemple :**
```php
$numbers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
$evens = helper::arrayFilter($numbers, function($value) {
    return $value % 2 === 0;
});
// Résultat : [2, 4, 6, 8, 10]
```

---

## Assistants de fichier

### fileExists($path)

Vérifie si un fichier existe (avec gestion des chemins relatifs).

**Paramètres :**
- `$path` (string) : Chemin du fichier

**Retour :** bool - True si le fichier existe

**Exemple :**
```php
if (helper::fileExists('site/file/source/image.jpg')) {
    echo "L'image existe";
}
```

### fileSize($path)

Retourne la taille d'un fichier formatée.

**Paramètres :**
- `$path` (string) : Chemin du fichier

**Retour :** string - Taille formatée (ex: "1.2 MB")

**Exemple :**
```php
$size = helper::fileSize('site/file/source/document.pdf');
// Résultat : "2.4 MB"
```

### fileExtension($filename)

Retourne l'extension d'un fichier.

**Paramètres :**
- `$filename` (string) : Nom du fichier

**Retour :** string - Extension (sans le point)

**Exemple :**
```php
$ext = helper::fileExtension('document.pdf');
// Résultat : "pdf"
```

### mimeType($path)

Retourne le type MIME d'un fichier.

**Paramètres :**
- `$path` (string) : Chemin du fichier

**Retour :** string - Type MIME

**Exemple :**
```php
$mime = helper::mimeType('site/file/source/image.jpg');
// Résultat : "image/jpeg"
```

---

## Assistants de date/heure

### timeAgo($timestamp)

Retourne une date relative (ex: "il y a 2 heures").

**Paramètres :**
- `$timestamp` (int) : Timestamp Unix

**Retour :** string - Date relative

**Exemple :**
```php
$time = helper::timeAgo(time() - 3600);
// Résultat : "il y a 1 heure"
```

### formatDate($format, $timestamp = null)

Formate une date avec des formats prédéfinis.

**Paramètres :**
- `$format` (string) : Format ('short', 'long', 'datetime', 'time')
- `$timestamp` (int|null) : Timestamp (null = maintenant)

**Retour :** string - Date formatée

**Exemple :**
```php
$date = helper::formatDate('short', time());
// Résultat : "17/02/2026"

$date = helper::formatDate('datetime', time());
// Résultat : "17/02/2026 14:30"
```

### isValidDate($date)

Valide une date selon différents formats.

**Paramètres :**
- `$date` (string) : Date à valider

**Retour :** bool - True si la date est valide

**Exemple :**
```php
$valid = helper::isValidDate('2026-02-17'); // true
$valid = helper::isValidDate('31/02/2026'); // false
```

---

## Assistants de sécurité

### encrypt($data, $key)

Chiffre des données.

**Paramètres :**
- `$data` (string) : Données à chiffrer
- `$key` (string) : Clé de chiffrement

**Retour :** string - Données chiffrées

**Exemple :**
```php
$encrypted = helper::encrypt('donnée sensible', 'clé-secrete');
```

### decrypt($data, $key)

Déchiffre des données.

**Paramètres :**
- `$data` (string) : Données chiffrées
- `$key` (string) : Clé de déchiffrement

**Retour :** string|false - Données déchiffrées ou false

**Exemple :**
```php
$decrypted = helper::decrypt($encrypted, 'clé-secrete');
```

### hashPassword($password)

Génère un hash de mot de passe sécurisé.

**Paramètres :**
- `$password` (string) : Mot de passe à hasher

**Retour :** string - Hash du mot de passe

**Exemple :**
```php
$hash = helper::hashPassword('monMotDePasse123');
```

### verifyPassword($password, $hash)

Vérifie un mot de passe contre son hash.

**Paramètres :**
- `$password` (string) : Mot de passe à vérifier
- `$hash` (string) : Hash à comparer

**Retour :** bool - True si le mot de passe correspond

**Exemple :**
```php
$valid = helper::verifyPassword('monMotDePasse123', $hash);
```

### sanitize($string, $type = 'string')

Nettoie une chaîne pour éviter les injections.

**Paramètres :**
- `$string` (string) : Chaîne à nettoyer
- `$type` (string) : Type de nettoyage ('string', 'html', 'sql', 'filename')

**Retour :** string - Chaîne nettoyée

**Exemple :**
```php
$clean = helper::sanitize('<script>alert("xss")</script>', 'html');
// Résultat : "&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;"
```

---

## Assistants d'URL

### currentUrl()

Retourne l'URL complète de la page actuelle.

**Retour :** string - URL actuelle

**Exemple :**
```php
$url = helper::currentUrl();
// Résultat : "https://monsite.com/page/accueil"
```

### buildUrl($path, $params = [])

Construit une URL avec des paramètres.

**Paramètres :**
- `$path` (string) : Chemin de base
- `$params` (array) : Paramètres GET

**Retour :** string - URL construite

**Exemple :**
```php
$url = helper::buildUrl('search', ['q' => 'test', 'page' => 2]);
// Résultat : "search?q=test&page=2"
```

### parseUrl($url)

Analyse une URL et retourne ses composants.

**Paramètres :**
- `$url` (string) : URL à analyser

**Retour :** array - Composants de l'URL

**Exemple :**
```php
$parts = helper::parseUrl('https://monsite.com/page/accueil?param=value');
// Résultat : [
//     'scheme' => 'https',
//     'host' => 'monsite.com',
//     'path' => '/page/accueil',
//     'query' => 'param=value'
// ]
```

---

## Assistants de validation

### isEmail($email)

Valide une adresse email.

**Paramètres :**
- `$email` (string) : Email à valider

**Retour :** bool - True si l'email est valide

**Exemple :**
```php
$valid = helper::isEmail('user@example.com'); // true
$valid = helper::isEmail('invalid-email'); // false
```

### isUrl($url)

Valide une URL.

**Paramètres :**
- `$url` (string) : URL à valider

**Retour :** bool - True si l'URL est valide

**Exemple :**
```php
$valid = helper::isUrl('https://monsite.com'); // true
$valid = helper::isUrl('not-a-url'); // false
```

### isPhone($phone)

Valide un numéro de téléphone français.

**Paramètres :**
- `$phone` (string) : Numéro à valider

**Retour :** bool - True si le numéro est valide

**Exemple :**
```php
$valid = helper::isPhone('0123456789'); // true
$valid = helper::isPhone('+33123456789'); // true
$valid = helper::isPhone('123'); // false
```

### isPostalCode($code)

Valide un code postal français.

**Paramètres :**
- `$code` (string) : Code postal à valider

**Retour :** bool - True si le code postal est valide

**Exemple :**
```php
$valid = helper::isPostalCode('75001'); // true
$valid = helper::isPostalCode('12345'); // false
```

---

## Constantes de filtre

```php
// Filtres de chaîne
helper::FILTER_STRING_SHORT   // Chaîne courte (255 caractères max)
helper::FILTER_STRING_LONG    // Chaîne longue (sans limite)
helper::FILTER_EMAIL          // Email valide
helper::FILTER_URL            // URL valide
helper::FILTER_INT            // Entier
helper::FILTER_FLOAT          // Nombre décimal
helper::FILTER_BOOLEAN       // Booléen
helper::FILTER_PASSWORD       // Mot de passe (avec validation)
helper::FILTER_TIMESTAMP      // Timestamp Unix
helper::FILTER_DATE           // Date (format JJ/MM/AAAA)
helper::FILTER_TIME           // Heure (format HH:MM)
helper::FILTER_DATETIME       // Date et heure
helper::FILTER_COLOR          // Couleur hexadécimale
helper::FILTER_PHONE          // Numéro de téléphone
helper::FILTER_POSTAL_CODE    // Code postal
```

---

## Exemples pratiques

### Formulaire complet avec validation

```php
<?php
// Traitement du formulaire
if ($this->isPost()) {
    // Validation avec les helpers
    $name = helper::filter($this->getInput('name'), helper::FILTER_STRING_SHORT);
    $email = helper::filter($this->getInput('email'), helper::FILTER_EMAIL);
    $phone = helper::filter($this->getInput('phone'), helper::FILTER_PHONE);
    
    // Validation personnalisée
    if (!helper::isEmail($email)) {
        $this->addOutput(['notification' => 'Email invalide']);
    } elseif (!helper::isPhone($phone)) {
        $this->addOutput(['notification' => 'Numéro de téléphone invalide']);
    } else {
        // Traitement réussi
        $this->addOutput(['notification' => 'Formulaire validé']);
    }
}
?>

<form method="post">
    <?php echo template::text('name', [
        'label' => 'Nom',
        'required' => true,
        'help' => 'Votre nom complet'
    ]); ?>
    
    <?php echo template::email('email', [
        'label' => 'Email',
        'required' => true,
        'help' => 'Exemple : nom@domaine.com'
    ]); ?>
    
    <?php echo template::text('phone', [
        'label' => 'Téléphone',
        'required' => true,
        'help' => 'Format : 0123456789'
    ]); ?>
    
    <?php echo template::submit('submit', [
        'value' => 'Valider'
    ]); ?>
</form>
```

### Traitement de fichier uploadé

```php
<?php
if ($this->isPost() && isset($_FILES['document'])) {
    $file = $_FILES['document'];
    
    // Validation du fichier
    if ($file['error'] === UPLOAD_ERR_OK) {
        $extension = helper::fileExtension($file['name']);
        $mimeType = helper::mimeType($file['tmp_name']);
        
        // Vérification du type MIME
        if (strpos($mimeType, 'image/') === 0) {
            // Déplacement du fichier
            $fileName = helper::slugify(pathinfo($file['name'], PATHINFO_FILENAME));
            $targetPath = 'site/file/source/' . $fileName . '.' . $extension;
            
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                // Création d'une miniature
                $thumbPath = $this->makeThumb($targetPath);
                
                $this->addOutput([
                    'notification' => 'Fichier uploadé avec succès'
                ]);
            }
        } else {
            $this->addOutput([
                'notification' => 'Seules les images sont autorisées'
            ]);
        }
    }
}
?>

<form method="post" enctype="multipart/form-data">
    <?php echo template::file('document', [
        'label' => 'Document',
        'filter' => 'image/*',
        'help' => 'Formats acceptés : jpg, png, gif, webp'
    ]); ?>
    
    <?php echo template::submit('upload', [
        'value' => 'Uploader'
    ]); ?>
</form>
```

Cette documentation couvre les principaux assistants disponibles dans ZwiiCMS. Ces fonctions facilitent le développement en fournissant des solutions standardisées pour les tâches courantes.
