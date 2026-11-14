# Maintenance de l'historique Git

Ce dépôt a fait l'objet d'une réécriture d'historique en novembre 2026 : le
dossier `site/` (données d'instance et sauvegardes) y avait été versionné par
erreur, ce qui portait `.git` à **645 Mo** pour un code source de 2 Mo. Après
purge, le dépôt pèse **50 Mo**.

## Ce qui ne doit jamais être versionné

| Chemin | Pourquoi |
|---|---|
| `site/data/`, `site/*/data/` | contenu du site, comptes, configuration, clés |
| `site/backup/`, `site/*/backup/` | sauvegardes automatiques (archives de plusieurs centaines de Mo) |
| `site/file/`, `site/*/file/` | médias téléversés |
| `site/tmp/`, `site/*/tmp/` | cache et fichiers temporaires |
| `*.zip` | archives de sauvegarde |
| `core/module/config/tool/data.key` | clé de chiffrement de l'instance |

**Piège à connaître** : les motifs ancrés du type `site/file/*` ne couvrent pas
les instances installées dans un sous-dossier, dont les chemins sont
`site/site/file/...`. Le `.gitignore` couvre désormais les deux formes.

## Garde-fou automatique

Un hook `pre-commit` refuse les chemins ci-dessus, les `*.zip` et tout fichier
de plus de 5 Mo. Il n'est pas actif par défaut après un clone :

```sh
git config core.hooksPath .githooks
```

Seuil personnalisable ponctuellement :

```sh
GIT_MAX_FILE_MB=10 git commit
```

En cas de faux positif, `git commit --no-verify` court-circuite le hook — à
n'utiliser qu'en connaissance de cause, un blob volumineux ne peut plus être
retiré sans réécrire l'historique.

## Réécrire l'historique (procédure utilisée)

```sh
# 1. sauvegarde complète avant toute manipulation
git clone --mirror . ../zwiicms-history-backup.git

# 2. purge des chemins indésirables (git-filter-repo : pip install git-filter-repo)
git filter-repo --invert-paths --path site --path autre/chemin

# 3. republication (l'historique distant est remplacé)
git remote add origin https://codeberg.org/fredtempez/ZwiiCMS-private.git
git push --force origin master api2 iframe master_v15
```

Après un `push --force`, les anciens objets sont conservés environ 30 jours par
le serveur avant le `git gc` automatique : la taille affichée par Codeberg ne
diminue donc pas immédiatement (voir la FAQ Codeberg).

## Clones existants

Une réécriture invalide les clones antérieurs. Sur chaque autre poste :

```sh
git fetch --all --force
git reset --hard origin/master
```

Un `git pull` depuis un clone périmé suivi d'un `push` réintroduirait tout
l'historique supprimé.
