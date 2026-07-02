# Gestion des bases de données (JsonDb) – ZwiiCMS

Ce guide décrit le fonctionnement des bases JSON et la procédure recommandée pour lire/écrire les données dans ZwiiCMS.

## Principes
- Chaque base est un fichier JSON (ex: `theme.json`, `page.json`, `locale.json`) chargé via JsonDb avec options de compression/chiffrement.
- Le cache partagé (`$this->dataFiles`) stocke les instances JsonDb pour les accès courants.
- Les bases de contenu sont rangées par langue dans `site/data/<langue>/`; les bases globales sont dans `site/data/`.
- La configuration est lue en priorité dans `site/data/config.json` (`config.database`), sinon dans les valeurs par défaut fournies par `core/module/install/resource/defaultdata.php`.

## Méthodes clés (classe `common`)
- `loadDataFile($dataFile, $lang)` (privée) : instancie une JsonDb dans le cache partagé en appliquant la config globale + spécifique, pour la langue cible.
- `ensureDataFileLoaded($dataFile, $lang)` (protégée) : permet aux modules de charger une base dans le cache sans accès direct à la méthode privée.
- `fetchDataFile($dataFile, $lang, $query)` : lit une base via une nouvelle instance (hors cache) avec la configuration complète ; par défaut la requête vise la racine nommée de la base.
- `saveDataFile($dataFile, $data, $lang)` : écrit à la racine d’une base et persiste via JsonDb en respectant compression/chiffrement et la langue ciblée.
- `isDataFileLoaded($dataFile)` : indique si la base est déjà en cache.
- `loadModuleDataFiles($moduleId, $lang)` : précharge les bases communes + spécifiques au module pour éviter des chargements paresseux plus loin (theme, font, admin, profil, blacklist, language…).

## Procédure de lecture
1) Accès standard : `getData([...])` charge automatiquement la base dans le cache si nécessaire (`loadDataFile`).
2) Accès isolé (autre langue, pas de pollution du cache) : `fetchDataFile('page', $lang, 'page')` ou `fetchDataFile('locale', $lang, 'locale')`.

## Procédure d’écriture
1) Charger la base avec la bonne config/langue : `saveDataFile('module', $payload, $lang)` (compression/chiffrement appliqués selon la configuration).
2) JsonDb gère `save()` et respecte `setAutoSave` si vous l’avez modifié.

## Configurer les bases
- `site/data/config.json` : section `config.database` pour définir par base `compression`, `compressionLevel`, `encryption`, `encryptionKey`, `autoBackup`, etc.
- Par défaut (si aucune entrée), la configuration est `JSONDB_DEFAULT_CONFIG` dans `common`.
- Si `encryption` est true sans `encryptionKey`, le chiffrement est automatiquement désactivé.

## Bonnes pratiques
- Pour lire/écrire dans une autre langue sans toucher au cache global : utiliser `fetchDataFile` puis `saveDataFile` avec le paramètre `$lang`.
- Toujours fournir la langue sur les bases de contenu afin d’éviter d’écrire dans l’espace par défaut.
- Précharger via `loadModuleDataFiles` à l’entrée d’un module pour limiter les accès répétés.
- Éviter les accès directs par `file_get_contents`/`file_put_contents` sur les JSON, sauf cas d’exception outillée.
- Protéger les clés de chiffrement et les stocker hors dépôt si possible.

## Exemples rapides
```php
// Lecture isolée des pages d’une langue
$pages = $this->fetchDataFile('page', $lang, 'page');

// Sauvegarde des modules pour une langue
$this->saveDataFile('module', $data, $lang);

// Préchargement des bases nécessaires au module blog
$this->loadModuleDataFiles('blog', $lang);
```
