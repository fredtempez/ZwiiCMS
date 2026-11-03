# Écrire un module ZwiiCMS

Ce guide présente le cycle de développement d'un module : déclaration de la classe,
actions et droits, accès aux données, vues, formulaires et prise en charge des instances
multiples. Il complète :

- [`api-core-methods.md`](./api-core-methods.md) pour les méthodes de `common` ;
- [`api-templates.md`](./api-templates.md) pour les assistants HTML ;
- [`api-helpers.md`](./api-helpers.md) pour les fonctions utilitaires ;
- [`guide-bdd.md`](./guide-bdd.md) pour les bases JSON ;
- [`guide-multimodule-data.md`](./guide-multimodule-data.md) pour les espaces de données
  page/instance.

## 1. Structure d'un module

Un module de contenu se trouve sous `module/<id>/`. Le dossier et le nom de classe
correspondent généralement à l'identifiant du module.

```text
module/monmodule/
├── monmodule.php              # Classe et actions
├── view/
│   ├── index/
│   │   ├── index.php          # Vue HTML
│   │   ├── index.css          # CSS optionnelle
│   │   └── index.js.php       # JavaScript optionnel (PHP autorisé)
│   └── config/
│       ├── config.php
│       ├── config.css
│       └── config.js.php
├── i18n/                      # Traductions, si le module en utilise
├── ressource/
│   └── defaultdata.php        # Données initiales, si nécessaire
└── vendor/                    # Ressources tierces, si nécessaire
```

Le contrôleur porte la logique métier. Une vue est un fichier PHP inclus par le routeur
après l'exécution de l'action ; elle ne doit pas recharger ou inclure elle-même son
contrôleur.

## 2. Déclarer la classe, les métadonnées et les actions

La classe étend `common`. La propriété statique `$actions` déclare les actions accessibles
par URL et le rôle minimal attendu par le routeur.

```php
<?php

class monmodule extends common
{
    const VERSION = '1.0';
    const REALNAME = 'Mon module';
    const DATADIRECTORY = '';
    const MULTIMODULE = false;
    const SINGLEINSTANCE = true;

    public static $actions = [
        'index' => self::ROLE_VISITOR,
        'config' => self::ROLE_EDITOR,
    ];

    public static $items = [];

    public function index()
    {
        self::$items = $this->getData(['module', $this->getUrl(0), 'items']) ?? [];

        $this->addOutput([
            'view' => 'index',
            'title' => self::REALNAME,
        ]);
    }

    public function config()
    {
        if ($this->getUser('permission', __CLASS__, __FUNCTION__) !== true) {
            $this->addOutput(['access' => false]);
            return;
        }

        if ($this->isPost()) {
            $title = $this->getInput('monmoduleTitle', helper::FILTER_STRING_SHORT, true);
            if ($title === null || $title === '') {
                return;
            }
            $this->setData(['module', $this->getUrl(0), 'config', 'title', $title]);
            $this->addOutput([
                'notification' => helper::translate('Modifications enregistrées'),
                'redirect' => helper::baseUrl() . $this->getUrl(0) . '/config',
                'state' => true,
            ]);
            return;
        }

        $this->addOutput(['view' => 'config']);
    }
}
```

Les métadonnées usuelles sont :

| Constante | Rôle |
|---|---|
| `REALNAME` | Nom affiché dans l'interface. |
| `VERSION` | Version du module. |
| `UPDATE` | Version de données à partir de laquelle exécuter une migration, si le module en a une. |
| `DELETE` | Indique si le module peut être supprimé depuis la gestion des modules. |
| `DATADIRECTORY` | Dossier de données localisées fourni par le module, le cas échéant. |
| `MULTIMODULE` | Autorise plusieurs instances du module sur une même page lorsque `true`. Absent ou `false` : une seule instance. |
| `SINGLEINSTANCE` | Affine le cas `MULTIMODULE = true` : à `true`, le module est ramené à une seule instance par page. Absent ou `false` : plusieurs instances autorisées. Sans effet si `MULTIMODULE` n'est pas `true`. |

Déclarer uniquement les actions réellement prises en charge. Une action non déclarée ne
peut pas être appelée comme action de module : le routeur retombe sur `index()` pour les
segments qui ne correspondent pas à une action connue. La déclaration de rôle est la
première barrière d'accès, pas un remplacement des vérifications dans le code métier.

## 3. Actions, URL et rendu

Une URL de module suit généralement cette forme :

```text
/<pageId>/<action>/<argument0>/<argument1>
```

`index` est l'action par défaut. Les noms d'action composés avec des tirets sont convertis
en camelCase avant l'appel : `delete-event` appelle `deleteEvent()`.

Une action choisit sa vue en appelant `addOutput()`. Les variables locales de la méthode
d'action ne sont pas disponibles dans le fichier de vue inclus ensuite ; pour transmettre
des données, employer une propriété de classe conforme aux conventions du module ou les
relire dans la vue via les méthodes du core. Les propriétés statiques employées pour une
vue doivent être réinitialisées pour chaque rendu.

```php
$this->addOutput([
    'view' => 'index',
    'showPageContent' => true,
]);
```

Le routeur cherche alors `module/<id>/view/index/index.php`, puis charge les fichiers
`index.css` et `index.js.php` s'ils existent. Les vues administratives (`config`, `edit`,
etc.) sont déclarées de la même manière. Le code HTML arbitraire ne doit pas être retourné
par l'action avec `return` : utiliser les options de sortie du core.

Exemple de vue correspondante :

```php
<?php echo template::formOpen('monmoduleConfigForm'); ?>
<?php echo template::text('monmoduleTitle', [
    'label' => helper::translate('Titre'),
    'value' => $this->getData(['module', $this->getUrl(0), 'config', 'title']),
]); ?>
<?php echo template::submit('monmoduleSubmit'); ?>
<?php echo template::formClose(); ?>
```

Pour afficher du texte fourni par un utilisateur, l'échapper au contexte HTML :

```php
<?php foreach (monmodule::$items as $item) : ?>
    <h2><?php echo htmlspecialchars($item['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h2>
<?php endforeach; ?>
```

`addOutput()` accepte entre autres :

| Clé | Usage |
|---|---|
| `view` | Nom de la vue à inclure. |
| `content` | Contenu déjà préparé, pour les cas où une vue fichier n'est pas adaptée. |
| `title` | Titre de vue ou de document. |
| `vendor` | Dépendances front-end à charger. |
| `redirect` | URL de redirection après traitement. |
| `notification`, `state` | Message et état de notification (`true`, `false` ou non défini). |
| `access` | Refuser l'accès avec `false`. |
| `showPageContent` | Choisir si le contenu de la page hôte accompagne la vue. |
| `showBarEditButton` | Afficher le bouton d'édition de la page dans la barre membre. |

Les réponses JSON, brutes ou RSS doivent également définir le mode de sortie correspondant
au lieu d'être assemblées comme une vue HTML. Consulter les usages existants du module et
les constantes `DISPLAY_*` du core avant de créer un nouveau format de réponse.

## 4. Droits et traitement des formulaires

La déclaration `$actions` permet au routeur d'appliquer le niveau de rôle requis. Pour une
action qui modifie des données, vérifier aussi explicitement la permission **avant toute
modification**, notamment pour respecter les permissions de profil :

```php
if ($this->getUser('permission', __CLASS__, __FUNCTION__) !== true) {
    $this->addOutput(['access' => false]);
    return;
}
```

Le routeur exécute l'action avant de finaliser le contrôle d'accès et le rendu. Il ne faut
donc jamais effectuer une suppression ou une écriture, puis compter sur la réponse du
routeur pour empêcher l'opération.

Utiliser `isPost()` pour traiter une soumission. Cette méthode vérifie la présence d'un
POST et le jeton CSRF. Lire les entrées avec `getInput()` et le filtre correspondant ;
valider également les règles métier (valeurs permises, existence de l'objet, limites,
droits sur l'objet).

```php
if ($this->isPost()) {
    $name = $this->getInput('monmoduleName', helper::FILTER_STRING_SHORT, true);
    $quantity = $this->getInput('monmoduleQuantity', helper::FILTER_INT, true);

    if ($quantity < 1) {
        $this->addOutput([
            'notification' => helper::translate('Valeur invalide'),
            'state' => false,
        ]);
        return;
    }

    // Écrire après validation de permission et des valeurs.
}
```

Dans la vue, utiliser les assistants `template::formOpen()`, `template::text()`,
`template::textarea()`, `template::select()`, `template::submit()` et
`template::formClose()`. Ils suivent les conventions du CMS et incluent les éléments
nécessaires aux formulaires. Voir [`api-templates.md`](./api-templates.md).

Ne jamais supposer que le filtre d'entrée suffit à sécuriser l'affichage. Échapper les
valeurs non fiables lors de leur insertion dans du HTML ou un attribut, ou utiliser un
assistant de template qui réalise l'échappement attendu. Éviter de concaténer une entrée
utilisateur dans du HTML, du JavaScript ou un chemin de fichier.

## 5. Données et séparation page/instance

Pour les données métier, utiliser les méthodes `getData()`, `setData()` et `deleteData()`
plutôt que de modifier directement les bases JSON. Exemple :

```php
$instanceId = $this->getUrl(0);
$items = $this->getData(['module', $instanceId, 'items']) ?? [];

$this->setData(['module', $instanceId, 'items', $itemId], $item);
$this->deleteData(['module', $instanceId, 'items', $itemId]);
```

Les données propres au module et sa configuration vont sous `['module', $instanceId, …]`.
Les données de la page hôte (CSS, JavaScript, titre ou configuration de ses modules)
doivent utiliser `['page', $this->getPageId(), …]`. Les détails et les exemples sont dans
[`guide-multimodule-data.md`](./guide-multimodule-data.md).

Éviter les accès directs à `file_get_contents()` / `file_put_contents()` sur les bases
JSON : ils contournent JsonDb et ses paramètres de sauvegarde. Un fichier métier séparé
(par exemple un export ou une archive) doit suivre une convention de chemin et de nommage
stable, vérifier le résultat des opérations et ne jamais utiliser un chemin fourni sans
validation par l'utilisateur.

## 6. URL et multimodule

Un module qui prend en charge plusieurs instances doit définir `MULTIMODULE = true`. Pour
n'en autoriser qu'une seule malgré ce mode multi-instance, ajouter
`SINGLEINSTANCE = true` : la règle est lue par le cœur via
`Core::isMultiInstanceAllowed($moduleId)`, utilisée à la fois par le routeur (qui n'affiche
qu'une instance des modules mono-instance) et par l'éditeur de page (qui désactive l'option
d'un module déjà choisi ailleurs).
Dans une action et sa vue, `getUrl(0)` désigne alors l'identifiant de l'instance ; hors
contexte du module, cet index désigne le `pageId`. Pour accéder aux données de la page
hôte depuis le module, utiliser `getPageId()`.

Pour un argument applicatif situé après une action (`/edit/<id>`, `/delete/<id>`), utiliser
`getUrlArg()` plutôt qu'un index `getUrl(2)` ou `getUrl(3)` codé en dur :

```php
// Premier argument après l'action, qu'il s'agisse d'un URL mono- ou multi-instance.
$itemId = $this->getUrlArg(0);

// Deuxième argument après l'action.
$subId = $this->getUrlArg(1);
```

En présence d'une instance explicite, le routeur peut normaliser l'URL sous la forme
`/<pageId>/<action>/<instanceId>/<arguments…>`. Les liens générés depuis le module peuvent
utiliser `helper::baseUrl() . $this->getUrl(0)` ; le routeur reconnaît ce chemin court et
le résout vers la page hôte et l'instance.

L'argument d'`index()` peut, selon le module, être un slug ou un identifiant directement
après la page ; ce n'est pas nécessairement un argument d'action. Conserver la convention
de routage du module existant et ne pas remplacer mécaniquement tout `getUrl(1)` par
`getUrlArg()`.

### État statique et plusieurs rendus dans la même requête

Le routeur peut construire et rendre plusieurs instances du même module au cours d'une
même requête PHP. Une propriété statique contenant des données calculées pour une vue ne
doit donc pas conserver les éléments de l'instance précédente.

Construire les listes localement puis remplacer la propriété statique :

```php
$instanceItems = [];
foreach ($items as $id => $item) {
    $instanceItems[$id] = $this->prepareItem($item);
}
monmodule::$items = $instanceItems;
```

Éviter d'accumuler sans réinitialisation avec `self::$items[] = …` ou
`self::$items[$id] = …` dans une action appelée pour plusieurs instances. Les valeurs de
configuration partagées et immuables peuvent rester statiques ; les données calculées par
instance doivent être remplacées ou être des variables locales.

Tester au minimum deux instances du même module sur la même page, avec des contenus
distincts, puis vérifier que les listes, formulaires, liens, modifications et suppressions
restent propres à l'instance ciblée.

## 7. Données initiales, versions et traductions

- Garder les valeurs par défaut cohérentes avec le schéma réellement lu par le module.
- Lors d'une initialisation paresseuse, vérifier et écrire la même clé ; utiliser
  `=== null` si une valeur vide reste une valeur valide.
- Pour une modification de schéma après publication du module, prévoir une migration
  versionnée et compatible avec les données déjà installées. Ne pas réinitialiser une
  configuration existante au chargement.
- Utiliser `helper::translate()` pour les chaînes localisées et fournir/mettre à jour les
  fichiers de langue dans `i18n/` lorsque le module en utilise.
- Conserver les bibliothèques tierces nécessaires sous `vendor/` et déclarer leur
  chargement via `addOutput(['vendor' => [...]])` conformément aux noms reconnus par le
  core.

## 8. Checklist avant livraison

- [ ] La classe étend `common`, ses métadonnées et ses actions sont déclarées.
- [ ] Chaque action est enregistrée dans `$actions` avec le niveau adapté.
- [ ] Les permissions sont contrôlées avant les effets de bord.
- [ ] Les formulaires utilisent `isPost()` et `getInput()` avec filtres et validation métier.
- [ ] Les sorties passent par `addOutput()` et les vues suivent `view/<action>/<action>.php`.
- [ ] Les données d'instance utilisent `getUrl(0)` ; les données de page utilisent
      `getPageId()`.
- [ ] Les arguments d'action utilisent `getUrlArg()` quand ils suivent l'action.
- [ ] Les propriétés statiques ne mélangent pas les états de plusieurs rendus.
- [ ] Le module fonctionne en navigation publique et avec les rôles/profils attendus.
- [ ] Si `MULTIMODULE` vaut `true`, deux instances ont été testées sur la même page.
- [ ] Si `SINGLEINSTANCE` vaut `true`, une seconde instance n'est ni proposée par l'éditeur
      ni rendue par le routeur.
- [ ] Les fichiers PHP passent `php -l` et les cas modifiés ont été testés.
