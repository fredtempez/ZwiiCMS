# Accès aux données en multi-module – ZwiiCMS

Ce guide décrit la convention d'accès aux données (`getData` / `setData` / `deleteData`)
pour les modules dans l'architecture **multi-module** de ZwiiCMS. Il répond à la question :
*quand utiliser `getUrl(0)`, quand utiliser `getPageId()` ?*

> Cible : développeurs de modules. Voir aussi `guide-bdd.md` (fonctionnement des bases JSON)
> et `api-core-methods.md` (méthodes publiques de `common`). Pour le cycle de création
> complet d'un module, voir [`guide-ecriture-modules.md`](./guide-ecriture-modules.md).

## Identité d'instance : clé `moduleId-<zone>`

Depuis la v16, l'identité d'une instance est portée par **sa clé** dans `pageModules`,
sous la forme `moduleId-<zone>` :

| Zone | Clé d'instance | Nombre d'instances |
|---|---|---|
| `top` | `moduleId-top` (ex. `gallery-top`) | une seule |
| `free` | `moduleId-free-<uniq>` (ex. `gallery-free-k3f9`) | plusieurs (jeton unique) |
| `bottom` | `moduleId-bottom` (ex. `gallery-bottom`) | une seule |

- Le champ `position` **n'est plus stocké** dans `pageModules` : la zone est **déduite
  de la clé** via `page::getInstancePosition($instanceId)` (implémentation de
  référence dans le cœur / trait `Data` : `Core::getInstancePosition()`).
- Pour la zone `free`, **chaque instance — y compris la première — reçoit un JETON
  UNIQUE** (`<uniq>`, sans tiret, ex. `k3f9`), généré CÔTÉ CLIENT et réutilisé tel quel
  par le serveur (le client fait autorité). L'id d'une instance ne dépend donc **jamais**
  de l'ordre ni du nombre des autres instances (plus de collecte ni de suffixe `-N`
  séquentiel). Les zones fixes (`top`/`bottom`) gardent `moduleId-<zone>`.
- Une même instance ne peut donc jamais désigner deux zones : `gallery-top` ≠
  `gallery-bottom`, ce qui rend toute confusion de zone structurellement impossible.

> Voir [`test-manuel-multimodule.md`](./test-manuel-multimodule.md) pour la procédure de
> validation manuelle complète de cette convention.

## Les deux espaces de données

Une page peut héberger **plusieurs instances** du même module (zones `top`, `free`,
`bottom`). Pour que ces instances ne se marchent pas dessus, les données sont réparties
dans deux espaces distincts :

| Espace | Clé d'index | Contenu | Identifiant à utiliser |
|---|---|---|---|
| **Données du module** | `['module', <instanceId>, …]` | contenu métier et config **propres à une instance** (posts, événements, paramètres…) | `$this->getUrl(0)` |
| **Données de la page hôte** | `['page', <pageId>, …]` | attributs **indivisibles de la page** (titre, `css`, `js`, `pageModules`, `role`, `block`…) | `$this->getPageId()` |

### Point clé

- **`getUrl(0)`** vaut l'**instanceId** en contexte multi-module (et le pageId sinon).
- **`getPageId()`** renvoie **toujours** l'id de la page hôte, quel que soit le contexte.

Il est essentiel de ne jamais confondre les deux : un **instanceId n'est pas une clé de page**
dans `page.json` (les clés de `page.json` sont les pageId réels).

## Règle selon la donnée manipulée

### 1. Données propres au module → `getUrl(0)`

Tout ce qui appartient à **l'instance** du module se lit/écrit sous `['module', …]` avec
`getUrl(0)` :

```php
// Contenu métier de l'instance
$events = $this->getData(['module', $this->getUrl(0), 'content']);

// Configuration de l'instance
$config = $this->getData(['module', $this->getUrl(0), 'config']);

// Écriture
$this->setData(['module', $this->getUrl(0), 'content', $id, [...]]);
```

En multi-module, deux instances du même module ont des données **séparées** : c'est
`getUrl(0)` (l'instanceId) qui garantit cette séparation.

### 2. Données de la page hôte → `getPageId()`

Tout ce qui appartient à **la page** (et non à une instance) se lit/écrit sous
`['page', …]` avec `getPageId()` :

```php
// Feuille de style de la page
$css = $this->getData(['page', $this->getPageId(), 'css']);
$this->setData(['page', $this->getPageId(), 'css', $cssContent]);

// Champs de la page hôte attendus par le cœur
$this->getData(['page', $this->getPageId(), 'pageModules']);
$this->getData(['page', $this->getPageId(), 'title']);
```

### 3. À ne jamais faire

```php
// ❌ FAUX : en multi-module getUrl(0) est un instanceId, pas un pageId.
//          getData ne trouvera rien (et setData écrirait sous une clé inexistante).
$this->getData(['page', $this->getUrl(0), 'css']);
$this->setData(['page', $this->getUrl(0), 'js', $script]);

// ✅ JUSTE
$this->getData(['page', $this->getPageId(), 'css']);
$this->setData(['page', $this->getPageId(), 'js', $script]);
```

## Initialisation : garde et écriture sur la même clé

Lorsqu'un module initialise une donnée par défaut (CSS, contenu…), la **garde** de présence
et l'**écriture** doivent porter sur **la même clé**. Une incohérence entre les deux provoque
la réécriture de la donnée à chaque chargement (et l'écrasement des personnalisations).

```php
private function update()
{
    $pageId = $this->getPageId();

    // CSS de page : garde ET écriture sur la clé 'page' de la page hôte
    if ($this->getData(['page', $pageId, 'css']) === null) {
        $this->setData(['page', $pageId, 'css', $cssContent]);
    }

    // Donnée de module : garde ET écriture sur la clé 'module' de l'instance
    if ($this->getData(['module', $this->getUrl(0), 'config']) === null) {
        $this->setData(['module', $this->getUrl(0), 'config', $defaults]);
    }
}
```

> Utiliser `=== null` (et non `empty()`) évite d'écraser une valeur volontairement vide
> (chaîne vide, tableau vide…).

## Lire les données d'une autre instance de la page

Pour un traitement qui concerne **toutes** les instances d'une page (par ex. charger les
vendors, dresser un inventaire), parcourir `pageModules` de la page hôte :

```php
$pageId = $this->getPageId();
$pageModules = $this->getData(['page', $pageId, 'pageModules']);

if (is_array($pageModules)) {
    foreach ($pageModules as $instanceId => $instance) {
        $moduleId = $instance['moduleId'] ?? '';
        // ...
    }
}
```

Le champ legacy `page[<pageId>]['moduleId']` ne contient que le **premier** module : il ne
doit être utilisé que comme repli pour les pages mono-module anciennes.

## Identifiants : instanceId, pageId, position

- **pageId** : clé réelle dans `page.json`. Identifie une page.
- **instanceId** : identifie une instance de module sur une page. Convention stable :
  `moduleId-top`, `moduleId-bottom` (zones fixes) et `moduleId-free-<uniq>` (zone libre,
  jeton unique, ex. `gallery-free-k3f9`). C'est la clé de `pageModules` **et** de
  `['module', …]`.
- **zone** (`top`, `bottom`, `free`) : emplacement d'affichage d'une instance. Elle est
  **encodée dans la clé** (`moduleId-<zone>`) et **n'est plus un champ** de `pageModules`.
  Ne pas confondre zone et instanceId : une URL ne doit jamais transporter une zone à la
  place d'un instanceId.

## Contexte d'instance et `getUrl`

Le routeur réécrit `getUrl` pendant l'exécution d'un module via
`pushModuleInstance()` / `popModuleInstance()` :

- **Pendant** l'exécution d'un module : `getUrl(0)` = instanceId, `getPageId()` = page hôte.
- **Hors** contexte d'instance (ex. rendu du layout, `showVendor()`) : `getUrl(0)` = pageId,
  et `getPageId()` = pageId.

C'est pour cette raison que le code **hors module** (layout, helpers de page) doit résoudre
les modules à partir de `getPageId()` et de `pageModules`, et non de `getUrl(0)`.

## Récapitulatif

| Besoin | Clé d'index |
|---|---|
| Lire/écrire le contenu métier du module | `['module', $this->getUrl(0), …]` |
| Lire/écrire la config du module | `['module', $this->getUrl(0), …]` |
| Lire/écrire un attribut de la page (css, js, champs cœur) | `['page', $this->getPageId(), …]` |
| Parcourir toutes les instances d'une page | `['page', $this->getPageId(), 'pageModules']` |
| Initialiser une valeur par défaut | garde **et** écriture sur **la même** clé |

## Erreurs fréquentes

| Erreur | Symptôme | Correction |
|---|---|---|
| `['page', $this->getUrl(0), …]` | donnée introuvable en multi-module ; liste déroulante vide | utiliser `$this->getPageId()` |
| Garde `['module', …]` mais écriture `['page', …]` | donnée réécrite à chaque accès, personnalisations écrasées | aligner garde et écriture sur la même clé |
| Résoudre un vendor module via `getUrl(0)` dans le layout | librairie JS/CSS du module non chargée | résoudre via `getPageId()` + `pageModules` |
| Utiliser une position (`top`, `bottom`, `free`) comme instanceId | mauvais routage / données perdues | utiliser l'instanceId (`getUrl(0)`) |
| Confondre pageId et instanceId | `getData`/`setData` ciblent la mauvaise clé | pageId → `getPageId()`, instanceId → `getUrl(0)` |
