    # ZwiiCMS — Feature « Multi-module » (v16.0.00) — Brief technique complet

> **Destinataire** : une autre instance/assistant. Objectif : reprendre le travail en cours sans perte de contexte (architecture, état d'implémentation, bug non résolu, fichiers, diffs, pistes).

---

## 1. Résumé pour reprendre vite

- **But** : permettre à une page d'afficher **plusieurs modules** (blog, galerie, formulaire, slider, search…) empilés, alors que ZwiiCMS est historiquement **1 page = 1 module**.
- **Version cible** : **16.0.00** (`dataVersion = 160000`).
- **État** : l'implémentation **existe déjà** (champ `pageModules`, boucle routeur, migration, `defaultData`). Le **rendu serveur fonctionne**.
- **Bug bloquant** : sur l'écran d'édition de page, **le code `[MODULE:…]` ne se rafraîchit pas** au changement de module dans les listes déroulantes (ni onglet Contenu, ni ligne onglet Extension).
- **Indice fort** : un marqueur JS injecté au `$(document).ready` affiche `lignes=0 selects=0`, **alors que les lignes et selects sont visibles dans le DOM**. → **problème de timing/ordre de chargement**, pas de logique métier.

---

## 2. Environnement

- **Langages** : PHP (cible 7.3+), JS (jQuery, TinyMCE, SortableJS, lity, tippy, simplelightbox).
- **CMS** : ZwiiCMS, namespace `Zwii\Cms`, **sans SGBD** — stockage JSON via `JsonDb`.
- **Racine projet locale** : `/Users/fred/htdocs/ZwiiCMS-private`.
- **Outils dispo** : `php` oui ; **pas** de `node`, `deno`, `bun`. Pas de serveur web testable en `curl` (appels annulés).
- L'utilisateur teste en local et manipule l'inspecteur du navigateur (c'est de là que venait l'artefact `élément { }` — **pas dans le code**).

---

## 3. Architecture clé

- `Core` (namespace `Zwii\Cms`) utilise les traits : `Data`, `InputOutput`, `PageContent`, `Tools`, `UserPermission`, `Security`.
- `Router extends Core` ; `Layout` pour le rendu.
- **Routeur** (`core/class/router.class.php`) capture le **JS de la vue** :
  ```php
  $scriptPath = $modulePath . core::MODULE_DIR . $moduleId . '/view/' . $output['view'] . '/' . $output['view'] . '.js.php';
  if (file_exists($scriptPath)) {
      ob_start();
      include $scriptPath;
      $this->output['script'] .= ob_get_clean();
  }
  ```
- **Layout** (`core/layout/layout.class.php`) :
  ```php
  $output .= '<script defer>' . Helper::minifyJs($coreScript . $this->core->output['script']) . '</script>';
  $output .= '<script defer>' . Helper::minifyJs(htmlspecialchars_decode($inlineScript)) . '</script>';
  ```
  ⚠️ `core.js.php` (cœur JS dépendant de jQuery) **et** le script de vue sont **concaténés dans un seul `<script defer>`**. Tout `echo` parasite capturé par `ob_start()` **pollue ce JS** et peut le rendre invalide.
- **Données JSON** : `site/data/<langue>/page.json`, `module.json` ; `site/data/config.json`, `theme.json`, `core.json` (`dataVersion`), `user.json`, `language.json`.
- **Rôles** : `ROLE_BANNED=-1`, `ROLE_VISITOR=0`, `ROLE_MEMBER=1`, `ROLE_EDITOR=2` (=`MODERATOR`), `ROLE_ADMIN=3`. `coreModuleIds = ['config','dashboard','install','language','maintenance','menu','page','plugin','sitemap','theme','user']`.
- **Versions de données** : entier `dataVersion` (`140000`, `150000`/`150001`, désormais **`160000`**). Migrations dans `core/include/update.inc.php`.

---

## 4. Modèle de données multi-module

Champ **`pageModules`** par page :

```php
'pageModules' => [
    '<instanceId>' => [
        'moduleId' => '<id module>',   // 'blog', 'gallery', 'form', 'slider', 'search'…
        'position' => 'top' | 'free' | 'bottom',
        'order'    => <int>,
    ],
]
```

- **`instanceId`** : clé unique (autorise plusieurs instances du même module).
- **Compat legacy** : champs `moduleId`/`modulePosition` conservés ; si `pageModules` absent → reconstruction d'une zone unique.
- **Zones** : `top` (1 module, avant contenu), `free` (N modules positionnés par code `[MODULE:<instanceId>]`), `bottom` (1 module, après contenu).

---

## 5. Rendu (routeur) — logique attendue

- Extraction : `$pageModules = $this->getData(['page', $pageId, 'pageModules']);`
- Fallback legacy si vide.
- Tri : `uasort($pageModules, fn($a,$b) => ($a['order'] ?? 1) <=> ($b['order'] ?? 1));`
- Classement : `topHtml[]`, `freeHtml[$instanceId]`, `bottomHtml[]`.
- Substitution dans `$pageContent` : `[MODULE:<instanceId>]` → HTML du module ; fallback `[MODULE]` → `preg_replace` limité à **1**.
- Assemblage : `implode('', $topHtml) . $pageContent . implode('', $bottomHtml)`.

---

## 6. Écran d'édition de page

- **Vue** : `core/module/page/view/edit/edit.php`
- **JS** : `core/module/page/view/edit/edit.js.php`
- **CSS** : `core/module/page/view/edit/edit.css`
- **Back** : `core/module/page/page.php`

### 6.1 Markup — onglet Extension

- `.pageModuleFixedZone` (haut / bas) :
  - `Template::select('pageModulesTopModuleId'|'pageModulesBottomModuleId', page::$moduleIds, ['class' => 'pageModuleSelect pageModuleFixedSelect', ...])`
  - hidden `pageModulesTopInstanceId`, `pageModulesTopModuleIdOld`, idem bottom.
- Zone centrale :
  - `#pageModulesListContainer`
  - `.pageModuleRow` (`data-row="<n>"`, `data-instance="<instanceId>"`) avec :
    - `.pageModuleHandle` (SortableJS)
    - `Template::select('pageModulesFreeModuleId<n>', page::$moduleIds, ['class' => 'pageModuleSelect', ...])`
    - `.pageModuleConfig` (engrenage), `.pageModuleDelete` (corbeille `buttonRed`)
    - `.pageModuleShortcode > .col12` : `.pageModuleShortcodeLabel` + (`.pageModuleShortcodeValue` ou `.pageModuleShortcodeNone`)
    - hidden `pageModulesFreeInstanceId<n>`, `pageModulesFreeModuleIdOld<n>`
  - `#pageModulesListInputs` (reçoit `input[name="pageModulesLevel[]"]` au submit)
  - `#pageModuleAdd`
- **Template** ligne : `#pageModuleTemplate` (`displayNone`, placeholder `__ROW__`).

### 6.2 Markup — onglet Contenu

- Bloc `#pageModuleContentShortcuts` (masqué si aucun `free`), corps `#pageModuleContentShortcutsBody`, une `.pageModuleShortcutCell` par module : **`Nom réel | [MODULE:instanceId]`**.
- PHP construit `$freeModuleCells` à partir des `pageModules` de position `free`.

### 6.3 Enregistrement (`page.php`)

Lecture POST : `pageModulesLevel[]`, `pageModulesFreeModuleId<row>`, `pageModulesFreeInstanceId<row>`, `pageModulesTopModuleId`/`TopInstanceId`, `pageModulesBottomModuleId`/`BottomInstanceId`. Champs `pageEditModuleRedirect` + `pageModuleConfigRow` → enregistre puis redirige vers la config du module.

### 6.4 CSS (`edit.css`) — extrait (fichier propre)

```css
.pageModulesList .pageModuleRow { border-bottom:1px solid #eee; padding:6px 0; cursor:move; }
.pageModulesList .pageModuleRow:last-child { border-bottom:none; }
.pageModulesList .pageModuleHandle { cursor:move; text-align:center; color:#999; font-size:1.2em; }
.pageModulesList .pageModuleRow select { margin-bottom:0; }
.pageModuleShortcode { margin-top:4px; }
.pageModuleShortcode .pageModuleShortcodeLabel { font-size:.85em; color:#666; margin-right:6px; }
.pageModuleShortcodeValue { background:#f4f4f4; border:1px solid #ddd; border-radius:3px; padding:1px 6px; font-family:monospace; cursor:pointer; user-select:all; }
.pageModuleContentSep { color:#aaa; margin:0 6px; }
.pageModuleShortcutCell { padding:2px 0; }
.pageModuleShortcode .pageModuleShortcodeNone { font-size:.85em; color:#aaa; font-style:italic; }
.pageModuleRow.sortable-ghost { opacity:.4; }
```

---

## 7. Logique JS actuelle (`edit.js.php`)

Constante : `var PAGE_MODULE_LIST_ID = "#pageModulesListContainer";`

**Fonctions** : `nextPageModuleRowKey()`, `serializePageModules()`, `protectPageModuleRow()`, `updatePageModuleConfigState()`, `refreshPageModuleRowStates()`, `pageModuleSelectedId()`, `pageModuleRealName()`, `pageModuleRowInstanceId()`, `updatePageModuleRowShortcode()`, `refreshFreeModuleShortcodes()`.

**Handlers délégués (`document`)** :
- `change` sur `.pageModuleFixedSelect`.
- `change` sur `select.pageModuleSelect` → `protect…` + `update…State` + `updatePageModuleRowShortcode` + `refreshFreeModuleShortcodes`.
- `click` : `.pageModuleDelete`, `.pageModuleConfig`, `.pageModuleFixedConfig`, `#pageModuleAdd`.

**`$(document).ready`** : init onglets/profil/barres ; `Sortable.create(#pageModulesListContainer, { handle: '.pageModuleHandle', onEnd: refreshFreeModuleShortcodes })` ; `refreshPageModuleRowStates()` ; `refreshFreeModuleShortcodes()`.

---

## 8. Bug actuel — description précise

1. Page avec 2 modules `free` : `search` (instance `search`) et `slider` (instance `slider`).
2. Le DOM affiche correctement :
   ```
   Module
   Code à insérer dans la page : [MODULE:search]
   Module
   Code à insérer dans la page : [MODULE:slider]
   ```
3. **Attendu** : changer le module met à jour le code (ligne + liste Contenu).
4. **Observé** : aucun changement, cache vidé sans effet.
5. **Debug** : bandeau au `ready` → `ZWII-DEBUG: script chargé — lignes=0 selects=0`.
   ⇒ le script **s'exécute**, mais `$("#pageModulesListContainer .pageModuleRow").length === 0` et `$("select.pageModuleSelect").length === 0` **au `ready`**, alors que les éléments existent visuellement.

**Interprétation** : **timing / ordre de chargement** — jQuery pas prêt, ou script exécuté avant insertion du DOM, ou double `defer`/contexte.

---

## 9. Pistes de diagnostic (priorité)

1. DevTools → Elements : ordre `jQuery` → `core.js.php` → **script de vue** ; le `<script defer>` est-il après le conteneur DOM ?
2. Console :
   ```js
   typeof jQuery, typeof $, ($.fn && $.fn.jquery)
   $("#pageModulesListContainer").length
   document.querySelectorAll("#pageModulesListContainer .pageModuleRow").length
   ```
3. Vérifier si `edit.js.php` est bien dans le buffer `<script>` du layout.
4. Vérifier qu'aucun `echo`/`var_dump` ne pollue `ob_start()` (§10).
5. Déplacer les init hors `ready` (`$(window).on("load")` ou `setTimeout(…,0)`).

**Correctifs envisagés** :
- Garder la **délégation** `$(document).on("change", "select.pageModuleSelect", …)` (indépendante de l'existence des lignes) ;
- Ne pas dépendre de l'init synchrone ; exécuter l'init après `window.load`.

---

## 10. Déjà corrigé

| # | Fichier | Correction |
|---|---------|------------|
| 1 | `core/class/template.class.php` | **Suppression de `echo "ppp";`** (debug) dans `Template::select()`, branche `multiple => true`. Un `echo` pendant `ob_start()` pollue le JS concaténé → JS possiblement invalide. |
| 2 | `core/class/router.class.php` | Les actions de gestion (`update`, `theme`, …) excluent le contenu de la page hôte. |
| 3 | `router.class.php` + `Data.trait.php` | `getPageId()` + réécriture `page/<action>/<instanceId>` → `page/<action>/<pageId>`. |
| 4 | `edit.php` / `edit.js.php` / `edit.css` | Liste de shortcodes sans tableau (cellules `Nom | [MODULE:…]`), régénérée à la volée (add/delete/change/sort). |
| 5 | `edit.js.php` | `pageModuleRowInstanceId()` : code affiché = `instanceId` sinon `moduleId` (le code suit le module courant). |

---

## 11. Debug à retirer (ajouté par l'assistant)

Dans `core/module/page/view/edit/edit.js.php` :
- Dans `$(document).ready`, début du bloc MULTI-MODULE :
  ```js
  console.log("[ZWII-DEBUG] edit.js.php chargé, ready exécuté",
      "lignes:", $("#pageModulesListContainer .pageModuleRow").length,
      "selects:", $("select.pageModuleSelect").length);
  $("body").prepend('<div style="position:fixed;top:0;left:0;z-index:99999;background:#c00;color:#fff;font:12px monospace;padding:2px 8px;">ZWII-DEBUG: script chargé — lignes=' + $("#pageModulesListContainer .pageModuleRow").length + ' selects=' + $("select.pageModuleSelect").length + '</div>');
  ```
- Dans le handler `change` :
  ```js
  console.log("[ZWII-DEBUG] change capté sur select.pageModuleSelect", $(this).attr("id"), "=", $(this).val());
  ```

➡️ **À supprimer** après diagnostic.

---

## 12. Tâches restantes pour finaliser v16.0.00

- [ ] **Résoudre le bug de rafraîchissement JS** (priorité).
- [ ] Retirer les marqueurs `[ZWII-DEBUG]` (§11).
- [ ] `CHANGES.md` : ajouter `## Version 16.0.00`.
- [ ] `core/core.php` : `ZWII_UPDATE_CHANNEL` encore `'v15'` → basculer en `'v16'` si décidé ; cohérence `ZWII_VERSION`.
- [ ] `doc/api-core-methods.md` : référence de version obsolète (`14.1.04`).
- [ ] Vérifs transverses : sitemap, metas, fusion `vendor`, sitemap blog (`moduleId === 'blog'`), barres latérales.
- [ ] `defaultData` : `core.dataVersion = 160000` + `pageModules` initialisé.
- [ ] Migration `160000` dans `update.inc.php` (legacy → `pageModules`).

---

## 13. Diffs clés (état actuel)

### 13.1 `edit.js.php` — cœur du rafraîchissement

```js
var PAGE_MODULE_LIST_ID = "#pageModulesListContainer";

function pageModuleSelectedId($select) {
    return ($select.val() || "").toString();
}

function pageModuleRealName($select) {
    var text = $select.find("option:selected").text() || "";
    return text.replace(/\s*\([^()]*\)\s*$/, "").trim();
}

function pageModuleRowInstanceId($row) {
    var instanceId = ($row.find("input[id^='pageModulesFreeInstanceId']").val() || "").toString();
    if (instanceId !== "") { return instanceId; }
    return pageModuleSelectedId($row.find("select.pageModuleSelect"));
}

function updatePageModuleRowShortcode($row) {
    var $shortcode = $row.find(".pageModuleShortcode");
    if (!$shortcode.length) { return; }
    var moduleId = pageModuleSelectedId($row.find("select.pageModuleSelect"));
    var instanceId = pageModuleRowInstanceId($row);
    var $label = $shortcode.find(".pageModuleShortcodeLabel").first();
    if (!$label.length) {
        $label = $('<span class="pageModuleShortcodeLabel"></span>');
        $label.text("Code à insérer dans la page :");
    }
    var html = $label.prop("outerHTML");
    if (moduleId !== "" && instanceId !== "") {
        html += ' <span class="pageModuleShortcodeValue">' + $("<div>").text("[MODULE:" + instanceId + "]").html() + '</span>';
    } else {
        html += ' <span class="pageModuleShortcodeNone">' + $("<div>").text("Disponible après enregistrement.").html() + '</span>';
    }
    var $cell = $shortcode.find(".col12");
    if ($cell.length) { $cell.html(html); } else { $shortcode.html(html); }
}

function refreshFreeModuleShortcodes() {
    var $body = $("#pageModuleContentShortcutsBody");
    if (!$body.length) { return; }
    var cells = [];
    $(PAGE_MODULE_LIST_ID + " .pageModuleRow").each(function () {
        var $row = $(this);
        var $select = $row.find("select.pageModuleSelect");
        var moduleId = pageModuleSelectedId($select);
        if (moduleId === "") { return; }
        var instanceId = pageModuleRowInstanceId($row);
        var realName = pageModuleRealName($select) || moduleId;
        cells.push({ realName: realName, shortcode: "[MODULE:" + instanceId + "]" });
    });
    var $wrapper = $("#pageModuleContentShortcuts");
    if (!cells.length) { $body.empty(); $wrapper.hide(); return; }
    $wrapper.show();
    var html = '';
    for (var i = 0; i < cells.length; i++) {
        html += '<div class="pageModuleShortcutCell">'
            + $("<div>").text(cells[i].realName).html()
            + ' <span class="pageModuleContentSep">|</span> '
            + '<span class="pageModuleShortcodeValue">' + $("<div>").text(cells[i].shortcode).html() + '</span></div>';
    }
    $body.html(html);
}

$(document).on("change", "select.pageModuleSelect", function () {
    var $row = $(this).closest(".pageModuleRow");
    if (!$row.length) { return; }
    protectPageModuleRow($row, $row.find("input[id^='pageModulesFreeModuleIdOld']"), $row.find("select[id^='pageModulesFreeModuleId']"));
    updatePageModuleConfigState($row.find("select.pageModuleSelect"), $row.find(".pageModuleConfig"));
    updatePageModuleRowShortcode($row);
    refreshFreeModuleShortcodes();
});
```

### 13.2 `edit.php` — extrait du bloc Contenu

```php
<?php
$freeModuleCells = [];
$modulesCatalog = Helper::getModules();
$pageModulesForContent = $this->getData(['page', $this->getUrl(2), 'pageModules']);
if (is_array($pageModulesForContent)) {
    foreach ($pageModulesForContent as $instanceId => $instanceConfig) {
        if (($instanceConfig['position'] ?? '') === 'free' && !empty($instanceConfig['moduleId'])) {
            $moduleId = $instanceConfig['moduleId'];
            $realName = $modulesCatalog[$moduleId]['realName'] ?? $moduleId;
            $freeModuleCells[] = htmlspecialchars($realName)
                . ' <span class="pageModuleContentSep">|</span> '
                . '<span class="pageModuleShortcodeValue">' . htmlspecialchars('[MODULE:' . $instanceId . ']') . '</span>';
        }
    }
}
?>
<div class="row" id="pageModuleContentShortcuts" <?php echo $freeModuleCells ? '' : 'style="display:none;"'; ?>>
    <div class="col12">
        <div class="block">
            <h4><?php echo Helper::translate('Modules à insérer dans le contenu'); ?></h4>
            <div class="blockContainer">
                <p class="helpBlock">
                    <?php echo Helper::translate('Insérez le code ci-dessous dans le contenu de la page, à l\'endroit où le module doit s\'afficher (position «À l\'emplacement du mot clé»).'); ?>
                </p>
                <div id="pageModuleContentShortcutsBody">
                    <?php foreach ($freeModuleCells as $cell): ?>
                        <div class="pageModuleShortcutCell"><?php echo $cell; ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
```

---

## 14. Points d'attention / pièges connus

1. **`Template::select` + `sprintAttributes`** : `class` **non exclu** → `pageModuleSelect` bien sur le `<select>`. Sélecteur OK.
2. **`Helper::minifyJs`** : supprime commentaires `//` et retours ligne ; protège `://`. Un `echo` parasite capturé **casse le JS**.
3. **Artefact `élément { }`** : vient de **l'inspecteur du navigateur**, **pas** du dépôt. `edit.css` est propre.
4. **Deux `<script defer>`** dans le layout : le 1er = `core.js.php` **+** script de vue (concaténés) ; le 2nd = `inlineScript`.
5. **`core/core.php`** apparaît avec deux états (formaté vs minifié) ; possible confusion avec une archive `v15/update/core/core.php`.

---

## 15. Historique de session

- Des fichiers ont été modifiés **par une autre IA (Copilot)** suite à une manip utilisateur. L'assistant actuel n'avait **pas** écrit avant cette itération (lectures seules), puis :
  - a **supprimé** `echo "ppp";` dans `template.class.php` ;
  - a modifié `edit.js.php` (fonctions + handlers) ;
  - a **ajouté** les marqueurs `[ZWII-DEBUG]` (à retirer).
- Commandes `curl` et `node --check` **annulées** ; `node` non installé.

---

## 16. Ce qu'il faut faire en arrivant

1. **Lire** : `core/layout/layout.class.php` (injection scripts), `core/class/router.class.php` (capture JS), `edit.js.php`, `edit.php`.
2. **Reproduire** : page à 2 modules `free`, onglet Extension, changer un `<select>`, observer ligne + onglet Contenu.
3. **Trouver** pourquoi `$(document).ready` voit `lignes=0 selects=0` alors que le DOM contient les lignes. **C'est LE point bloquant.**
4. Corriger le timing, **retirer le debug**, puis traiter le §12.

---

## 17. Incrément « configurabilité et lisibilité des instances » (état actuel du code)

> Les sections 6–13 reflètent un état antérieur (liste de shortcodes dans l'onglet Contenu, etc.).
> Le code actuel diffère : l'onglet Contenu utilise un **panneau `#pageModuleShortcutHelp`**
> rempli par `refreshPageModuleShortcuts()` (nom + code + boutons *Insérer* / *Copier*),
> et la clé d'instance est `moduleId-<pageId>-<zone>` (`<uniq>` pour `free`). Cet incrément
> ajoute deux capacités :

### 17.1 Onglet Extension → Gestion des modules (`core/module/plugin/plugin.php::index()`)

La colonne « Pages » liste `Titre (pageId)`. Pour les modules en **position libre**, on suffixe
désormais le **nom réel** et l'**instance nettoyée** (sans le nom du module ni le marqueur
`-free`), via `self::getInstancePosition()` (méthode du trait `Data`, donc toujours disponible)
et le helper `instanceLabel()` :

```php
$link = '<a href="' . Helper::baseUrl() . $info['pageId'] . '" target="_blank">'
      . $info['title'] . ' (' . $info['pageId'] . ')</a>';
if (self::getInstancePosition($info['instanceId']) === 'free') {
    $realName = $infoModules[$info['moduleId']]['realName'] ?? $info['moduleId'];
    $instanceLabel = $this->instanceLabel($info['instanceId'], $info['moduleId']);
    $link .= ' — ' . htmlspecialchars($realName)
           . ($instanceLabel !== '' ? ' (' . htmlspecialchars($instanceLabel) . ')' : '');
}
```

`instanceLabel()` retire le préfixe `<moduleId>-` et le marqueur `-free` : `blog-accueil-free-k3f9`
→ `accueil-k3f9`. Les zones fixes (`top`/`bottom`) restent affichées `Titre (pageId)` sans suffixe.

### 17.2 Onglet Contenu → bouton « Configurer » dans le panneau des modules libres

- Modèle de bouton ajouté dans `edit.php` (`#pageModuleShortcutButtonTemplates`) :
  `Template::button('pageModuleShortcutConfigTpl', ['class' => 'pageModuleShortcutConfig', 'value' => Template::ico('gear'), …])`.
- `edit.js.php` → `refreshPageModuleShortcuts()` clone ce modèle en portant `data-code`, `data-row`
  (ligne source) et `data-instance`.
- Handler `.pageModuleShortcutConfig` : **même flux que `.pageModuleConfig`** de l'onglet Extension
  (`#pageModuleConfigRow = data-row`, `#pageEditModuleRedirect = 1`, `submit()` du formulaire page) :
  enregistre la page puis ouvre la configuration du module.
- `edit.css` : `.pageModuleShortcutConfig` intégré à la règle de mise en page des boutons du panneau
  (largeur auto, `margin-left:0`).
