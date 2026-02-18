# Documentation des templates ZwiiCMS

Cette documentation décrit le système de templates et les assistants disponibles dans ZwiiCMS pour les développeurs.

## Table des matières

1. [Structure des templates](#structure-des-templates)
2. [Templates principaux](#templates-principaux)
3. [Templates de modules](#templates-de-modules)
4. [Assistants de template](#assistants-de-template)
5. [Variables et helpers](#variables-et-helpers)
6. [Exemples pratiques](#exemples-pratiques)

---

## Structure des templates

### Organisation des fichiers

```
core/layout/
├── blank.php          # Template vide
├── blank.css          # Styles du template vide
├── common.css         # Styles communs
├── main.php           # Template principal
└── mail.php           # Template pour emails

module/[nom]/view/
├── index/
│   ├── index.php      # Vue principale
│   ├── index.css     # Styles spécifiques
│   └── index.js.php  # JavaScript spécifique
├── add/
│   ├── add.php        # Vue d'ajout
│   └── add.css
├── edit/
│   ├── edit.php       # Vue d'édition
│   └── edit.css
└── config/
    ├── config.php     # Vue de configuration
    └── config.css
```

### Structure d'un template PHP

```php
<?php
// En-tête standard
if ($this->getData(['page', $this->getUrl(0), 'moduleId']) !== '[nom-module]') {
    header('Location: ' . helper::baseUrl() . 'page/not-found');
    exit();
}

// Traitement des données
if ($this->isPost()) {
    $title = $this->getInput('title', helper::FILTER_STRING_SHORT, true);
    $this->setData(['module', '[nom-module]', 'title'], $title);
    $this->addOutput(['notification' => 'Données sauvegardées']);
}

// Préparation des variables pour la vue
$moduleData = $this->getData(['module', '[nom-module]']);
?>

<!-- Contenu HTML -->
<div class="row">
    <div class="col12">
        <h2><?php echo $moduleData['title']; ?></h2>
        
        <form method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col6">
                    <?php echo template::text('title', [
                        'label' => 'Titre',
                        'value' => $moduleData['title'],
                        'required' => true
                    ]); ?>
                </div>
            </div>
            
            <div class="row">
                <div class="col12">
                    <?php echo template::submit('submit', [
                        'value' => 'Sauvegarder'
                    ]); ?>
                </div>
            </div>
        </form>
    </div>
</div>
```

---

## Templates principaux

### blank.php

Template minimal sans header ni footer, utilisé pour les réponses AJAX ou les iframes.

**Utilisation :**
```php
$this->addOutput([
    'display' => common::DISPLAY_LAYOUT_BLANK,
    'content' => $content
]);
```

### main.php

Template principal avec header, navigation et footer complet.

**Structure :**
```php
<!DOCTYPE html>
<html lang="<?php echo common::$siteContent; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo $this->output['style']; ?>
    <?php echo $this->output['inlineStyle']; ?>
    <title><?php echo $this->output['metaTitle']; ?></title>
    <meta name="description" content="<?php echo $this->output['metaDescription']; ?>">
</head>
<body>
    <?php if ($this->isConnected()): ?>
        <?php include 'core/layout/bar.php'; ?>
    <?php endif; ?>
    
    <div id="site">
        <?php include 'core/layout/header.php'; ?>
        <?php include 'core/layout/nav.php'; ?>
        
        <section>
            <?php echo $this->output['content']; ?>
        </section>
        
        <?php include 'core/layout/footer.php'; ?>
    </div>
    
    <?php echo $this->output['script']; ?>
    <?php echo $this->output['inlineScript']; ?>
</body>
</html>
```

### mail.php

Template pour l'envoi d'emails HTML.

**Utilisation :**
```php
// Dans la méthode sendMail()
ob_start();
include 'core/layout/mail.php';
$layout = ob_get_clean();
```

---

## Templates de modules

### Structure standard d'un module

```php
<?php
// module/monModule/monModule.php
class monModule extends common {
    public static $actions = [
        'index' => self::ROLE_MEMBER,
        'add' => self::ROLE_EDITOR,
        'edit' => self::ROLE_EDITOR,
        'delete' => self::ROLE_ADMIN,
        'config' => self::ROLE_ADMIN
    ];

    public function index() {
        // Vérifier si l'utilisateur est connecté et a le bon rôle
        if (!$this->isConnected() || $this->getUser('role') < common::ROLE_MEMBER) {
            return 'Accès refusé';
        }

        // Récupérer les données
        $items = $this->getData(['module', $this->getUrl(0), 'items']);

        // Préparer la vue
        return $this->getLayout();
    }

    public function add() {
        // Traitement du formulaire
        if ($this->isPost()) {
            $itemId = uniqid();
            $this->setData([
                'module',
                $this->getUrl(0),
                'items',
                $itemId
            ], [
                'title' => $this->getInput('title', helper::FILTER_STRING_SHORT, true),
                'content' => $this->getInput('content', helper::FILTER_STRING_LONG),
                'createdOn' => time(),
                'userId' => $this->getUser('id')
            ]);
            
            $this->addOutput([
                'notification' => 'Élément ajouté avec succès',
                'redirect' => helper::baseUrl() . $this->getUrl(0)
            ]);
        }

        // Afficher le formulaire
        return $this->getLayout();
    }

    private function getLayout() {
        // Inclure le template approprié
        if (file_exists('module/' . $this->getUrl(0) . '/view/' . $this->getUrl(1) . '/' . $this->getUrl(1) . '.php')) {
            return file_get_contents('module/' . $this->getUrl(0) . '/view/' . $this->getUrl(1) . '/' . $this->getUrl(1) . '.php');
        } else {
            return file_get_contents('module/' . $this->getUrl(0) . '/view/index/index.php');
        }
    }
}
```

### Template d'index

```php
<?php
// module/monModule/view/index/index.php
echo '<div class="row">';
echo '<div class="col12">';
echo '<h2>Liste des éléments</h2>';

if (empty($items)) {
    echo '<p>Aucun élément trouvé.</p>';
} else {
    echo '<div class="tableWrapper">';
    echo '<table class="table">';
    echo '<thead><tr>';
    echo '<th>Titre</th>';
    echo '<th>Date</th>';
    echo '<th>Actions</th>';
    echo '</tr></thead>';
    echo '<tbody>';
    
    foreach ($items as $id => $item) {
        echo '<tr>';
        echo '<td>' . $item['title'] . '</td>';
        echo '<td>' . helper::dateUTF8('%d/%m/%Y', $item['createdOn']) . '</td>';
        echo '<td>';
        if ($this->isConnected() && $this->getUser('role') >= common::ROLE_EDITOR) {
            echo '<a href="' . helper::baseUrl() . $this->getUrl(0) . '/edit/' . $id . '" class="button">Éditer</a> ';
        }
        if ($this->isConnected() && $this->getUser('role') >= common::ROLE_ADMIN) {
            echo '<a href="' . helper::baseUrl() . $this->getUrl(0) . '/delete/' . $id . '" class="button buttonRed">Supprimer</a>';
        }
        echo '</td>';
        echo '</tr>';
    }
    
    echo '</tbody></table></div>';
}

if ($this->isConnected() && $this->getUser('role') >= common::ROLE_EDITOR) {
    echo '<a href="' . helper::baseUrl() . $this->getUrl(0) . '/add" class="button">Ajouter un élément</a>';
}

echo '</div></div>';
```

---

## Assistants de template

### Classe template

La classe `template` fournit des assistants pour générer des éléments de formulaire.

#### Champ texte

```php
<?php echo template::text('nom', [
    'label' => 'Libellé',
    'value' => $valeur,
    'placeholder' => 'Texte indicatif',
    'required' => true,
    'disabled' => false,
    'readonly' => false,
    'maxlength' => 255,
    'help' => 'Texte d\'aide',
    'class' => 'custom-class'
]); ?>
```

#### Champ mot de passe

```php
<?php echo template::password('password', [
    'label' => 'Mot de passe',
    'required' => true,
    'help' => 'Minimum 8 caractères'
]); ?>
```

#### Champ email

```php
<?php echo template::email('email', [
    'label' => 'Adresse email',
    'value' => $userEmail,
    'required' => true
]); ?>
```

#### Zone de texte

```php
<?php echo template::textarea('content', [
    'label' => 'Contenu',
    'value' => $content,
    'rows' => 10,
    'class' => 'editor'
]); ?>
```

#### Sélecteur

```php
<?php echo template::select('category', [
    'news' => 'Actualités',
    'blog' => 'Blog',
    'page' => 'Page'
], [
    'label' => 'Catégorie',
    'selected' => $selectedCategory,
    'required' => true
]); ?>
```

#### Cases à cocher

```php
<?php echo template::checkbox('enabled', true, [
    'label' => 'Activer',
    'checked' => $isEnabled,
    'help' => 'Cochez pour activer'
]); ?>
```

#### Boutons radio

```php
<?php echo template::radio('type', [
    'type1' => 'Type 1',
    'type2' => 'Type 2'
], [
    'label' => 'Type',
    'selected' => $selectedType
]); ?>
```

#### Sélecteur de fichier

```php
<?php echo template::file('image', [
    'label' => 'Image',
    'value' => $imagePath,
    'filter' => 'image/*',
    'help' => 'Formats acceptés : jpg, png, gif'
]); ?>
```

#### Sélecteur de date

```php
<?php echo template::date('date', [
    'label' => 'Date',
    'value' => $date,
    'required' => true
]); ?>
```

#### Bouton

```php
<?php echo template::submit('submit', [
    'value' => 'Sauvegarder',
    'class' => 'buttonGreen',
    'disabled' => false
]); ?>

<?php echo template::button('cancel', [
    'value' => 'Annuler',
    'href' => helper::baseUrl() . $this->getUrl(0),
    'class' => 'buttonGrey'
]); ?>
```

#### Champ caché

```php
<?php echo template::hidden('csrf', [
    'value' => $_SESSION['csrf']
]); ?>
```

---

## Variables et helpers

### Variables de sortie

```php
// Métadonnées
$this->output['metaTitle'] = 'Titre de la page';
$this->output['metaDescription'] = 'Description de la page';

// Contenu
$this->output['content'] = $content;
$this->output['contentLeft'] = $sidebarLeft;
$this->output['contentRight'] = $sidebarRight;

// Notifications
$this->output['notification'] = 'Message de succès';
$this->output['notification'] = 'Message d\'erreur';

// Styles et scripts
$this->output['style'] = '<link rel="stylesheet" href="custom.css">';
$this->output['script'] = '<script src="custom.js"></script>';
$this->output['inlineStyle'] = '<style>.custom { color: red; }</style>';
$this->output['inlineScript'] = '<script>alert("Test");</script>';

// Affichage
$this->output['display'] = common::DISPLAY_LAYOUT_MAIN;
$this->output['redirect'] = helper::baseUrl() . 'page/target';
```

### Helpers courants

#### helper::baseUrl()

Retourne l'URL de base du site.

```php
$url = helper::baseUrl(); // https://monsite.com/
$url = helper::baseUrl(true); // https://monsite.com/zwii/
```

#### helper::dateUTF8()

Formate une date selon la locale.

```php
$date = helper::dateUTF8('%d/%m/%Y %H:%M', time(), 'fr_FR');
```

#### helper::filter()

Filtre une valeur selon un type.

```php
$value = helper::filter($input, helper::FILTER_STRING_SHORT);
$value = helper::filter($input, helper::FILTER_EMAIL);
$value = helper::filter($input, helper::FILTER_INT);
```

#### Constants de filtre

```php
helper::FILTER_STRING_SHORT   // Chaîne courte (255 caractères)
helper::FILTER_STRING_LONG    // Chaîne longue
helper::FILTER_EMAIL          // Email
helper::FILTER_INT            // Entier
helper::FILTER_BOOLEAN       // Booléen
helper::FILTER_URL           // URL
helper::FILTER_FLOAT         // Nombre décimal
helper::FILTER_PASSWORD      // Mot de passe
```

---

## Exemples pratiques

### Formulaire complet avec validation

```php
<?php
// module/contact/view/index/index.php
if ($this->isPost()) {
    // Validation des champs
    $name = $this->getInput('name', helper::FILTER_STRING_SHORT, true);
    $email = $this->getInput('email', helper::FILTER_EMAIL, true);
    $subject = $this->getInput('subject', helper::FILTER_STRING_SHORT, true);
    $message = $this->getInput('message', helper::FILTER_STRING_LONG, true);
    
    // Envoi de l'email
    $content = '<h2>Nouveau message de contact</h2>';
    $content .= '<p><strong>Nom :</strong> ' . $name . '</p>';
    $content .= '<p><strong>Email :</strong> ' . $email . '</p>';
    $content .= '<p><strong>Sujet :</strong> ' . $subject . '</p>';
    $content .= '<p><strong>Message :</strong><br>' . nl2br($message) . '</p>';
    
    $success = $this->sendMail(
        $this->getData(['config', 'email']),
        'Nouveau message de contact',
        $content,
        $email
    );
    
    if ($success) {
        $this->addOutput([
            'notification' => 'Message envoyé avec succès',
            'redirect' => helper::baseUrl() . $this->getUrl(0)
        ]);
    } else {
        $this->addOutput([
            'notification' => 'Erreur lors de l\'envoi du message'
        ]);
    }
}
?>

<div class="row">
    <div class="col12">
        <h2>Contact</h2>
        
        <form method="post">
            <div class="row">
                <div class="col6">
                    <?php echo template::text('name', [
                        'label' => 'Nom',
                        'required' => true
                    ]); ?>
                </div>
                <div class="col6">
                    <?php echo template::email('email', [
                        'label' => 'Email',
                        'required' => true
                    ]); ?>
                </div>
            </div>
            
            <div class="row">
                <div class="col12">
                    <?php echo template::text('subject', [
                        'label' => 'Sujet',
                        'required' => true
                    ]); ?>
                </div>
            </div>
            
            <div class="row">
                <div class="col12">
                    <?php echo template::textarea('message', [
                        'label' => 'Message',
                        'required' => true,
                        'rows' => 8
                    ]); ?>
                </div>
            </div>
            
            <div class="row">
                <div class="col12">
                    <?php echo template::submit('submit', [
                        'value' => 'Envoyer'
                    ]); ?>
                </div>
            </div>
        </form>
    </div>
</div>
```

### Template avec gestion des permissions

```php
<?php
// module/galerie/view/index/index.php
$images = $this->getData(['module', $this->getUrl(0), 'images']);
?>

<div class="row">
    <div class="col12">
        <h2>Galerie</h2>
        
        <?php if ($this->isConnected() && $this->getUser('role') >= common::ROLE_EDITOR): ?>
            <a href="<?php echo helper::baseUrl() . $this->getUrl(0); ?>/add" class="button">
                Ajouter une image
            </a>
        <?php endif; ?>
        
        <?php if (empty($images)): ?>
            <p>Aucune image dans la galerie.</p>
        <?php else: ?>
            <div class="row">
                <?php foreach ($images as $id => $image): ?>
                    <div class="col4">
                        <div class="gallery-item">
                            <img src="<?php echo helper::baseUrl() . $image['path']; ?>" 
                                 alt="<?php echo $image['title']; ?>"
                                 class="gallery-image">
                            
                            <h4><?php echo $image['title']; ?></h4>
                            
                            <?php if ($this->isConnected() && $this->getUser('role') >= common::ROLE_EDITOR): ?>
                                <a href="<?php echo helper::baseUrl() . $this->getUrl(0); ?>/edit/<?php echo $id; ?>" 
                                   class="button">Éditer</a>
                            <?php endif; ?>
                            
                            <?php if ($this->isConnected() && $this->getUser('role') >= common::ROLE_ADMIN): ?>
                                <a href="<?php echo helper::baseUrl() . $this->getUrl(0); ?>/delete/<?php echo $id; ?>" 
                                   class="button buttonRed">Supprimer</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.gallery-item {
    margin-bottom: 20px;
    text-align: center;
}
.gallery-image {
    max-width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 4px;
}
</style>
```

### Template avec JavaScript intégré

```php
<?php
// module/calendrier/view/index/index.php
$events = $this->getData(['module', $this->getUrl(0), 'events']);
?>

<div class="row">
    <div class="col12">
        <h2>Calendrier</h2>
        
        <div id="calendar"></div>
    </div>
</div>

<?php $this->output['inlineScript'] = '
<script>
// Initialisation du calendrier
document.addEventListener(\'DOMContentLoaded\', function() {
    const calendar = new FullCalendar.Calendar(document.getElementById(\'calendar\'), {
        initialView: \'dayGridMonth\',
        locale: \'fr\',
        events: ' . json_encode(array_values($events)) . ',
        eventClick: function(info) {
            alert(\'Événement: \' + info.event.title);
        }
    });
    calendar.render();
});
</script>';
?>
```

Cette documentation couvre les principaux aspects du système de templates et des assistants disponibles dans ZwiiCMS. Pour plus de détails, consultez le code source des modules existants et les fichiers dans `core/layout/`.
