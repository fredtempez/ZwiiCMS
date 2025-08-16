<?php

/**
 * Ce fichier fait partie de Zwii.
 *
 * Pour les informations complètes sur les droits d'auteur et la licence, veuillez consulter le fichier LICENSE
 * qui a été distribué avec ce code source.
 *
 * @author Michel Tuboeuf <divers@lemimi.fr>
 * @copyright Copyright (C) 2018-2025, Michel Tuboeuf
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link https://zwiicms.fr/
 */

/**
 * La classe journal étend la classe common et gère les fonctionnalités liées au journal des éditions.
 */
class journal extends common
{
    /**
     * Actions disponibles et les groupes autorisés à les exécuter.
     * @var array
     */
    public static $actions = [
        'index' => self::GROUP_VISITOR, // Accessible par les visiteurs
        'config' => self::GROUP_ADMIN   // Accessible uniquement par les administrateurs
    ];

    /**
     * Répertoires utilisés par le module.
     * @var array
     */
    public static $directories = [];

    public static $datas = [];

    /**
     * Version du module.
     * @const string
     */
    const VERSION = '2.03';

    /**
     * Nom réel du module.
     * @const string
     */
    const REALNAME = 'Journal des éditions';

    /**
     * Indique si le module peut être supprimé.
     * @const bool
     */
    const DELETE = true;

    /**
     * Version minimale requise pour la mise à jour.
     * @const string
     */
    const UPDATE = '0.0';

    /**
     * Méthode pour afficher l'index du journal.
     * @return void
     */
    public function index()
    {
        // Chemin vers le fichier journal.log
        $logPath = self::DATA_DIR . 'journal.log';
        $entries = []; // Tableau pour stocker les entrées du journal
        $users = []; // Tableau pour stocker les utilisateurs uniques

        // Limite des pages à afficher, récupérée depuis la configuration ou par défaut à 20
        $limitePages = $this->getData(['module', $this->getUrl(0), 'limite']) ?? 20;

        // Filtres utilisateur définis dans la configuration (admin, visitor, tous)
        $filtersEnabled = $this->getData(['module', $this->getUrl(0), 'filtres']) ?? ['admin', 'visitor', 'tous'];

        // Vérifie si le fichier journal.log existe
        if (is_file($logPath)) {
            // Lit les lignes du fichier journal.log en ignorant les lignes vides
            $lines = file($logPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $pagesSeen = []; // Tableau pour suivre les pages déjà vues et éviter les doublons

            // Parcourt les lignes du fichier à l'envers pour commencer par les plus récentes
            foreach (array_reverse($lines) as $line) {
                // Divise chaque ligne en parties en utilisant le délimiteur ";"
                $parts = explode(';', $line);

                // Vérifie que la ligne a le bon format (5 parties)
                if (count($parts) === 5) {
                    list($dateRaw, $time, $ip, $user, $page) = $parts;

                    // Ignore les doublons de page
                    if (isset($pagesSeen[$page])) {
                        continue;
                    }

                    // Reformate la date de AAAAMMJJ à JJ/MM/AAAA
                    $date = substr($dateRaw, 6, 2) . '/' . substr($dateRaw, 4, 2) . '/' . substr($dateRaw, 0, 4);

                    // Ajoute l'entrée au tableau des entrées
                    $entries[] = [
                        'date' => $date,
                        'time' => $time,
                        'ip' => $ip,
                        'user' => $user,
                        'page' => $page
                    ];

                    // Normalisation de l'utilisateur pour la comparaison
                    $lowerUser = strtolower($user);
                    $addUser = false;

                    // Vérifie si l'utilisateur doit être ajouté en fonction des filtres activés
                    if (in_array('admin', $filtersEnabled) && $lowerUser === 'admin') {
                        $addUser = true;
                    } elseif (in_array('visitor', $filtersEnabled) && $lowerUser === 'visiteur') {
                        $addUser = true;
                    } elseif (in_array('tous', $filtersEnabled) && $lowerUser !== 'admin' && $lowerUser !== 'visiteur') {
                        $addUser = true;
                    }

                    // Ajoute l'utilisateur au tableau des utilisateurs si nécessaire
                    if ($addUser) {
                        $users[$lowerUser] = $user;
                    }

                    // Marque la page comme vue
                    $pagesSeen[$page] = true;

                    // Arrête la boucle si le nombre maximum d'entrées est atteint
                    if (count($entries) >= $limitePages) {
                        break;
                    }
                }
            }
        }

        // Utilisateur sélectionné dans le formulaire, par défaut 'admin'
        $defaultUser = $users['admin'] ?? '';
        $filterUser = $this->getInput('filtreUtilisateur', helper::FILTER_STRING_SHORT, false) ?? $defaultUser;

        // Filtre les entrées en fonction de l'utilisateur sélectionné
        $filteredEntries = [];
        if ($filterUser) {
            foreach ($entries as $entry) {
                if ($entry['user'] === $filterUser) {
                    $filteredEntries[] = $entry;
                }
            }
        } else {
            $filteredEntries = $entries;
        }

        // Tri des utilisateurs avec priorité pour admin et visiteur
        $priorite = ['admin', 'visiteur'];
        uksort($users, function ($a, $b) use ($priorite) {
            $pa = array_search($a, $priorite);
            $pb = array_search($b, $priorite);
            $pa = $pa === false ? PHP_INT_MAX : $pa;
            $pb = $pb === false ? PHP_INT_MAX : $pb;
            return $pa <=> $pb ?: strcasecmp($a, $b);
        });

        // Données transmises à la vue
        self::$datas = [
            'limitePages' => $limitePages,
            'users' => $users,
            'filterUser' => $filterUser,
            'filteredEntries' => $filteredEntries,
            'filtersEnabled' => $filtersEnabled
        ];

        // Préparation des données pour la vue
        $this->addOutput([
            'showBarEditButton' => true,
            'showPageContent' => true,
            'view' => 'index',
            'vendor' => [
                'tinymce'
            ]
        ]);
    }

    /**
     * Méthode pour configurer le journal.
     * @return void
     */
    public function config()
    {
        $logPath = self::DATA_DIR . 'journal.log'; // Chemin vers le fichier journal.log

        // Vérifie si la requête est de type POST
        if ($this->isPost()) {
            $action = $this->getInput('action');

            // Si l'action est de réinitialiser le journal
            if ($action === 'resetLog') {
                if (file_exists($logPath)) {
                    file_put_contents($logPath, ''); // Vide le fichier journal.log
                    $notification = 'Fichier journal.log vidé';
                } else {
                    $notification = 'Fichier journal.log introuvable';
                }
            } else {
                // Récupère les filtres sélectionnés
                $filtres = [];
                if ($this->getInput('journalFiltresAdmin'))
                    $filtres[] = 'admin';
                if ($this->getInput('journalFiltresVisitor'))
                    $filtres[] = 'visitor';
                if ($this->getInput('journalFiltresTous'))
                    $filtres[] = 'tous';

                // Si aucun filtre n'est sélectionné, tous les filtres sont appliqués par défaut
                if (empty($filtres))
                    $filtres = ['admin', 'visitor', 'tous'];

                // Enregistre la configuration
                $this->setData([
                    'module',
                    $this->getUrl(0),
                    [
                        'identifiant' => $this->getInput('journalIdentifiant'),
                        'limite' => (int) $this->getInput('journalLimite'),
                        'filtres' => $filtres
                    ]
                ]);

                $notification = 'Configuration enregistrée';
            }

            // Redirige vers la page de configuration avec une notification
            $this->addOutput([
                'redirect' => helper::baseUrl() . 'journal/config',
                'notification' => $notification,
                'state' => true
            ]);
        }

        // Récupère les filtres actuels ou utilise les valeurs par défaut
        $filtres = $this->getData(['module', $this->getUrl(0), 'filtres']) ?? ['admin', 'visitor', 'tous'];

        // Affiche la vue de configuration
        $this->addOutput([
            'title' => 'Configuration du journal',
            'view' => 'config',
            'filtres' => $filtres
        ]);
    }
}
